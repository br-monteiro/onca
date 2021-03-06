<?php

/**
 * @file Session.php
 * @version 0.2
 * - Helper que auxilia no gerenciamento de Sessões no PHP
 */
namespace HTR\Helpers\Session;

use HTR\Helpers\Criptografia\Criptografia as Cripto;

class Session
{
    /**
     * Recebe o id da sessão
     *
     * @var string
     */
    private $sessionId;
    /**
     * Token gerado pelo sistema
     *
     * @var string
     */
    private $token;
    /**
     * Ip do usuário
     *
     * @var mixed
     */
    private $ip;
    /**
     * User Agente
     *
     * @var string
     */
    private $userAgent;
    /**
     * Instância de Criptografia
     *
     * @var \Criptografia
     */
    private $cripto;

    public function __construct()
    {
        // instancia a Class de criptografia
        $this->cripto = new Cripto();
        // configura os atributos
        $this->config();
    }

    /*
     * Método usado para configurar os atributos da Classe
     */
    private function config()
    {
        // recebe o valor IP do usuário
        $this->ip = $_SERVER['REMOTE_ADDR'];
        // recebe o User Agente do usuário
        $this->userAgent = $_SERVER['HTTP_USER_AGENT'];
        // Seta o valor do Token gerado
        $this->setToken();
    }

    /*
     * Método usado para gerar os caracteres usados como token
     */
    private function setToken()
    {
        // String usada como Salt
        // "salt+ip+ProgramaNome+ProgramaVersao+User Agent+salt"
        $strSalt = STRSAL.$this->ip.APPNAM.APPVER.$this->userAgent.STRSAL;
        $this->token = $this->cripto->encode($strSalt, true);
    }

    /*
     * Método usado para gerar os caracteres usados como token
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * 
     * @param string $sessionId
     * @return boolean|null
     */
    public function startSession($sessionId = null)
    {
        /// Verfica se foi passado um id de sessÃ£o existente
        if ($sessionId) {
            /// Recupera sessão exitente
            isset($this->sessionId) ? session_id($sessionId) : null;
        }
        /// verifica se a GLOBAL SESSION foi iniciada
        if (isset($_SESSION)) {
            /// Compara o token da sessão
            if ($_SESSION['token'] != $this->getToken()) {
                // se houver divergencia no token, destroy a sessão
               $this->stopSession();
               return false;
            }
            return true;
        } else {
            /// Caso a Sessão não seja iniciada, inicia o processo de criação da sessão
            session_set_cookie_params( 
                1800, // Tempo de vida da sessão. Padrão 30min
                APPDIR, // Path da Sessão
                DOMAIN, // Nome no Domínio
                false, // SSL
                true // HTTP Only
            );
            session_start();
            session_regenerate_id(true);
        }
        // gera um ID novo para a sessão
        // seta o ID da sessão para o atributo 'sessionId'
        $this->sessionId = session_id();
        return null;
    }

    /**
     * Método usado para destruir as sessões
     * 
     * @return boolean
     */
    public function stopSession()
    {
        // Verifica se a global foi iniciada, caso contrário inicia a sessão
        isset($_SESSION) ? null : session_start();
        // Destroi a sessão
        return session_destroy();
    }
}
