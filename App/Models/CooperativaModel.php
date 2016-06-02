<?php

 /**
  * Criado Automaticamente pelo HTR Assist - 0.0.1
  * LAUS DEO - Programming est ars
  * @filesource Cooperativa.php
  * @create 2016-05-24 11:49:08
  */
namespace App\Models;

use HTR\System\ModelCRUD as CRUD;
use HTR\Helpers\Mensagem\Mensagem as msg;
use HTR\Helpers\Paginator\Paginator;
use Respect\Validation\Validator as v;

class CooperativaModel extends CRUD
{
    // Nome da entidade (tabela) usada neste Model.
    // Por padrão, é preciso fornecer o nome da entidade como string
    protected $entidade = 'cooperativas';
    protected $id;
    protected $nome;
    protected $sigla;
    protected $tipoDoc;
    protected $cnpjCpf;
    protected $responsavelNome;
    protected $endereco;
    protected $foneFixo;
    protected $foneCelular;
    protected $email;

    private $resultadoPaginator;
    private $navPaginator;
    
    public function __construct(\PDO $pdo = null)
    {
        parent::__construct($pdo);
    }

    /**
     * Retorna todos os valores da tabela
     * @return array Valores na tabela cooperativas
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
            'nome' => $this->getNome(),
            'sigla' => $this->getSigla(),
            'tipo_doc' => $this->getTipoDoc(),
            'cnpj_cpf' => $this->getCnpjCpf(),
            'responsavel_nome' => $this->getResponsavelNome(),
            'endereco' => $this->getEndereco(),
            'fone_fixo' => $this->getFoneFixo(),
            'fone_celular' => $this->getFoneCelular(),
            'email' => $this->getEmail(),

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
            'nome' => $this->getNome(),
            'sigla' => $this->getSigla(),
            'tipo_doc' => $this->getTipoDoc(),
            'cnpj_cpf' => $this->getCnpjCpf(),
            'responsavel_nome' => $this->getResponsavelNome(),
            'endereco' => $this->getEndereco(),
            'fone_fixo' => $this->getFoneFixo(),
            'fone_celular' => $this->getFoneCelular(),
            'email' => $this->getEmail(),

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
            header('Location: '.APPDIR.'cooperativa/visualizar/');
        }
    }

    /**
     * Evita a duplicidade de registros no sistema
     */
    private function notDuplicate()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->entidade} WHERE id != ? AND sigla = ?");
        $stmt->bindValue(1, $this->getId());
        $stmt->bindValue(2, $this->getSigla());
        $stmt->execute();
        if ($stmt->fetch(\PDO::FETCH_ASSOC)) {
            msg::showMsg('Já existe um registro com este(s) caractere(s) no campo ' 
                . '<strong>Sigla</strong>.'
                . '<script>focusOn("sigla")</script>', 'warning');
        }
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->entidade} WHERE id != ? AND nome = ?");
        $stmt->bindValue(1, $this->getId());
        $stmt->bindValue(2, $this->getNome());
        $stmt->execute();
        if ($stmt->fetch(\PDO::FETCH_ASSOC)) {
            msg::showMsg('Já existe um registro com este(s) caractere(s) no campo ' 
                . '<strong>Nome</strong>.'
                . '<script>focusOn("nome")</script>', 'warning');
        }
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->entidade} WHERE id != ? AND cnpj_cpf = ?");
        $stmt->bindValue(1, $this->getId());
        $stmt->bindValue(2, $this->getCnpjCpf());
        $stmt->execute();
        if ($stmt->fetch(\PDO::FETCH_ASSOC)) {
            msg::showMsg('Já existe um registro com este(s) caractere(s) no campo ' 
                . '<strong>CNPJ/CPF</strong>.'
                . '<script>focusOn("cnpj_cpf")</script>', 'warning');
        }
    }

    /**
     * Validação dos Dados enviados pelo formulário
     */
    private function validateAll()
    {
        // Seta todos os valores
        $this->setId(filter_input(INPUT_POST, 'id'));
        $this->setNome(filter_input(INPUT_POST, 'nome'));
        $this->setSigla(filter_input(INPUT_POST, 'sigla'));
        $this->setTipoDoc(filter_input(INPUT_POST, 'tipo_doc'));
        $this->setCnpjCpf(filter_input(INPUT_POST, 'cnpj_cpf'));
        $this->setResponsavelNome(filter_input(INPUT_POST, 'responsavel_nome'));
        $this->setEndereco(filter_input(INPUT_POST, 'endereco'));
        $this->setFoneFixo(filter_input(INPUT_POST, 'fone_fixo'));
        $this->setFoneCelular(filter_input(INPUT_POST, 'fone_celular'));
        $this->setEmail(filter_input(INPUT_POST, 'email'));

        // Inicia a Validação dos dados
        $this->validateId();
        $this->validateSigla();
        $this->validateNome();
        $this->validateTipoDoc();
        $this->validateCnpjCpf();
        $this->validateResponsavelNome();
        $this->validateEndereco();
        $this->validateFoneFixo();
        $this->validateFoneCelular();
        $this->validateEmail();

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
    private function validateNome()
    {
        $value = v::string()->notEmpty()->length(1, 50)->validate($this->getNome());
        if (!$value) {
            msg::showMsg('O campo nome deve ser preenchido corretamente.'
                . '<script>focusOn("nome");</script>', 'danger');
        }
        return $this;
    }
    private function validateSigla()
    {
        $value = v::string()->notEmpty()->length(1, 20)->validate($this->getSigla());
        if (!$value) {
            msg::showMsg('O campo sigla deve ser preenchido corretamente.'
                . '<script>focusOn("sigla");</script>', 'danger');
        }
        return $this;
    }
    private function validateCnpjCpf()
    {
        // 1 - CNPJ; 2 - CPF
        if ($this->getTipoDoc() == 1) {
            $value = v::cnpj()->validate($this->getCnpjCpf());
        } else {
            $value = v::cpf()->validate($this->getCnpjCpf());
        }
        if (!$value) {
            msg::showMsg('O campo CNPJ/CPF deve ser preenchido corretamente.'
                . '<script>focusOn("cnpj_cpf");</script>', 'danger');
        }
        return $this;
    }
    private function validateResponsavelNome()
    {
        $value = v::string()->notEmpty()->length(1, 50)->validate($this->getResponsavelNome());
        if (!$value) {
            msg::showMsg('O campo Nome do Responsável deve ser preenchido corretamente.'
                . '<script>focusOn("responsavel_nome");</script>', 'danger');
        }
        return $this;
    }
    private function validateEndereco()
    {
        $value = v::string()->notEmpty()->length(1, 128)->validate($this->getEndereco());
        if (!$value) {
            msg::showMsg('O campo Endereço deve ser preenchido corretamente.'
                . '<script>focusOn("endereco");</script>', 'danger');
        }
        return $this;
    }
    private function validateTipoDoc()
    {
        $value = v::int()->between(0, 3)->validate($this->getTipoDoc());
        if (!$value) {
            msg::showMsg('O campo Tipo do Documento deve ser preenchido corretamente.'
                . '<script>focusOn("tipo_doc");</script>', 'danger');
        }
        return $this;
    }
    private function validateFoneFixo()
    {
        $value = is_numeric($this->getFoneFixo());
        if (!$value) {
            msg::showMsg('O campo Fone Fixo deve ser preenchido corretamente.'
                . '<script>focusOn("fone_fixo");</script>', 'danger');
        }
        return $this;
    }
    private function validateFoneCelular()
    {
        $value = is_numeric($this->getFoneCelular());
        if (!$value) {
            msg::showMsg('O campo Fone Celular deve ser preenchido corretamente.'
                . '<script>focusOn("fone_celular");</script>', 'danger');
        }
        return $this;
    }
    private function validateEmail()
    {
        if (!$this->getEmail()) {
            return $this;
        }
        $value = v::email()->validate($this->getEmail());
        if (!$value) {
            msg::showMsg('O campo E-mail deve ser preenchido corretamente.'
                . '<script>focusOn("email");</script>', 'danger');
        }
        return $this;
    }

}
