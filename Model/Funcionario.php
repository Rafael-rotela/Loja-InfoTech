<?php

namespace InfoTech\Model;

use InfoTech\DAO\LoginDAO;
use InfoTech\DAO\PermissaoDAO;
use InfoTech\DAO\FuncionarioDAO;

final class Funcionario extends Model
{
    public ?int $id_funcionario = null;
    public string $nome = '';
    public string $email = '';
    public string $senha = '';
    public ?string $status_funcionario = 'ATIVO';
    public int $ultima_categoria_vista = 0;

    // ===== RBAC =====
    public ?int $id_perfil = null;     // FK para a tabela perfil
    public ?string $perfil = null;     // nome do perfil (vem do JOIN)
    public array $permissoes = [];     // ['cliente.listar', 'cliente.cadastrar', ...]

    public function logar()
    {
        $objLogin = new LoginDAO();
        return $objLogin->auth($this);
    }

    // Busca no banco as permissões do perfil deste funcionário
    public function carregarPermissoes(): void
    {
        $objPerm = new PermissaoDAO();
        $this->permissoes = $objPerm->selectByPerfil((int) $this->id_perfil);
    }

    // A pergunta do RBAC: "este funcionário PODE fazer isso?"
    public function pode(string $permissao): bool
    {
        return in_array($permissao, $this->permissoes, true);
    }

    // ===== CRUD de funcionário (tela do admin) =====
    public function getAllRows()
    {
        $obj = new FuncionarioDAO();
        $this->rows = $obj->select();
        return $this->rows;
    }

    public static function getById($id)
    {
        $obj = new FuncionarioDAO();
        return $obj->selectById($id);
    }

    public function save()
    {
        $obj = new FuncionarioDAO();
        return $obj->save($this);
    }
}
