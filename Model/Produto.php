<?php

namespace InfoTech\Model;

use InfoTech\DAO\ProdutoDAO; // vou chamar um método da class DAO, use ClienteDAO

class Produto extends Model
{
    
    public ?int $id_produto;
    public string $nome;
    public string $descricao;
    public string $preco; 
    public int $quantidade; 
    public bool $status_produto;
    
       public function getAllRows()
    {   
        $objCat = new ProdutoDAO();
        $this->rows = $objCat->select();
        return $this->rows;
    }

    public static function getById($id)
    {   
        //recebemos o ID capturado via GET
        $objCat = new ProdutoDAO();
        return $objCat->selectById($id);
    }

    public function save()
    {   
        $objCat = new ProdutoDAO();
        return $objCat->save($this);
    }

    public function delete(int $id)
    {   
        $objCat = new ProdutoDAO();
        return $objCat->delete($id);
    }
}