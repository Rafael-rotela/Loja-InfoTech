<?php

namespace InfoTech\Controller;

use InfoTech\Model\Funcionario;
use InfoTech\Model\Perfil;

class FuncionarioController extends Controller
{
    public static function index()
    {
        $model = new Funcionario();
        $model->getAllRows();

        parent::render('/Funcionario/listar_funcionarios.php', $model);
    }

    public static function cadastro()
    {
        $model = new Funcionario();

        if (parent::isPost()) {
            $model->id_funcionario     = !empty($_POST['id_funcionario']) ? (int) $_POST['id_funcionario'] : null;
            $model->nome               = $_POST['nome'];
            $model->email              = $_POST['email'];
            $model->senha              = $_POST['senha'] ?? '';
            $model->status_funcionario = $_POST['status_funcionario'];
            $model->id_perfil          = (int) $_POST['id_perfil'];   // <- o perfil escolhido no <select>

            $model->save();
            parent::redirect('/infotech/funcionario/listar');
        }

        if (isset($_GET['id_funcionario'])) {
            $model = Funcionario::getById($_GET['id_funcionario']);
        }

        // lista de perfis para o <select>
        $perfis = (new Perfil())->getAllRows();

        parent::render('/Funcionario/cadastrar_funcionario.php', $model, ['perfis' => $perfis]);
    }
}
