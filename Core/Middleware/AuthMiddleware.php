<?php

namespace InfoTech\Core\Middleware;

/**
 * 1ª barreira da autorização: AUTENTICAÇÃO ("quem é você?").
 *
 * Não sabe nada de permissão — só responde se existe alguém logado.
 */
final class AuthMiddleware extends Middleware
{
    public static function exigir(): self
    {
        return new self();
    }

    public function handle(): bool
    {
        if ($this->usuario() !== null) {
            return true;
        }

        // Rota de JSON: o JS precisa de um 401, não de um HTML de login
        if ($this->json) {
            return $this->recusar(401, 'Sessão expirada. Faça login novamente.');
        }

        header('Location: ' . URL_BASE . '/login');
        return false;
    }
}
