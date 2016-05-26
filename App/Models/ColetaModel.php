<?php

 /**
  * Criado Automaticamente pelo HTR Assist - 0.0.1
  * LAUS DEO - Programming est ars
  * @filesource Coleta.php
  * @create 2016-05-24 11:48:46
  */
namespace App\Models;

use HTR\System\ModelCRUD as CRUD;
use HTR\Helpers\Mensagem\Mensagem as msg;
use HTR\Helpers\Paginator\Paginator;
use Respect\Validation\Validator as v;
use App\Models\DistribuicaoModel as Distribuicao;

class ColetaModel extends CRUD
{
    // Nome da entidade (tabela) usada neste Model.
    // Por padrão, é preciso fornecer o nome da entidade como string
    protected $entidade = 'coletas';
    protected $id;
    protected $materiaisId;
    protected $uf;
    protected $quantidadeInicial;
    protected $quantidadeDistribuida;

    private $resultadoPaginator;
    private $navPaginator;

    public function cancelar($id)
    {
        $distibuicao = new Distribuicao;
        $distibuicao->deleteByColtasId($id);
        if (parent::remover($id)) {
            header('Location: '.APPDIR.'coleta/');
        }
    }
    /**
     * Retorna os itens coletados não zerados
     * @return Array
     */
    public function returnNoEmpty($maxResult = null)
    {
        $maxResult = !$maxResult ? null : 'LIMIT ' . $maxResult;
        $stmt = $this->pdo->prepare("SELECT `coletas`.`id`, `coletas`.`uf`, "
            . "`coletas`.`quantidade_distribuida` as quantidade, `coletas`.`quantidade_inicial`, `materiais`.`caracteristica`,"
            . "`materiais`.`tipo` FROM `coletas` INNER JOIN `materiais` "
            . "ON `coletas`.`materiais_id` = `materiais`.`id` "
            . "WHERE `coletas`.`quantidade_distribuida` != 0 ORDER BY `coletas`.`data` DESC {$maxResult}");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function retirarQquantidade($id, $quantidade)
    {
        $dados = $this->findById($id);
        $quantidade = $dados['quantidade_distribuida'] - $quantidade;
        $dados = ['quantidade_distribuida' => $quantidade];
        if (parent::editar($dados, $id)) {
            return true;
        }
        return false;
    }

    public function adicionaQquantidade($id, $quantidade)
    {
        $dados = $this->findById($id);
        $quantidade = $dados['quantidade_distribuida'] + $quantidade;
        $dados = ['quantidade_distribuida' => $quantidade];
        if (parent::editar($dados, $id)) {
            return true;
        }
        return false;
    }
    /**
     * Retorna todos os valores da tabela
     * @return array Valores na tabela coletas
     */
    public function returnAll()
    {
        // Método padrão do sistema usado para retornar todos os valores
        return $this->findAll();
    }

    public function paginator($pagina)
    {
        $dados = [
            'pdo' => $this->pdo,
            'select' => '`coletas`.*, `materiais`.`tipo`, `materiais`.`caracteristica`',
            'entidade' => '`coletas` INNER JOIN `materiais` ON `coletas`.`materiais_id` = `materiais`.`id`',
            'where' => '`coletas`.`quantidade_distribuida` > 0',
            'pagina' => $pagina,
            'maxResult' => 50,
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
        
        $dados = [
            'id' => $this->getId(),
            'materiais_id' => $this->getMateriaisId(),
            'data' => time(),
            'uf' => $this->getUf(),
            'quantidade_inicial' => $this->getQuantidadeInicial(),
            'quantidade_distribuida' => $this->getQuantidadeInicial(),

        ];
        if (parent::novo($dados)) {
            msg::showMsg('111', 'success');
        }
    }

    /**
     * Validação dos Dados enviados pelo formulário
     */
    private function validateAll()
    {
        // Seta todos os valores
        $this->setId(filter_input(INPUT_POST, 'id'));
        $this->setMateriaisId(filter_input(INPUT_POST, 'materiais_id'));
        $this->setUf(filter_input(INPUT_POST, 'uf'));
        $this->setQuantidadeInicial(filter_input(INPUT_POST, 'quantidade_inicial'));

        // Inicia a Validação dos dados
        $this->validateId();
        $this->validateMateriaisId();
        $this->validateUf();
        $this->validateQuantidadeInicial();

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
    private function validateMateriaisId()
    {
        $value = v::int()->validate($this->getMateriaisId());
        if (!$value) {
            msg::showMsg('O campo materiais_id deve ser preenchido corretamente.'
                . '<script>focusOn("materiais_id");</script>', 'danger');
        }
        return $this;
    }
    private function validateUf()
    {
        $value = v::string()->notEmpty()->length(1, 4)->validate($this->getUf());
        if (!$value) {
            msg::showMsg('O campo uf deve ser preenchido corretamente.'
                . '<script>focusOn("uf");</script>', 'danger');
        }
        return $this;
    }
    private function validateQuantidadeInicial()
    {
        $value = v::int()->validate($this->getQuantidadeInicial());
        if (!$value) {
            msg::showMsg('O campo quantidade_inicial deve ser preenchido corretamente.'
                . '<script>focusOn("quantidade_inicial");</script>', 'danger');
        }
        return $this;
    }

}
