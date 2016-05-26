<?php

/**
 * @file Bootstrap.php
 * @version 0.5
 * - Class responsavel por organizar e gerenciar as requições passadas por URLs amigaveis
 * setando os Controllers, Actions e Parâmetros
 */
namespace HTR\Init;

class Bootstrap
{
    /**
     * Recebe o valor da url
     *
     * @var array
     */
    private $url;
    /**
     * Recebe o nome do Controller
     *
     * @var string 
     */
    private $controller;
    /**
     * Recebe o nome da Action
     *
     * @var string
     */
    private $action;
    /**
     * Recebe os parâmetros enviados pela URL
     *
     * @var array
     */
    private $params;

    public function __construct()
    {
        $this->setUrl(filter_input(INPUT_GET, 'url'))
             ->setController()
             ->setAction()
             ->setParam();
    }

    /**
     * Seta a URL
     * 
     * @return \HTR\Init\Bootstrap
     */
    private function setUrl($url)
    {
        // Controller e Action padrão
        $this->url = explode('/', isset($url) ? $url : 'Index/index' );
        return $this;
    }

    /**
     * Retorna a URL
     * 
     * @return array
     */
    private function getUrl()
    {
        return $this->url;
    }

    /**
     * Seta o nome do Controller
     * 
     * @return \HTR\Init\Bootstrap
     */
    private function setController()
    {
        $url = $this->getUrl();
        $this->controller = ucfirst($url[0]).'Controller';
        return $this;
    }

    /**
     * Retorna o nome do Controller
     * 
     * @return string
     */
    private function getController()
    {
        return $this->controller;
    }

    /**
     * Seta o nome da Action
     * 
     * @return \HTR\Init\Bootstrap
     */
    private function setAction()
    {
        $url = $this->getUrl();
        $this->action = !empty($url[1]) ? strtolower( $url[1] ).'Action' : 'indexAction';
        return $this;
    }

    /**
     * Retorna o nome da Action
     * 
     * @return strin
     */
    private function getAction()
    {
        return $this->action;
    }

    /**
     * Seta os parâmetros enviados pela URL
     * 
     * @return \HTR\Init\Bootstrap
     */
    private function setParam()
    {
        $url = $this->getUrl();
        unset ($url[0], $url[1]);
        
        if (empty(end($url))) {
            array_pop($url);
        }

        if (!empty($url)) {
            $i = 0;
            foreach ($url as $key) {
                if ($i % 2 == 0) {
                    $index[] = $key;
                } else {
                    $value[] = $key;
                }
                $i++;
            }
        } else {
            $value = $index = array();
        }

        if (!empty($value)) {
            if (count($index) == count($value) && !empty($index) && !empty($value)) {
                $this->params = array_combine($index, $value );
            } else {
                $this->params = array();
            }
        } else {
            $this->params = array();
        }
        return $this;
    }

    /**
     * Retorna os parâmetros setados pela URL
     * 
     * @param mixed $key Nome/Chave do Parâmetro
     * @return array Array de parâmetros
     */
    public function getParam($key = null)
    {
        /**
         * Se o Parâmetro $key for idêntico a true, então devolve o array de URL completo
         */
        if ($key === true) {
            return $this->getUrl();
        }
        // se o parâmetro for diferente de NULL
        // o script executará a função array_key_exists
        return $key != null ?
                /**
                 * verifica se a chave requisitada existe no Array de parâmetros
                 * se existir, retorna o parâmetro com a chave indicada, caso
                 * contrário, retornará o valor NULL
                 */
                array_key_exists($key, $this->params)? $this->params[$key] : null
            // retorna o Array de parâmteros completo
            : $this->params ;
    }

    /**
     * Roda a aplicação
     */
    protected function run()
    {
        if(file_exists(DRINST . 'App/Controllers/'.$this->getController().'.php')){
            
            $class = "App\\Controllers\\".$this->getController();
            
            // instacia o Controller
            $controller = new $class;
            // verifica se o método é existente
            if(!method_exists($class, $this->getAction())) {
                // caso o método (action) não exista, retorna um Erro 404
                new \HTR\Helpers\ErrorPag\ErrorPag('error_404');
            }
            // retorna  a Action
            $action = $this->getAction();
            // executa a Action
            $controller->$action();
        } else {
            // caso o Controller não exista, retorna um Erro 404
            new \HTR\Helpers\ErrorPag\ErrorPag('error_404');
        }
    }

}
