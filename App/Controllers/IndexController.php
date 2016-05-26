<?php

/*
 * @Controller Index
 */
namespace App\Controllers;

use HTR\System\ControllerAbstract as Controller;
use HTR\Interfaces\ControllerInterface as CtrlInterface;

class IndexController extends Controller implements CtrlInterface
{

    /*
     * Inicia os atributos usados na View
     */
    public function __construct()
    {
        parent::__construct();
    }

    /*
     * Action DEFAULT
     * Atenção: Todo Controller deve conter uma Action 'indexAction'
     */
    public function indexAction()
    {
        // Renderiza a view index.phtml com o layout blank
        $this->render('index', true, 'reportagem');
    }
}
