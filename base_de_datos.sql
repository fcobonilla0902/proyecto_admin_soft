-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: localhost    Database: oferta_educativa_interactiva
-- ------------------------------------------------------
-- Server version	8.0.40

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `alumnos`
--

DROP TABLE IF EXISTS `alumnos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `alumnos` (
  `identificador` varchar(50) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `apellido` varchar(45) NOT NULL,
  `password` varchar(60) NOT NULL,
  `tipo` tinyint NOT NULL,
  PRIMARY KEY (`identificador`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alumnos`
--

LOCK TABLES `alumnos` WRITE;
/*!40000 ALTER TABLE `alumnos` DISABLE KEYS */;
INSERT INTO `alumnos` VALUES ('1871949','Tharek Alexis','García Arellano','$2y$10$6qEj6AFKPE3wmf64zXelF.7dG52CcOtytIrEWpgoe.Vvtrtydn9iS',1),('2028753','Grecia Rubí ','Treviño Romero','$2y$10$BJGmznNwgI7BItycJZsutefohwZIHJslv61Am92d/WQZ00vDDwK7q',1),('2043930','Emmanuel Gerard ','Espinosa Almaguer','$2y$10$xp9B6n8ocXDseuGzXvL8C.8eoLUI0KfyQlRTbyJiGGED/1yeQnbqW',1),('2047608','Leslie Lizeth','Reyna Flores','$2y$10$x7vvgz.hdbdDqBRFu.yhxOWeP9/5HmTaTeBwHhKdw6TOQ7XoT9d.S',1),('2050012','José Miguel','Urdiales Carriales','$2y$10$QapauEPfMQQxs0ZQDyh9XOFyaQapEwpnjK/.FZRlD48cuVk6Pv9Qq',1),('2057220','Diego Enrique ','Vega Lara','$2y$10$udeZF0FtEEJuhRc.zOI2veHVEERp725ZLbSOwzFJlvmYketjVzVMC',1),('2099624','Francisco Alonso','Bonilla Rodríguez','$2y$10$OtrVfSHcJz2kEBsKKEiehusC7vZOhZml/Td0jjl18pezy/ZqL4iBi',1),('2109367','Sebastián','Saldívar Lerma','$2y$10$ndRaplalQ/afdHYp7B.oiuz26sgRs2HCl5hwYBBh7KvuAfYOsDH2y',1),('2173930','Julio Enrique','Pérez Reyes','$2y$10$dC6FmnTPTTsW8tpQ9rmvr.XpqIoXxXEg9tFbUZNZvwoFd7iZrnMjC',1),('2177988','Manuel','Magaña López','$2y$10$dHWu3tGd5T5fZ5z4tC9H1uQ0SGVVHBBr0KO5arJhYjkB/ZrAY7Qfu',1),('correo@gmail.com','ejemplo','ejemplo','$2y$10$FNIir6Oza6hvY2TOQGAUb.CTPRN/DjNLdYFbHbirh0ec3THKRxl/S',0);
/*!40000 ALTER TABLE `alumnos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comunidad`
--

DROP TABLE IF EXISTS `comunidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comunidad` (
  `id` int NOT NULL AUTO_INCREMENT,
  `identificador` varchar(50) NOT NULL,
  `comentario` varchar(200) NOT NULL,
  `fecha` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `matriculaFk_idx` (`identificador`),
  CONSTRAINT `fk_identificador` FOREIGN KEY (`identificador`) REFERENCES `alumnos` (`identificador`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comunidad`
--

LOCK TABLES `comunidad` WRITE;
/*!40000 ALTER TABLE `comunidad` DISABLE KEYS */;
INSERT INTO `comunidad` VALUES (1,'2099624','hola','2025-09-30 22:40:23'),(5,'2043930','prueba','2025-10-01 00:48:41'),(6,'2050012','prueba2','2025-10-01 00:48:59'),(8,'2050012','Hola la verdad esta muy bienHola la verdad esta muy bienHola la verdad esta muy bienHola la verdad esta muy bienHola la verdad esta muy bienHola la verdad esta muy bienHola la verdad esta muy bienHola','2025-10-01 23:34:29'),(10,'2099624','prueba 5 de octubre','2025-10-05 21:23:22');
/*!40000 ALTER TABLE `comunidad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inicio`
--

DROP TABLE IF EXISTS `inicio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inicio` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inicio`
--

LOCK TABLES `inicio` WRITE;
/*!40000 ALTER TABLE `inicio` DISABLE KEYS */;
INSERT INTO `inicio` VALUES (1,'Introducción a la Industria 4.0'),(2,'Casos de Uso'),(3,'Bolsa de Trabajo'),(4,'Guía de Carreras');
/*!40000 ALTER TABLE `inicio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `juegos`
--

DROP TABLE IF EXISTS `juegos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `juegos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `juegos`
--

LOCK TABLES `juegos` WRITE;
/*!40000 ALTER TABLE `juegos` DISABLE KEYS */;
INSERT INTO `juegos` VALUES (1,'Trivia de la Industria 4.0'),(2,'Sopa de Letras de la IA y la Robótica'),(3,'Circuitos Mágicos'),(4,'Robo-Puzle'),(5,'Big Data Puzzles'),(6,'Retos de programación');
/*!40000 ALTER TABLE `juegos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modulos`
--

DROP TABLE IF EXISTS `modulos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modulos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modulos`
--

LOCK TABLES `modulos` WRITE;
/*!40000 ALTER TABLE `modulos` DISABLE KEYS */;
INSERT INTO `modulos` VALUES (1,'Internet de las Cosas (IoT)'),(2,'Inteligencia Artificial'),(3,'Big Data'),(4,'Computación en la Nube'),(5,'Ciberseguridad'),(6,'Robótica Avanzada');
/*!40000 ALTER TABLE `modulos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reacciones`
--

DROP TABLE IF EXISTS `reacciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reacciones` (
  `id` int NOT NULL AUTO_INCREMENT,
  `identificador` varchar(50) NOT NULL,
  `id_comunidad` int NOT NULL,
  `reaccion` tinyint NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fkComunidad_idx` (`id_comunidad`),
  KEY `fkIdentificador_idx` (`identificador`),
  CONSTRAINT `fkComunidad` FOREIGN KEY (`id_comunidad`) REFERENCES `comunidad` (`id`),
  CONSTRAINT `fkIdentificador` FOREIGN KEY (`identificador`) REFERENCES `alumnos` (`identificador`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reacciones`
--

LOCK TABLES `reacciones` WRITE;
/*!40000 ALTER TABLE `reacciones` DISABLE KEYS */;
INSERT INTO `reacciones` VALUES (6,'2050012',1,1),(11,'2050012',6,1),(13,'2050012',8,1),(14,'2050012',5,0),(21,'2099624',5,0),(22,'2099624',6,1),(23,'2099624',8,1),(24,'2099624',10,0),(25,'2099624',1,1);
/*!40000 ALTER TABLE `reacciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `records_juegos`
--

DROP TABLE IF EXISTS `records_juegos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `records_juegos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `identificador` varchar(50) NOT NULL,
  `juego` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `matricula_fk_idx` (`identificador`),
  KEY `juego_fk_idx` (`juego`),
  CONSTRAINT `identificador_fk` FOREIGN KEY (`identificador`) REFERENCES `alumnos` (`identificador`),
  CONSTRAINT `juego_fk` FOREIGN KEY (`juego`) REFERENCES `juegos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `records_juegos`
--

LOCK TABLES `records_juegos` WRITE;
/*!40000 ALTER TABLE `records_juegos` DISABLE KEYS */;
INSERT INTO `records_juegos` VALUES (1,'2099624',1),(3,'1871949',5),(4,'1871949',6),(5,'1871949',1),(6,'1871949',2),(7,'1871949',3),(8,'2099624',6),(9,'2050012',3),(10,'2099624',4),(11,'2099624',5);
/*!40000 ALTER TABLE `records_juegos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `records_modulos`
--

DROP TABLE IF EXISTS `records_modulos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `records_modulos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `identificador` varchar(50) NOT NULL,
  `modulo` int NOT NULL,
  `porcentaje` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `matricula_fk_idx` (`identificador`),
  KEY `fk_modulo_idx` (`modulo`),
  CONSTRAINT `fk_modulo` FOREIGN KEY (`modulo`) REFERENCES `modulos` (`id`),
  CONSTRAINT `identificadorFk` FOREIGN KEY (`identificador`) REFERENCES `alumnos` (`identificador`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `records_modulos`
--

LOCK TABLES `records_modulos` WRITE;
/*!40000 ALTER TABLE `records_modulos` DISABLE KEYS */;
INSERT INTO `records_modulos` VALUES (90,'correo@gmail.com',1,17);
/*!40000 ALTER TABLE `records_modulos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `soporte`
--

DROP TABLE IF EXISTS `soporte`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `soporte` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `soporte`
--

LOCK TABLES `soporte` WRITE;
/*!40000 ALTER TABLE `soporte` DISABLE KEYS */;
INSERT INTO `soporte` VALUES (1,'Preguntas Frecuentes'),(2,'Guías Tecnológicas'),(3,'Videos Tutoriales'),(4,'Reportar un Problema'),(5,'Teléfonos de Soporte'),(6,'Ubicación Física');
/*!40000 ALTER TABLE `soporte` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-13 21:33:28
