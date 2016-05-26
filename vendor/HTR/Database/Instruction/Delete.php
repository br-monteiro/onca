<?php

/**
 * @file Delete.php
 * @version 0.2
 * - Class que gerencia a exclusão de registros do Banco de Dados
 */
namespace HTR\Database\Instruction;

use HTR\Database\Instruction;

final class Delete extends Instruction
{
    /**
     * Retorna a instrução SQL
     * 
     * @return string
     * @throws Exception
     */
    public function returnSql()
    {
        /*
         * Verifica se a entidade foi setada corretamente
         */
        if (empty($this->entidade)) {
            throw new \Exception('Você não declarou a entidade!');
        }

        $sql = 'DELETE FROM '.$this->entidade.' ';

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
        throw new \Exception('Você não pode chamar o método setaValores em um Delete!');
    }
}