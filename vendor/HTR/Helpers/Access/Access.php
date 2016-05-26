<?php

/**
 * @file Access.php
 * @version 0.3
 * - Helper que auxilia no gerencimento de controle de acesso a páginas
 */
namespace HTR\Helpers\Access;

use HTR\System\ModelCRUD as CRUD;
use HTR\Helpers\Session\Session;

class Access extends CRUD
{
    /**
     * Nome da entidade
     * 
     * @var string
     */
    protected $entidade = ENTLOG;
    /**
     * Nível de acesso da página
     * @var array
     */
    private $nivelAcesso = [];
    /**
     * URL de redirecionamento
     *
     * @var string
     */
    private $url;
    /**
     * Determina se o redirecionamento será continuo
     * Default Value: false
     *
     * @var boolean
     */
    private $breakRedirect = false;
    
    /**
     * Método usado para setar os possíveis niveis de acesso
     * 
     * @param array $nivelAcesso
     */
    private function setNivelAcesso(array $nivelAcesso)
    {
        // seta para o array os possíveis níveis de acesso
        array_push($this->nivelAcesso, $nivelAcesso);
        $this->nivelAcesso = $this->nivelAcesso[0];
    }

    /**
     * Método usado para comparar o nível de acesso do usuário com os 
     * padrões de níveis de acesso
     * 
     * @param array $nivelAcessoIndicado
     * @return boolean
     */
    private function verificarNivelAcesso($nivelAcessoIndicado)
    {
        // procura no Array o nível de acesso indicado nos padrões de nivel de acesso
        return in_array($nivelAcessoIndicado, $this->nivelAcesso);
    }

    /**
     * Método usado para permitir o acesso somente ao usuário logado
     * retorna os dados do usuário logado
     * 
     * @param array $nivelAcesso
     * @return array
     */
    public function authenticAccess(array $nivelAcesso)
    {
        $session = new Session();
        $session->startSession();
        // Compara o registro de token da sessão com o
        // token gerado automaticamente
        if(!isset($_SESSION['token'])) {
            $session->stopSession();
            $this->redirectTo(CTRLOG);
        }
        if ($_SESSION['token'] == $session->getToken()) {
            $result = $this->findById($_SESSION['userId']);
            // Seta os níveis de acesso permitidos na página
            $this->setNivelAcesso($nivelAcesso);
            // Verifica se o usuário tem permissão de acesso
            if (!$this->verificarNivelAcesso($result['nivel'])) {
                // Redireciona o usuário sem permissão
                // de acesso para página inicial
                $this->redirectTo();
            }
            // Verifica se há necessidade de efetuar a troca de senha
            if ($result[COLMOP]) {
                $this->redirectTo(CTRMOP);
            }
            // Retorna o resultado da consulta
            // feita no Banco de Dados com o ID fornecido
            return $result;
        }
        // Exclui a sessão
        $session->stopSession();
        // Redireciona o usuário
        $this->redirectTo();
    }

    /**
     * Método usado para evitar o RELOGIN do usuário
     * 
     * @return boolean
     */
    public function notAuthenticatedAccess()
    {
        $session = new Session();
        $session->startSession();
        // Compara o registro de token da sessão com o token gerado automaticamente
        if (!empty($_SESSION)) {
            if ($_SESSION['token'] == $session->getToken()) {
                // Redireciona para página incial
                $this->redirectTo(); // url = /
            }
        }
        $session->stopSession();
        return true;
    }

    /**
     * Redireciona o usuário
     * 
     * @param string $url
     */
    private function redirectTo($url = null)
    {
        // Redireciona se o atributo breakRedirect conter o valor false
        if (!$this->breakRedirect) {  
            $url = $this->url ? : $url;
            echo '<meta http-equiv="refresh" content="0;URL='.APPDIR.$url.'" />'
                . '<script>window.location = "'.APPDIR.$url.'"; </script>';
                header('Location:'.APPDIR.$url);
            exit;
        }
    }

        /**
     * Para o redirecionamento
     * 
     * @return \HTR\Helpers\Access\Access
     */
    public function breakRedirect()
    {
        $this->breakRedirect = true;
        return $this;
    }

    /**
     * Seta o endereço para onde o usuário será redirecionado
     * 
     * @param string $url
     */
    public function setUrl($url = null)
    {
        $this->url = $url ? : false;
        return $this;
    }

    /**
     * Limpa a lista de nivels de acesso
     * 
     * @return \HTR\Helpers\Access\Access
     */
    public function clearAccessList()
    {
        $this->nivelAcesso = array_splice($this->nivelAcesso, count($this->nivelAcesso));
        return $this;
    }
}
