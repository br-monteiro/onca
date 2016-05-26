<?php

namespace App\Helpers;

class Util
{
    /**
     * Método usado para gerar um código alfanumérico único
     * 
     * @param int $length Tamanho do código a ser gerado. Default length 6
     * @return string Retorna o código
     */
    public static function geraCod($length = 6)
    {
        $salt = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $pass = '';
        mt_srand(10000000*(double)microtime());
        for ($i = 0; $i < $length; $i++) {
            $pass .= $salt[mt_rand(0,strlen($salt) - 1)];
        }
        return $pass;
    }
}
