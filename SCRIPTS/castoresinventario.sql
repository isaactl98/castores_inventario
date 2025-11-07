/*
 Navicat Premium Data Transfer

 Source Server         : LocalServer
 Source Server Type    : MySQL
 Source Server Version : 100432
 Source Host           : localhost:3306
 Source Schema         : castoresinventario

 Target Server Type    : MySQL
 Target Server Version : 100432
 File Encoding         : 65001

 Date: 04/11/2025 20:57:49
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for movement_items
-- ----------------------------
DROP TABLE IF EXISTS `movement_items`;
CREATE TABLE `movement_items`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `movement_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(16, 2) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `movement_id`(`movement_id`) USING BTREE,
  INDEX `idx_movement_items_product`(`product_id`) USING BTREE,
  CONSTRAINT `movement_items_ibfk_1` FOREIGN KEY (`movement_id`) REFERENCES `movements` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `movement_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of movement_items
-- ----------------------------

-- ----------------------------
-- Table structure for movements
-- ----------------------------
DROP TABLE IF EXISTS `movements`;
CREATE TABLE `movements`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `movement_type` enum('ENTRADA','SALIDA') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` int NOT NULL,
  `movement_date` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  `notes` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_movements_type_date`(`movement_type`, `movement_date`) USING BTREE,
  INDEX `movements_ibfk_1`(`user_id`) USING BTREE,
  CONSTRAINT `movements_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`idUsuario`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of movements
-- ----------------------------

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `code`(`code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of permissions
-- ----------------------------

-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price` decimal(16, 2) NOT NULL DEFAULT 0,
  `stock` int NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int NULL DEFAULT NULL,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_products_active`(`active`) USING BTREE,
  INDEX `products_ibfk_1`(`created_by`) USING BTREE,
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `usuarios` (`idUsuario`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of products
-- ----------------------------

-- ----------------------------
-- Table structure for role_permissions
-- ----------------------------
DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE `role_permissions`  (
  `role_id` int NOT NULL,
  `permission_id` int NOT NULL,
  PRIMARY KEY (`role_id`, `permission_id`) USING BTREE,
  INDEX `permission_id`(`permission_id`) USING BTREE,
  CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of role_permissions
-- ----------------------------

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `name`(`name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of roles
-- ----------------------------

-- ----------------------------
-- Table structure for usuarios
-- ----------------------------
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios`  (
  `idUsuario` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `correo` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `contrasena` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `idRol` int NOT NULL,
  `estatus` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`idUsuario`) USING BTREE,
  UNIQUE INDEX `correo`(`correo`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of usuarios
-- ----------------------------

-- ----------------------------
-- Triggers structure for table movement_items
-- ----------------------------
DROP TRIGGER IF EXISTS `trg_mi_before_insert`;
delimiter ;;
CREATE TRIGGER `trg_mi_before_insert` BEFORE INSERT ON `movement_items` FOR EACH ROW BEGIN
  DECLARE m_type VARCHAR(20);
  SELECT movement_type INTO m_type FROM movements WHERE id = NEW.movement_id;
  IF m_type = 'SALIDA' THEN
    -- Comprobar stock actual del producto
    IF (SELECT stock FROM products WHERE id = NEW.product_id) < NEW.quantity THEN
      SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Stock insuficiente para realizar la salida';
    END IF;
  END IF;
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table movement_items
-- ----------------------------
DROP TRIGGER IF EXISTS `trg_mi_after_insert`;
delimiter ;;
CREATE TRIGGER `trg_mi_after_insert` AFTER INSERT ON `movement_items` FOR EACH ROW BEGIN
  DECLARE m_type VARCHAR(20);
  SELECT movement_type INTO m_type FROM movements WHERE id = NEW.movement_id;
  IF m_type = 'ENTRADA' THEN
    UPDATE products SET stock = stock + NEW.quantity, updated_at = NOW() WHERE id = NEW.product_id;
  ELSEIF m_type = 'SALIDA' THEN
    UPDATE products SET stock = stock - NEW.quantity, updated_at = NOW() WHERE id = NEW.product_id;
  END IF;
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table movement_items
-- ----------------------------
DROP TRIGGER IF EXISTS `trg_mi_after_delete`;
delimiter ;;
CREATE TRIGGER `trg_mi_after_delete` AFTER DELETE ON `movement_items` FOR EACH ROW BEGIN
  DECLARE m_type VARCHAR(20);
  SELECT movement_type INTO m_type FROM movements WHERE id = OLD.movement_id;
  IF m_type = 'ENTRADA' THEN
    UPDATE products SET stock = stock - OLD.quantity, updated_at = NOW() WHERE id = OLD.product_id;
  ELSEIF m_type = 'SALIDA' THEN
    UPDATE products SET stock = stock + OLD.quantity, updated_at = NOW() WHERE id = OLD.product_id;
  END IF;
END
;;
delimiter ;

SET FOREIGN_KEY_CHECKS = 1;
