<?php

namespace InfoTech\Model;

use InfoTech\DAO\PerfilDAO;

final class Perfil extends Model
{
    public ?int $id_perfil = null;
    public string $nome = '';
    public ?string $descricao = null;

    // usado para montar o <select> de perfis no formulário de funcionário
    public function getAllRows()
    {
        $obj = new PerfilDAO();
        $this->rows = $obj->select();
        return $this->rows;
    }
}
