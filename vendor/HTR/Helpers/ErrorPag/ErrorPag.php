<?php

/**
 * @file ErrorPag.php
 * @version 0.1
 * - Helper que gerencia a inclusão de páginas de erro do sistema
 */
namespace HTR\Helpers\ErrorPag;

class ErrorPag
{
    /**
     * @param string $layout_error
     */
    public function __construct($layout_error)
    {
        $this->getError($layout_error);
    }

    /**
     * Inclui a página de erro
     * 
     * @param string $layout_error
     */
    private function getError($layout_error)
    {
        require_once ERRPAG.$layout_error.'.phtml';
        exit();
    }
}
