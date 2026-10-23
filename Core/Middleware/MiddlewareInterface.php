<?php

namespace InfoTech\Core\Middleware;

/**
 * Contrato de um middleware: um filtro que roda ANTES do controller.
 *
 * handle() devolve:
 *   true  -> pode seguir para o próximo middleware / para o controller
 *   false -> a requisição PARA aqui (o middleware já enviou a resposta:
 *            um redirect, um 401 JSON, a tela de acesso negado...)
 */
interface MiddlewareInterface
{
    public function handle(): bool;
}
