<?php

namespace InfoTech\Controller;

use InfoTech\Model\Model;

abstract class Controller
{
    final protected static function isLogged()
    {
        if( !isset($_SESSION['usuario_logado'])) {
            header("Location: /infotech/login");
            exit; // SEM o exit o PHP continua e executa a ação!
        }
    }

    // ===== RBAC: telas (HTML) =====
    final protected static function autorizar(string $permissao): void
    {
        self::isLogged(); // 1ª barreira: está logado?

        if (!$_SESSION['usuario_logado']->pode($permissao)) { // 2ª barreira: tem permissão?
            http_response_code(403);
            include VIEW . '/Erro/acesso_negado.php';
            exit;
        }
    }

    // ===== RBAC: rotas que respondem JSON (fetch do JS) =====
    final protected static function autorizarJson(string $permissao): void
    {
        $usuario = $_SESSION['usuario_logado'] ?? null;

        if ($usuario === null) {
            http_response_code(401);
            self::jsonResponse(['status' => 401, 'msg' => 'Faça login novamente']);
        }

        if (!$usuario->pode($permissao)) {
            http_response_code(403);
            self::jsonResponse(['status' => 403, 'msg' => 'Você não tem permissão para isso']);
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
        echo json_encode($data);
        exit;
    }
}
