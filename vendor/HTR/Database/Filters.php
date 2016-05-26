<?php

/*
 * @file Filters.php
 * @version 0.2
 * - Class que gerencia os filtros e regras das consultas SQL
 */
namespace HTR\Database;

final class Filters
{
    /**
     * Array de opções de execução SQL
     *
     * @var array
     */
    private $sql;

    /**
     * Array contendo os dados usados no método bindValue do PDO
     *
     * @var array
     */
    private $bind;

    /**
     * Organiza as regras da SQL string
     * 
     * @param string $column
     * @param string $option
     * @param string $value
     * @return \HTR\Database\Filters
     */
    public function where($column, $option, $value)
    {
        $this->setBind($column, $value);
        $this->sql['where'][] = $column.$option.':'.$column;
        return $this;
    }

    /**
     * Organiza as regras da SQL string
     * 
     * @param string $option
     * @return \HTR\Database\Filters
     */
    public function whereOperator($option)
    {
        $this->sql['where'][] = $option;
        return $this;
    }

    /**
     * Seta os limites de consulta
     * 
     * @param string $limit
     * @return \HTR\Database\Filters
     */
    public function limit($limit)
    {
        $this->sql['limit'] = $limit;
        return $this;
    }

    /**
     * Organiza os parâmetros de Ordenação de resultados
     * 
     * @param string $order
     * @return \HTR\Database\Filters
     */
    public function orderBy($order)
    {
        $this->sql['order'] = $order;
        return $this;
    }

    /**
     * Organiza os parâmetros de execução da operação SQL
     * 
     * @return string
     */
    public function returnSql()
    {
        $sql = [];
        if (!empty($this->sql['where'])) {
            $sqlString = 'WHERE ';
            $sqlString .= implode(' ', $this->sql['where']);
            $sql[] = $sqlString;
        }

        if (!empty($this->sql['order'])) {
            $sqlString = 'ORDER BY '.$this->sql['order'];
            $sql[] = $sqlString;
        }

        if (!empty($this->sql['limit'])) {
            $sqlString = 'LIMIT '.$this->sql['limit'];
            $sql[] = $sqlString;
        }

        return implode(' ', $sql);
    }

    /**
     * Retorna os valores contidos em $this->bind
     * 
     * @return array
     */
    public function returnBind()
    {
        return $this->bind;
    }

    /**
     * Seta os valores a serem usados no método bindValue() do PDO
     * 
     * @param string $column
     * @param string $value
     * @return \HTR\Database\Filters
     */
    private function setBind($column, $value)
    {
        $this->bind[$column] = $value;
        return $this;
    }
}
