-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: 26/05/2016 às 11:03
-- Versão do servidor: 5.5.49-0ubuntu0.14.04.1
-- Versão do PHP: 5.5.9-1ubuntu4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de dados: `projeto_onca`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `coletas`
--

CREATE TABLE IF NOT EXISTS `coletas` (
  `id` int(15) NOT NULL,
  `materiais_id` int(15) NOT NULL,
  `data` int(15) NOT NULL,
  `uf` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `quantidade_inicial` int(4) NOT NULL,
  `quantidade_distribuida` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Fazendo dump de dados para tabela `coletas`
--

INSERT INTO `coletas` (`id`, `materiais_id`, `data`, `uf`, `quantidade_inicial`, `quantidade_distribuida`) VALUES
(1464100000, 1464101816, 1464101816, 'KG', 50, 50),
(1464112421, 1464101816, 1464101817, 'KG', 100, 100);

-- --------------------------------------------------------

--
-- Estrutura para tabela `cooperativas`
--

CREATE TABLE IF NOT EXISTS `cooperativas` (
  `id` int(15) NOT NULL,
  `nome` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `sigla` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `tipo_doc` int(1) NOT NULL,
  `cnpj_cpf` varchar(18) COLLATE utf8_unicode_ci NOT NULL,
  `responsavel_nome` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `endereco` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `fone_fixo` bigint(10) NOT NULL,
  `fone_celular` bigint(11) DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Fazendo dump de dados para tabela `cooperativas`
--

INSERT INTO `cooperativas` (`id`, `nome`, `sigla`, `tipo_doc`, `cnpj_cpf`, `responsavel_nome`, `endereco`, `fone_fixo`, `fone_celular`, `email`) VALUES
(1464106266, 'COOPERATIVA DOS ESTUDANTES DA FAP', 'COOPESFAP', 2, '010.442.332-30', 'EDSON BRUNO SOARES MONTEIRO', 'RUA DOS BOBOS, Nº 0, SANTA ISABEL', 9137444884, 91999137954, 'bruno.monteirodg@gmail.com'),
(1464264633, 'COOPERATIVA DE TESTE', 'COOPTESTE', 1, '40.432.544/0001-47', 'HEITOR MATIAS MONTEIRO', 'teste', 1111111111, 11111111111, '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `distribuicoes`
--

CREATE TABLE IF NOT EXISTS `distribuicoes` (
  `id` int(15) NOT NULL,
  `coletas_id` int(15) NOT NULL,
  `materiais_id` int(15) NOT NULL,
  `cooperativas_id` int(15) NOT NULL,
  `data` int(15) NOT NULL,
  `codigo` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `quantidade` int(4) NOT NULL,
  `uf` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `materiais`
--

CREATE TABLE IF NOT EXISTS `materiais` (
  `id` int(15) NOT NULL,
  `tipo` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `caracteristica` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Fazendo dump de dados para tabela `materiais`
--

INSERT INTO `materiais` (`id`, `tipo`, `caracteristica`) VALUES
(1464101816, 'GARRAFA', 'GARRAFA DE PLÁSTICO DE 2L - TRANSPARENTE');

-- --------------------------------------------------------

--
-- Estrutura para tabela `reportagens`
--

CREATE TABLE IF NOT EXISTS `reportagens` (
  `id` int(15) NOT NULL,
  `codigo` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `lat` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `lon` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `data` int(15) NOT NULL,
  `conclusao` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Fazendo dump de dados para tabela `reportagens`
--

INSERT INTO `reportagens` (`id`, `codigo`, `lat`, `lon`, `data`, `conclusao`) VALUES
(1464207517, 'REMO33', '-22.9068467', '-43.1728965', 1464207517, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(15) NOT NULL,
  `username` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `nivel` int(1) NOT NULL,
  `trocar_senha` int(1) NOT NULL,
  `created_at` int(15) NOT NULL,
  `updated_at` int(15) NOT NULL,
  `active` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Fazendo dump de dados para tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `password`, `name`, `email`, `nivel`, `trocar_senha`, `created_at`, `updated_at`, `active`) VALUES
(1, 'OFh+gRU0QWHuKIrIeUZGEuHJZN0XFUeKkbkUOoaRG3k=', '$2y$11$29TkehiCj0imG7HmFXS7N.PmF2ppmujx7SohH1/jb4htY7Tyatb.y', 'EDSON B. S. MONTEIRO', 'OT/WcW+1wFygVHGhd/9nlCMjsNVTFkgXE2EHlw7Ekfw=', 1, 0, 1445514631, 1464269768, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
