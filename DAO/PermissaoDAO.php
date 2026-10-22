<?php

namespace InfoTech\DAO;

use PDO;

class PermissaoDAO extends DAO
{
    public function __construct()
    {
        parent::__construct();
    }

    // Retorna só as chaves: ['cliente.listar', 'cliente.cadastrar', ...]
    public function selectByPerfil(int $id_perfil): array
    {
        $sql = "SELECT p.chave
                  FROM permissao p
                  JOIN perfil_permissao pp ON pp.id_permissao = p.id_permissao
                 WHERE pp.id_perfil = ?";
        $stmt = parent::$connection->prepare($sql);
        $stmt->bindValue(1, $id_perfil, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN); // uma coluna -> array simples
    }
}
