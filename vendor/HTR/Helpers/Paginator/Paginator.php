<?php

/**
 * @file Paginador.php
 * @version 0.6
 * - Helper que auxilia no gerenciamento de página onde há a necessidade do uso de Links de paginação
 */
namespace HTR\Helpers\Paginator;

class Paginator
{
    /**
     * Nome da tabela usada na operação
     *
     * @var string
     */
    protected $entidade;
    /**
     * Número da página a ser exibida
     *
     * @var int
     */
    private $pagina;
    /**
     * Total de resultados
     *
     * @var int
     */
    private $totalResult;
    /**
     * Número total de páginas
     *
     * @var int 
     */
    private $totalPagina;
    /**
     * Número máximo de resultados exibidos por página
     *
     * @var int
     */
    private $maxResult;
    /**
     * Colunas que devem ser selecionadas na consulta SQL
     *
     * @var string
     */
    private $select;
    /**
     * Contem os links de navegação (botões)
     *
     * @var array
     */
    private $btn;
    /**
     * Resultado retornado da consulta feita ao Banco de Dados
     *
     * @var array
     */
    private $resultado;
    /**
     * Complementa a SQL string
     *
     * @var int
     */
    private $maxOffSet;
    /**
     * Regras de consulta
     *
     * @var string
     */
    private $where;
    /**
     * Organiza a ordem de exibição de resultados
     *
     * @var string
     */
    private $orderBy;
    /**
     * Valores usados no filtro do método bindValue do PDO
     *
     * @var array
     */
    private $bindValue;
    /**
     * Instância de PDO
     *
     * @var \PDO
     */
    private $pdo;

    /**
     * @param array $dados
     */
    public function __construct(Array $dados)
    {
        /**
         * DEFAULT
         *  [
         *      'pdo' => Instância do \PDO
         *      'entidade' => tabela do Banco de Dados
         *      'pagina' => Página Corrente
         *      'maxResult' => Número Máximo de resultados por Página
         *      'where' => regras de consulta ao Banco de Dados
         *      'bindValue' => valor que complementam a consulta
         *      'select' => indica quais campos serão selecionados. DEFAULT = *
         *  ]
         */        
        $this->setEntidade($dados['entidade'])
            ->setPDO(isset($dados['pdo']) ? $dados['pdo'] : null)
            ->setWhere(isset($dados['where']) ? $dados['where'] : null)
            ->setOrderBy(isset($dados['orderBy']) ? $dados['orderBy'] : null)
            ->setBindValue(isset($dados['bindValue']) ? $dados['bindValue'] : null)
            ->setMaxResult(isset($dados['maxResult']) ? $dados['maxResult'] : null)
            ->setSelect(isset($dados['select']) ? $dados['select'] : '*')
            ->setTotalResult()
            ->setPagina(isset($dados['pagina']) ? $dados['pagina'] : 1)
            ->setTotalPagina()
            ->setMaxOffSet()
            ->paginator()
            ->setBtn();
    }

    /**
     * Seta a instância do PDO
     * 
     * @param \PDO $pdo
     * @return \HTR\Helpers\Paginator\Paginator
     */
    private function setPDO(\PDO $pdo)
    {
        $this->pdo = $pdo;
        return $this;
    }

    /**
     * Seta o nome da tabela usada na consulta
     * 
     * @param string $entidade
     * @return \HTR\Helpers\Paginator\Paginator
     */
    private function setEntidade($entidade)
    {
        $this->entidade = $entidade;
        return $this;
    }

    /**
     * Retorna o nome da tabela
     * 
     * @return string
     */
    private function getEntidade()
    {
        return $this->entidade;
    }

    /**
     * Indica o número da página a ser exibida
     * 
     * @param int $pagina
     * @return \HTR\Helpers\Paginator\Paginator
     */
    private function setPagina($pagina)
    {
        // Verifica se foi indicada algum número de página,
        // caso ainda não tenha sido setado um valor, por padrão
        // retornará o valor '1'
        $this->pagina = isset($pagina)? $pagina : 1;
        // Verifica se o valor passado é numérico
        if (!is_numeric($this->pagina)) {
            // caso não seja, seta o valor '1' ao atributo $this->pagina
            $this->pagina = 1;
        } elseif ($this->getPagina() > $this->getTotalResult()) {
            $this->pagina = 1;
        }
        return $this;
    }

    /**
     * Retornar o número da página a ser exibida
     * 
     * @return int
     */
    private function getPagina()
    {
        return $this->pagina;
    }

    /**
     * Seta o número máximo de resultados exibidos por página
     * 
     * @param int $valor
     * @return \HTR\Helpers\Paginator\Paginator
     */
    private function setMaxResult($valor = null)
    {
        $this->maxResult = isset($valor)? $valor : 20;
        // Verifica se o valor passado é numérico
        if (!is_numeric($this->maxResult)) {
            // caso não seja, seta o valor '20' ao atributo $this->maxResult
            $this->maxResult = 20;
        }
        return $this;
    }

    /**
     * Retorna o número máximo de resultados exibidos por página
     * 
     * @return int
     */
    private function getMaxResult()
    {
        return $this->maxResult;
    }

    /**
     * Seta o nome das colunas a serem selecionadas na cosulta SQL
     * 
     * @param string $valor
     * @return \HTR\Helpers\Paginator\Paginator
     */
    private function setSelect($valor = null)
    {
        $this->select = $valor;
        return $this;
    }

    /**
     * Retorna o nome das colunas a serem selecionadas na consulta SQL
     * 
     * @return string
     */
    private function getSelect()
    {
        return $this->select;
    }

    /**
     * Total de resultados retornados da consulta
     * 
     * @return \HTR\Helpers\Paginator\Paginator
     */
    private function setTotalResult()
    {
        //SQL query
        $sql = "SELECT {$this->getSelect()} FROM {$this->getEntidade()} "
        . "{$this->getWhere()} "
        . "{$this->getOrderBy()} ;";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->getBindValue());
        $this->totalResult = count($stmt->fetchAll(\PDO::FETCH_ASSOC));
        return $this;
    }

    /**
     * @return string
     */
    private function getTotalResult()
    {
        return $this->totalResult;
    }

    /**
     * Número total de páginas
     * 
     * @return \HTR\Helpers\Paginator\Paginator
     */
    private function setTotalPagina()
    {
        $this->totalPagina = ceil($this->getTotalResult()/$this->getMaxResult());
        return $this;
    }

    /**
     * Retorna o número total de páginas
     * 
     * @return int
     */
    private function getTotalPagina()
    {
        return $this->totalPagina;
    }

    /**
     * @return \HTR\Helpers\Paginator\Paginator
     */
    private function setMaxOffSet()
    {
        $maxOffSet = ($this->getMaxResult()*$this->getPagina()) - $this->getMaxResult();
        if ($maxOffSet >= $this->getTotalResult()) {
            $this->maxOffSet = $this->getTotalResult() - 1;
            if ($this->maxOffSet < 0) {
                $this->maxOffSet = 0;
            }
        } else {
            $this->maxOffSet = $maxOffSet;
        }
        return $this;
    }

    /**
     * @return int
     */
    private function getMaxOffSet()
    {
        return $this->maxOffSet;
    }

    /**
     * Seta os parâmetros de Ordem de exibição do resultados
     * 
     * @param string $orderBy
     * @return \HTR\Helpers\Paginator\Paginator
     */
    private function setOrderBy($orderBy = null)
    {
        $this->orderBy = ($orderBy) ? ' ORDER BY '.$orderBy : null;
        return $this;
    }

    /**
     * Retorna os parâmetros de Ordem de exibição do resultados
     * 
     * @return string
     */
    private function getOrderBy(){
        return $this->orderBy;
    }

    /**
     * Seta as regras usadas na consulta SQL
     * 
     * @param string $where
     * @return \HTR\Helpers\Paginator\Paginator
     */
    private function setWhere($where = null)
    {
        $this->where = ($where) ? ' WHERE '.$where : null;
        return $this;
    }

    /**
     * Retrona as regras usadas na consulta SQL
     * 
     * @return string
     */
    private function getWhere(){
        return $this->where;
    }

    /**
     * Seta os valores usados no filtro do método bindValue() do PDO
     * 
     * @param array $bindValue
     * @return \HTR\Helpers\Paginator\Paginator
     */
    private function setBindValue($bindValue = null)
    {
        $this->bindValue = is_array($bindValue) ? $bindValue : [];
        return $this;
    }

    /**
     * @return array
     */
    private function getBindValue()
    {
        return $this->bindValue;
    }

    /**
     * Configura os links de navegação
     * 
     * @return \HTR\Helpers\Paginator\Paginator
     */
    private function setBtn()
    {
        for ($i = 1; $i < $this->getTotalPagina() + 1; $i++) {
             $this->btn[] = $i;
        }
        return $this;
    }

    /**
     * Retorna os links de navegação
     * 
     * @return array
     */
    private function getBtn()
    {
        return $this->btn;
    }

    /**
     * Organiza os resultados dos links de navegação
     * 
     * @return array
     */
    private function makeBtn()
    {
        $btn['link'] = $this->getBtn();
        $btn['previus'] = 1;
        $btn['next'] = $this->getTotalPagina();
        return $btn;        
    }

    /**
     * Retorna os links de navegação
     * 
     * @return array
     */
    public function getNaveBtn()
    {
        if (!$this->getTotalResult()) {
            // esconde os links de navegação da paginação
            echo '<style>.pagination{display:none !important;}</style>';
            return [
                'link' => [],
                'previus' => '#',
                'next' => '#'
            ];
        }
        return $this->btn = $this->makeBtn();
    }

    /**
     * Seta os resultados retornador da consulta SQL
     * 
     * @param array $resultado
     * @return \HTR\Helpers\Paginator\Paginator
     */
    private function setResultado($resultado)
    {
        $this->resultado = $resultado;
        return $this;
    }

    /**
     * Retorna os resultados retornados da consulta SQL
     * 
     * @return array
     */
    public function getResultado()
    {
        return $this->resultado;
    }

    /**
     * Executa a consulta SQL com os parâmetros configurados anteriomente
     * 
     * @return \HTR\Helpers\Paginator\Paginator
     */
    private function paginator(){
        //SQL query
        $sql = "SELECT {$this->getSelect()} FROM {$this->getEntidade()} "
        . "{$this->getWhere()} "
        . "{$this->getOrderBy()} "
        . "LIMIT {$this->getMaxOffSet()},{$this->getMaxResult()} ;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->getBindValue());
        $this->setResultado($stmt->fetchAll(\PDO::FETCH_ASSOC));
        return $this;
    }
}
