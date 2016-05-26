<?php

/**
 * @file Init.php
 * @version 0.1
 * - Class que Inicia o Aplicativo
 */
namespace HTR;

use HTR\Init\Bootstrap;

class Init extends Bootstrap
{
    /**
     * Inicia a Aplicação
     */
    public function __construct()
    {
        parent::__construct();
        // Roda a aplicação
        $this->run();
    }
}
