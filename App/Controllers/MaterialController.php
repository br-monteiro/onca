<?php

 /**
  * Criado Automaticamente pelo HTR Assist - 0.0.1
  * LAUS DEO - Programming est ars
  * @filesource MaterialController.php
  * @create 2016-05-24 11:48:14
  */
namespace App\Controllers;

use HTR\System\ControllerAbstract as Controller;
use HTR\Interfaces\ControllerInterface as CtrlInterface;
use HTR\Helpers\Access\Access;
use App\Models\ColetaModel;

class MaterialController extends Controller implements CtrlInterface
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
         * $this->view->controller = APPDIR.'material/'
         * 
         * @View
         *  <a href='<?=$this->view->controller;?>novo' > Novo</a>
         * 
         * @Browser
         * <a href='/material/novo' > Novo</a>
         */
        $this->view->controller = APPDIR.'material/';
        $this->modelPath ='App\\Models\\MaterialModel';
        // Instancia o Helper que auxilia na proteção e autenticação de usuários
        $this->access = new Access();
        // Inicia a proteção das páginas com permissão de acesso apenas para
        // usuários autenticados com o nível 1.
        $this->view->userLoggedIn = $this->access->authenticAccess([1]);
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
        // Atribui título à página através do atributo padrão '$this->view->title'
        $this->view->title = 'Novo Registro';
        // Renderiza a página 'form_novo.phtml' que encontra-se em App\Views\Materialorm_novo.phtml
        $this->render('form_novo');
        
    }

    /**
     * Action responsável por renderizar o formulário para edição de registros
     */
    public function editarAction()
    {
        // Instanciando o Model padrão usado.
        $model = new $this->modelPath($this->access->pdo);
        
        // Atribui título à página através do atributo padrão '$this->view->title'
        $this->view->title = 'Editando Registro';
        
        // Executa a consulta no Banco de Dados
        $this->view->result = $model->findById($this->getParam('id'));
        // Renderiza a página 'form_editar.phtml' que encontra-se em App\Views\Materialorm_editar.phtml
        $this->render('form_editar');
    }

    /**
     * Action responsável por eliminar os registros
     */
    public function eliminarAction()
    {
        // Instanciando o Model padrão usado.
        $model = new $this->modelPath($this->access->pdo);
        $model->remover($this->getParam('id'));
    }

    /**
     * Action responsável por eliminar os registros
     */
    public function visualizarAction()
    {
        // Instanciando o Model padrão usado.
        $model = new $this->modelPath($this->access->pdo);

        // Atribui título à página através do atributo padrão '$this->view->title'
        $this->view->title = 'Lista de Todos os Registros de Material';

        // Atribui os resultados retornados pela consulta
        // feita através do método paginator()
        $model->paginator($this->getParam('pagina'));

        $this->view->result = $model->getResultadoPaginator();
        $this->view->btn = $model->getNavePaginator();

        // Renderiza a página 'index.phtml' que encontra-se em App\Views\Material\index.phtml
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

    /**
     * Action responsável controlar a edição de registros
     */
    public function alteraAction()
    {
        // Instanciando o Model padrão usado.
        $model = new $this->modelPath($this->access->pdo);
        $model->editar();
    }
}
