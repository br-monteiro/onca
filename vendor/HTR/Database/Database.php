<?php

/**
 * @file Database.php
 * @version 0.2
 * - Class que Executa, Seleciona a Instrução e Gerencia a Conexão com o Banco de Dados
 */
namespace HTR\Database;

class Database
{

    /**
     * Configurações de conexão
     * 
     * @var array
     */
    private $config;
    /**
     * Recebe uma instância do PDO
     * 
     * @var \PDO
     */
    private $pdo;
    /**
     * Recebe a instância da instrução requisitada
     * que podem ser: Insert, Update, Select, Delete
     */
    private $instruction;

    /**
     * Construtor da Class
     * 
     * @param array $config
     */
    public function __construct(Array $config)
    {
        $this->config = $config;
        $this->validateConnection();
    }

    /**
     * 
     * @param \PDO $pdo Recebe um objeto PDO de uma conexão realizar anteriormente
     * @return type \PDO Objeto PDO
     * @throws \Exception
     */
    public function connect(\PDO $pdo = null)
    {
        if ($pdo != null) {
            // reaproveita a conexão aberta anteriormente
            $this->pdo = $pdo;
            return $this->pdo;
        }

        try {
            if ($this->config['sqlite'] == null) {
                $this->pdo = new \PDO(
                    'mysql:host='.base64_decode($this->config['server']).';dbname='.base64_decode($this->config['dbname']),
                    base64_decode($this->config['username']),
                    base64_decode($this->config['password']),
                    $this->config['options']
                );
            } else {
                $this->pdo = new \PDO('sqlite:'.DATADR.$this->config['sqlite']);
            }
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            throw new \Exception('Erro ao conectar. Código: '.$e->getCode(). '! Mensagem: '.$e->getMessage());
        }
        return $this->pdo;
    }

    /**
     * Seta as instruções necessárias para a execução do CRUD
     * 
     * @param string $instruction
     * @return instance of Instruction\*
     * @throws \Exception
     */
    public function instruction ($instruction)
    {
        switch($instruction) {
            case 'select':
                $this->instruction = new Instruction\Select();
                break;
            case 'update':
                $this->instruction = new Instruction\Update();
                break;
            case 'delete':
                $this->instruction = new Instruction\Delete();
                break;
            case 'insert':
                $this->instruction = new Instruction\Insert();
                break;
            default:
                throw new \Exception('Este tipo de instrução não á válido');
        }
        return $this->instruction;
    }

    /**
     * 
     * @param boolean $select
     * @return \PDOStatement
     */
    public function execute($select = false)
    {
        $sql = $this->pdo->prepare($this->instruction->returnSql());

        $binds = $this->instruction->returnBind();
        
        foreach ($binds as $k => &$bind) {            
            $sql->bindValue(':'.$k, $bind);            
        }

        if (!$select) {
            return $sql->execute();
        }

        $sql->execute();
        return $sql;
    }

    /**
     * Valida as confgiurações de conexão
     * 
     * @return boolean
     * @throws \Exception
     */
    private function validateConnection()
    {
        if (is_array($this->config)) {
            if (empty($this->config['server'])) {
                throw new \Exception('Você não informou o servidor!');
            }
            if (empty($this->config['dbname'])) {
                throw new \Exception('Você não informou o banco de dados!');
            }
            if (empty($this->config['username'])) {
                throw new \Exception('Você não informou o usuário!');
            }
            if (!isset($this->config['password'])) {
                throw new \Exception('Você não informou a senha!');
            }
            if (!isset($this->config['options']) or !is_array($this->config['options'])) {
                throw new \Exception('Você não informou as opções ou não é '
                    . 'um array, você precisa informar isso mesmo que vazio!');
            }
            return true;
        }
        throw new \Exception('Esta não é uma configuração válida!');
    }
    
}
