-- 2016-06-01T09:14:41-03:00 - mysql:dbname=projeto_onca;host=localhost

-- Table structure for table `coletas`

DROP TABLE IF EXISTS `coletas`;
CREATE TABLE `coletas` (
  `id` int(15) NOT NULL,
  `materiais_id` int(15) NOT NULL,
  `data` int(15) NOT NULL,
  `uf` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `quantidade_inicial` int(4) NOT NULL,
  `quantidade_distribuida` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table `coletas`

LOCK TABLES `coletas` WRITE;
INSERT INTO `coletas` VALUES (1464100000,1464101816,1464101816,'KG',50,50);
INSERT INTO `coletas` VALUES (1464112421,1464101816,1464101817,'KG',100,100);
UNLOCK TABLES;

-- Table structure for table `cooperativas`

DROP TABLE IF EXISTS `cooperativas`;
CREATE TABLE `cooperativas` (
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

-- Dumping data for table `cooperativas`

LOCK TABLES `cooperativas` WRITE;
INSERT INTO `cooperativas` VALUES (1464106266,'COOPERATIVA DOS ESTUDANTES DA FAP','COOPESFAP',2,'010.442.332-30','EDSON BRUNO SOARES MONTEIRO','RUA DOS BOBOS, Nº 0, SANTA ISABEL','9137444884','91999137954','bruno.monteirodg@gmail.com');
INSERT INTO `cooperativas` VALUES (1464264633,'COOPERATIVA DE TESTE','COOPTESTE',1,'40.432.544/0001-47','HEITOR MATIAS MONTEIRO','teste',1111111111,'11111111111','');
UNLOCK TABLES;

-- Table structure for table `distribuicoes`

DROP TABLE IF EXISTS `distribuicoes`;
CREATE TABLE `distribuicoes` (
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

-- Dumping data for table `distribuicoes`

LOCK TABLES `distribuicoes` WRITE;
UNLOCK TABLES;

-- Table structure for table `materiais`

DROP TABLE IF EXISTS `materiais`;
CREATE TABLE `materiais` (
  `id` int(15) NOT NULL,
  `tipo` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `caracteristica` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table `materiais`

LOCK TABLES `materiais` WRITE;
INSERT INTO `materiais` VALUES (1464101816,'GARRAFA','GARRAFA DE PLÁSTICO DE 2L - TRANSPARENTE');
UNLOCK TABLES;

-- Table structure for table `reportagens`

DROP TABLE IF EXISTS `reportagens`;
CREATE TABLE `reportagens` (
  `id` int(15) NOT NULL,
  `codigo` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `lat` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `lon` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `data` int(15) NOT NULL,
  `conclusao` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table `reportagens`

LOCK TABLES `reportagens` WRITE;
INSERT INTO `reportagens` VALUES (1464207517,'REMO33','-22.9068467','-43.1728965',1464207517,0);
UNLOCK TABLES;

-- Table structure for table `usuarios`

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
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

-- Dumping data for table `usuarios`

LOCK TABLES `usuarios` WRITE;
INSERT INTO `usuarios` VALUES (1,'OFh+gRU0QWHuKIrIeUZGEuHJZN0XFUeKkbkUOoaRG3k=','$2y$11$29TkehiCj0imG7HmFXS7N.PmF2ppmujx7SohH1/jb4htY7Tyatb.y','EDSON B. S. MONTEIRO','OT/WcW+1wFygVHGhd/9nlCMjsNVTFkgXE2EHlw7Ekfw=',1,0,1445514631,1464269768,1);
UNLOCK TABLES;

-- Completed on: 2016-06-01T09:14:41-03:00
