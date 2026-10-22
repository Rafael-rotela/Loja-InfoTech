<?php

namespace InfoTech\Controller; //local onde se encontra a classe ClienteController
use InfoTech\Model\Cliente; // irei utilizar a model de cliente

class ClienteController extends Controller
{

    public static function index() //TODOS OS CLIENTES
    {
        parent::autorizar('cliente.listar');
        $model = new Cliente();
        $model->getAllRows(); //pega os dados da model

        parent::render('/Cliente/listar_clientes.php', $model);
    }

    public static function cadastro() //ENVIAR OS DADOS RECEBIDOS VIA POST
    {
        // a mesma rota cria E edita: a permissão depende de vir id ou não
        $id = $_POST['id_cliente'] ?? $_GET['id_cliente'] ?? '';
        parent::autorizar(empty($id) ? 'cliente.cadastrar' : 'cliente.editar');

        $model = new Cliente();
        if(parent::isPost())
        {
            $model->id_cliente = !empty($_POST['id_cliente']) ? $_POST['id_cliente'] : null;
            $model->nome = $_POST['nome'];
            $model->status_cliente = $_POST['status_cliente'];
            $model->telefone = $_POST['telefone'];
            $model->email = $_POST['email'];
            $model->id_categoria = $_POST['id_categoria'];
            $model = $model->save();
            if($model){
                parent::redirect('/infotech/cliente/listar');
            }
        }
        else{
            if(isset($_GET['id_cliente'])){

                 $id = $_GET['id_cliente']; //captura o id que veio via GET
                 $model = Cliente::getById($id); // solicita ao banco o cliente com esse id
            }

            parent::render('/Cliente/cadastrar_cliente.php', $model);
        }

    }

    public static function exclusao()
    {
        parent::autorizar('cliente.excluir');
        if(isset($_GET['id_cliente'])){

            $id = $_GET['id_cliente']; //captura o id que veio via GET
            $model = new Cliente();
            $model->delete($id);
            parent::redirect('/infotech/cliente/listar');
       }
    }
}
