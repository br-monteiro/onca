<?php

 /**
  * Criado Automaticamente pelo HTR Assist - 0.0.1
  * LAUS DEO - Programming est ars
  * @filesource Reportagem.php
  * @create 2016-05-25 13:53:14
  */
namespace App\Controllers;

use HTR\System\ControllerAbstract as Controller;
use HTR\Interfaces\ControllerInterface as CtrlInterface;
use HTR\Helpers\Access\Access;
use App\Models\ColetaModel;

class ReportagemController extends Controller implements CtrlInterface
{
    // Model padrão usado para este Controller
    private $modelPath;
    // Atributo que guarda o Objeto de Proteção de Páginas (Access)
    private $access;

    public function __construct()
    {
        parent::__construct();
        /**
         * Name do controller
         * USADO NOS LINKS DA CAMA VIEW
         * EXEMPLO:
         * @Controller
         * $this->view->controller = APPDIR.'reportagem/'
         * 
         * @View
         *  <a href='<?=$this->view->controller;?>novo' > Novo</a>
         * 
         * @Browser
         * <a href='/reportagem/novo' > Novo</a>
         */
        $this->view->controller = APPDIR.'reportagem/';
        $this->modelPath ='App\\Models\\ReportagemModel';
        // Instancia o Helper que auxilia na proteção e autenticação de usuários
        $this->access = new Access();
        $coleta = new ColetaModel($this->access->pdo);
        $this->view->resultColetaGrafico = $coleta->returnNoEmpty(4);
    }

    /**
     * Action DEFAULT
     * Atenção: Todo Controller deve conter um método 'indexAction'
     */
    public function indexAction()
    {
        // Chama a Action visualizar
        $this->visualizarAction();
    }

    /**
     * Action responsável por renderizar o formulário para novos registros
     */
    public function novoAction()
    {
        // Instanciando o Model padrão usado.
        $model = new $this->modelPath($this->access->pdo);    
        $model->novo();
    }

    public function solucionarAction()
    {
        $this->view->userLoggedIn = $this->access->authenticAccess([1,2]);
        // Instanciando o Model padrão usado.
        $model = new $this->modelPath($this->access->pdo);    
        $model->solucionar($this->getParam('id'));
    }

    public function detalharAction()
    {
        $this->view->userLoggedIn = $this->access->authenticAccess([1,2]);
        // Instanciando o Model padrão usado.
        $model = new $this->modelPath($this->access->pdo);
        $this->view->result = $model->findById($this->getParam('id'));
        $this->view->title = 'Visualisando Reportagem Cód.: ' . $this->view->result['codigo'];
        $this->render("detalhar");
    }

    /**
     * Action responsável por eliminar os registros
     */
    public function visualizarAction()
    {
        $this->view->userLoggedIn = $this->access->authenticAccess([1,2]);
        // Instanciando o Model padrão usado.
        $model = new $this->modelPath($this->access->pdo);

        // Atribui título à página através do atributo padrão '$this->view->title'
        $this->view->title = 'Lista de Reportagens não solucionadas';

        // Atribui os resultados retornados pela consulta
        // feita através do método paginator()
        $model->paginator($this->getParam('pagina'));

        $this->view->result = $model->getResultadoPaginator();
        $this->view->btn = $model->getNavePaginator();

        // Renderiza a página 'index.phtml' que encontra-se em App\Views\Reportagem\index.phtml
        $this->render('index');
    }

    public function descartarAction()
    {
        $this->view->userLoggedIn = $this->access->authenticAccess([1,2]);
        // Instanciando o Model padrão usado.
        $model = new $this->modelPath($this->access->pdo);    
        $model->descartar($this->getParam('id'));
    }
}
