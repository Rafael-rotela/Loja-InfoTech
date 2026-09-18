<?php

namespace InfoTech\DAO;

use InfoTech\Model\Produto;

class ProdutoDAO extends DAO
{
    public function __construct()
    {

        parent::__construct();
    }

    public function save(): Produto
    {
        return new Produto;
    }

    public function insert(Produto $model): Produto
    {
        $sql = "INSERT INTO produto (nome,descricao,preco,quantidade,status_produto) VALUES(?, ?, ?, ?)";

        $stmt = parent::$connection->prepare($sql);


        return $model;
    }

    public function update(Produto $model)
    {
        $sql = "UPDATE produto SET nome=?, descricao=?, preco=?, quantidade=?, status_produto=?
                WHERE id_produto =?";
        $stmt = parent::$connection->prepare($sql);
        $stmt->bindValue(1, $model->nome);
        $stmt->bindValue(2, $model->descricao);
        $stmt->bindValue(3, $model->preco);
        $stmt->bindValue(4, $model->quantidade);
        $stmt->bindValue(5, $model->status_produto);


        return $stmt->execute();
    }

    public function selectById(int $id): ?Produto
    {
        $sql = "SELECT * FROM produto WHERE id_produto = ?";

        $stmt = parent::$connection->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();

        return $stmt->fetchObject("App\Model\Produto");
    }


    public static function select()
    {
        $sql = "SELECT * FROM produto";
        $stmt = parent::$connection->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(DAO::FETCH_CLASS, "InfoTech\Model\Produto");
    }

    public static function delete(int $id)
    {
        $sql = "DELETE FROM produto WHERE id_produto = ?";

        $stmt = parent::$connection->prepare($sql);
        $stmt->bindValue(1, $id);
        return $stmt->execute();
    }
}
