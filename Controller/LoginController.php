<?php

namespace InfoTech\Controller;

use InfoTech\Model\Funcionario;

class LoginController extends Controller
{

    public static function index()
    {
        $model = new Funcionario();
        if(parent::isPost())
        {
            $model->email = $_POST['email'];
            $model->senha = $_POST['senha'];
            $usuario_logado = $model->logar();

            if($usuario_logado === false){
                parent::redirect('/infotech/login?erro=1');
            }

            // RBAC: carrega as permissões do perfil UMA vez, no login
            $usuario_logado->carregarPermissoes();

            // troca o id da sessão (evita sequestro de sessão)
            session_regenerate_id(true);

            $_SESSION['usuario_logado'] = $usuario_logado;
            parent::redirect('/infotech/cliente/listar'); ## REDIRECIONAR DASHBOARD
        }
        parent::render('/Login/form_login.php', $model);
    }

    public static function logout()
    {
        session_destroy();
        parent::redirect('/infotech/login');
    }
}
