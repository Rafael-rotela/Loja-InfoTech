<?php

namespace InfoTech\Controller;

use InfoTech\Model\Produto;
use Throwable;

class ProdutoController extends Controller
{
    public static function listar()
    {
        parent::isLogged();
        try {
            $model = new Produto();

            $produto = $model->getAllRows();

            $produto = array_map(fn($produtos) => [
                'id_produto' => $produtos->id_produto,
                'nome' => $produtos->nome,
                'descricao' => $produtos->descricao,
                'quantidade' => $produtos->quantidade,
                'preco' => $produtos->preco,
                'status_produto' => $produtos->status_produto,
            ], $model->rows);

            $result = ['status' => 200, 'data' => $produto];
        } catch (\Throwable) {
            $result = ['status' => 400, 'data' => 'Nao existe produto'];
        }

        parent::jsonResponse($result);
    }

    public static function cadastro()
    {
        $model = new Produto();
        $model->id_produto = null;
        $model->nome = (string) $_POST['nome'];
        $model->descricao = (string) $_POST['descricao'];
        $model->preco = (int) $_POST['preco'];
        $model->quantidade = (int) $_POST['quantidade'];
        $model->status_produto = (bool) $_POST['status_produto'];

        if ($model->nome === '') {
            $array = ['status' => 400, 'msg' => 'Informe o nome da produto'];
            parent::jsonResponse($array);
        }

        try {
            $model->save();
            $result = [
                'status' => 200,
                'msg' => 'Cadastrado com sucesso',
                'id_produto' => $model->id_produto
            ];
        } catch (\PDOException $e) {
            $result =  [
                'status' => 500,
                'msg' => 'Erro ao cadastrar ao '
            ];
        }

        parent::jsonResponse($result);
    }
}
