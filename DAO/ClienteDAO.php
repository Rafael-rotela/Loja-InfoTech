<?php

namespace InfoTech\DAO;

use InfoTech\Model\Cliente;

class ClienteDAO extends DAO 
{
    public function __construct(){
        
        parent::__construct();
    }

    public static function select()
    {
        $sql = "SELECT * FROM cliente";
        $stmt = parent::$connection->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(DAO::FETCH_CLASS, "InfoTech\Model\Cliente");
    }

    public function selectById(int $id)
    {
        $sql = "SELECT * FROM cliente WHERE id_cliente = ?";
        $stmt = parent::$connection->prepare($sql);
        $stmt->bindValue(1, $id, \PDO::PARAM_INT);
        $stmt->execute();

        $cliente = $stmt->fetchObject(Cliente::class);
        return $cliente ?: null;
    }
    public function insert(Cliente $model)
    {
        $sql = "INSERT INTO cliente (nome,status_cliente,telefone,email) VALUES (?,?,?,?)";
        $stmt = parent::$connection->prepare($sql);
        $stmt->bindValue(1, $model->nome);
        $stmt->bindValue(2, $model->status_cliente);
        $stmt->bindValue(3, $model->telefone);
        $stmt->bindValue(4, $model->email);
        $stmt->execute();

        $model->id_cliente = parent::$connection->lastInsertId();
        return $model;
    }

    public function update(Cliente $model)
    {
        $sql = "UPDATE cliente SET nome = ?, status_cliente = ?, telefone = ?, email = ? WHERE id_cliente = ?";
        $stmt = parent::$connection->prepare($sql);
        $stmt->bindValue(1, $model->nome);
        $stmt->bindValue(2, $model->status_cliente);
        $stmt->bindValue(3, $model->telefone);
        $stmt->bindValue(4, $model->email);
        $stmt->bindValue(5, $model->id_cliente, \PDO::PARAM_INT);
        $stmt->execute();

        return $model;
    }

}

