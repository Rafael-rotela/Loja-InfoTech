<?php

namespace InfoTech\DAO;

use InfoTech\Model\Perfil;

class PerfilDAO extends DAO
{
    public function __construct()
    {
        parent::__construct();
    }

    public function select()
    {
        $sql = "SELECT * FROM perfil ORDER BY nome";
        $stmt = parent::$connection->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(DAO::FETCH_CLASS, Perfil::class);
    }
}
