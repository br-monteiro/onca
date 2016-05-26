<?php

/**
 * @file Select.php
 * @version 0.2
 * - Class que gerencia a seleção de registros do Banco de Dados
 */
namespace HTR\Database\Instruction;

use HTR\Database\Instruction;

final class Select extends Instruction
{

    /**
     * Campos usados na Seleção de registros do Banco de Dados
     *
     * @var array
     */
    private $fields;

    /**
     * Configura os campos para busca no Banco de Dados
     * 
     * @param array $fields
     * @return \HTR\Database\Instruction\Select
     */
    public function setFields(Array $fields)
    {
        $this->fields = implode(', ', $fields);
        return $this;
    }

    /**
     * Retorna a instrução SQL
     * 
     * @return string
     * @throws \Exception
     */
    public function returnSql()
    {
        $this->fields = (empty($this->fields)) ? '*' : $this->fields;

        if (empty($this->entidade)) {
            throw new \Exception('Você não declarou a entidade!');
        }

        $sql = 'SELECT '.$this->fields.' FROM '.$this->entidade.' ';

        if (!empty($this->filters)) {
            $sql .= $this->filters->returnSql();
        }
        return $sql.';';
    }

    /**
     * @param array $values
     * @throws \Exception
     */
    public function setValues(Array $values = [])
    {
        throw new \Exception('Você não pode chamar o método setaValores em um Select!');
    }
}