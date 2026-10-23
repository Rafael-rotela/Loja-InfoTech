<?php

/**
 * =====================================================================
 *  MAPA DE ROTAS + RBAC DECLARATIVO
 * =====================================================================
 *
 *  Regra do projeto: olhando SÓ este arquivo você sabe quem pode entrar
 *  em cada URL. Nenhum controller chama autorização por conta própria.
 *
 *  Como ler uma linha:
 *
 *      $r->get('/cliente/excluir', 'ClienteController@exclusao', [
 *          PermissaoMiddleware::pode('cliente.excluir'),
 *      ]);
 *       |     |                      |                     |
 *       |     |                      |                     +-- chave da tabela `permissao`
 *       |     |                      +-- filtro que roda ANTES do controller
 *       |     +-- URL (o prefixo vem do grupo)
 *       +-- verbo HTTP
 *
 *  Secure by default: TUDO o que está dentro do grupo "autenticado" exige
 *  sessão. Rota pública é exceção e fica declarada fora dele, à vista.
 */

use InfoTech\Core\Router;
use InfoTech\Core\Middleware\AuthMiddleware;
use InfoTech\Core\Middleware\PermissaoMiddleware;

$router->grupo(['prefixo' => '/infotech'], function (Router $r) {

    // =================================================================
    // PÚBLICO — sem sessão (quem não entrou ainda precisa poder entrar)
    // =================================================================
    $r->get('/',       'InicioController@index');
    $r->get('/login',  'LoginController@index');
    $r->post('/login', 'LoginController@index');

    // =================================================================
    // TELAS AUTENTICADAS (HTML)
    // Recusa = redirect para o login / tela de acesso negado
    // =================================================================
    $r->grupo(['middlewares' => [AuthMiddleware::class]], function (Router $r) {

        $r->get('/logout', 'LoginController@logout');

        // -------------------------------------------------------------
        // Clientes (telas HTML)
        // -------------------------------------------------------------
        $r->get('/cliente/listar', 'ClienteController@index', [
            PermissaoMiddleware::pode('cliente.listar'),
        ]);

        // A mesma rota cria E edita. A rota faz o gate grosso (precisa de
        // ao menos uma das duas), e o controller refina conforme vier id
        // ou não — regra que depende do DADO, não da URL.
        $r->get('/cliente/cadastro', 'ClienteController@cadastro', [
            PermissaoMiddleware::podeQualquer('cliente.cadastrar', 'cliente.editar'),
        ]);
        $r->post('/cliente/cadastro', 'ClienteController@cadastro', [
            PermissaoMiddleware::podeQualquer('cliente.cadastrar', 'cliente.editar'),
        ]);

        $r->get('/cliente/exclusao', 'ClienteController@exclusao', [
            PermissaoMiddleware::pode('cliente.excluir'),
        ]);

        // -------------------------------------------------------------
        // Funcionários (só quem administra o RBAC)
        // -------------------------------------------------------------
        $r->grupo(['middlewares' => [PermissaoMiddleware::pode('funcionario.gerenciar')]],
            function (Router $r) {
                $r->get('/funcionario/listar',    'FuncionarioController@index');
                $r->get('/funcionario/cadastro',  'FuncionarioController@cadastro');
                $r->post('/funcionario/cadastro', 'FuncionarioController@cadastro');
            }
        );

    });

    // =================================================================
    // API AUTENTICADA (JSON, consumida pelo fetch do JS)
    //
    // Grupo separado de propósito: um fetch que cai no login HTML recebe
    // um 302 + página inteira e o JS quebra no JSON.parse. Aqui ->json()
    // faz a recusa sair como {"status":401|403,"msg":"..."}.
    // =================================================================
    $r->grupo(['middlewares' => [AuthMiddleware::exigir()->json()]], function (Router $r) {

        $r->get('/categoria/listar', 'CategoriaController@listar', [
            PermissaoMiddleware::pode('categoria.listar')->json(),
        ]);
        $r->post('/categoria/cadastro', 'CategoriaController@cadastro', [
            PermissaoMiddleware::pode('categoria.cadastrar')->json(),
        ]);

        // Notificações são do próprio usuário: basta estar logado,
        // nenhuma permissão extra — por isso não há PermissaoMiddleware.
        $r->get('/notificacao/listar', 'NotificacaoController@listar');
        $r->post('/notificacao/marcar-lidas', 'NotificacaoController@marcarLidas');
    });
});
