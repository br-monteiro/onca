<?php

 /**
  * Criado Automaticamente pelo HTR Assist - 0.0.1
  * LAUS DEO - Programming est ars
  * @filesource DistribuicaoController.php
  * @create 2016-05-24 11:49:26
  */
namespace App\Controllers;

use HTR\System\ControllerAbstract as Controller;
use HTR\Interfaces\ControllerInterface as CtrlInterface;
use HTR\Helpers\Access\Access;
use App\Models\CooperativaModel;
use App\Models\ColetaModel;

class DistribuicaoController extends Controller implements CtrlInterface
{
    // Model padrão usado para este Controller
    private $modelPath;
    // Atributo que guarda o Objeto de Proteção de Páginas (Access)
    private $access;
    
    private $coleta;

    public function __construct()
    {
        parent::__construct();
        /**
         * Name do controller
         * USADO NOS LINKS DA CAMA VIEW
         * EXEMPLO:
         * @Controller
         * $this->view->controller = APPDIR.'distribuicao/'
         * 
         * @View
         *  <a href='<?=$this->view->controller;?>novo' > Novo</a>
         * 
         * @Browser
         * <a href='/distribuicao/novo' > Novo</a>
         */
        $this->view->controller = APPDIR.'distribuicao/';
        $this->modelPath ='App\\Models\\DistribuicaoModel';
        // Instancia o Helper que auxilia na proteção e autenticação de usuários
        $this->access = new Access();
        // Inicia a proteção das páginas com permissão de acesso apenas para
        // usuários autenticados com o nível 1.
        $this->view->userLoggedIn = $this->access->authenticAccess([1]);
        $this->coleta = new ColetaModel($this->access->pdo);
        $this->view->resultColetaGrafico = $this->coleta->returnNoEmpty(4);
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
        // Atribui título à página através do atributo padrão '$this->view->title'
        $this->view->title = 'Novo Registro';
        
        $cooperativa = new CooperativaModel($this->access->pdo);
        $this->view->resultCooperativa = $cooperativa->returnAll();
        $this->view->resultColeta = $this->coleta->returnNoEmpty();
        
        // Renderiza a página 'form_novo.phtml' que encontra-se em App\Views\Distribuicaoorm_novo.phtml
        $this->render('form_novo');
        
    }

    /**
     * Action responsável por eliminar os registros
     */
    public function visualizarAction()
    {
        // Instanciando o Model padrão usado.
        $model = new $this->modelPath($this->access->pdo);

        // Atribui título à página através do atributo padrão '$this->view->title'
        $this->view->title = 'Lista de Todos os Registros de Distribuicao';

        // Atribui os resultados retornados pela consulta
        // feita através do método paginator()
        $model->paginator($this->getParam('pagina'));

        $this->view->result = $model->getResultadoPaginator();
        $this->view->btn = $model->getNavePaginator();

        // Renderiza a página 'index.phtml' que encontra-se em App\Views\Distribuicao\index.phtml
        $this->render('index');
    }

    /**
     * Action responsável controlar a inserção de registros
     */
    public function registraAction()
    {
        // Instanciando o Model padrão usado.
        $model = new $this->modelPath($this->access->pdo);    
        $model->novo();
    }

    public function imprimirAction()
    {
        $model = new $this->modelPath;
        $this->view->result = $model->returnPrint($this->getParam('id'));
        $this->render('imprimir', true, 'blank');
    }
    
    public function cancelarAction()
    {
        // Instanciando o Model padrão usado.
        $model = new $this->modelPath();    
        $model->cancelar($this->getParam('id'));
    }

}
