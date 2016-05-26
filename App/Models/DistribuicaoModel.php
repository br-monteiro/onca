<?php

 /**
  * Criado Automaticamente pelo HTR Assist - 0.0.1
  * LAUS DEO - Programming est ars
  * @filesource Distribuicao.php
  * @create 2016-05-24 11:49:26
  */
namespace App\Models;

use HTR\System\ModelCRUD as CRUD;
use HTR\Helpers\Mensagem\Mensagem as msg;
use HTR\Helpers\Paginator\Paginator;
use Respect\Validation\Validator as v;
use App\Models\ColetaModel as Coleta;
use App\Helpers\Util as u;

class DistribuicaoModel extends CRUD
{
    // Nome da entidade (tabela) usada neste Model.
    // Por padrão, é preciso fornecer o nome da entidade como string
    protected $entidade = 'distribuicoes';
    protected $id;
    protected $coletasId;
    protected $cooperativasId;
    protected $codigo;
    protected $quantidade;
    protected $uf;

    private $resultadoPaginator;
    private $navPaginator;
    
    public function deleteByColtasId($idColetas)
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->entidade} WHERE coletas_id = {$idColetas}");
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    private function validarRetirada($dados)
    {
        if ($dados['quantidade_distribuida'] < $this->getQuantidade()) {
            return $dados['quantidade_distribuida'];
        }
        return $this->getQuantidade();       
    }
    
    public function cancelar($id)
    {
        $coleta = new Coleta;
        $dados = $this->findById($id);
        $coleta->adicionaQquantidade($dados['coletas_id'], $dados['quantidade']);
        if (parent::remover($id)) {
            header('Location: '.APPDIR.'distribuicao/visualizar/');
        }
    }

    public function returnPrint($id)
    {
        $stmt = $this->pdo->prepare("SELECT `distribuicoes`.*, `materiais`.`tipo`,"
            . "`materiais`.`caracteristica`, `cooperativas`.`nome`, `cooperativas`.`sigla`,"
            . "`cooperativas`.`responsavel_nome` "
            . "FROM `distribuicoes` "
            . "INNER JOIN `materiais` ON `distribuicoes`.`materiais_id` = `materiais`.`id` "
            . "INNER JOIN `cooperativas` ON `distribuicoes`.`cooperativas_id` = `cooperativas`.`id` "
            . "WHERE `distribuicoes`.`id` = ?");
        $stmt->bindValue(1, $id);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    /**
     * Retorna todos os valores da tabela
     * @return array Valores na tabela distribuicoes
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
            'select' => '`distribuicoes`.*, `cooperativas`.`sigla`, `materiais`.`caracteristica`',
            'entidade' => '`distribuicoes` INNER JOIN `materiais` ON `distribuicoes`.`materiais_id` = `materiais`.`id`'
                . 'INNER JOIN `cooperativas` ON `distribuicoes`.`cooperativas_id` = `cooperativas`.`id`',
            'orderBy' => '`distribuicoes`.`data` DESC ',
            'pagina' => $pagina,
            'maxResult' => 50
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
        
        // instancia o Model de coleta
        $coleta = new Coleta;
        $dados = $coleta->findById($this->getColetasId());
        
        // valida a quantidade a ser retirada para que o solicitado nunca ultrapasse o disponível
        $quantidade = $this->validarRetirada($dados);

        $id = $this->getId();
        $dados = [
            'id' => $id,
            'coletas_id' => $this->getColetasId(),
            'materiais_id' => $dados['materiais_id'],
            'cooperativas_id' => $this->getCooperativasId(),
            'codigo' => $this->getCodigo(),
            'data' => time(),
            'quantidade' => $quantidade,
            'uf' => $dados['uf'],

        ];

        if (parent::novo($dados) && $coleta->retirarQquantidade($this->getColetasId(), $quantidade)) {
            msg::showMsg('A distribuição foi efetuada com sucesso!<br>'
                    . "Deseja <a href=\"".APPDIR."distribuicao/imprimir/id/{$id}\"
                        class=\"btn btn-primary\" target=\"_blank\">
                        <i class=\"fa fa-print\"></i> Imprimir</a> a declaração?"
                    . "<script>resetForm();</script>", 'success');
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
            'coletas_id' => $this->getColetasId(),
            'cooperativas_id' => $this->getCooperativasId(),
            'codigo' => $this->getCodigo(),
            'quantidade' => $this->getQuantidade(),
            'uf' => $this->getUf()

        ];
        if (parent::editar($dados, $this->getId())) {
            msg::showMsg('001', 'success');
        }
    }

    /**
     * Evita a duplicidade de registros no sistema
     */
    private function notDuplicate()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->entidade} WHERE id != ? AND codigo = ?");
        $stmt->bindValue(1, $this->getId());
        $stmt->bindValue(2, $this->getCodigo());
        $stmt->execute();
        if ($stmt->fetch(\PDO::FETCH_ASSOC)) {
            msg::showMsg('Já existe um registro com este <strong>código</strong>.', 'warning');
        }
    }

    /**
     * Validação dos Dados enviados pelo formulário
     */
    private function validateAll()
    {
        // Seta todos os valores
        $this->setId(filter_input(INPUT_POST, 'id'));
        $this->setColetasId(filter_input(INPUT_POST, 'coletas_id'));
        $this->setCooperativasId(filter_input(INPUT_POST, 'cooperativas_id'));
        $this->setCodigo(filter_input(INPUT_POST, 'codigo'));
        $this->setQuantidade(filter_input(INPUT_POST, 'quantidade'));

        // Inicia a Validação dos dados
        $this->validateId();
        $this->validateColetasId();
        $this->validateCooperativasId();
        $this->validateCodigo();
        $this->validateQuantidade();

    }

    /**
     * Seta valor ao atributo id
     */
    private function setId($value)
    {
        $this->id = $value ? : time();
        return $this;
    }
    
    private function setCodigo($value = null)
    {
        $this->codigo = $value ? : u::geraCod();
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
    private function validateColetasId()
    {
        $value = v::int()->validate($this->getColetasId());
        if (!$value) {
            msg::showMsg('O campo coletas_id deve ser preenchido corretamente.'
                . '<script>focusOn("coletas_id");</script>', 'danger');
        }
        return $this;
    }
    private function validateCooperativasId()
    {
        $value = v::int()->validate($this->getCooperativasId());
        if (!$value) {
            msg::showMsg('O campo cooperativas_id deve ser preenchido corretamente.'
                . '<script>focusOn("cooperativas_id");</script>', 'danger');
        }
        return $this;
    }
    private function validateCodigo()
    {
        $value = v::string()->notEmpty()->length(1, 20)->validate($this->getCodigo());
        if (!$value) {
            msg::showMsg('O campo codigo deve ser preenchido corretamente.'
                . '<script>focusOn("codigo");</script>', 'danger');
        }
        return $this;
    }
    private function validateQuantidade()
    {
        $value = v::int()->validate($this->getQuantidade());
        if (!$value) {
            msg::showMsg('O campo quantidade deve ser preenchido corretamente.'
                . '<script>focusOn("quantidade");</script>', 'danger');
        }
        return $this;
    }

}
