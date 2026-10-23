<?php

namespace InfoTech\Core;

use InfoTech\Core\Middleware\MiddlewareInterface;

/**
 * Router com pilha de middlewares por rota.
 *
 * A autorização NÃO fica mais dentro do controller: a rota declara o que
 * exige, e o router roda esses filtros antes de instanciar o controller.
 * Se um middleware devolve false, o controller nem chega a ser criado.
 */
class Router
{
    /** @var array<int, array{method:string, uri:string, action:string, middlewares:array}> */
    protected array $routes = [];

    /** Prefixo e middlewares herdados do grupo que está aberto */
    private string $prefixoAtual = '';
    private array $middlewaresAtuais = [];

    // ------------------------------------------------------------------
    // Registro de rotas
    // ------------------------------------------------------------------

    /**
     * @param array<MiddlewareInterface|class-string<MiddlewareInterface>> $middlewares
     */
    public function add(string $method, string $uri, string $action, array $middlewares = []): void
    {
        $this->routes[] = [
            'method'      => strtoupper($method),
            'uri'         => $this->normalizar($this->prefixoAtual . $uri),
            'action'      => $action,
            'middlewares' => array_merge($this->middlewaresAtuais, $middlewares),
        ];
    }

    public function get(string $uri, string $action, array $middlewares = []): void
    {
        $this->add('GET', $uri, $action, $middlewares);
    }

    public function post(string $uri, string $action, array $middlewares = []): void
    {
        $this->add('POST', $uri, $action, $middlewares);
    }

    /**
     * Agrupa rotas que compartilham prefixo e/ou middlewares.
     *
     *   $router->grupo(['prefixo' => '/cliente', 'middlewares' => [AuthMiddleware::class]],
     *       function (Router $r) { $r->get('/listar', '...'); }
     *   );
     *
     * Grupos podem ser aninhados: prefixo e middlewares se acumulam.
     */
    public function grupo(array $opcoes, callable $rotas): void
    {
        $prefixoAnterior     = $this->prefixoAtual;
        $middlewaresAnterior = $this->middlewaresAtuais;

        $this->prefixoAtual      .= $opcoes['prefixo'] ?? '';
        $this->middlewaresAtuais  = array_merge($middlewaresAnterior, $opcoes['middlewares'] ?? []);

        $rotas($this);

        $this->prefixoAtual      = $prefixoAnterior;
        $this->middlewaresAtuais = $middlewaresAnterior;
    }

    // ------------------------------------------------------------------
    // Despacho
    // ------------------------------------------------------------------

    public function dispatch(): void
    {
        $uri    = $this->normalizar(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/');
        $method = strtoupper($_SERVER['REQUEST_METHOD']);

        $metodosDaUri = [];

        foreach ($this->routes as $route) {
            if (strcasecmp($route['uri'], $uri) !== 0) {
                continue;
            }

            $metodosDaUri[] = $route['method'];

            if ($route['method'] !== $method) {
                continue;
            }

            if (!$this->passouPelosMiddlewares($route['middlewares'])) {
                return; // o middleware já respondeu (401, 403, redirect...)
            }

            $this->executar($route['action']);
            return;
        }

        // URI existe, mas não nesse verbo HTTP
        if ($metodosDaUri !== []) {
            http_response_code(405);
            header('Allow: ' . implode(', ', array_unique($metodosDaUri)));
            echo '405 - Method Not Allowed';
            return;
        }

        http_response_code(404);
        echo '404 - Page Not Found';
    }

    /** Roda a pilha na ordem declarada; para no primeiro que recusar */
    private function passouPelosMiddlewares(array $middlewares): bool
    {
        foreach ($middlewares as $middleware) {
            if (is_string($middleware)) {
                $middleware = new $middleware();
            }

            if (!$middleware instanceof MiddlewareInterface) {
                throw new \RuntimeException(
                    get_class($middleware) . ' deve implementar MiddlewareInterface.'
                );
            }

            if (!$middleware->handle()) {
                return false;
            }
        }

        return true;
    }

    private function executar(string $action): void
    {
        [$controllerName, $methodName] = explode('@', $action);
        $controllerName = 'InfoTech\Controller\\' . $controllerName;

        if (!class_exists($controllerName) || !method_exists($controllerName, $methodName)) {
            http_response_code(500);
            echo "500 - Ação inválida: {$action}";
            return;
        }

        (new $controllerName())->$methodName();
    }

    /** '/infotech/cliente/listar/' e '/infotech/cliente/listar' viram a mesma rota */
    private function normalizar(string $uri): string
    {
        $uri = '/' . trim($uri, '/');
        return $uri;
    }
}
