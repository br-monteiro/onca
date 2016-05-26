<?php

 /**
  * Criado Automaticamente pelo HTR Assist - 0.0.1
  * LAUS DEO - Programming est ars
  * @filesource CooperativaController.php
  * @create 2016-05-24 11:49:08
  */
namespace App\Controllers;

use HTR\System\ControllerAbstract as Controller;
use HTR\Interfaces\ControllerInterface as CtrlInterface;
use HTR\Helpers\Access\Access;
use App\Models\ColetaModel as Coleta;

class CooperativaController extends Controller implements CtrlInterface
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
         * $this->view->controller = APPDIR.'cooperativa/'
         * 
         * @View
         *  <a href='<?=$this->view->controller;?>novo' > Novo</a>
         * 
         * @Browser
         * <a href='/cooperativa/novo' > Novo</a>
         */
        $this->view->controller = APPDIR.'cooperativa/';
        $this->modelPath ='App\\Models\\CooperativaModel';
        // Instancia o Helper que auxilia na proteção e autenticação de usuários
        $this->access = new Access();
        // Inicia a proteção das páginas com permissão de acesso apenas para
        // usuários autenticados com o nível 1.
        $this->view->userLoggedIn = $this->access->authenticAccess([1]);
        $coleta = new Coleta;
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
        // Atribui título à página através do atributo padrão '$this->view->title'
        $this->view->title = 'Novo Registro';
        // Renderiza a página 'form_novo.phtml' que encontra-se em App\Views\Cooperativaorm_novo.phtml
        $this->render('form_novo');
        
    }

    /**
     * Action responsável por renderizar o formulário para edição de registros
     */
    public function editarAction()
    {
        // Instanciando o Model padrão usado.
        $model = new $this->modelPath();
        
        // Atribui título à página através do atributo padrão '$this->view->title'
        $this->view->title = 'Editando Registro';
        
        // Executa a consulta no Banco de Dados
        $this->view->result = $model->findById($this->getParam('id'));
        // Renderiza a página 'form_editar.phtml' que encontra-se em App\Views\Cooperativaorm_editar.phtml
        $this->render('form_editar');
    }

    /**
     * Action responsável por eliminar os registros
     */
    public function eliminarAction()
    {
        // Instanciando o Model padrão usado.
        $model = new $this->modelPath();
        $model->remover($this->getParam('id'));
    }

    /**
     * Action responsável por eliminar os registros
     */
    public function visualizarAction()
    {
        // Instanciando o Model padrão usado.
        $model = new $this->modelPath();

        // Atribui título à página através do atributo padrão '$this->view->title'
        $this->view->title = 'Lista de Todos os Registros de Cooperativa';

        // Atribui os resultados retornados pela consulta
        // feita através do método paginator()
        $model->paginator($this->getParam('pagina'));

        $this->view->result = $model->getResultadoPaginator();
        $this->view->btn = $model->getNavePaginator();

        // Renderiza a página 'index.phtml' que encontra-se em App\Views\Cooperativa\index.phtml
        $this->render('index');
    }

    /**
     * Action responsável controlar a inserção de registros
     */
    public function registraAction()
    {
        // Instanciando o Model padrão usado.
        $model = new $this->modelPath();    
        $model->novo();
    }

    /**
     * Action responsável controlar a edição de registros
     */
    public function alteraAction()
    {
        // Instanciando o Model padrão usado.
        $model = new $this->modelPath();
        $model->editar();
    }
}
