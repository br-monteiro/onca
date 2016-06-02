<?php

/**
 * @file ModelAbstract.php
 * @version 0.2
 * - Class responsavel por processar as configurações e iniciar a conexão com o Banco de Dados
 */
namespace HTR\System;

use App\Config\DatabaseConfig as Config;
use HTR\Database\Database as DB;
use HTR\Helpers\DatabaseBKP\DatabaseBKP as BKP;

abstract class ModelAbstract
{
    /**
     * Configurações de conexão com o Banco de Dados
     *
     * @var \HTR\Database\Database Instâcia de Database
     */
    protected $db;
    /**
     * Intância do \PDO
     *
     * @var \PDO Intância de PDO
     */
    public $pdo;

    public function __construct(\PDO $pdo = null)
    {
        if (class_exists('\App\Config\DatabaseConfig')) {
            $configDb = new Config();
            $this->db = new DB($configDb->db);
            $this->pdo = $this->db->connect($pdo);
            // Efetua o Backup do Banco de Dados
            new BKP($configDb->db);
        } else {
            throw new \Exception('Arquivo de configuração do Banco de Dados '
                . 'não encontrado em App\Config\DatabaseConfig');
        }
    }
}
