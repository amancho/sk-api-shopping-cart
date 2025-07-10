-- MySQL dump 10.13  Distrib 8.0.42, for Linux (x86_64)
--
-- Host: 172.33.0.3    Database: sk_shopping_db
-- ------------------------------------------------------
-- Server version	8.0.42

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart_items` (
                              `id` int NOT NULL AUTO_INCREMENT,
                              `public_id` char(36) NOT NULL,
                              `product_id` int DEFAULT NULL,
                              `price` int NOT NULL,
                              `quantity` int NOT NULL,
                              `name` varchar(255) DEFAULT NULL,
                              `color` varchar(255) DEFAULT NULL,
                              `size` varchar(255) DEFAULT NULL,
                              `cart_id` int NOT NULL,
                              `created_at` datetime NOT NULL,
                              `updated_at` datetime NOT NULL,
                              PRIMARY KEY (`id`),
                              UNIQUE KEY `cart_items_public_id_idx` (`public_id`),
                              KEY `cart_items_id_idx` (`cart_id`),
                              CONSTRAINT `fk_cart_item_cart` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_items`
--

LOCK TABLES `cart_items` WRITE;
/*!40000 ALTER TABLE `cart_items` DISABLE KEYS */;
INSERT INTO `cart_items` VALUES (1,'f545296d-68ec-4974-9777-15cd16de7410',0,100,5,'Trail runnning t-shirt','blue','s',1,'2025-07-10 09:13:38','2025-07-10 09:13:38'),(2,'c468452c-fc8e-411b-98b4-897d2df7fd6a',66,750,5,'Sunglasses','Black','XS',1,'2025-07-10 09:14:16','2025-07-10 09:14:16'),(3,'06d8e437-fc01-4b17-bf63-fc3197c0f5b2',99,500,1,'Shirt','White','XS',1,'2025-07-10 09:14:44','2025-07-10 09:17:39'),(4,'3e474190-1d59-48ba-bc95-f9230a3e8a02',55,50,1,'Shirt','White','XS',1,'2025-07-10 09:23:26','2025-07-10 09:23:26'),(5,'096421cb-4910-4050-b5bf-01878037abe8',66,750,5,'Sunglasses','Black','XS',3,'2025-07-10 11:34:34','2025-07-10 11:34:34');
/*!40000 ALTER TABLE `cart_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carts` (
                         `id` int NOT NULL AUTO_INCREMENT,
                         `public_id` char(36) NOT NULL,
                         `code` varchar(30) NOT NULL,
                         `checkout_id` varchar(255) DEFAULT NULL,
                         `user_id` int DEFAULT NULL,
                         `order_id` int DEFAULT NULL,
                         `status` varchar(50) NOT NULL,
                         `session_id` varchar(255) DEFAULT NULL,
                         `shipping_name` varchar(150) DEFAULT NULL,
                         `shipping_address` varchar(255) DEFAULT NULL,
                         `shipping_city` varchar(100) DEFAULT NULL,
                         `shipping_postal_code` varchar(25) DEFAULT NULL,
                         `shipping_province` varchar(50) DEFAULT NULL,
                         `shipping_country` varchar(50) DEFAULT NULL,
                         `shipping_phone` varchar(50) DEFAULT NULL,
                         `shipping_email` varchar(100) DEFAULT NULL,
                         `metadata` json DEFAULT NULL,
                         `created_at` datetime NOT NULL,
                         `updated_at` datetime NOT NULL,
                         PRIMARY KEY (`id`),
                         UNIQUE KEY `cart_public_id_idx` (`public_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carts`
--

LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
INSERT INTO `carts` VALUES (1,'f96b09fb-01bf-4778-acb3-8437ec063eed','CA-20250710091338-410',NULL,NULL,NULL,'new',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-07-10 09:13:38','2025-07-10 09:13:38'),(2,'e563cc33-44cf-474e-b928-64221f44aa1e','CA-20250710103236-195',NULL,NULL,NULL,'pending',NULL,'XX','Rue del Percebe, 13','',NULL,'Barcelona','España','666555444','pmanteca@dev.io',NULL,'2025-07-10 10:32:36','2025-07-10 10:49:04'),(3,'567b8510-c09f-4463-acc4-01ac4a8c4550','CA-20250710113357-737','chk_eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9',NULL,NULL,'completed',NULL,'Pepe Manteca','Rue del Percebe, 13','Ripollet',NULL,'Barcelona','España','666555444','pmanteca@dev.io',NULL,'2025-07-10 11:33:57','2025-07-10 11:34:50');
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctrine_migration_versions` (
                                               `version` varchar(191) NOT NULL,
                                               `executed_at` datetime DEFAULT NULL,
                                               `execution_time` int DEFAULT NULL,
                                               PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20250706202124','2025-07-10 09:13:38',43),('DoctrineMigrations\\Version20250708084016','2025-07-10 09:13:38',126),('DoctrineMigrations\\Version20250709160636','2025-07-10 09:13:38',22),('DoctrineMigrations\\Version20250709222026','2025-07-10 09:13:38',88);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messenger_messages` (
                                      `id` bigint NOT NULL AUTO_INCREMENT,
                                      `body` longtext NOT NULL,
                                      `headers` longtext NOT NULL,
                                      `queue_name` varchar(190) NOT NULL,
                                      `created_at` datetime NOT NULL,
                                      `available_at` datetime NOT NULL,
                                      `delivered_at` datetime DEFAULT NULL,
                                      PRIMARY KEY (`id`),
                                      KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
                                      KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
                                      KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messenger_messages`
--

LOCK TABLES `messenger_messages` WRITE;
/*!40000 ALTER TABLE `messenger_messages` DISABLE KEYS */;
INSERT INTO `messenger_messages` VALUES (1,'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:21:\\\"messenger.bus.default\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:40:\\\"App\\\\Domain\\\\Cart\\\\Events\\\\CartCheckoutEvent\\\":2:{s:6:\\\"cartId\\\";O:34:\\\"App\\\\Domain\\\\Cart\\\\ValueObject\\\\CartId\\\":1:{s:38:\\\"\\0App\\\\Domain\\\\Cart\\\\ValueObject\\\\CartId\\0id\\\";i:3;}s:10:\\\"checkoutId\\\";s:40:\\\"chk_eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9\\\";}}','[]','default','2025-07-10 11:34:50','2025-07-10 11:34:50',NULL);
/*!40000 ALTER TABLE `messenger_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_products`
--

DROP TABLE IF EXISTS `order_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_products` (
                                  `id` int NOT NULL AUTO_INCREMENT,
                                  `product_id` int NOT NULL,
                                  `name` varchar(255) DEFAULT NULL,
                                  `color` varchar(255) DEFAULT NULL,
                                  `size` varchar(255) DEFAULT NULL,
                                  `price` int NOT NULL,
                                  `quantity` int NOT NULL,
                                  `created_at` datetime NOT NULL,
                                  `updated_at` datetime NOT NULL,
                                  `order_id` int NOT NULL,
                                  PRIMARY KEY (`id`),
                                  KEY `order_id_idx` (`order_id`),
                                  CONSTRAINT `fk_order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_products`
--

LOCK TABLES `order_products` WRITE;
/*!40000 ALTER TABLE `order_products` DISABLE KEYS */;
INSERT INTO `order_products` VALUES (1,66,'Sunglasses','Black','XS',750,5,'2025-07-10 11:34:50','2025-07-10 11:34:50',1);
/*!40000 ALTER TABLE `order_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
                          `id` int NOT NULL AUTO_INCREMENT,
                          `code` varchar(30) NOT NULL,
                          `user_id` int DEFAULT NULL,
                          `status` varchar(50) NOT NULL,
                          `total_amount` int DEFAULT NULL,
                          `shipping_address` json DEFAULT NULL,
                          `metadata` json DEFAULT NULL,
                          `created_at` datetime NOT NULL,
                          `updated_at` datetime NOT NULL,
                          PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'O-20250710113450-234',NULL,'new',3750,'{\"city\": \"Ripollet\", \"name\": \"Pepe Manteca\", \"address\": \"Rue del Percebe, 13\", \"country\": \"España\", \"province\": \"Barcelona\", \"postal_code\": null}','{\"notes\": null, \"cart_id\": 3, \"cart_code\": \"CA-20250710113357-737\"}','2025-07-10 11:34:50','2025-07-10 11:34:50');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-07-10 13:36:32
