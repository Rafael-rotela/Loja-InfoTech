<?php

namespace InfoTech\DAO;

use InfoTech\Model\Funcionario;

class LoginDAO extends DAO
{
    public function __construct(){

        parent::__construct();
    }

    public function auth(Funcionario $model)
    {
        // JOIN com perfil: já traz o NOME do perfil junto com o funcionário
        $sql = "SELECT f.id_funcionario, f.nome, f.email, f.status_funcionario,
                       f.ultima_categoria_vista, f.id_perfil,
                       p.nome AS perfil
                  FROM funcionario f
                  JOIN perfil p ON p.id_perfil = f.id_perfil
                 WHERE f.email = ? AND f.senha = ?
                   AND f.status_funcionario = 'ATIVO'";
        $stmt = parent::$connection->prepare($sql);
        $stmt->bindValue(1, $model->email);
        $stmt->bindValue(2, $model->senha);
        $stmt->execute();

        return $stmt->fetchObject(Funcionario::class);
    }

}
