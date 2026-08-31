<?php

namespace InfoTech\Model;

use InfoTech\DAO\ClienteDAO;

class Cliente
{
    public ?int $id_cliente;
    public string $nome;
    public string $status_cliente;
    public string $telefone;
    public string $email;
    public ?string $data_cadastro;

    public static function getAllRows()
    {   
        $objCli = new ClienteDAO();
        return $objCli->select();
        // return new ClienteDAO()->select();
    }

    public static function getById(int $id)
    {   
        $objCli = new ClienteDAO();
        return $objCli->selectById($id);
        // return new ClienteDAO()->select();
    }

    public function save()
    {   
        $objCli = new ClienteDAO();

        if (isset($this->id_cliente)) {
            return $objCli->update($this);
        }

        return $objCli->insert($this);
    }
}
