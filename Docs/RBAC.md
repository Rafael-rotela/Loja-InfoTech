# RBAC declarativo nas rotas

Referência de implementação. O gate de acesso vive em `Core/routes.php`;
nenhum controller chama autorização por conta própria.

## 1. As três camadas

| Camada | Pergunta | Onde |
|---|---|---|
| Autenticação | "quem é você?" | `AuthMiddleware` |
| Autorização | "você PODE isso?" | `PermissaoMiddleware` |
| Dados | "você pode isso **neste registro**?" | controller (exceção, ver §5) |

## 2. Arquivos

```
Core/
  Router.php                        pilha de middlewares + despacho
  routes.php                        O MAPA: rota -> permissão exigida
  Middleware/
    MiddlewareInterface.php         handle(): bool
    Middleware.php                  base: usuário da sessão + como recusar
    AuthMiddleware.php              401 / redirect para o login
    PermissaoMiddleware.php         403 — é aqui que o RBAC decide
```

O contrato é uma linha:

```php
public function handle(): bool;   // true = segue | false = parou (já respondeu)
```

Se um middleware devolve `false`, o controller **nem é instanciado**.

## 3. Como declarar uma rota

```php
$r->get('/cliente/excluir', 'ClienteController@exclusao', [
    PermissaoMiddleware::pode('cliente.excluir'),
]);
```

- `pode('a', 'b')` → exige **todas** (AND)
- `podeQualquer('a', 'b')` → basta **uma** (OR)
- `->json()` → recusa como `{"status":403,"msg":"..."}` em vez de HTML

Permissão que vale para várias rotas vai no grupo, não repetida linha a linha:

```php
$r->grupo(['middlewares' => [PermissaoMiddleware::pode('funcionario.gerenciar')]],
    function (Router $r) {
        $r->get('/funcionario/listar',    'FuncionarioController@index');
        $r->post('/funcionario/cadastro', 'FuncionarioController@cadastro');
    }
);
```

As chaves (`'cliente.excluir'`) são exatamente as da coluna `permissao.chave`
(ver `DAO/rbac.sql`). Criar uma permissão nova = `INSERT` na tabela + a linha
na rota. Nada de `if (perfil == 'admin')` espalhado no código: perfil é dado,
não código.

## 4. Decisões que fazem diferença

**Secure by default.** Rota autenticada é o padrão (está dentro do grupo com
`AuthMiddleware`); rota pública é a exceção e fica declarada fora dele, à vista.
Esquecer de proteger passa a ser difícil — antes, esquecer o
`parent::autorizar()` no controller abria a rota silenciosamente.

**HTML e JSON em grupos separados.** Um `fetch` que cai no `AuthMiddleware` de
HTML recebe um 302 + a página de login inteira, e o JS quebra no `JSON.parse`.
Por isso existem dois grupos: telas e API.

**Defesa em profundidade.** `PermissaoMiddleware` também checa sessão: se
alguém esquecer o `AuthMiddleware` na rota, ele não libera — recusa com 401.

**Permissões carregadas uma vez.** `Funcionario::carregarPermissoes()` roda no
login e o array vai para a sessão; o middleware só faz `in_array`, sem SELECT
por requisição. O preço: mudar o perfil de alguém só vale no próximo login.
Se isso incomodar, recarregue as permissões no middleware (1 SELECT/request).

**As views repetem a mesma pergunta.** `$usuario->pode('cliente.excluir')`
esconde o botão. Esconder não é proteger — a rota é que protege; a view só
evita oferecer um caminho que vai dar 403.

## 5. Quando a regra fica no controller

Só quando depende do **dado**, não da URL. Ex.: `/cliente/cadastro` cria e
edita na mesma rota:

```php
// rota: gate grosso
PermissaoMiddleware::podeQualquer('cliente.cadastrar', 'cliente.editar')

// controller: qual das duas depende de vir id no request
parent::autorizar(empty($id) ? 'cliente.cadastrar' : 'cliente.editar');
```

Mesma coisa para "o analista só vê os projetos alocados a ele" — isso é filtro
no `WHERE` do DAO, não middleware.

## 6. Portar para o CyberReport

A arquitetura de lá já casa: `Middleware\MiddlewareInterface` tem o mesmo
`handle(): bool`, e `Routes\Route` já aceita `array $middlewares` e grupos via
`Route::middleware([...], fn)`.

1. Copiar `Middleware.php` e `PermissaoMiddleware.php` para `app/Middleware/`,
   trocando o namespace `InfoTech\Core\Middleware` por `Middleware` e
   `InfoTech\Model\Funcionario` pelo usuário da sessão de lá
   (`$_SESSION['usuario_id']` / `permissoes`).

2. **Único ajuste de infra necessário:** `Routes\Router::dispatch()` hoje só
   aceita middleware como *class-string* (`new $middlewareClass()`), e
   `PermissaoMiddleware` precisa receber a chave da permissão no construtor.
   Aceitar instâncias também:

   ```php
   foreach ($route['middlewares'] as $middleware) {
       if (is_string($middleware)) {
           $middleware = new $middleware();
       }
       if (!$middleware instanceof MiddlewareInterface) {
           throw new \RuntimeException(get_class($middleware) . ' deve implementar MiddlewareInterface.');
       }
       if (!$middleware->handle()) {
           return;
       }
   }
   ```

3. No login de lá, carregar as permissões do `perfil_acesso` para a sessão
   (equivalente ao `carregarPermissoes()`), e criar as tabelas `permissao` e
   `perfil_acesso_permissao` (N:N) espelhando `DAO/rbac.sql`.

4. Em `app/Routes/Routes.php`, trocar o grupo único de `AuthMiddleware` por:
   um bloco público, um bloco de telas autenticadas e um bloco de API
   (`->json()`), e ir anotando `PermissaoMiddleware::pode('...')` rota por rota.
