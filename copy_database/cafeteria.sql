-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.24-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para cafeteria
DROP DATABASE IF EXISTS `cafeteria`;
CREATE DATABASE IF NOT EXISTS `cafeteria` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `cafeteria`;

-- Volcando estructura para tabla cafeteria.category
DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla cafeteria.category: ~5 rows (aproximadamente)
DELETE FROM `category`;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` (`id`, `name`, `description`) VALUES
	(1, 'LACTEOS', 'Descripción categoria de producto #1'),
	(2, 'CARNES', 'Descripción categoria de producto #2'),
	(3, 'UTILES ESCOLARES', 'Descripción categoria de producto #3'),
	(4, 'GOLOSINAS', 'Descripción categoria de producto #4'),
	(5, 'FRUTAS Y VERDURAS', 'Descripción categoria de producto #5');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;

-- Volcando estructura para tabla cafeteria.product
DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(70) NOT NULL,
  `reference` varchar(30) NOT NULL,
  `price` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  `id_category` int(11) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `creation_date` date NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `referencia` (`reference`) USING BTREE,
  KEY `id_categoria` (`id_category`) USING BTREE,
  CONSTRAINT `FK1_product_category` FOREIGN KEY (`id_category`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla cafeteria.product: ~3 rows (aproximadamente)
DELETE FROM `product`;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` (`id`, `name`, `reference`, `price`, `weight`, `id_category`, `stock`, `creation_date`) VALUES
	(35, 'BANANO', '123464', 20, 200, 1, 200, '2022-11-12'),
	(36, 'BANANO  PEQUEÑO', '1234642', 2222, 222, 2, 222, '2022-11-12');
/*!40000 ALTER TABLE `product` ENABLE KEYS */;

-- Volcando estructura para tabla cafeteria.sale
DROP TABLE IF EXISTS `sale`;
CREATE TABLE IF NOT EXISTS `sale` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_product` int(11) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `creation_date` date DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `FK1p_c` (`id_product`),
  CONSTRAINT `FK1p_c` FOREIGN KEY (`id_product`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla cafeteria.sale: ~5 rows (aproximadamente)
DELETE FROM `sale`;
/*!40000 ALTER TABLE `sale` DISABLE KEYS */;
INSERT INTO `sale` (`id`, `id_product`, `amount`, `creation_date`) VALUES
	(10, 35, 100, '2022-11-12');
/*!40000 ALTER TABLE `sale` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
