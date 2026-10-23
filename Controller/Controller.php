<?php

namespace InfoTech\Controller;

use InfoTech\Model\Model;
use InfoTech\Model\Funcionario;

abstract class Controller
{
    /**
     * Autenticação e permissão de ROTA são responsabilidade dos middlewares
     * (ver Core/routes.php). Quando a execução chega aqui, o usuário já está
     * logado e já passou pelo gate da rota.
     */

    /** O funcionário logado (as views também usam esta informação) */
    final protected static function usuario(): ?Funcionario
    {
        return $_SESSION['usuario_logado'] ?? null;
    }

    /**
     * Refinamento de permissão — use APENAS quando a regra depende do dado,
     * não da URL. Ex.: a mesma rota /cliente/cadastro cria (cliente.cadastrar)
     * ou edita (cliente.editar) conforme vier um id no request.
     *
     * Se a regra depende só da URL, ela pertence à rota, não aqui.
     */
    final protected static function autorizar(string $permissao): void
    {
        if (!self::usuario()?->pode($permissao)) {
            http_response_code(403);
            include VIEW . '/Erro/acesso_negado.php';
            exit;
        }
    }

    final protected static function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === "POST";
    }

    final protected static function redirect(string $route): void
    {
        header("Location: $route");
        exit;
    }

    // $dados (opcional): variáveis extras para a view. ['perfis' => [...]] vira $perfis
    final protected static function render(string $view, ?Model $model, array $dados = []): void
    {
        extract($dados);
        include VIEW . $view;
    }

    final protected static function jsonResponse(array $data)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
