<?php

/**
 * @file Instruction.php
 * @version 0.2
 * - Class que auxilia no gerenciamento e abstração nas consultas SQL
 */
namespace HTR\Database;

abstract class Instruction
{
    /**
     * SQL string
     *
     * @var string
     */
    protected $sql;
    /**
     * Instância das instruções que podem ser Select, Update, Insert, Delete
     */
    protected $filters;
    /**
     * Recebe o nome da tabela a ser tratada
     *
     * @var string
     */
    protected $entidade;
    /**
     * Valores que serão trados pelo método bindValue() do PDO
     *
     * @var array
     */
    protected $bind;

    /**
     * Seta o nome da tabela do Banco de Dados
     * 
     * @param string $entidade
     * @return \HTR\Database\Instruction
     * @throws \Exception
     */
    final public function setEntidade($entidade)
    {
        if (is_string($entidade)) {
            $this->entidade = $entidade;
            return $this;
        } else {
            throw new \Exception('A entidade deve ser uma string');
        }

    }

    /**
     * Seta os valores em $this->bind
     * 
     * @param string $values
     * @return \HTR\Database\Instruction
     */
    final public function setBind($values)
    {
        $this->bind = $values;
        return $this;
    }

    /**
     * @return array
     */
    final public function returnBind()
    {
        if (!empty($this->filters)) {
            if (empty($this->bind)) {
                $this->bind = $this->filters->returnBind();
            } else {
                $this->bind = array_merge($this->bind, $this->filters->returnBind());
            }
        }
        if (!is_array($this->bind)) {
            $this->bind = [];
        }
        return $this->bind;
    }

    /**
     * @return \HTR\Database\Filters
     */
    final public function setFilters()
    {
        $this->filters = new Filters();
        return $this->filters;
    }

    abstract public function returnSql();

    /**
     * @param array $values
     */
    public function setValues(Array $values)
    {
        $this->setBind($values);
    }
}
