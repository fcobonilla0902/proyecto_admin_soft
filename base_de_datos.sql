SET FOREIGN_KEY_CHECKS=0;

------------------------------------------------------
-- Table structure for table `alumnos`
------------------------------------------------------
DROP TABLE IF EXISTS `alumnos`;

CREATE TABLE `alumnos` (
  `identificador` VARCHAR(50) NOT NULL,
  `nombre` VARCHAR(45) NOT NULL,
  `apellido` VARCHAR(45) NOT NULL,
  `password` VARCHAR(60) NOT NULL,
  `tipo` SMALLINT NOT NULL,
  PRIMARY KEY (`identificador`)
);

--
-- Dumping data for table `alumnos` (Tabla principal sin FKs)
--
INSERT INTO `alumnos` VALUES 
('1871949','Tharek Alexis','García Arellano','$2y$10$6qEj6AFKPE3wmf64zXelF.7dG52CcOtytIrEWpgoe.Vvtrtydn9iS',1),
('2028753','Grecia Rubí ','Treviño Romero','$2y$10$BJGmznNwgI7BItycJZsutefohwZIHJslv61Am92d/WQZ00vDDwK7q',1),
('2043930','Emmanuel Gerard ','Espinosa Almaguer','$2y$10$xp9B6n8ocXDseuGzXvL8C.8eoLUI0KfyQlRTbyJiGGED/1yeQnbqW',1),
('2047608','Leslie Lizeth','Reyna Flores','$2y$10$x7vvgz.hdbdDqBRFu.yhxOWeP9/5HmTaTeBwHhKdw6TOQ7XoT9d.S',1),
('2050012','José Miguel','Urdiales Carriales','$2y$10$QapauEPfMQQxs0ZQDyh9XOFyaQapEwpnjK/.FZRlD48cuVk6Pv9Qq',1),
('2057220','Diego Enrique ','Vega Lara','$2y$10$udeZF0FtEEJuhRc.zOI2veHVEERp725ZLbSOwzFJlvmNketjVzVMC',1),
('2099624','Francisco Alonso','Bonilla Rodríguez','$2y$10$OtrVfSHcJz2kEBsKKEiehusC7vZOhZml/Td0jjl18pezy/ZqL4iBi',1),
('2109367','Sebastián','Saldívar Lerma','$2y$10$ndRaplalQ/afdHYp7B.oiuz26sgRs2HCl5hwYBBh7KvuAfYOsDH2y',1),
('2173930','Julio Enrique','Pérez Reyes','$2y$10$dC6FmnTPTTsW8tpQ9rmvr.XpqIoXxXEg9tFbUZNZvwoFd7iZrnMjC',1),
('2177988','Manuel','Magaña López','$2y$10$dHWu3tGd5T5fZ5z4tC9H1uQ0SGVVHBBr0KO5arJhYjkB/ZrAY7Qfu',1),
('correo@gmail.com','ejemplo','ejemplo','$2y$10$FNIir6Oza6hvY2TOQGAUb.CTPRN/DjNLdYFbHbirh0ec3THKRxl/S',0);


------------------------------------------------------
-- Table structure for table `comunidad`
------------------------------------------------------
DROP TABLE IF EXISTS `comunidad`;

CREATE TABLE `comunidad` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `identificador` VARCHAR(50) NOT NULL,
  `comentario` VARCHAR(200) NOT NULL,
  `fecha` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_identificador` FOREIGN KEY (`identificador`) REFERENCES `alumnos` (`identificador`)
);
-- Se han eliminado los INSERTs para evitar el fallo 1146/1451.


------------------------------------------------------
-- Table structure for table `inicio`
------------------------------------------------------
DROP TABLE IF EXISTS `inicio`;

CREATE TABLE `inicio` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`)
);

--
-- Dumping data for table `inicio`
--
INSERT INTO `inicio` (`nombre`) VALUES 
('Introducción a la Industria 4.0'),
('Casos de Uso'),
('Bolsa de Trabajo'),
('Guía de Carreras');


------------------------------------------------------
-- Table structure for table `juegos`
------------------------------------------------------
DROP TABLE IF EXISTS `juegos`;

CREATE TABLE `juegos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`)
);

--
-- Dumping data for table `juegos`
--
INSERT INTO `juegos` (`nombre`) VALUES 
('Trivia de la Industria 4.0'),
('Sopa de Letras de la IA y la Robótica'),
('Circuitos Mágicos'),
('Robo-Puzle'),
('Big Data Puzzles'),
('Retos de programación');


------------------------------------------------------
-- Table structure for table `modulos`
------------------------------------------------------
DROP TABLE IF EXISTS `modulos`;

CREATE TABLE `modulos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`)
);

--
-- Dumping data for table `modulos`
--
INSERT INTO `modulos` (`nombre`) VALUES 
('Internet de las Cosas (IoT)'),
('Inteligencia Artificial'),
('Big Data'),
('Computación en la Nube'),
('Ciberseguridad'),
('Robótica Avanzada');


------------------------------------------------------
-- Table structure for table `reacciones`
------------------------------------------------------
DROP TABLE IF EXISTS `reacciones`;

CREATE TABLE `reacciones` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `identificador` VARCHAR(50) NOT NULL,
  `id_comunidad` INT NOT NULL,
  `reaccion` SMALLINT NOT NULL,
  PRIMARY KEY (`id`)
  -- FKs ELIMINADAS TEMPORALMENTE
);
-- Se han eliminado los INSERTs para evitar el fallo 1146/1451.


------------------------------------------------------
-- Table structure for table `records_juegos`
------------------------------------------------------
DROP TABLE IF EXISTS `records_juegos`;

CREATE TABLE `records_juegos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `identificador` VARCHAR(50) NOT NULL,
  `juego` INT NOT NULL,
  PRIMARY KEY (`id`)
  -- FKs ELIMINADAS TEMPORALMENTE
);
-- Se han eliminado los INSERTs para evitar el fallo 1146/1451.


------------------------------------------------------
-- Table structure for table `records_modulos`
------------------------------------------------------
DROP TABLE IF EXISTS `records_modulos`;

CREATE TABLE `records_modulos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `identificador` VARCHAR(50) NOT NULL,
  `modulo` INT NOT NULL,
  `porcentaje` INT NOT NULL,
  PRIMARY KEY (`id`)
  -- FKs ELIMINADAS TEMPORALMENTE
);
-- Se han eliminado los INSERTs para evitar el fallo 1146/1451.

SET FOREIGN_KEY_CHECKS=1;