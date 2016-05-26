<?php

/**
 * @file Criptografia.php
 * @version 0.3
 * - Helper que auxilia nas operações de criptografia da aplicação
 */
namespace HTR\Helpers\Criptografia;

use PHPassLib\Application\Context as PHPass;

class Criptografia
{
    /**
     * Custo da criptografia
     *
     * @var int
     */
    private $cost;

    /**
     * 
     * @param string $valor
     * @param boolean $definitivo
     * @return string
     */
    public function encode($valor, $definitivo = false)
    {
        /// VERIFICA SE O VALOR A SER ENCRIPTOGRAFADO SERÁ DE APENAS UMA VIA (DEFINITIVO*)
        if ($definitivo) {
            /// ENCRIPTOGRAFA A STRING PASSADA
            $valor = sha1(STRSAL.md5($valor).STRSAL);
        } else {
            /// ENCRIPTOGRAFA A STRING PASSADA
            $valor = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(STRSAL), $valor, MCRYPT_MODE_CBC, md5(md5(STRSAL))));
        }
        return $valor;
    }

    /**
     * MÉTODO USADO PARA DESENCRIPTOGRAFAR DADOS
     * 
     * @param string $valor
     * @return string
     */
    public function decode($valor)
    {
        /// ENCRIPTOGRAFA A STRING PASSADA
        $valor = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(STRSAL), base64_decode($valor), MCRYPT_MODE_CBC, md5(md5(STRSAL))), "\0");
        return $valor;
    }

    /**
     * Seta o custo da criptografia
     * 
     * @param int $cost
     */
    public function setCost($cost = null)
    {
        $this->cost = isset($cost) ? : 11;
    }

    /**
     * Retorna o custo da criptografia
     * 
     * @return int
     */
    private function getCost()
    {
        if (is_numeric($this->cost) && $this->cost > 0) {
            $cost = $this->cost;
        } else {
            $cost = 11;
        }
        
        return $cost;
    }

    /**
     * Gera as senhas para o sistema
     * 
     * @param string $password
     * @return string
     */
    public function passHash($password)
    {
        if (function_exists('password_hash')) {
            // Nativo do PHP 5.5
            return password_hash($password, PASSWORD_BCRYPT, ['cost' => $this->getCost()]);
            
        } else {
            // Biblioteca PHPassLib
            $phpass = new PHPass;
            $phpass->addConfig('bcrypt', ['rounds' => $this->getCost()]);
            return $phpass->hash($password);
        }
    }

    /**
     * 
     * @param string $password
     * @param string $hash
     * @return boolean
     */
    public function passVerify($password, $hash)
    {
        if (function_exists('password_verify')) {
            // Nativo do PHP 5.5
            return password_verify($password, $hash);
            
        } else {
            // Biblioteca PHPassLib
            $phpass = new PHPass;
            $phpass->addConfig('bcrypt', ['rounds' => $this->getCost()]);
            return $phpass->verify($password, $hash);
        }
    }
}
