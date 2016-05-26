<?php

/**
 * @file Insert.php
 * @version 0.2
 * - Class que gerencia a inserção de registros do Banco de Dados
 */
namespace HTR\Database\Instruction;

use HTR\Database\Instruction;

final class Insert extends Instruction
{

    /**
     * Valores usados na inserção de dados no Banco de Dados
     * 
     * @var string
     */
    private $values;

    /**
     * Retorna a instrução SQL
     * 
     * @return string
     * @throws \Exception
     */
    public function returnSql()
    {
        if (empty($this->entidade)) {
            throw new \Exception('Você não declarou a entidade!');
        }

        $sql = 'INSERT INTO '.$this->entidade.' '.$this->values.';';
        return $sql;
    }

    /**
     * Configura os valores da instrução SQL
     * 
     * @param array $values
     * @return \HTR\Database\Instruction\Insert
     */
    public function setValues(Array $values = [])
    {
        parent::setValues($values);
        $keys = array_keys($values);
        $column = implode(', ', $keys);
        $values = implode(', :', $keys);

        $this->values = '('.$column.') VALUES (:'.$values.')';

        return $this;
    }
}