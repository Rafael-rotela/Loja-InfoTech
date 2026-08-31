<?php

namespace InfoTech\Controller;

use InfoTech\Model\Cliente;

class ClienteController
{

    public static function index() //TODOS OS CLIENTES
    {
        $dadosClientes = Cliente::getAllRows(); //pega os dados da model
        include VIEW . '/Cliente/listar_clientes.php';
    }

    public static function cadastro() //ENVIAR OS DADOS RECEBIDOS VIA POST
    {
     
        $model = new Cliente();
        if($_SERVER['REQUEST_METHOD'] === "POST")
        {
            $nome = trim($_POST['nome'] ?? '');
            $status = $_POST['status'] ?? '';
            $telefone = trim($_POST['telefone'] ?? '');
            $email = trim($_POST['email'] ?? '');

            if ($nome === '' || $telefone === '' || $email === '' ||
                !in_array($status, ['ATIVO', 'INATIVO'], true)) {
                exit('Preencha todos os campos corretamente.');
            }

            if (!empty($_POST['id_cliente'])) {
                $model->id_cliente = (int) $_POST['id_cliente'];
            }

            $model->nome = $nome;
            $model->status_cliente = $status;
            $model->telefone = $telefone;
            $model->email = $email;
            // print_r($model);
            // exit;
            $model = $model->save();
            if ($model->id_cliente) {
                header("Location: /infotech/cliente/listar");
                exit;
            }
        }
        else{
            $model = null;

            if (!empty($_GET["id_cliente"])) {
                $id = (int) $_GET['id_cliente'];
                $model = Cliente::getById($id);

                if ($model === null) {
                    http_response_code(404);
                    exit('Cliente não encontrado.');
                }
            }
            include VIEW . '/Cliente/cadastrar_cliente.php';
        }

    }
}
