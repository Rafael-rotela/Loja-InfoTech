<?php

namespace InfoTech\Model; // localizando onde está a classe ClienteModel

use InfoTech\DAO\LoginDAO;

 // vou chamar um método da class DAO, use ClienteDAO

final class Funcionario extends Model
{
    public ?int $id_funcionario;
    public string $nome;
    public string $senha;
    public string $email;
    public ?string $status_funcionario;

    public function logar()
    {
        $objLogin = new LoginDAO;
        return $objLogin->auth($this); ### This é o obj funcionario preenchido com os dados que via POST

    }
}
