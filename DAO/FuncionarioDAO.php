<?php

namespace InfoTech\DAO;

use InfoTech\Model\Funcionario;

class FuncionarioDAO extends DAO
{
    public function __construct()
    {
        parent::__construct();
    }

    public function save(Funcionario $model)
    {
        return ($model->id_funcionario == null) ? $this->insert($model) : $this->update($model);
    }

    public function select()
    {
        $sql = "SELECT f.id_funcionario, f.nome, f.email, f.status_funcionario,
                       f.id_perfil, p.nome AS perfil
                  FROM funcionario f
                  LEFT JOIN perfil p ON p.id_perfil = f.id_perfil
                 ORDER BY f.nome";
        $stmt = parent::$connection->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(DAO::FETCH_CLASS, Funcionario::class);
    }

    public function selectById(int $id)
    {
        // a senha NÃO vem para o formulário
        $sql = "SELECT id_funcionario, nome, email, status_funcionario, id_perfil
                  FROM funcionario WHERE id_funcionario = ?";
        $stmt = parent::$connection->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();

        return $stmt->fetchObject(Funcionario::class);
    }

    public function insert(Funcionario $model)
    {
        $sql = "INSERT INTO funcionario (nome, email, senha, status_funcionario, id_perfil)
                VALUES (?,?,?,?,?)";
        $stmt = parent::$connection->prepare($sql);
        $stmt->bindValue(1, $model->nome);
        $stmt->bindValue(2, $model->email);
        $stmt->bindValue(3, $model->senha);
        $stmt->bindValue(4, $model->status_funcionario);
        $stmt->bindValue(5, $model->id_perfil);
        $stmt->execute();

        $model->id_funcionario = parent::$connection->lastInsertId();
        return $model;
    }

    public function update(Funcionario $model)
    {
        $sql = "UPDATE funcionario SET nome=?, email=?, status_funcionario=?, id_perfil=?";
        $params = [$model->nome, $model->email, $model->status_funcionario, $model->id_perfil];

        // senha em branco = mantém a atual
        if ($model->senha !== '') {
            $sql .= ", senha=?";
            $params[] = $model->senha;
        }

        $sql .= " WHERE id_funcionario=?";
        $params[] = $model->id_funcionario;

        $stmt = parent::$connection->prepare($sql);
        return $stmt->execute($params);
    }
}
