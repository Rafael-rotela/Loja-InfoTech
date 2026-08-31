<?php

namespace InfoTech\DAO;

class ClienteDAO extends DAO 
{
    public function __construct(){
        
        parent::__construct();
    }

    public static function select()
    {
        $sql = "SELECT * FROM produto";
        $stmt = parent::$connection->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(DAO::FETCH_CLASS, "InfoTech\Model\Cliente");
    }

    public static function insert($dados)
    {
        $sql = "INSERT INTO cliente $dados";
        $stmt = parent::$connection->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(DAO::FETCH_CLASS, "InfoTech\Model\Cliente");
    }

    public static function update()
    {
        $sql = "UPDATE dhaskhdkshadhsak";
        $stmt = parent::$connection->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(DAO::FETCH_CLASS, "InfoTech\Model\Cliente");
    }

    public static function relatiorasdhsak()
    {
        $sql = "SELECT * FROM cliente";
        $stmt = parent::$connection->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(DAO::FETCH_CLASS, "InfoTech\Model\Cliente");
    }
}

