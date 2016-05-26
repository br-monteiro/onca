<?php

/*
 * @file Config.conf.php
 * @version 0.7
 * - Arquivo responsavel por conter as configurações do Aplicativo
 * 
 * ////////////////////////////////////////////////////
 * AS CONSTANTES DEFINIDAS AQUI NÃO DEVEM SER RENOMEADAS
 */

// Rota padrão para a plicação
// Altere o valor somente se a aplicação não estiver rodando em um
// subdomínio específico.
// Default Value : /
define('APPDIR', '/');
// Nome da Aplicação
define('APPNAM', 'Onça-pintada');
// Versão da Aplicação
define('APPVER', '0.1');
// Salt String usado na criptografia
// Atenção: É de suma importância alterar este valor.
define('STRSAL', 'n%0$8VgDH6U6At %% (B16XZdZwVPGT^55u4I)TBU3VV');
// Nome do Domínio onde o Aplicativo foi instalado
// Atenção: é de suma importância que seja configurado um subdomínio específico
// para a correta instalação da aplicação.
define('DOMAIN', 'localhost');
// Contato do Administrador do Sistema
define('ADCONT', 'xxxx');
// Define o nome da entidade (tabela do Banco de Dados) usada para login
define('ENTLOG', 'usuarios');
// Rota padrão para o formulário de login
define('CTRLOG', 'acesso/login');
// Rota padrão para o formulário de troca de senha no primeiro acesso
define('CTRMOP', 'acesso/mudarsenha');
// Coluna padrão que indica a necessidade de troca de senha
// O valor deve ser igual ao nome do campo na tabela do Banco de Dados
define('COLMOP', 'trocar_senha');
// Repositório dos arquivos do sistema
// Este recurso é usado quando não há suporte a virtual hosts, onde o diretório
// dos arquivos da aplicação não poderão ficar acessíveis ao público
// Default Value: ../
define('DRINST', '../');
// redirecionamento padrão após o login
define('REDLOG', APPDIR . 'reportagem');
