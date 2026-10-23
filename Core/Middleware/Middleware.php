<?php

namespace InfoTech\Core\Middleware;

use InfoTech\Model\Funcionario;

/**
 * Base dos middlewares: guarda o que todos precisam —
 * pegar o usuário da sessão e saber COMO recusar a requisição.
 *
 * A mesma rota pode responder HTML (tela) ou JSON (fetch do JS).
 * Em vez de adivinhar pelo cabeçalho Accept, a rota declara:
 *
 *     PermissaoMiddleware::pode('categoria.listar')->json()
 */
abstract class Middleware implements MiddlewareInterface
{
    /** true = esta rota responde JSON; false = responde HTML */
    protected bool $json = false;

    public function json(): static
    {
        $this->json = true;
        return $this;
    }

    /** O funcionário logado, ou null se não há sessão */
    final protected function usuario(): ?Funcionario
    {
        return $_SESSION['usuario_logado'] ?? null;
    }

    /**
     * Recusa a requisição e devolve SEMPRE false,
     * para o middleware poder fazer `return $this->recusar(...)`.
     */
    final protected function recusar(int $status, string $msg, ?string $view = null): bool
    {
        http_response_code($status);

        if ($this->json) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => $status, 'msg' => $msg], JSON_UNESCAPED_UNICODE);
            return false;
        }

        if ($view !== null && file_exists(VIEW . $view)) {
            include VIEW . $view;
            return false;
        }

        echo $msg;
        return false;
    }
}
