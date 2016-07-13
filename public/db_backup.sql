/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.6.30 : Database - idrdgov_simgeneral_planillas
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`idrdgov_simgeneral_planillas` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;

USE `idrdgov_simgeneral_planillas`;

/*Table structure for table `Bancos` */

DROP TABLE IF EXISTS `Bancos`;

CREATE TABLE `Bancos` (
  `Id_Banco` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Codigo` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `Nombre` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`Id_Banco`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `Bancos` */

insert  into `Bancos`(`Id_Banco`,`Codigo`,`Nombre`,`deleted_at`,`created_at`,`updated_at`) values (1,'51','BANCO DAVIVIENDA S.A.',NULL,NULL,NULL),(2,'01','BANCO DE BOGOTÁ',NULL,NULL,NULL),(3,'02','BANCO POPULAR',NULL,NULL,NULL),(4,'06','BANCO CORPBANCA COLOMBIA S.A.',NULL,NULL,NULL),(5,'07','BANCOLOMBIA S.A.',NULL,NULL,NULL),(6,'09','CITIBANK COLOMBIA',NULL,NULL,NULL),(7,'10','BANCO GNB COLOMBIA S.A.',NULL,NULL,NULL),(8,'12','BANCO GNB SUDAMERIS COLOMBIA',NULL,NULL,NULL),(9,'13','BBVA COLOMBIA',NULL,NULL,NULL),(10,'14','HELM BANK',NULL,NULL,NULL),(11,'19','RED MULTIBANCA COLPATRIA S.A.',NULL,NULL,NULL),(12,'23','BANCO DE OCCIDENTE',NULL,NULL,NULL),(13,'31','BANCO DE COMERCIO EXTERIOR DE COLOMBIA S.A. (BANCOLDEX)',NULL,NULL,NULL),(14,'32','BANCO CAJA SOCIAL - BCSC S.A.',NULL,NULL,NULL),(15,'40','BANCO AGRARIO DE COLOMBIA S.A.',NULL,NULL,NULL),(16,'52','BANCO AV VILLAS',NULL,NULL,NULL),(17,'53','BANCO WWB S.A.',NULL,NULL,NULL),(18,'58','BANCO PROCREDIT',NULL,NULL,NULL),(19,'59','BANCAMIA',NULL,NULL,NULL),(20,'60','BANCO PICHINCHA S.A.',NULL,NULL,NULL),(21,'61','BANCOOMEVA',NULL,NULL,NULL),(22,'62','BANCO FALABELLA S.A.',NULL,NULL,NULL),(23,'63','BANCO FINANDINA S.A.',NULL,NULL,NULL),(24,'65','BANCO SANTANDER DE NEGOCIOS COLOMBIA S.A. - BANCO SANTANDER',NULL,NULL,NULL),(25,'66','BANCO COOPERATIVO COOPCENTRAL',NULL,NULL,NULL),(26,'00','PAGO CON CHEQUE',NULL,NULL,NULL);

/*Table structure for table `Componentes` */

DROP TABLE IF EXISTS `Componentes`;

CREATE TABLE `Componentes` (
  `Id_Componente` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Codigo` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`Id_Componente`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `Componentes` */

insert  into `Componentes`(`Id_Componente`,`Nombre`,`Codigo`,`deleted_at`,`created_at`,`updated_at`) values (1,'Honorarios','3110203',NULL,'2016-07-12 14:05:29','2016-07-12 14:05:29');

/*Table structure for table `Contratistas` */

DROP TABLE IF EXISTS `Contratistas`;

CREATE TABLE `Contratistas` (
  `Id_Contratista` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `Cedula` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Numero_Cta` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Activo` tinyint(1) NOT NULL DEFAULT '1',
  `Hijos` tinyint(1) NOT NULL DEFAULT '1',
  `Declarante` tinyint(1) NOT NULL DEFAULT '1',
  `Medicina_Prepagada` tinyint(1) NOT NULL DEFAULT '1',
  `Hijos_Cantidad` tinyint(3) unsigned NOT NULL,
  `Medicina_Prepagada_Cantidad` int(10) unsigned NOT NULL,
  `Tipo_Cuenta` enum('CC','CA') COLLATE utf8_unicode_ci NOT NULL,
  `Nivel_Riesgo_ARL` enum('1','2','3','4','5') COLLATE utf8_unicode_ci NOT NULL,
  `Id_TipoDocumento` int(10) unsigned NOT NULL,
  `Id_Banco` int(10) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`Id_Contratista`),
  UNIQUE KEY `contratistas_cedula_unique` (`Cedula`),
  KEY `contratistas_id_banco_foreign` (`Id_Banco`),
  CONSTRAINT `contratistas_id_banco_foreign` FOREIGN KEY (`Id_Banco`) REFERENCES `Bancos` (`Id_Banco`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `Contratistas` */

insert  into `Contratistas`(`Id_Contratista`,`Nombre`,`Cedula`,`Numero_Cta`,`Activo`,`Hijos`,`Declarante`,`Medicina_Prepagada`,`Hijos_Cantidad`,`Medicina_Prepagada_Cantidad`,`Tipo_Cuenta`,`Nivel_Riesgo_ARL`,`Id_TipoDocumento`,`Id_Banco`,`deleted_at`,`created_at`,`updated_at`) values (1,'AGUILAR TORRES EVELIS JAZMIN','1015436737','7700706802',1,0,0,0,0,0,'CA','1',1,1,NULL,'2016-07-12 14:14:14','2016-07-12 14:14:14'),(2,'AYALA AYALA NESTOR JAVIER','80191665','7770294192',1,0,0,0,0,0,'CA','1',1,1,NULL,'2016-07-12 14:15:31','2016-07-12 14:15:31'),(4,'BELTRAN BRAVO STEPHANIE LIZETH','1014243939','7770277403',1,0,0,0,0,0,'CA','1',1,1,NULL,NULL,NULL),(5,'BENT FORBES LUKEN','18001724','0',1,0,0,0,0,0,'CA','1',1,1,NULL,NULL,NULL),(6,'CARDENAS VALBUENA EDDER','79367952','7770279417',1,0,0,0,0,0,'CA','1',1,1,NULL,NULL,NULL),(7,'CIFUENTES CARRANZA LEIDY CATERINE','1012391928','7770278005',1,1,0,0,1,0,'CA','1',1,1,NULL,NULL,NULL),(8,'CRUZ MORENO YURY MARCELA','1032421349','0',1,0,0,0,0,0,'CA','1',1,1,NULL,NULL,NULL),(9,'DUQUE QUINCHE DIEGO','80213267','0',1,0,0,0,0,0,'CA','1',1,1,NULL,NULL,NULL),(10,'ESCAMILLA OSPINA CARLOS ANDRES','1018420607','7700688695',1,0,0,0,0,0,'CA','1',1,1,NULL,NULL,NULL),(11,'FAGUA CAMARGO NEFTALI RICARDO','1014187139','7100742803',1,0,0,0,0,0,'CA','1',1,1,NULL,NULL,NULL),(12,'MOSQUERA KLINGER MIGUEL ALONSO','79802496','1770107512',1,0,1,0,0,0,'CA','1',1,1,NULL,NULL,NULL),(13,'RAMIREZ OSORIO ELEAZAR','14232068','8870541557',1,1,0,0,1,0,'CA','1',1,1,NULL,NULL,NULL),(14,'FUTTINICO VARGAS JOHN JAIRO','1020741350','1770104832',1,0,0,0,0,0,'CA','1',1,1,NULL,NULL,NULL),(15,'FUQUENE FIQUITIVA MARIO EMERSON','1023907144','24039558495',1,0,0,0,0,0,'CA','1',1,14,NULL,NULL,NULL),(16,'GARCIA CABRERA ANA MARIA','1015432233','‘0122462706',1,0,0,0,0,0,'CA','1',1,11,NULL,NULL,NULL),(17,'GARCIA OLMOS TITO ALONSO','6910303','24048593360',1,0,0,0,0,0,'CA','1',1,14,NULL,NULL,NULL),(18,'GARRIDO TRIANA IVAN GABRIEL','1019102113','0',1,0,0,0,0,0,'CA','1',1,26,NULL,NULL,NULL),(19,'RODRIGUEZ CAMACHO LUIS ALIRIO','11430449','0',1,0,0,0,0,0,'CA','1',1,26,NULL,NULL,NULL),(20,'CORREDOR CAMACHO DIEGO CAMILO','1014236501','0',1,0,0,0,0,0,'CA','1',1,5,NULL,NULL,NULL),(21,'AREVALO TRUQUE EIDER ORLANDO','1019082085','58972737991',1,0,0,0,0,0,'CA','1',1,5,NULL,NULL,NULL),(22,'CABRERA GARAVITO JORGE MARIO','80199070','68814036174',1,1,0,0,1,0,'CA','1',1,5,NULL,NULL,NULL),(23,'CALIXTO LOPEZ MIGUEL ALEJANDRO','1136886485','607082120',1,0,0,0,0,0,'CA','1',1,1,NULL,NULL,NULL),(24,'CAMARGO GUARIN ANDRES CAMILO','1015395345','56745012103',1,1,0,0,1,0,'CA','1',1,5,NULL,NULL,NULL),(35,'ABRIL RESTREPO LAURA VALENTINA','1118293146','0',1,0,0,0,0,0,'CA','1',1,26,NULL,'2016-07-12 15:17:47','2016-07-12 15:17:47');

/*Table structure for table `Contratos` */

DROP TABLE IF EXISTS `Contratos`;

CREATE TABLE `Contratos` (
  `Id_Contrato` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Numero` int(10) unsigned NOT NULL,
  `Usuario` bigint(20) unsigned NOT NULL,
  `Objeto` text COLLATE utf8_unicode_ci,
  `Fecha_Inicio` date NOT NULL,
  `Fecha_Terminacion` date NOT NULL,
  `Fecha_Terminacion_Modificada` date DEFAULT NULL,
  `Tipo_Modificacion` enum('terminado','suspendido') COLLATE utf8_unicode_ci DEFAULT NULL,
  `Terminado_Por` enum('contratista','entidad','mutuo') COLLATE utf8_unicode_ci DEFAULT NULL,
  `Saldo_A_Favor` int(10) unsigned DEFAULT NULL,
  `Total_Contrato` bigint(20) unsigned NOT NULL,
  `Dias_Trabajados` int(10) unsigned NOT NULL DEFAULT '0',
  `Tipo_Pago` enum('Dia','Fecha','Mes') COLLATE utf8_unicode_ci NOT NULL,
  `Estado` enum('finalizado','pendiente') COLLATE utf8_unicode_ci DEFAULT NULL,
  `Id_Contratista` int(10) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`Id_Contrato`),
  KEY `contratos_id_contratista_foreign` (`Id_Contratista`),
  CONSTRAINT `contratos_id_contratista_foreign` FOREIGN KEY (`Id_Contratista`) REFERENCES `Contratistas` (`Id_Contratista`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `Contratos` */

insert  into `Contratos`(`Id_Contrato`,`Numero`,`Usuario`,`Objeto`,`Fecha_Inicio`,`Fecha_Terminacion`,`Fecha_Terminacion_Modificada`,`Tipo_Modificacion`,`Terminado_Por`,`Saldo_A_Favor`,`Total_Contrato`,`Dias_Trabajados`,`Tipo_Pago`,`Estado`,`Id_Contratista`,`deleted_at`,`created_at`,`updated_at`) values (1,1878,71766,'CPS 1878–16 MONITOR DEPORTIVO IED','2016-06-03','2016-09-02','2016-09-02',NULL,NULL,NULL,4200000,0,'Mes',NULL,1,NULL,'2016-07-12 15:10:17','2016-07-12 15:10:17'),(2,1875,71766,'CPS 1875–16 MONITOR DEPORTIVO IED','2016-05-17','2016-08-16','2016-08-16',NULL,NULL,NULL,6300000,0,'Dia',NULL,2,NULL,'2016-07-12 15:21:45','2016-07-12 15:21:45'),(3,1874,71766,'CPS 1874–16 MONITOR DEPORTIVO IED','2016-05-16','2016-08-15','2016-08-15',NULL,NULL,NULL,4200000,0,'Dia',NULL,4,NULL,'2016-07-12 15:53:20','2016-07-12 15:53:20'),(4,1866,71766,'CPS 1866–16 MONITOR DEPORTIVO IED','2016-05-16','2016-08-15','2016-08-15',NULL,NULL,NULL,4200000,0,'Mes',NULL,15,NULL,'2016-07-12 16:04:21','2016-07-12 16:04:21'),(5,1871,71766,'CPS 1871–16 MONITOR DEPORTIVO IED','2016-05-24','2016-08-23','2016-08-23',NULL,NULL,NULL,4200000,0,'Mes',NULL,16,NULL,'2016-07-12 16:07:11','2016-07-12 16:07:11'),(6,1857,71766,'CPS 1857–16 MONITOR DEPORTIVO IED','2016-05-16','2016-08-15','2016-08-15',NULL,NULL,NULL,4200000,0,'Mes',NULL,17,NULL,'2016-07-12 16:08:32','2016-07-12 16:08:32'),(7,1815,71766,'CPS 1815–16 ENTRENADOR SEMILLEROS DEPORTIVOS','2016-04-27','2016-07-26','2016-07-26',NULL,NULL,NULL,8273448,0,'Dia',NULL,6,NULL,'2016-07-12 16:08:40','2016-07-12 16:08:40'),(8,1929,71766,'CPS 1929–16 MONITOR DEPORTIVO IED','2016-05-19','2016-08-18','2016-08-18',NULL,NULL,NULL,4200000,0,'Mes',NULL,7,NULL,'2016-07-12 16:13:19','2016-07-12 16:13:19'),(9,1872,71766,'CPS 1872–16 MONITOR DEPORTIVO IED','2016-06-02','2016-09-01','2016-09-01',NULL,NULL,NULL,4200000,0,'Mes',NULL,10,NULL,'2016-07-12 16:15:28','2016-07-12 16:15:28'),(10,1868,71766,'CPS 1868–16 MONITOR DEPORTIVO IED','2016-05-17','2016-08-16','2016-08-16',NULL,NULL,NULL,4200000,0,'Mes',NULL,11,NULL,'2016-07-12 16:17:10','2016-07-12 16:17:10'),(11,1879,71766,'CPS 1879–16 MONITOR DEPORTIVO IED','2016-05-23','2016-08-22','2016-08-22',NULL,NULL,NULL,4200000,0,'Mes',NULL,20,NULL,'2016-07-12 16:18:49','2016-07-12 16:18:49'),(12,1835,71766,'CPS 1835–16 MONITOR DEPORTIVO IED','2016-05-04','2016-08-17','2016-08-17',NULL,NULL,NULL,5400000,0,'Mes',NULL,21,NULL,'2016-07-12 16:20:29','2016-07-12 16:20:29'),(13,2234,71766,'CPS 2234–16 ENTRENADOR DE SEMILLEROS DEPORTIVOS','2016-06-01','2016-08-30','2016-08-30',NULL,NULL,NULL,10341810,0,'Mes',NULL,12,NULL,'2016-07-12 16:20:30','2016-07-12 16:20:30'),(14,1925,71766,'CPS 1925–16 MONITOR DEPORTIVO IED','2016-05-17','2016-08-16','2016-08-16',NULL,NULL,NULL,4200000,0,'Mes',NULL,22,NULL,'2016-07-12 16:21:49','2016-07-12 16:21:49'),(15,1998,71766,'CPS 1998–16 ENTRENADOR DE SEMILLEROS DEPORTIVOS','2016-06-01','2016-08-30','2016-08-30',NULL,NULL,NULL,8273448,0,'Mes',NULL,13,NULL,'2016-07-12 16:22:56','2016-07-12 16:22:56'),(16,1918,71766,'CPS 1918–16 MONITOR DEPORTIVO IED','2016-05-18','2016-08-17','2016-08-17',NULL,NULL,NULL,4200000,0,'Mes',NULL,23,NULL,'2016-07-12 16:23:02','2016-07-12 16:23:02'),(17,1943,71766,'CPS 1943–16  MONITOR DEPORTIVO IED','2016-05-27','2016-08-26','2016-08-26',NULL,NULL,NULL,4200000,0,'Mes',NULL,24,NULL,'2016-07-12 16:24:13','2016-07-12 16:24:13'),(18,1905,71766,'CPS 1905–16 MONITOR DEPORTIVO IED','2016-05-23','2016-08-22','2016-08-22',NULL,NULL,NULL,4200000,0,'Mes',NULL,14,NULL,'2016-07-12 16:33:36','2016-07-12 16:33:36');

/*Table structure for table `Fuentes` */

DROP TABLE IF EXISTS `Fuentes`;

CREATE TABLE `Fuentes` (
  `Id_Fuente` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Codigo` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`Id_Fuente`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `Fuentes` */

insert  into `Fuentes`(`Id_Fuente`,`Nombre`,`Codigo`,`deleted_at`,`created_at`,`updated_at`) values (0,'FUENTES ANTERIORES','0000000',NULL,'2016-07-13 08:10:21','2016-07-13 08:10:21'),(1,'ESPECTÁCULOS PÚBLICOS Y FONDO DE POBRES','1101197',NULL,'2016-07-12 13:58:50','2016-07-12 13:58:50');

/*Table structure for table `Planillas` */

DROP TABLE IF EXISTS `Planillas`;

CREATE TABLE `Planillas` (
  `Id_Planilla` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Id_Fuente` int(10) unsigned NOT NULL,
  `Usuario` bigint(20) unsigned NOT NULL,
  `Verificador` bigint(20) unsigned DEFAULT NULL,
  `Aprobador` bigint(20) unsigned DEFAULT NULL,
  `Numero` int(10) unsigned NOT NULL,
  `Titulo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Colectiva` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Descripcion` text COLLATE utf8_unicode_ci,
  `Observaciones` text COLLATE utf8_unicode_ci,
  `Desde` date NOT NULL,
  `Hasta` date NOT NULL,
  `Estado` enum('1','2','3','4') COLLATE utf8_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`Id_Planilla`),
  KEY `planillas_id_fuente_foreign` (`Id_Fuente`),
  CONSTRAINT `planillas_id_fuente_foreign` FOREIGN KEY (`Id_Fuente`) REFERENCES `Fuentes` (`Id_Fuente`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `Planillas` */

insert  into `Planillas`(`Id_Planilla`,`Id_Fuente`,`Usuario`,`Verificador`,`Aprobador`,`Numero`,`Titulo`,`Colectiva`,`Descripcion`,`Observaciones`,`Desde`,`Hasta`,`Estado`,`deleted_at`,`created_at`,`updated_at`) values (0,0,0,0,0,0,'PLANILLA SALDOS ANTERIORES','NP','NP','NP','2016-07-13','2016-07-13','3',NULL,'2016-07-13 08:17:02','2016-07-13 08:17:02');

/*Table structure for table `PlanillasRecursos` */

DROP TABLE IF EXISTS `PlanillasRecursos`;

CREATE TABLE `PlanillasRecursos` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Id_Planilla` int(10) unsigned NOT NULL,
  `Id_Recurso` int(10) unsigned NOT NULL,
  `Dias_Trabajados` smallint(5) unsigned DEFAULT NULL,
  `Total_Pagar` bigint(20) unsigned DEFAULT NULL,
  `UVT` double(8,2) DEFAULT NULL,
  `EPS` int(10) unsigned DEFAULT NULL,
  `Pension` int(10) unsigned DEFAULT NULL,
  `ARL` int(10) unsigned DEFAULT NULL,
  `Medicina_Prepagada` int(10) unsigned DEFAULT NULL,
  `Hijos` int(10) unsigned DEFAULT NULL,
  `AFC` int(10) unsigned DEFAULT NULL,
  `Ingreso_Base_Gravado_384` int(10) unsigned DEFAULT NULL,
  `Ingreso_Base_Gravado_1607` int(10) unsigned DEFAULT NULL,
  `Ingreso_Base_Gravado_25` int(10) unsigned DEFAULT NULL,
  `Base_UVR_Ley_1607` double(8,2) DEFAULT NULL,
  `Base_UVR_Art_384` double(8,2) DEFAULT NULL,
  `Base_ICA` int(10) unsigned DEFAULT NULL,
  `PCUL` int(10) unsigned DEFAULT NULL,
  `PPM` int(10) unsigned DEFAULT NULL,
  `Total_ICA` int(10) unsigned DEFAULT NULL,
  `DIST` int(10) unsigned DEFAULT NULL,
  `Retefuente` int(10) unsigned DEFAULT NULL,
  `Retefuente_1607` int(10) unsigned DEFAULT NULL,
  `Retefuente_384` int(10) unsigned DEFAULT NULL,
  `Otros_Descuentos_Expresion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Otros_Descuentos` int(10) unsigned DEFAULT NULL,
  `Otras_Bonificaciones` int(10) unsigned DEFAULT NULL,
  `Cod_Retef` int(10) unsigned DEFAULT NULL,
  `Cod_Seven` int(10) unsigned DEFAULT NULL,
  `Total_Deducciones` int(10) unsigned DEFAULT NULL,
  `Declarante` tinyint(1) DEFAULT NULL,
  `Neto_Pagar` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `planillasrecursos_id_planilla_foreign` (`Id_Planilla`),
  KEY `planillasrecursos_id_recurso_foreign` (`Id_Recurso`),
  CONSTRAINT `planillasrecursos_id_planilla_foreign` FOREIGN KEY (`Id_Planilla`) REFERENCES `Planillas` (`Id_Planilla`) ON DELETE CASCADE,
  CONSTRAINT `planillasrecursos_id_recurso_foreign` FOREIGN KEY (`Id_Recurso`) REFERENCES `Recursos` (`Id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `PlanillasRecursos` */

/*Table structure for table `PlanillasRubros` */

DROP TABLE IF EXISTS `PlanillasRubros`;

CREATE TABLE `PlanillasRubros` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Id_Planilla` int(10) unsigned NOT NULL,
  `Id_Rubro` int(10) unsigned NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `planillasrubros_id_planilla_foreign` (`Id_Planilla`),
  KEY `planillasrubros_id_rubro_foreign` (`Id_Rubro`),
  CONSTRAINT `planillasrubros_id_planilla_foreign` FOREIGN KEY (`Id_Planilla`) REFERENCES `Planillas` (`Id_Planilla`) ON DELETE CASCADE,
  CONSTRAINT `planillasrubros_id_rubro_foreign` FOREIGN KEY (`Id_Rubro`) REFERENCES `Rubros` (`Id_Rubro`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `PlanillasRubros` */

/*Table structure for table `Recursos` */

DROP TABLE IF EXISTS `Recursos`;

CREATE TABLE `Recursos` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Id_Fuente` int(10) unsigned NOT NULL,
  `Id_Rubro` int(10) unsigned NOT NULL,
  `Id_Contrato` int(10) unsigned NOT NULL,
  `Id_Componente` int(10) unsigned NOT NULL,
  `Numero_Registro` int(10) unsigned NOT NULL,
  `Valor_CRP` int(10) unsigned NOT NULL,
  `Saldo_CRP` int(10) unsigned NOT NULL,
  `Expresion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Pago_Mensual` bigint(20) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `recursos_id_fuente_foreign` (`Id_Fuente`),
  KEY `recursos_id_rubro_foreign` (`Id_Rubro`),
  KEY `recursos_id_componente_foreign` (`Id_Componente`),
  KEY `recursos_id_contrato_foreign` (`Id_Contrato`),
  CONSTRAINT `recursos_id_componente_foreign` FOREIGN KEY (`Id_Componente`) REFERENCES `Componentes` (`Id_Componente`) ON DELETE CASCADE,
  CONSTRAINT `recursos_id_contrato_foreign` FOREIGN KEY (`Id_Contrato`) REFERENCES `Contratos` (`Id_Contrato`) ON DELETE CASCADE,
  CONSTRAINT `recursos_id_fuente_foreign` FOREIGN KEY (`Id_Fuente`) REFERENCES `Fuentes` (`Id_Fuente`) ON DELETE CASCADE,
  CONSTRAINT `recursos_id_rubro_foreign` FOREIGN KEY (`Id_Rubro`) REFERENCES `Rubros` (`Id_Rubro`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `Recursos` */

insert  into `Recursos`(`Id`,`Id_Fuente`,`Id_Rubro`,`Id_Contrato`,`Id_Componente`,`Numero_Registro`,`Valor_CRP`,`Saldo_CRP`,`Expresion`,`Pago_Mensual`,`deleted_at`,`created_at`,`updated_at`) values (1,1,1,1,1,5232,4200000,4200000,'crp/3',1400000,NULL,'2016-07-12 15:10:17','2016-07-12 15:10:17'),(2,1,1,2,1,5233,6300000,6300000,'crp/3',2100000,NULL,'2016-07-12 15:21:45','2016-07-12 15:21:45'),(3,1,1,3,1,5234,4200000,4200000,'crp/3',1400000,NULL,'2016-07-12 15:53:20','2016-07-12 15:53:20'),(4,1,1,4,1,5239,4200000,4200000,'crp/3',1400000,NULL,'2016-07-12 16:04:21','2016-07-12 16:04:21'),(5,1,1,5,1,5236,4200000,4200000,'crp/3',1400000,NULL,'2016-07-12 16:07:11','2016-07-12 16:07:11'),(6,1,1,6,1,5240,4200000,4200000,'crp/3',1400000,NULL,'2016-07-12 16:08:32','2016-07-12 16:08:32'),(7,1,1,7,1,4367,8273448,5607559,'crp/3',2757816,NULL,'2016-07-12 16:08:40','2016-07-12 16:08:40'),(8,1,1,8,1,5324,4200000,4200000,'crp/3',1400000,NULL,'2016-07-12 16:13:19','2016-07-12 16:13:19'),(9,1,1,9,1,5235,4200000,4200000,'crp/3',1400000,NULL,'2016-07-12 16:15:28','2016-07-12 16:15:28'),(10,1,1,10,1,5238,4200000,4200000,'crp/3',1400000,NULL,'2016-07-12 16:17:10','2016-07-12 16:17:10'),(11,1,1,11,1,5231,4200000,4200000,'crp/3',1400000,NULL,'2016-07-12 16:18:49','2016-07-12 16:18:49'),(12,1,1,12,1,5091,5400000,5400000,'crp/3',1800000,NULL,'2016-07-12 16:20:29','2016-07-12 16:20:29'),(13,1,1,13,1,5728,10341810,10341810,'crp/3',3447270,NULL,'2016-07-12 16:20:30','2016-07-12 16:20:30'),(14,1,1,14,1,5322,4200000,4200000,'crp/3',1400000,NULL,'2016-07-12 16:21:49','2016-07-12 16:21:49'),(15,1,1,15,1,5439,8273448,8273448,'crp/3',2757816,NULL,'2016-07-12 16:22:56','2016-07-12 16:22:56'),(16,1,1,16,1,5315,4200000,4200000,'crp/3',1400000,NULL,'2016-07-12 16:23:03','2016-07-12 16:23:03'),(17,1,1,17,1,5369,4200000,4200000,'crp/3',1400000,NULL,'2016-07-12 16:24:13','2016-07-12 16:24:13'),(18,1,1,18,1,5292,4200000,4200000,'crp/3',1400000,NULL,'2016-07-12 16:33:36','2016-07-12 16:33:36');

/*Table structure for table `Rubros` */

DROP TABLE IF EXISTS `Rubros`;

CREATE TABLE `Rubros` (
  `Id_Rubro` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Codigo` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`Id_Rubro`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `Rubros` */

insert  into `Rubros`(`Id_Rubro`,`Nombre`,`Codigo`,`deleted_at`,`created_at`,`updated_at`) values (0,'RUBROS ANTERIORES','0000000000000',NULL,'2016-07-13 08:14:34','2016-07-13 08:14:34'),(1,'JORNADA ESCOLAR 40 HORAS SEMANALES','3311401030928',NULL,'2016-07-12 13:59:28','2016-07-12 16:17:27');

/*Table structure for table `Saldos` */

DROP TABLE IF EXISTS `Saldos`;

CREATE TABLE `Saldos` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Id_Planilla` int(10) unsigned NOT NULL,
  `Id_Contrato` int(10) unsigned NOT NULL,
  `Id_Recurso` int(10) unsigned NOT NULL,
  `Fecha_Registro` date DEFAULT NULL,
  `Total_Pagado` bigint(20) unsigned NOT NULL,
  `operacion` enum('sumar','restar') COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `saldos_id_planilla_foreign` (`Id_Planilla`),
  KEY `saldos_id_contrato_foreign` (`Id_Contrato`),
  KEY `saldos_id_recurso_foreign` (`Id_Recurso`),
  CONSTRAINT `saldos_id_contrato_foreign` FOREIGN KEY (`Id_Contrato`) REFERENCES `Contratos` (`Id_Contrato`) ON DELETE CASCADE,
  CONSTRAINT `saldos_id_planilla_foreign` FOREIGN KEY (`Id_Planilla`) REFERENCES `Planillas` (`Id_Planilla`) ON DELETE CASCADE,
  CONSTRAINT `saldos_id_recurso_foreign` FOREIGN KEY (`Id_Recurso`) REFERENCES `Recursos` (`Id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `Saldos` */

insert  into `Saldos`(`Id`,`Id_Planilla`,`Id_Contrato`,`Id_Recurso`,`Fecha_Registro`,`Total_Pagado`,`operacion`,`deleted_at`,`created_at`,`updated_at`) values (1,0,7,7,'2016-07-13',2665889,'sumar',NULL,'2016-07-13 08:41:17','2016-07-13 08:41:17');

/*Table structure for table `Suspenciones` */

DROP TABLE IF EXISTS `Suspenciones`;

CREATE TABLE `Suspenciones` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Id_Contrato` int(10) unsigned NOT NULL,
  `Fecha_Inicio` date NOT NULL,
  `Fecha_Terminacion` date NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `suspenciones_id_contrato_foreign` (`Id_Contrato`),
  CONSTRAINT `suspenciones_id_contrato_foreign` FOREIGN KEY (`Id_Contrato`) REFERENCES `Contratos` (`Id_Contrato`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `Suspenciones` */

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`migration`,`batch`) values ('2016_04_29_193323_tabla_banco',1),('2016_04_29_193334_tabla_contratista',1),('2016_05_04_203641_crear_tabla_contratos',1),('2016_05_05_172120_crear_tablas_presupuestos',1),('2016_05_16_135000_crear_tabla_planillas',1),('2016_06_16_190805_crear_tablas_segumientos_contratos',1),('2016_06_21_195144_campo_estado_contratos',1);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
