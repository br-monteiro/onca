<?php

/**
 * @file DatabaseBKP.php
 * @version 0.2
 * - Helper que auxilia no Backup das Bases de Dados do Sistema
 */
namespace HTR\Helpers\DatabaseBKP;

use Rah\Danpu\Dump;
use Rah\Danpu\Export;

class DatabaseBKP
{
    /**
     * Tipo de Banco de Dados usado
     * MySQL ou Sqlite
     *
     * @var string
     */
    private $dbType;
    /**
     * Sevidor
     *
     * @var string
     */
    private $host;
    /**
     * Nome do Usuário
     *
     * @var string
     */
    private $userName;
    /**
     * Senha do Usuário
     *
     * @var string
     */
    private $passWord;
    /**
     * Nome do Banco
     *
     * @var string
     */
    private $dbname;

    /**
     * @param array $config
     */
    public function __construct(Array $config)
    {
        // Verifica se a opção de backup está ativa (true)
        if (BACKUP) {
            $bkp = $this->setDbType($config)
                ->setHost($config)
                ->setUserName($config)
                ->setPassWord($config)
                ->setDbName($config)
                ->verifyStructure()
                ->verifyFileTimestamp();
            if ($bkp) {
                // Efetua o backup do Banco de Dados
                $this->createBKP();
            }
        }
    }

    /**
     * Indica o banco de dados usado
     * 
     * @param array $config
     * @return \HTR\Helpers\DatabaseBKP\DatabaseBKP
     */
    private function setDbType($config)
    {
        $this->dbType = 'mysql';
        if($config['sqlite']) {
            $this->dbType = 'sqlite';
        }

        return $this;
    }

    /**
     * @return string
     */
    private function getDbType()
    {
        return $this->dbType;
    }

    /**
     * @param array $config
     * @return \HTR\Helpers\DatabaseBKP\DatabaseBKP
     */
    private function setHost($config)
    {
        $this->host = base64_decode($config['server']);
        return $this;
    }

    /**
     * @return string
     */
    private function getHost()
    {
        return $this->host;
    }

    /**
     * @param array $config
     * @return \HTR\Helpers\DatabaseBKP\DatabaseBKP
     */
    private function setUserName($config)
    {
        $this->userName = base64_decode($config['username']);
        return $this;
    }

    /**
     * @return string
     */
    private function getUserName()
    {
        return $this->userName;
    }

    /**
     * @param array $config
     * @return \HTR\Helpers\DatabaseBKP\DatabaseBKP
     */
    private function setPassWord($config)
    {
        $this->passWord = base64_decode($config['password']);
        return $this;
    }

    /**
     * @return string
     */
    private function getPassWord()
    {
        return $this->passWord;
    }

    /**
     * @param array $config
     * @return \HTR\Helpers\DatabaseBKP\DatabaseBKP
     */
    private function setDbName($config)
    {
        if ($this->dbType == 'sqlite') {
            $this->dbname = $config['sqlite'];
        } else {
            $this->dbname = base64_decode($config['dbname']);
        }
        return $this;
    }

    /**
     * @return string
     */
    private function getDbName()
    {
        return $this->dbname;
    }

    /**
     * Verifica a estrutura de diretórios necessária para a operação
     * caso algum diretório não exista, tenta criá-lo
     * 
     * @return \HTR\Helpers\DatabaseBKP\DatabaseBKP
     */
    private function verifyStructure()
    {
        if (!is_dir(DIRBKP)) {
            // Cria o Diretório que comportará todos os Backups
            mkdir(DIRBKP, 0777);
        }
        if (!is_dir(DIRBKP.$this->getDbType())) {
            // Cria o Diretório de Backup de acordo com o tipo de DB usado
            mkdir(DIRBKP.$this->getDbType(), 0777);
        }
        if (!is_dir(DIRBKP.$this->getDbType().DS.date('Y'))) {
            // Cria o diretório de acordo com o ano corrente
            mkdir(DIRBKP.$this->getDbType().DS.date('Y'), 0777);
        }
        return $this;
    }

    /**
     * Verifica a validade do timestamp do backup
     * 
     * @return boolean
     */
    private function verifyFileTimestamp()
    {
        if  (!BKPCON) {
            // Arquivo contendo o timestamp da frequencia de backup
            $filename = DIRBKP.$this->getDbType().DS.date('Y').DS.'timestamp.tmp';
            // verifica se o arquivo existe
            if (file_exists($filename)) {
                // Abre para leitura;
                // coloca o ponteiro de escrita no começo do arquivo.
                $handle = fopen ($filename, "r");
                $timestamp = fread ($handle, filesize ($filename));
                fclose ($handle);
                return $this->compareTimestamp($timestamp);
            } else {
                $timestamp = new \DateTime();
                // seta a frequencia estabelecida no arquivo de configuração
                $timestamp->modify(BKPFQC);
                // cria o arquivo com timestamp atual
                $handle = fopen ($filename, "w");
                fwrite($handle, $timestamp->getTimestamp());
                fclose ($handle);
                return true;
            }
        }
        return true;
    }

    /**
     * Compara o timestamp do backup com o timestamp atual
     * 
     * @param int $timestamp
     * @return boolean
     */
    private function compareTimestamp($timestamp) 
    {
        // Se o Timestamp do bacukp for menor, então atualiza o Timestamp
        // do arquivo e prossegue com o backp
        if ($timestamp < time()) {
            // Arquivo contendo o timestamp da frequencia de backup
            $filename = DIRBKP.$this->getDbType().DS.date('Y').DS.'timestamp.tmp';
            $timestamp = new \DateTime();
            // seta a frequencia estabelecida no arquivo de configuração
            $timestamp->modify(BKPFQC);
            // cria o arquivo com timestamp atual
            $handle = fopen ($filename, "w");
            fwrite($handle, $timestamp->getTimestamp());
            fclose ($handle);
            return true;
        } else {
            // Para a execução do script
            return false;
        }
    }

    /**
     * Cria o backup do Danco de Dados
     */
    private function createBKP()
    {
        if ($this->getDbType() == 'sqlite') {
            // Arquivo original
            $fileFrom = DATADR.$this->getDbName();
            // Arquivo de Backup
            $fileTo = DIRBKP.$this->getDbType().DS.date('Y').DS.date('Y-m-d_').$this->getDbName();
            // Copia o arquivo para o diretório de Backup
            copy($fileFrom, $fileTo);
        } else {
            try {
                // Arquivo de Backup
                $file = DIRBKP.$this->getDbType().DS.date('Y').DS.date('Y-m-d_').$this->getDbName().'.sql';
                $dump = new Dump;
                $dump->file($file)
                    ->dsn('mysql:dbname='.$this->getDbName().';host='.$this->getHost())
                    ->user($this->getUserName())
                    ->pass($this->getPassWord())
                    ->tmp('/tmp');

                new Export($dump);
            } catch (\Exception $e) {
                echo 'Export failed with message: ' . $e->getMessage();
            }
        }
    }
}
