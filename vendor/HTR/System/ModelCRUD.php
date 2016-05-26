<?php

/**
 * @file ModelCRUD.php
 * @version 0.3
 * - Class que gerencia a abstração do Banco de Dados - CRUD
 */
namespace HTR\System;

use HTR\System\ModelAbstract;

class ModelCRUD extends ModelAbstract
{
    /**
     * Retorna todos os dados
     * 
     * @return array Retorna os resultados enontrados na consulta SQL
     */
    public function findAll()
    {
        $this->db->instruction('select')
            ->setEntidade($this->entidade);

        return $this->db->execute('select')->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Insere novos dados no Banco de Dados
     * 
     * @param array $dados Dados a serem tratados na execução
     * @return \PDOStatement
     */
    public function novo($dados)
    {
        $this->db->instruction('insert')
            ->setEntidade($this->entidade)
            ->setValues($dados);

        return $this->db->execute();
    }

    /**
     * Altera dados do Banco de Dados
     * 
     * @param array $dados Dados a serem tratados na execução
     * @param mixed $id Id da(s) linha(s) a ser(em) alterada(s)
     * @return \PDOStatement
     */
    public function editar($dados, $id)
    {
        $this->db->instruction('update')
            ->setEntidade($this->entidade)
            ->setValues($dados)
            ->setFilters()
            ->where('id', '=', $id);

        return $this->db->execute();
    }

    /**
     * Remove dados do Banco de Dados
     * 
     * @param mixed $id Id da(s) linha(s) a ser(em) alterada(s)
     * @return \PDOStatement
     */
    public function remover($id)
    {
        $this->db->instruction('delete')
            ->setEntidade($this->entidade)
            ->setFilters()
            ->where('id', '=', $id);

        return $this->db->execute();
    }

    /**
     * Método usado como curinga para realiza uma busca no Banco de Dados
     * por um campo específico; Como também setar e retornar valores de atributos
     * 
     * @param string $method Nome do Método requisitado
     * @param array|null $properties Propriedades do Método
     * @return \HTR\System\ModelCRUD
     * @throws \Exception
     */
    public function __call($method, $properties = null)
    {
        if (substr($method, 0, 6) == 'findBy') {
            
            $campo = strtolower(substr($method, 6, strlen($method)));
            $this->db->instruction('select')
                ->setEntidade($this->entidade)
                ->setFilters()
                ->where($campo, '=', isset($properties[0]) ? $properties[0] : null);

            return $this->db->execute('select')->fetch(\PDO::FETCH_ASSOC);
            
        } elseif (substr($method, 0, 3) == 'set') {
            
            $attributeName = lcfirst(substr($method, 3, strlen($method)));
            $this->$attributeName = isset($properties[0]) ? $properties[0] : null;
            return $this;
            
        } elseif (substr($method, 0, 3) == 'get') {
            
            $attributeName = lcfirst(substr($method, 3, strlen($method)));
            return $this->$attributeName;
            
        } else {
            throw new \Exception('Método não encontrado');
        }
    }
}
