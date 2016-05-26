<?php

/**
 * @file Update.php
 * @version 0.2
 * - Class que gerencia a alteração de registros do Banco de Dados
 */
namespace HTR\Database\Instruction;

use HTR\Database\Instruction;

final class Update extends Instruction
{
    /**
     * Valores usados na alteração de dados no Banco de Dados
     * 
     * @var string
     */
    private $values;

    /**
     * Retorna a instrução SQL
     * 
     * @return type
     * @throws \Exception
     */
    public function returnSql()
    {
        if (empty($this->entidade)) {
            throw new \Exception('Você não declarou a entidade!');
        }

        $sql = 'UPDATE '.$this->entidade.' SET '.$this->values.' ';

        if (!empty($this->filters)) {
            $sql .= $this->filters->returnSql();
        }
        return $sql.';';
    }

    /**
     * Configura os valores da instrução SQL
     * 
     * @param array $values
     * @return \HTR\Database\Instruction\Update
     */
    public function setValues(Array $values = [])
    {
        parent::setValues($values);

        $keys = array_keys($values);

        $sql = [];
        foreach ($keys as &$key) {
                $sql[] = $key . '=:' . $key;
        }

        $this->values = implode(', ', $sql);

        return $this;
    }
}