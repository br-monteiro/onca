<?php

/**
 * @file ControllerAbstract.php
 * @version 0.5
 * - Class responsavel por gerenciar os Controllers da Aplicação
 */
namespace HTR\System;

use HTR\Helpers\ErrorPag\ErrorPag as Error;

class ControllerAbstract
{
    protected $view;
    protected $pagina;

    public function __construct()
    {
        $this->view = new \stdClass();
    }

    /**
     * Renderiza a página
     * 
     * @param string $pagina Nome do Arquivo a ser renderizado
     * @param boolean $useLaytou Uso de outro Layout
     * @param string $alternativeLayout Nome do Layout alternativo
     */
    protected function render($pagina, $useLaytou = true, $alternativeLayout = 'default')
    {
        $this->pagina = $pagina;
        $fileLayout = DRINST . "App/Views/Layout/{$alternativeLayout}.phtml";
        if ($useLaytou == true && file_exists($fileLayout)) {
            include_once "$fileLayout";
        } else {
            echo $this->content();
        }
    }

    /**
     * Conteúdo da página
     */
    protected function content()
    {
      $classAtual = get_class($this);
      $controller = strtolower(str_replace("App\\Controllers\\", "", $classAtual));
      $singleClassName = str_replace("controller", "", $controller);
      $filename = DRINST . 'App/Views/'.ucfirst($singleClassName).'/'.$this->pagina.'.phtml';
      if (!file_exists($filename)) {
          new Error('error_404');
      }
      include_once $filename;
    }

    /**
     * Retorna os parâmetros setados na URL
     * Se o valor de $key for idêntico a true, então retonar o array de URL completo
     * 
     * @param mixed $key Nome do Parâmetro ou Chave do array
     * @return array|string Retorna o parâmetro requisitado
     */
    protected function getParam($key = null)
    {
        $params = new \HTR\Init\Bootstrap();
        return $params->getParam($key);
    }
}
