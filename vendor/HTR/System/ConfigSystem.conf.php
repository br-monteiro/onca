<?php

/**
 * @file ConfigSystem.conf.php
 * @version 0.5
 * - Arquivo responsavel por conter as configurações do Sistema
 * 
 * ////////////////////////////////////////////////////
 * AS CONTANTES DEFINIDAS AQUI NÃO DEVEM SER RENOMEADAS
 */

// Separador de Diretório
define('DS', DIRECTORY_SEPARATOR);
// ATENÇÃO: Esta opção pode interferir na performance da aplicação
// Controlador de Backup do sitema
// Default Value: true (true/false)
define('BACKUP', true);
// Estabelece a continuidade do backup
// ATENÇÃO: Esta opção com o valor true fará com que uma cópia de 
// contantemente a cada a acesso ao sistema.
// Se já existir um um cópia, a mesma será sobrescrevida.
// Default Value: false (true/false)
define('BKPCON', false);
// Estabelece a frequencia do backup
// Esta opção é baseada na Class Nativa DateTime do PHP
// Eexemplo: Se o Valor estiver setado como '+1 day' o backup
// será feito no intervalo de um dia
// Esta opção será ignorada se o valor de BKPCON for true
define('BKPFQC', '+1 day');
// Diretório padrão onde serão salvos os arquivos de Banco de Dados
define('DATADR' , DRINST . 'App/Database/DbRepository/');
// Diretório padrão onde serão salvos os arquivos de Backup do Banco de Dados
define('DIRBKP' , DRINST . 'App/Database/DbBackup/');
// Diretório padrão onde serão salvos os arquivos de outras bibliotecas
define('ATTACH' , APPDIR.'attach/');
// Diretório padrão onde serão salvos os arquivos de fragmentos de páginas
define('ATTPAG' , 'attach/partPage/');
// Diretório padrão onde serão salvos as páginas de erro
define('ERRPAG' , 'attach/ErrorPag/');
// Diretório padrão onde serão salvos os arquivos Javascript
define('DIRJS_' , APPDIR.'js/');
// Diretório padrão onde serão salvos os arquivos CSS
define('DIRCSS' , APPDIR.'css/');
// Diretório padrão onde serão salvos os arquivos de imagem
define('DIRIMG' , APPDIR.'images/');

