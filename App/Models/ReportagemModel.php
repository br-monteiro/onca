<?php

 /**
  * Criado Automaticamente pelo HTR Assist - 0.0.1
  * LAUS DEO - Programming est ars
  * @filesource Reportagem.php
  * @create 2016-05-25 13:53:14
  */
namespace App\Models;

use HTR\System\ModelCRUD as CRUD;
use HTR\Helpers\Mensagem\Mensagem as msg;
use HTR\Helpers\Paginator\Paginator;
use Respect\Validation\Validator as v;
use App\Helpers\Util as u;

class ReportagemModel extends CRUD
{
    // Nome da entidade (tabela) usada neste Model.
    // Por padrão, é preciso fornecer o nome da entidade como string
    protected $entidade = 'reportagens';
    protected $id;
    protected $lat;
    protected $lon;
    protected $conclusao;

    private $resultadoPaginator;
    private $navPaginator;
    
    public function __construct(\PDO $pdo = null)
    {
        parent::__construct($pdo);
    }

    private function upload($dados, $id)
    {
        if (!$dados) {
            return true;
        }
        
        if ($dados['myfile']['type'] != 'image/jpeg') {
            msg::showMsg('A imagem a ser enviada tem que ser do tipo JPEG. '
                . '<strong>Extensão *.jpg</strong>.', 'danger');
        }
        
        if ($dados['myfile']['size'] == 0) {
            msg::showMsg('Ocorreu um erro ao enviar a imagem. '
                . 'Verifique se o tamanho da imagem ultrapassa <strong>2MB</strong>.', 'danger');
        }
        
        if (!move_uploaded_file($dados['myfile']['tmp_name'], 'uploads/' . $id . '.jpg')) {
            msg::showMsg('Ocorreu um erro ao salvar a imagem. '
                . 'Verifique sua conexão com a internet.', 'danger');
        }
    }

    public function descartar($id)
    {
        $file = 'uploads/' . $id . '.jpg';
        if (file_exists($file)) {
            unlink($file);
        }
        
        if (parent::remover($id)) {
            header('Location: '.APPDIR.'reportagem/');
        }
    }
    
    /**
     * Retorna todos os valores da tabela
     * @return array Valores na tabela reportagens
     */
    public function returnAll()
    {
        // Método padrão do sistema usado para retornar todos os valores
        return $this->findAll();
    }

    public function paginator($pagina)
    {
        // Preparando as diretrizes da consulta
        $dados = [
            'pdo' => $this->pdo,
            'entidade' => $this->entidade,
            'pagina' => $pagina,
            'maxResult' => 50,
            // USAR QUANDO FOR PARA DEMONSTRAR O RESULTADO DE UMA PESQUISA
            //'orderBy' => 'nome ASC',
            'where' => 'conclusao = ?',
            'bindValue' => [0 => '0']
        ];

        // Instacia o Helper que auxilia na paginação de páginas
        $paginator = new Paginator($dados);
        // Resultado da consulta
        $this->resultadoPaginator =  $paginator->getResultado();
        // Links para criação do menu de navegação da paginação @return array
        $this->navPaginator = $paginator->getNaveBtn();
    }

    // Acessivel para o Controller coletar os resultados
    public function getResultadoPaginator()
    {
        return $this->resultadoPaginator;
    }
    // Acessivel para o Controller coletar os links da paginação
    public function getNavePaginator()
    {
        return $this->navPaginator;
    }

    /**
     * Método responsável por salvar os registros
     */
    public function novo()
    {
        // Valida dados
        $this->validateAll();
        $id = $this->getId();
        $this->upload($_FILES, $id);
        
        $dados = [
            'id' => $id,
            'codigo' => u::geraCod(),
            'lat' => $this->getLat(),
            'lon' => $this->getLon(),
            'data' => time(),
            'conclusao' => 0,

        ];
        if (parent::novo($dados)) {
            msg::showMsg('Denúncia reportada com sucesso!'
                . '<script>
                    $(\'form\').each (function(){
                        this.reset();
                    });
                   </script>', 'success');
        }
    }

    /*
     * Método responsável por alterar os registros
     */
    public function solucionar($id)
    {
        $dados = [
            'conclusao' => 1,
        ];
        if (parent::editar($dados, $id)) {
            header('Location: '.APPDIR.'reportagem/visualizar/');
        }
    }

    /**
     * Validação dos Dados enviados pelo formulário
     */
    private function validateAll()
    {
        // Seta todos os valores
        $this->setId(filter_input(INPUT_POST, 'id'));
        $this->setLat(filter_input(INPUT_POST, 'lat'));
        $this->setLon(filter_input(INPUT_POST, 'lon'));

        // Inicia a Validação dos dados
        $this->validateId();
        $this->validateLat();
        $this->validateLon();
    }

    /**
     * Seta valor ao atributo id
     */
    private function setId($value)
    {
        $this->id = $value ? : time();
        return $this;
    }

    private function validateId()
    {
        $value = v::int()->validate($this->getId());
        if (!$value) {
            msg::showMsg('O campo id deve ser preenchido corretamente.'
                . '<script>focusOn("id");</script>', 'danger');
        }
        return $this;
    }
    private function validateLat()
    {
        $value = v::string()->notEmpty()->length(1, 20)->validate($this->getLat());
        if (!$value) {
            msg::showMsg('Não foi possível detectar sua localização.', 'danger');
        }
        return $this;
    }
    private function validateLon()
    {
        $value = v::string()->notEmpty()->length(1, 20)->validate($this->getLon());
        if (!$value) {
            msg::showMsg('Não foi possível detectar sua localização.', 'danger');
        }
        return $this;
    }
}
