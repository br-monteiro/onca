<?php

 /**
  * Criado Automaticamente pelo HTR Assist - 0.0.1
  * LAUS DEO - Programming est ars
  * @filesource Material.php
  * @create 2016-05-24 11:48:14
  */
namespace App\Models;

use HTR\System\ModelCRUD as CRUD;
use HTR\Helpers\Mensagem\Mensagem as msg;
use HTR\Helpers\Paginator\Paginator;
use Respect\Validation\Validator as v;

class MaterialModel extends CRUD
{
    // Nome da entidade (tabela) usada neste Model.
    // Por padrão, é preciso fornecer o nome da entidade como string
    protected $entidade = 'materiais';
    protected $id;
    protected $tipo;
    protected $caracteristica;

    private $resultadoPaginator;
    private $navPaginator;
    
    public function __construct(\PDO $pdo = null)
    {
        parent::__construct($pdo);
    }

    /**
     * Retorna todos os valores da tabela
     * @return array Valores na tabela materiais
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
            'maxResult' => 20,
            // USAR QUANDO FOR PARA DEMONSTRAR O RESULTADO DE UMA PESQUISA
            //'orderBy' => 'nome ASC',
            //'where' => 'nome LIKE ?',
            //'bindValue' => [0 => '%MONTEIRO%']
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
        // Verifica se há registro igual e evita a duplicação
        $this->notDuplicate();
        
        $dados = [
            'id' => $this->getId(),
            'tipo' => $this->getTipo(),
            'caracteristica' => $this->getCaracteristica(),

        ];
        if (parent::novo($dados)) {
            msg::showMsg('111', 'success');
        }
    }

    /*
     * Método responsável por alterar os registros
     */
    public function editar()
    {
        // Valida dados
        $this->validateAll();
        // Verifica se há registro igual e evita a duplicação
        $this->notDuplicate();
        
        $dados = [
            'id' => $this->getId(),
            'tipo' => $this->getTipo(),
            'caracteristica' => $this->getCaracteristica(),

        ];
        if (parent::editar($dados, $this->getId())) {
            msg::showMsg('001', 'success');
        }
    }

    /*
     * Método responsável por remover os registros do sistema
     */
    public function remover($id)
    {
        if (parent::remover($id)) {
            header('Location: '.APPDIR.'material/visualizar/');
        }
    }

    /**
     * Evita a duplicidade de registros no sistema
     */
    private function notDuplicate()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->entidade} WHERE id != ? AND tipo = ?");
        $stmt->bindValue(1, $this->getId());
        $stmt->bindValue(2, $this->getTipo());
        $stmt->execute();
        if ($stmt->fetch(\PDO::FETCH_ASSOC)) {
            msg::showMsg('Já existe um registro com este(s) caractere(s) no campo ' 
                . '<strong>tipo</strong>.'
                . '<script>focusOn("tipo")</script>', 'warning');
        }
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->entidade} WHERE id != ? AND caracteristica = ?");
        $stmt->bindValue(1, $this->getId());
        $stmt->bindValue(2, $this->getCaracteristica());
        $stmt->execute();
        if ($stmt->fetch(\PDO::FETCH_ASSOC)) {
            msg::showMsg('Já existe um registro com este(s) caractere(s) no campo ' 
                . '<strong>caracteristica</strong>.'
                . '<script>focusOn("caracteristica")</script>', 'warning');
        }

    }

    /**
     * Validação dos Dados enviados pelo formulário
     */
    private function validateAll()
    {
        // Seta todos os valores
        $this->setId(filter_input(INPUT_POST, 'id'));
        $this->setTipo(filter_input(INPUT_POST, 'tipo'));
        $this->setCaracteristica(filter_input(INPUT_POST, 'caracteristica'));

        // Inicia a Validação dos dados
        $this->validateId();
        $this->validateTipo();
        $this->validateCaracteristica();

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
    private function validateTipo()
    {
        $value = v::string()->notEmpty()->length(1, 15)->validate($this->getTipo());
        if (!$value) {
            msg::showMsg('O campo tipo deve ser preenchido corretamente.'
                . '<script>focusOn("tipo");</script>', 'danger');
        }
        return $this;
    }
    private function validateCaracteristica()
    {
        $value = v::string()->notEmpty()->length(1, 50)->validate($this->getCaracteristica());
        if (!$value) {
            msg::showMsg('O campo caracteristica deve ser preenchido corretamente.'
                . '<script>focusOn("caracteristica");</script>', 'danger');
        }
        return $this;
    }

}
