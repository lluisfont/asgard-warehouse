/*
 Navicat Premium Dump SQL

 Source Server         : AsgardWarehouse
 Source Server Type    : MySQL
 Source Server Version : 80045 (8.0.45-azure)
 Source Host           : asgardwarehouse.mysql.database.azure.com:3306
 Source Schema         : almacen

 Target Server Type    : MySQL
 Target Server Version : 80045 (8.0.45-azure)
 File Encoding         : 65001

 Date: 05/08/2026 14:40:16
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for f_transporteslg
-- ----------------------------
DROP TABLE IF EXISTS `f_transporteslg`;
CREATE TABLE `f_transporteslg`  (
  `idpagosdetalle` int NULL DEFAULT NULL,
  `idcasos` int NULL DEFAULT NULL,
  `idciudad` int NULL DEFAULT NULL,
  `carpeta` int NULL DEFAULT NULL,
  `idfacturaplanilla` int NULL DEFAULT NULL,
  `descripcion` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `monto` decimal(13, 2) NULL DEFAULT NULL,
  `nro` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  INDEX `idpagosdetalle`(`idpagosdetalle` ASC) USING BTREE,
  INDEX `idciudad`(`idciudad` ASC) USING BTREE,
  INDEX `carpeta`(`carpeta` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for log_ovp
-- ----------------------------
DROP TABLE IF EXISTS `log_ovp`;
CREATE TABLE `log_ovp`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `TABLA` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `FECHA` datetime NULL DEFAULT NULL,
  `NOMBRE` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `TEXTO` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `TIPO` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `IP_USUARIO` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `PC_USUARIO` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `ID_USUARIO` int NOT NULL,
  `USERNAME` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `FILENAME` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `ACCION` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `JSON` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `JSONOVP` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12351 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for pbi_facturacion
-- ----------------------------
DROP TABLE IF EXISTS `pbi_facturacion`;
CREATE TABLE `pbi_facturacion`  (
  `my_row_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `idfactura` int NOT NULL DEFAULT 0,
  `cliente` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `fecha` date NULL DEFAULT NULL,
  `monto` decimal(13, 2) NULL DEFAULT NULL,
  `nrofactura` int NULL DEFAULT NULL,
  `estadofactura` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`my_row_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12330 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for pbi_ingresos
-- ----------------------------
DROP TABLE IF EXISTS `pbi_ingresos`;
CREATE TABLE `pbi_ingresos`  (
  `my_row_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `idingresodetalle` int NULL DEFAULT 0,
  `idingreso` int NULL DEFAULT NULL,
  `cliente` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `numeroingreso` varchar(23) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `fechaingreso` datetime NULL DEFAULT NULL,
  `placa` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `contenedor` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idtipoingreso` int NULL DEFAULT NULL,
  `tipoingreso` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `precinto` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idtipodescarga` int NULL DEFAULT NULL,
  `tipodescarga` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `idtipocamion` int NULL DEFAULT NULL,
  `tipocamion` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idtipocontenedor` int NULL DEFAULT NULL,
  `tipocontenedor` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idtipoproducto` int NULL DEFAULT NULL,
  `tipoproducto` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `piezas_manifestadas` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `peso_total` decimal(13, 2) NULL DEFAULT NULL,
  `proveedor` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `no_contrato` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `delivery_batch` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `rubro_producto` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `project` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `invoice` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `dui` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `cantidad_pallet` int NULL DEFAULT NULL,
  `cantidad_cajas` int NULL DEFAULT NULL,
  `hora_inicio` time NULL DEFAULT NULL,
  `hora_fin` time NULL DEFAULT NULL,
  `cantidad_estibadores` int NULL DEFAULT NULL,
  `nota_adicional` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `idusuario_recibido` int NULL DEFAULT NULL,
  `usuario_recibido` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `fechasistema` varchar(21) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `nombre_entrega` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `ci_entrega` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `empresa_entrega` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `codigo` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `serie` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `descripcion` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `centro_distribucion` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `sustanciascontroladas` bigint NOT NULL DEFAULT 0,
  `categoria` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `cantidad` decimal(13, 2) NULL DEFAULT NULL,
  `idembalaje` int NULL DEFAULT NULL,
  `codigoembalaje` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `lote` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `costo_un` decimal(13, 2) NULL DEFAULT NULL,
  `cantidad_no_conf` decimal(13, 2) NULL DEFAULT NULL,
  `no_conf` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `merma` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `fechaproduccion` varchar(19) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `fechavencimiento` varchar(19) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `relacion_caja` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `volumen` decimal(13, 2) NULL DEFAULT NULL,
  `bultos` decimal(13, 3) NULL DEFAULT NULL,
  `peso` decimal(13, 2) NULL DEFAULT NULL,
  `temperatura` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `observaciones` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `ubicacionalmacen` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `codigoembalaje_salida` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `factor_conversion` decimal(13, 2) NOT NULL DEFAULT 0.00,
  `fecha_cierre_transito` datetime NULL DEFAULT NULL,
  `fecha_emision_parte` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`my_row_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 30512 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for pbi_logistico
-- ----------------------------
DROP TABLE IF EXISTS `pbi_logistico`;
CREATE TABLE `pbi_logistico`  (
  `my_row_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `idembarque` int NOT NULL DEFAULT 0,
  `tipoembarque` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `cliente` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `ciudad` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `embarque` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `prefijo` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `Tipo` varchar(28) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `Cargo` decimal(13, 2) NULL DEFAULT NULL,
  `Costo` decimal(13, 2) NULL DEFAULT NULL,
  `Balance` decimal(14, 2) NULL DEFAULT NULL,
  `fecharealizacion` date NULL DEFAULT NULL,
  `peso` decimal(13, 2) NULL DEFAULT NULL,
  `incoterms` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `mediotransporte` varchar(70) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `transportista` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `descripcioncarga` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `salidade` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `fechasalida` date NULL DEFAULT NULL,
  `arriboa` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `fechaarribo` date NULL DEFAULT NULL,
  `origen` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `destino` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `nombre` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `finalizado` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`my_row_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8219 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for pbi_reporte_inventario_fisico
-- ----------------------------
DROP TABLE IF EXISTS `pbi_reporte_inventario_fisico`;
CREATE TABLE `pbi_reporte_inventario_fisico`  (
  `my_row_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `idinventariofisico` int NULL DEFAULT NULL,
  `fecha_creacion` datetime NULL DEFAULT NULL,
  `ciudad` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `codigo_almacen` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `almacen` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `marca` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `chasis` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `modelo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `color` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `categoria` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `estado` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `usuario_conteo` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `fecha_inicio_conteo` datetime NULL DEFAULT NULL,
  `fecha_fin_conteo` datetime NULL DEFAULT NULL,
  `inventariofisicoetiqueta` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `usuario_fin_inventario` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `fecha_fin_inventario` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`my_row_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1445 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for pbi_salidas
-- ----------------------------
DROP TABLE IF EXISTS `pbi_salidas`;
CREATE TABLE `pbi_salidas`  (
  `my_row_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `idsalidadetalle` int NULL DEFAULT 0,
  `almacen` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `cliente` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `numerosalida` varchar(23) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `fechasalida` varchar(28) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `solicitado_por` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `autorizado_por` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `delivery_note` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `proyecto_no` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `contrato_no` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `rubro_producto` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `ciudad` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `direccion_entrega` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `transporte` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `placa` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `cantidad_pallet` int NULL DEFAULT NULL,
  `cantidad_cajas` int NULL DEFAULT NULL,
  `autorizacion_compra` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `hora_inicio_a` time NULL DEFAULT NULL,
  `hora_fin_a` time NULL DEFAULT NULL,
  `cantidad_estibadores_a` int NULL DEFAULT NULL,
  `nota_adicional` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `hora_inicio_b` time NULL DEFAULT NULL,
  `hora_fin_b` time NULL DEFAULT NULL,
  `cantidad_estibadores_b` int NULL DEFAULT NULL,
  `nombre_entrega` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `ci_entrega` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `nombre_recibido` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `ci_recibido` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `empresa_recibido` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `fecha_recibido` varchar(19) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `entrega_a_tiempo` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `entrega_completa_conforme` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `codigo` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `serie` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `descripcion` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `categoria` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `cantidad` decimal(13, 2) NULL DEFAULT NULL,
  `codigoembalaje` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `lote` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `merma` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `fechavencimiento` varchar(19) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `bultos` decimal(13, 2) NULL DEFAULT NULL,
  `cantidad_no_conf` decimal(13, 2) NULL DEFAULT NULL,
  `no_conf` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `peso` decimal(13, 2) NULL DEFAULT NULL,
  `temperatura` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `observaciones` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `numeroingreso` varchar(23) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `fechaingreso` datetime NULL DEFAULT NULL,
  `ubicacionalmacen` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`my_row_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 147222 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for pbi_vencimientos
-- ----------------------------
DROP TABLE IF EXISTS `pbi_vencimientos`;
CREATE TABLE `pbi_vencimientos`  (
  `my_row_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `idalmacen` int NULL DEFAULT NULL,
  `idcliente` int NULL DEFAULT NULL,
  `almacen` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `cliente` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `codigo` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `serie` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `descripcion` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `cantidadactual` decimal(14, 2) NULL DEFAULT NULL,
  `codigoembalaje` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `fechaingreso` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `fechavencimiento` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `lote` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `diasvencimiento` int NULL DEFAULT NULL,
  `estado` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `color` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `idingresodetalle` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`my_row_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 30761 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_accesorios_vehiculos
-- ----------------------------
DROP TABLE IF EXISTS `t_accesorios_vehiculos`;
CREATE TABLE `t_accesorios_vehiculos`  (
  `idaccesorios_vehiculos` int NOT NULL AUTO_INCREMENT,
  `idcliente` int NULL DEFAULT NULL,
  `accesorios_vehiculos` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `requiere_cantidad` int NULL DEFAULT NULL,
  `requiere_texto` int NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`idaccesorios_vehiculos`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 39 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_accesorios_vehiculos_salidas
-- ----------------------------
DROP TABLE IF EXISTS `t_accesorios_vehiculos_salidas`;
CREATE TABLE `t_accesorios_vehiculos_salidas`  (
  `idaccesorios_vehiculos_salidas` int NOT NULL AUTO_INCREMENT,
  `accesorios_vehiculos_salidas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `requiere_cantidad` int NULL DEFAULT NULL,
  `requiere_texto` int NULL DEFAULT NULL,
  PRIMARY KEY (`idaccesorios_vehiculos_salidas`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_aduana
-- ----------------------------
DROP TABLE IF EXISTS `t_aduana`;
CREATE TABLE `t_aduana`  (
  `idaduana` int NOT NULL AUTO_INCREMENT,
  `aduana` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idaduana`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_agentecarga
-- ----------------------------
DROP TABLE IF EXISTS `t_agentecarga`;
CREATE TABLE `t_agentecarga`  (
  `idagentecarga` int NOT NULL AUTO_INCREMENT,
  `idempresa` int NULL DEFAULT NULL,
  `agentecarga` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `numeroidentificacion` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `telefono` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `fax` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `email` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `numerocuenta` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `nombrecontacto` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `plazo` int NULL DEFAULT NULL,
  `id_OVPProv` varchar(11) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idtipodocumento` int NULL DEFAULT NULL,
  `numerofacturacion` int NULL DEFAULT NULL,
  `razonsocial` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idagentecarga`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 116 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_agentecargacorreofacturacion
-- ----------------------------
DROP TABLE IF EXISTS `t_agentecargacorreofacturacion`;
CREATE TABLE `t_agentecargacorreofacturacion`  (
  `idagentecargacorreofacturacion` int NOT NULL AUTO_INCREMENT,
  `idagentecarga` int NULL DEFAULT NULL,
  `correo` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idagentecargacorreofacturacion`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_agentecargadireccion
-- ----------------------------
DROP TABLE IF EXISTS `t_agentecargadireccion`;
CREATE TABLE `t_agentecargadireccion`  (
  `idagentecargadireccion` int NOT NULL AUTO_INCREMENT,
  `idagentecarga` int NULL DEFAULT NULL,
  `direccion` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `ciudad` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idpais` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `nombrecontacto` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `email` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idagentecargadireccion`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 131 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_almacen
-- ----------------------------
DROP TABLE IF EXISTS `t_almacen`;
CREATE TABLE `t_almacen`  (
  `idalmacen` int NOT NULL AUTO_INCREMENT,
  `almacen` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `codigo_almacen` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idciudad` int NULL DEFAULT NULL,
  `direccion` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `filas` int NULL DEFAULT NULL,
  `columnas` int NULL DEFAULT NULL,
  PRIMARY KEY (`idalmacen`) USING BTREE,
  INDEX `idciudad`(`idciudad` ASC) USING BTREE,
  CONSTRAINT `idciudad` FOREIGN KEY (`idciudad`) REFERENCES `t_ciudad` (`idciudad`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 233 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_almacendetalle
-- ----------------------------
DROP TABLE IF EXISTS `t_almacendetalle`;
CREATE TABLE `t_almacendetalle`  (
  `idalmacendetalle` int NOT NULL AUTO_INCREMENT,
  `idalmacen` int NULL DEFAULT NULL,
  `filainicial` int NULL DEFAULT NULL,
  `columnainicial` int NULL DEFAULT NULL,
  `tipo` int NULL DEFAULT NULL,
  `idtipoalmacendetalle` int NULL DEFAULT NULL,
  `idcategoriaalmacendetalle` int NULL DEFAULT NULL,
  `sector` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `texto` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `columna` int NULL DEFAULT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `filafinal` int NULL DEFAULT NULL,
  `columnafinal` int NULL DEFAULT NULL,
  `lineasuperior` int NULL DEFAULT NULL,
  `color` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `orden` int NULL DEFAULT NULL,
  `activo` int NULL DEFAULT NULL,
  `bloqueado` int NULL DEFAULT NULL,
  `ubicacion_unica` int NULL DEFAULT NULL,
  PRIMARY KEY (`idalmacendetalle`) USING BTREE,
  INDEX `nombre`(`nombre` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8133 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_anticipo
-- ----------------------------
DROP TABLE IF EXISTS `t_anticipo`;
CREATE TABLE `t_anticipo`  (
  `idanticipo` int NOT NULL AUTO_INCREMENT,
  `identidad` int NULL DEFAULT NULL,
  `idtipoentidad` int NULL DEFAULT NULL,
  `fecha` date NULL DEFAULT NULL,
  `recibo` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idcuenta` int NULL DEFAULT NULL,
  `idtipotransferencia` int NULL DEFAULT NULL,
  `glosa` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `monto` decimal(13, 2) NULL DEFAULT NULL,
  `anticiporeal` int NULL DEFAULT NULL,
  `idusuario` int NULL DEFAULT NULL,
  PRIMARY KEY (`idanticipo`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4467 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_ate_gas
-- ----------------------------
DROP TABLE IF EXISTS `t_ate_gas`;
CREATE TABLE `t_ate_gas`  (
  `idate_gas` int NOT NULL AUTO_INCREMENT,
  `idalmacen` int NULL DEFAULT NULL,
  `idcliente` int NULL DEFAULT NULL,
  `sede` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `tipo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `chasis` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `marca` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `modelo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `cliente` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `canal` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `configuracion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `tipo_tanque` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `tipo_ot` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `valor_neto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `pedido_ot` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `fecha_ot` date NULL DEFAULT NULL,
  `prog_envio` date NULL DEFAULT NULL,
  `estado_vehiculo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `observaciones` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `fecha_recepcion` datetime NULL DEFAULT NULL,
  `fecha_programacion_salida` datetime NULL DEFAULT NULL,
  `fecha_salida` datetime NULL DEFAULT NULL,
  `destino_salida` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `transportista_salida` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `nombre_original_salida` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `nombre_guardado_salida` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `ubicacion_fisica_salida` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `edited_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`idate_gas`) USING BTREE,
  INDEX `idalmacen`(`idalmacen` ASC) USING BTREE,
  INDEX `idcliente`(`idcliente` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_ate_gas_etapa
-- ----------------------------
DROP TABLE IF EXISTS `t_ate_gas_etapa`;
CREATE TABLE `t_ate_gas_etapa`  (
  `idate_gas_etapa` int NOT NULL AUTO_INCREMENT,
  `idate_gas` int NULL DEFAULT NULL,
  `paso` int NULL DEFAULT NULL,
  `idalmacendetalle` int NULL DEFAULT NULL,
  `idetapa` int NULL DEFAULT NULL,
  `observaciones_inventario` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `idestado_etapa` int NULL DEFAULT NULL,
  `fecha_inventario` datetime NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `edited_at` datetime NULL DEFAULT NULL,
  `deteled_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`idate_gas_etapa`) USING BTREE,
  INDEX `idate_gas`(`idate_gas` ASC) USING BTREE,
  INDEX `paso`(`paso` ASC) USING BTREE,
  INDEX `idetapa`(`idetapa` ASC) USING BTREE,
  INDEX `idestado_etapa`(`idestado_etapa` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_ate_gas_etapa_imagen
-- ----------------------------
DROP TABLE IF EXISTS `t_ate_gas_etapa_imagen`;
CREATE TABLE `t_ate_gas_etapa_imagen`  (
  `idate_gas_etapa_imagen` int NOT NULL AUTO_INCREMENT,
  `idate_gas_etapa` int NULL DEFAULT NULL,
  `nombre_original` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `nombre_guardado` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `ubicacion_fisica` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `ubicacion_thumb` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`idate_gas_etapa_imagen`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_ate_gas_etapa_inventario
-- ----------------------------
DROP TABLE IF EXISTS `t_ate_gas_etapa_inventario`;
CREATE TABLE `t_ate_gas_etapa_inventario`  (
  `idate_gas_etapa_inventario` int NOT NULL AUTO_INCREMENT,
  `idate_gas_etapa` int NULL DEFAULT NULL,
  `iddanios_vehiculos` int NULL DEFAULT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `edited_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`idate_gas_etapa_inventario`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_ate_gas_etapa_inventario_imagen
-- ----------------------------
DROP TABLE IF EXISTS `t_ate_gas_etapa_inventario_imagen`;
CREATE TABLE `t_ate_gas_etapa_inventario_imagen`  (
  `idate_gas_etapa_inventario_imagen` int NOT NULL AUTO_INCREMENT,
  `idate_gas_etapa` int NULL DEFAULT NULL,
  `iddanios_vehiculos` int NULL DEFAULT NULL,
  `nombre_original` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `nombre_guardado` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `ubicacion_fisica` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `ubicacion_thumb` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`idate_gas_etapa_inventario_imagen`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_ate_gas_etapa_tecnico
-- ----------------------------
DROP TABLE IF EXISTS `t_ate_gas_etapa_tecnico`;
CREATE TABLE `t_ate_gas_etapa_tecnico`  (
  `idate_gas_etapa_tecnico` int NOT NULL AUTO_INCREMENT,
  `idate_gas_etapa` int NULL DEFAULT NULL,
  `idusuario` int NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`idate_gas_etapa_tecnico`) USING BTREE,
  INDEX `idate_gas_etapa`(`idate_gas_etapa` ASC) USING BTREE,
  INDEX `idusuario`(`idusuario` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_ate_gas_etapa_tecnico_qa
-- ----------------------------
DROP TABLE IF EXISTS `t_ate_gas_etapa_tecnico_qa`;
CREATE TABLE `t_ate_gas_etapa_tecnico_qa`  (
  `idate_gas_etapa_tecnico_qa` int NOT NULL AUTO_INCREMENT,
  `idate_gas_etapa` int NULL DEFAULT NULL,
  `idusuario` int NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`idate_gas_etapa_tecnico_qa`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_ate_gas_etapa_tiempos
-- ----------------------------
DROP TABLE IF EXISTS `t_ate_gas_etapa_tiempos`;
CREATE TABLE `t_ate_gas_etapa_tiempos`  (
  `idate_gas_etapa_tiempos` int NOT NULL AUTO_INCREMENT,
  `idate_gas_etapa` int NULL DEFAULT NULL,
  `inicio` datetime NULL DEFAULT NULL,
  `fin` datetime NULL DEFAULT NULL,
  `idusuario` int NULL DEFAULT NULL,
  `idate_gas_motivo_pausa` int NULL DEFAULT NULL,
  `motivo_pausa` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `edited_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`idate_gas_etapa_tiempos`) USING BTREE,
  INDEX `idate_gas_etapa`(`idate_gas_etapa` ASC) USING BTREE,
  INDEX `idusuario`(`idusuario` ASC) USING BTREE,
  INDEX `inicio`(`inicio` ASC) USING BTREE,
  INDEX `fin`(`fin` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_ate_gas_motivo_pausa
-- ----------------------------
DROP TABLE IF EXISTS `t_ate_gas_motivo_pausa`;
CREATE TABLE `t_ate_gas_motivo_pausa`  (
  `idate_gas_motivo_pausa` int NOT NULL AUTO_INCREMENT,
  `ate_gas_motivo_pausa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idate_gas_motivo_pausa`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_ate_gas_ubicacion
-- ----------------------------
DROP TABLE IF EXISTS `t_ate_gas_ubicacion`;
CREATE TABLE `t_ate_gas_ubicacion`  (
  `idate_gas_ubicacion` int NOT NULL AUTO_INCREMENT,
  `idate_gas` int NULL DEFAULT NULL,
  `idate_gas_etapa` int NULL DEFAULT NULL,
  `idalmacendetalle` int NULL DEFAULT NULL,
  `fecha_ingreso` datetime NULL DEFAULT NULL,
  `fecha_salida` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`idate_gas_ubicacion`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_baseproductos
-- ----------------------------
DROP TABLE IF EXISTS `t_baseproductos`;
CREATE TABLE `t_baseproductos`  (
  `idbaseproductos` int NOT NULL AUTO_INCREMENT,
  `idcliente` int NULL DEFAULT NULL,
  `rubro` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `codigo` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `serie` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `descripcion` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `categoria` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idembalaje` int NULL DEFAULT NULL,
  `umcompra` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `umalterna` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `alto` decimal(13, 2) NULL DEFAULT NULL,
  `ancho` decimal(13, 2) NULL DEFAULT NULL,
  `largo` decimal(13, 2) NULL DEFAULT NULL,
  `color` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `centro_distribucion` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idembalaje_salida` int NULL DEFAULT NULL,
  `factor_conversion` decimal(13, 2) NULL DEFAULT NULL,
  `meta_timbrado` decimal(13, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`idbaseproductos`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3712 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_cargo
-- ----------------------------
DROP TABLE IF EXISTS `t_cargo`;
CREATE TABLE `t_cargo`  (
  `idcargo` int NOT NULL AUTO_INCREMENT,
  `idembarque` int NULL DEFAULT NULL,
  `idconcepto` int NULL DEFAULT NULL,
  `iddivisa` int NULL DEFAULT NULL,
  `monto` decimal(13, 3) NULL DEFAULT NULL,
  `cantidad` decimal(13, 3) NULL DEFAULT NULL,
  `iddestinatario` int NULL DEFAULT NULL,
  `idtipodestinatario` int NULL DEFAULT NULL,
  `notas` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `idfacturanotadebito` int NULL DEFAULT NULL,
  `idtipofacturanotadebito` int NULL DEFAULT NULL,
  `idplanilla` int NULL DEFAULT NULL,
  `idtipoplanilla` int NULL DEFAULT NULL,
  `invoiceseleccionado` int NULL DEFAULT NULL,
  `idinvoice` int NULL DEFAULT NULL,
  `esagente` int NULL DEFAULT NULL,
  `idordenservicioi` int NULL DEFAULT NULL,
  `ordenservicioiseleccionado` int NULL DEFAULT NULL,
  `factura` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `iddestinocargo` int NULL DEFAULT NULL,
  `idusuario` int NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`idcargo`) USING BTREE,
  INDEX `idfacturanotadebito`(`idfacturanotadebito` ASC) USING BTREE,
  INDEX `idtipofacturanotadebito`(`idtipofacturanotadebito` ASC) USING BTREE,
  INDEX `idembarque`(`idembarque` ASC) USING BTREE,
  INDEX `iddivisa`(`iddivisa` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 44784 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_categoriaalmacendetalle
-- ----------------------------
DROP TABLE IF EXISTS `t_categoriaalmacendetalle`;
CREATE TABLE `t_categoriaalmacendetalle`  (
  `idcategoriaalmacendetalle` int NOT NULL AUTO_INCREMENT,
  `categoriaalmacendetalle` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idcategoriaalmacendetalle`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_centro_rubro
-- ----------------------------
DROP TABLE IF EXISTS `t_centro_rubro`;
CREATE TABLE `t_centro_rubro`  (
  `idcentro_rubro` int NOT NULL AUTO_INCREMENT,
  `idcliente` int NULL DEFAULT NULL,
  `centro_distribucion` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `rubro` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idcentro_rubro`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_ciudad
-- ----------------------------
DROP TABLE IF EXISTS `t_ciudad`;
CREATE TABLE `t_ciudad`  (
  `idciudad` int NOT NULL AUTO_INCREMENT,
  `idempresa` int NULL DEFAULT NULL,
  `codigo` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `ciudad` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `modotransporte` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `pais` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `timezone_name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `utc_offset_minutos` smallint NULL DEFAULT NULL,
  `almacen` int NULL DEFAULT NULL,
  `parametrizacion` int NULL DEFAULT NULL,
  `idaduana` int NULL DEFAULT NULL,
  PRIMARY KEY (`idciudad`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 227 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_clasificacion
-- ----------------------------
DROP TABLE IF EXISTS `t_clasificacion`;
CREATE TABLE `t_clasificacion`  (
  `idclasificacion` int NOT NULL AUTO_INCREMENT,
  `clasificacion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idclasificacion`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_cleintemetodotimbrado
-- ----------------------------
DROP TABLE IF EXISTS `t_cleintemetodotimbrado`;
CREATE TABLE `t_cleintemetodotimbrado`  (
  `idcleintemetodotimbrado` int NOT NULL AUTO_INCREMENT,
  `idcliente` int NULL DEFAULT NULL,
  `metodotimbrado` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `monto` decimal(13, 2) NULL DEFAULT NULL,
  `iddivisa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idcleintemetodotimbrado`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_cliente
-- ----------------------------
DROP TABLE IF EXISTS `t_cliente`;
CREATE TABLE `t_cliente`  (
  `idcliente` int NOT NULL AUTO_INCREMENT,
  `idempresa` int NULL DEFAULT NULL,
  `cliente` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `web` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `numeroidentificacion` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `direccion` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idciudad` int NULL DEFAULT NULL,
  `pais` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `telefono` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `fax` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `representante_legal` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `telefono_representante` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `email_representante` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `nombrecontacto` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idtipodocumento` int NULL DEFAULT NULL,
  `numerofacturacion` bigint NULL DEFAULT NULL,
  `razonsocial` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `username` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `contrasena` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `numerocuenta` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `plazo` int NULL DEFAULT NULL,
  `id_OVP` int NULL DEFAULT NULL,
  `color` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `color_fecha_pase_salida` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `notificacion_vencimiento` int NULL DEFAULT NULL,
  `idtipoliquidacion` int NULL DEFAULT NULL,
  `monto_fee_mensual` decimal(13, 2) NULL DEFAULT NULL,
  `tarifa_adicional` decimal(13, 2) NULL DEFAULT NULL,
  `descarguio_adicional` decimal(13, 2) NULL DEFAULT NULL,
  `inbound` decimal(13, 2) NULL DEFAULT NULL,
  `outbound` decimal(13, 2) NULL DEFAULT NULL,
  `servicios_administrativos` decimal(13, 2) NULL DEFAULT NULL,
  `servicio_nocturno` decimal(13, 2) NULL DEFAULT NULL,
  `servicio_fin_semana` decimal(13, 2) NULL DEFAULT NULL,
  `estibadores` decimal(13, 2) NULL DEFAULT NULL,
  `posiciones_fee` decimal(13, 2) NULL DEFAULT NULL,
  `alto` decimal(13, 2) NULL DEFAULT NULL,
  `ancho` decimal(13, 2) NULL DEFAULT NULL,
  `largo` decimal(13, 2) NULL DEFAULT NULL,
  `alto_adicional` decimal(13, 2) NULL DEFAULT NULL,
  `ancho_adicional` decimal(13, 2) NULL DEFAULT NULL,
  `largo_adicional` decimal(13, 2) NULL DEFAULT NULL,
  `dias_por_vencer` int NULL DEFAULT NULL,
  `dias_vencido` int NULL DEFAULT NULL,
  `id_asgard` int NULL DEFAULT NULL,
  `id_cliente_usuario_asgard` int NULL DEFAULT NULL,
  PRIMARY KEY (`idcliente`) USING BTREE,
  INDEX `idciudad`(`idciudad` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 869 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_cliente_color
-- ----------------------------
DROP TABLE IF EXISTS `t_cliente_color`;
CREATE TABLE `t_cliente_color`  (
  `idcliente_color` int NOT NULL AUTO_INCREMENT,
  `idcliente` int NULL DEFAULT NULL,
  `condicion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `color` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idcliente_color`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_cliente_no_conf_no_considerar
-- ----------------------------
DROP TABLE IF EXISTS `t_cliente_no_conf_no_considerar`;
CREATE TABLE `t_cliente_no_conf_no_considerar`  (
  `idcliente_no_conf_no_considerar` int NOT NULL AUTO_INCREMENT,
  `idcliente` int NULL DEFAULT NULL,
  `idno_conf` int NULL DEFAULT NULL,
  PRIMARY KEY (`idcliente_no_conf_no_considerar`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 138 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_clientecorreofacturacion
-- ----------------------------
DROP TABLE IF EXISTS `t_clientecorreofacturacion`;
CREATE TABLE `t_clientecorreofacturacion`  (
  `idclientecorreofacturacion` int NOT NULL AUTO_INCREMENT,
  `idcliente` int NULL DEFAULT NULL,
  `correo` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idclientecorreofacturacion`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 204 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_clientecorreonotificacion
-- ----------------------------
DROP TABLE IF EXISTS `t_clientecorreonotificacion`;
CREATE TABLE `t_clientecorreonotificacion`  (
  `idclientecorreonotificacion` int NOT NULL AUTO_INCREMENT,
  `idcliente` int NULL DEFAULT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `correo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `vencimiento` int NULL DEFAULT NULL,
  `vencido` int NULL DEFAULT NULL,
  PRIMARY KEY (`idclientecorreonotificacion`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 26 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_clientecorreovencimientoprm
-- ----------------------------
DROP TABLE IF EXISTS `t_clientecorreovencimientoprm`;
CREATE TABLE `t_clientecorreovencimientoprm`  (
  `idclientecorreovencimientoprm` int NOT NULL AUTO_INCREMENT,
  `idcliente` int NULL DEFAULT NULL,
  `correo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idclientecorreovencimientoprm`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_clientediasvencimiento
-- ----------------------------
DROP TABLE IF EXISTS `t_clientediasvencimiento`;
CREATE TABLE `t_clientediasvencimiento`  (
  `idclientediasvencimiento` int NOT NULL AUTO_INCREMENT,
  `idcliente` int NULL DEFAULT NULL,
  `rubro_producto` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `diasvencimiento` int NULL DEFAULT NULL,
  PRIMARY KEY (`idclientediasvencimiento`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_clientedireccion
-- ----------------------------
DROP TABLE IF EXISTS `t_clientedireccion`;
CREATE TABLE `t_clientedireccion`  (
  `idclientedireccion` int NOT NULL AUTO_INCREMENT,
  `idcliente` int NULL DEFAULT NULL,
  `direccion` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `ciudad` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idpais` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `nombrecontacto` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `email` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idclientedireccion`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 964 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_clientegestionlogistica
-- ----------------------------
DROP TABLE IF EXISTS `t_clientegestionlogistica`;
CREATE TABLE `t_clientegestionlogistica`  (
  `idclientegestionlogistica` int NOT NULL AUTO_INCREMENT,
  `idcliente` int NULL DEFAULT NULL,
  `importacion_exportacion` int NULL DEFAULT NULL,
  `idmediotransporte` int NULL DEFAULT NULL,
  `idtipocarga` int NULL DEFAULT NULL,
  `aduana` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `idaduana` int NULL DEFAULT NULL,
  `iddestino` int NULL DEFAULT NULL,
  `idtemperatura` int NULL DEFAULT NULL,
  `idhorario` int NULL DEFAULT NULL,
  `volumen` decimal(13, 2) NULL DEFAULT NULL,
  `peso_desde` decimal(13, 2) NULL DEFAULT NULL,
  `peso_hasta` decimal(13, 2) NULL DEFAULT NULL,
  `cantidad_pallets` int NULL DEFAULT NULL,
  `monto_fijo` decimal(13, 2) NULL DEFAULT NULL,
  `monto_por_peso` decimal(13, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`idclientegestionlogistica`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2140 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_clientereporte
-- ----------------------------
DROP TABLE IF EXISTS `t_clientereporte`;
CREATE TABLE `t_clientereporte`  (
  `idclientereporte` int NOT NULL AUTO_INCREMENT,
  `idcliente` int NULL DEFAULT NULL,
  `idreporte` int NULL DEFAULT NULL,
  PRIMARY KEY (`idclientereporte`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 119 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_clienteserviciologistico
-- ----------------------------
DROP TABLE IF EXISTS `t_clienteserviciologistico`;
CREATE TABLE `t_clienteserviciologistico`  (
  `idclienteserviciologistico` int NOT NULL AUTO_INCREMENT,
  `idcliente` int NULL DEFAULT NULL,
  `idconcepto` int NULL DEFAULT NULL,
  `monto` decimal(13, 2) NULL DEFAULT NULL,
  `iddivisa` int NULL DEFAULT NULL,
  `montofijo` int NULL DEFAULT NULL,
  PRIMARY KEY (`idclienteserviciologistico`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 32 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_cobro
-- ----------------------------
DROP TABLE IF EXISTS `t_cobro`;
CREATE TABLE `t_cobro`  (
  `idcobro` int NOT NULL AUTO_INCREMENT,
  `numero` int NULL DEFAULT NULL,
  `fecha` date NULL DEFAULT NULL,
  `fechapago` date NULL DEFAULT NULL,
  `idanticipo` int NULL DEFAULT NULL,
  `idtipocobro` int NULL DEFAULT NULL,
  `idfacturanotadebito` int NULL DEFAULT NULL,
  `monto` decimal(13, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`idcobro`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 19079 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_codigosrecuperarcontrasena
-- ----------------------------
DROP TABLE IF EXISTS `t_codigosrecuperarcontrasena`;
CREATE TABLE `t_codigosrecuperarcontrasena`  (
  `idcodigosrecuperarcontrasena` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `idusuario` int NULL DEFAULT NULL,
  `fecha` datetime NULL DEFAULT NULL,
  `activo` int NULL DEFAULT NULL,
  PRIMARY KEY (`idcodigosrecuperarcontrasena`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 265 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_columnas_moverdividir
-- ----------------------------
DROP TABLE IF EXISTS `t_columnas_moverdividir`;
CREATE TABLE `t_columnas_moverdividir`  (
  `idcolumnas_moverdividir` int NOT NULL AUTO_INCREMENT,
  `field` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `header` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idcolumnas_moverdividir`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 24 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_columnas_pedido
-- ----------------------------
DROP TABLE IF EXISTS `t_columnas_pedido`;
CREATE TABLE `t_columnas_pedido`  (
  `idcolumnas_pedido` int NOT NULL AUTO_INCREMENT,
  `field` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `header` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `type` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idcolumnas_pedido`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_concepto
-- ----------------------------
DROP TABLE IF EXISTS `t_concepto`;
CREATE TABLE `t_concepto`  (
  `idconcepto` int NOT NULL AUTO_INCREMENT,
  `idempresa` int NULL DEFAULT NULL,
  `concepto` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `codigo` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idcuenta` int NULL DEFAULT NULL,
  `tipo` int NULL DEFAULT NULL,
  `idconceptocargo` int NULL DEFAULT NULL,
  `concepto_en` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `flete` int NULL DEFAULT NULL,
  `activo` int NULL DEFAULT NULL,
  `id_OVP` int NULL DEFAULT NULL,
  `id_OVPRef` varchar(11) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idconcepto`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 337 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_consideraciones
-- ----------------------------
DROP TABLE IF EXISTS `t_consideraciones`;
CREATE TABLE `t_consideraciones`  (
  `idconsideraciones` int NOT NULL AUTO_INCREMENT,
  `idempresa` int NULL DEFAULT NULL,
  `consideraciones` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idconsideraciones`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_contemplacion
-- ----------------------------
DROP TABLE IF EXISTS `t_contemplacion`;
CREATE TABLE `t_contemplacion`  (
  `idcontemplacion` int NOT NULL AUTO_INCREMENT,
  `idempresa` int NULL DEFAULT NULL,
  `contemplacion` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idcontemplacion`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 26 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_contrasenamaestra
-- ----------------------------
DROP TABLE IF EXISTS `t_contrasenamaestra`;
CREATE TABLE `t_contrasenamaestra`  (
  `idcontrasenamaestra` int NOT NULL AUTO_INCREMENT,
  `contrasena` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `fechavencimiento` date NULL DEFAULT NULL,
  PRIMARY KEY (`idcontrasenamaestra`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_correonit
-- ----------------------------
DROP TABLE IF EXISTS `t_correonit`;
CREATE TABLE `t_correonit`  (
  `idcorreonit` int NOT NULL AUTO_INCREMENT,
  `idtipodocumento` int NULL DEFAULT NULL,
  `numero` int NULL DEFAULT NULL,
  `correo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idcorreonit`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 216 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_correosembarque
-- ----------------------------
DROP TABLE IF EXISTS `t_correosembarque`;
CREATE TABLE `t_correosembarque`  (
  `idcorreosembarque` int NOT NULL AUTO_INCREMENT,
  `idembarque` int NULL DEFAULT NULL,
  `correo` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idcorreosembarque`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_costo
-- ----------------------------
DROP TABLE IF EXISTS `t_costo`;
CREATE TABLE `t_costo`  (
  `idcosto` int NOT NULL AUTO_INCREMENT,
  `idembarque` int NULL DEFAULT NULL,
  `idconcepto` int NULL DEFAULT NULL,
  `iddivisa` int NULL DEFAULT NULL,
  `monto` decimal(13, 2) NULL DEFAULT NULL,
  `cantidad` decimal(13, 2) NULL DEFAULT NULL,
  `iddestinatario` int NULL DEFAULT NULL,
  `idtipodestinatario` int NULL DEFAULT NULL,
  `notas` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `idfacturanotadebito` int NULL DEFAULT NULL,
  `idtipofacturanotadebito` int NULL DEFAULT NULL,
  `idcargo` int NULL DEFAULT NULL,
  `esagente` int NULL DEFAULT NULL,
  `idordenservicioe` int NULL DEFAULT NULL,
  `ordenservicioeseleccionado` int NULL DEFAULT NULL,
  `factura` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `nota_entrega` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idusuario` int NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`idcosto`) USING BTREE,
  INDEX `idfacturanotadebito`(`idfacturanotadebito` ASC) USING BTREE,
  INDEX `idembarque`(`idembarque` ASC) USING BTREE,
  INDEX `iddivisa`(`iddivisa` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 26056 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_costocotizacion
-- ----------------------------
DROP TABLE IF EXISTS `t_costocotizacion`;
CREATE TABLE `t_costocotizacion`  (
  `idcostocotizacion` int NOT NULL AUTO_INCREMENT,
  `idcotizacion` int NULL DEFAULT NULL,
  `idconcepto` int NULL DEFAULT NULL,
  `cantidad` decimal(13, 2) NULL DEFAULT NULL,
  `montocargo` decimal(13, 2) NULL DEFAULT NULL,
  `montocosto` decimal(13, 2) NULL DEFAULT NULL,
  `iddivisa` int NULL DEFAULT NULL,
  PRIMARY KEY (`idcostocotizacion`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7817 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_cotizacion
-- ----------------------------
DROP TABLE IF EXISTS `t_cotizacion`;
CREATE TABLE `t_cotizacion`  (
  `idcotizacion` int NOT NULL AUTO_INCREMENT,
  `idempresa` int NULL DEFAULT NULL,
  `numero` int NULL DEFAULT NULL,
  `gestion` int NULL DEFAULT NULL,
  `fecha` datetime NULL DEFAULT NULL,
  `idcliente` int NULL DEFAULT NULL,
  `otrocliente` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `nombre` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idtipoembarque` int NULL DEFAULT NULL,
  `importacion_exportacion` int NULL DEFAULT NULL,
  `noidentificacion` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idorigen` int NULL DEFAULT NULL,
  `iddestino` int NULL DEFAULT NULL,
  `idexpedidor` int NULL DEFAULT NULL,
  `idexpedidordireccion` int NULL DEFAULT NULL,
  `idtipoexpedidor` int NULL DEFAULT NULL,
  `descripcioncarga` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `piezas` decimal(13, 2) NULL DEFAULT NULL,
  `peso` decimal(13, 2) NULL DEFAULT NULL,
  `idtipobulto` int NULL DEFAULT NULL,
  `volumen` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idincoterms` int NULL DEFAULT NULL,
  `idciudad` int NULL DEFAULT NULL,
  `idusuario` int NULL DEFAULT NULL,
  `idestadocotizacion` int NULL DEFAULT NULL,
  PRIMARY KEY (`idcotizacion`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 961 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_cotizacionconsideraciones
-- ----------------------------
DROP TABLE IF EXISTS `t_cotizacionconsideraciones`;
CREATE TABLE `t_cotizacionconsideraciones`  (
  `idcotizacionconsideraciones` int NOT NULL AUTO_INCREMENT,
  `idcotizacion` int NULL DEFAULT NULL,
  `idconsideraciones` int NULL DEFAULT NULL,
  PRIMARY KEY (`idcotizacionconsideraciones`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4058 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_cotizacioncontemplacion
-- ----------------------------
DROP TABLE IF EXISTS `t_cotizacioncontemplacion`;
CREATE TABLE `t_cotizacioncontemplacion`  (
  `idcotizacioncontemplacion` int NOT NULL AUTO_INCREMENT,
  `idcotizacion` int NULL DEFAULT NULL,
  `idcontemplacion` int NULL DEFAULT NULL,
  `estado` int NULL DEFAULT NULL,
  PRIMARY KEY (`idcotizacioncontemplacion`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5265 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_cuenta
-- ----------------------------
DROP TABLE IF EXISTS `t_cuenta`;
CREATE TABLE `t_cuenta`  (
  `idcuenta` int NOT NULL AUTO_INCREMENT,
  `idempresa` int NULL DEFAULT NULL,
  `banco` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `cuenta` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `moneda` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idcuenta`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_danios_vehiculos
-- ----------------------------
DROP TABLE IF EXISTS `t_danios_vehiculos`;
CREATE TABLE `t_danios_vehiculos`  (
  `iddanios_vehiculos` int NOT NULL AUTO_INCREMENT,
  `danios_vehiculos` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`iddanios_vehiculos`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_destinocargo
-- ----------------------------
DROP TABLE IF EXISTS `t_destinocargo`;
CREATE TABLE `t_destinocargo`  (
  `iddestinocargo` int NOT NULL AUTO_INCREMENT,
  `destinocargo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`iddestinocargo`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_devolucion
-- ----------------------------
DROP TABLE IF EXISTS `t_devolucion`;
CREATE TABLE `t_devolucion`  (
  `iddevolucion` int NOT NULL AUTO_INCREMENT,
  `numero` int NULL DEFAULT NULL,
  `identidad` int NULL DEFAULT NULL,
  `idtipoentidad` int NULL DEFAULT NULL,
  `fechadevolucion` date NULL DEFAULT NULL,
  `idcuenta` int NULL DEFAULT NULL,
  `numerotransaccion` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `concepto` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `ordende` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`iddevolucion`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 74 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_devoluciondetalle
-- ----------------------------
DROP TABLE IF EXISTS `t_devoluciondetalle`;
CREATE TABLE `t_devoluciondetalle`  (
  `iddevoluciondetalle` int NOT NULL AUTO_INCREMENT,
  `iddevolucion` int NULL DEFAULT NULL,
  `idanticipo` int NULL DEFAULT NULL,
  `monto` decimal(13, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`iddevoluciondetalle`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 148 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_dias_creacion_entrega
-- ----------------------------
DROP TABLE IF EXISTS `t_dias_creacion_entrega`;
CREATE TABLE `t_dias_creacion_entrega`  (
  `iddias_creacion_entrega` int NOT NULL AUTO_INCREMENT,
  `idcliente` int NOT NULL,
  `contrato_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `tienda` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `dia_creacion` int NOT NULL,
  `dia_entrega` int NOT NULL,
  PRIMARY KEY (`iddias_creacion_entrega`, `idcliente`, `contrato_no`, `tienda`, `dia_creacion`, `dia_entrega`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 981 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_diasemana
-- ----------------------------
DROP TABLE IF EXISTS `t_diasemana`;
CREATE TABLE `t_diasemana`  (
  `iddiasemana` int NOT NULL AUTO_INCREMENT,
  `diasemana` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`iddiasemana`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_diasvencimiento
-- ----------------------------
DROP TABLE IF EXISTS `t_diasvencimiento`;
CREATE TABLE `t_diasvencimiento`  (
  `iddiasvencimiento` int NOT NULL AUTO_INCREMENT,
  `diasvencimiento` int NULL DEFAULT NULL,
  PRIMARY KEY (`iddiasvencimiento`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_divisa
-- ----------------------------
DROP TABLE IF EXISTS `t_divisa`;
CREATE TABLE `t_divisa`  (
  `iddivisa` int NOT NULL AUTO_INCREMENT,
  `divisa` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `codigo` varchar(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`iddivisa`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 161 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_divisaordenservicio
-- ----------------------------
DROP TABLE IF EXISTS `t_divisaordenservicio`;
CREATE TABLE `t_divisaordenservicio`  (
  `iddivisaordenservicio` int NOT NULL AUTO_INCREMENT,
  `divisaordenservicio` varchar(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `iddivisa` int NULL DEFAULT NULL,
  PRIMARY KEY (`iddivisaordenservicio`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_doc_errada
-- ----------------------------
DROP TABLE IF EXISTS `t_doc_errada`;
CREATE TABLE `t_doc_errada`  (
  `iddoc_errada` int NOT NULL AUTO_INCREMENT,
  `doc_errada` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`iddoc_errada`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_dosificacion
-- ----------------------------
DROP TABLE IF EXISTS `t_dosificacion`;
CREATE TABLE `t_dosificacion`  (
  `iddosificacion` int NOT NULL AUTO_INCREMENT,
  `idempresa` int NULL DEFAULT NULL,
  `razonsocial` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `nitrazonsocial` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `nroautorizacion` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `fechalimite` date NULL DEFAULT NULL,
  `nrofacturaactual` int NULL DEFAULT NULL,
  `llave` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `actividadeconomica` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `leyenda` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `fechainicio` date NULL DEFAULT NULL,
  `fechafin` date NULL DEFAULT NULL,
  PRIMARY KEY (`iddosificacion`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 31 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_embalaje
-- ----------------------------
DROP TABLE IF EXISTS `t_embalaje`;
CREATE TABLE `t_embalaje`  (
  `idembalaje` int NOT NULL AUTO_INCREMENT,
  `codigoembalaje` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `embalaje` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `divisible` int NULL DEFAULT NULL,
  PRIMARY KEY (`idembalaje`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_embarque
-- ----------------------------
DROP TABLE IF EXISTS `t_embarque`;
CREATE TABLE `t_embarque`  (
  `idembarque` int NOT NULL AUTO_INCREMENT,
  `idempresa` int NULL DEFAULT NULL,
  `idcotizacion` int NULL DEFAULT NULL,
  `idcliente` int NULL DEFAULT NULL,
  `embarque` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `correlativo` int NULL DEFAULT NULL,
  `gestion` int NULL DEFAULT NULL,
  `idtipoembarque` int NULL DEFAULT NULL,
  `importacion_exportacion` int NULL DEFAULT NULL,
  `numeroguia` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idciudad` int NULL DEFAULT NULL,
  `idusuario` int NULL DEFAULT NULL,
  `fecharealizacion` date NULL DEFAULT NULL,
  `valordeclarado` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `descripcioncarga` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `carpetapacena` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `servicio_logistico` int NULL DEFAULT NULL,
  `noidentificacion` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idexpedidor` int NULL DEFAULT NULL,
  `idexpedidordireccion` int NULL DEFAULT NULL,
  `idtipoexpedidor` int NULL DEFAULT NULL,
  `idultimoconsignatario` int NULL DEFAULT NULL,
  `idultimoconsignatariodireccion` int NULL DEFAULT NULL,
  `idtipoultimoconsignatario` int NULL DEFAULT NULL,
  `identidadnotificar` int NULL DEFAULT NULL,
  `identidadnotificardireccion` int NULL DEFAULT NULL,
  `idtipoentidadnotificar` int NULL DEFAULT NULL,
  `idagentecarga` int NULL DEFAULT NULL,
  `idagentecargadireccion` int NULL DEFAULT NULL,
  `idagentedestino` int NULL DEFAULT NULL,
  `idagentedestinodireccion` int NULL DEFAULT NULL,
  `idmediotransporte` int NULL DEFAULT NULL,
  `idtipocarga` int NULL DEFAULT NULL,
  `idtransportista` int NULL DEFAULT NULL,
  `numerovehiculo` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idsalida` int NULL DEFAULT NULL,
  `fechasalida` date NULL DEFAULT NULL,
  `idarribo` int NULL DEFAULT NULL,
  `fechaarribo` date NULL DEFAULT NULL,
  `idorigen` int NULL DEFAULT NULL,
  `iddestino` int NULL DEFAULT NULL,
  `idhorario` int NULL DEFAULT NULL,
  `idtemperatura` int NULL DEFAULT NULL,
  `numero_precinto` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `estibadoresSLG` int NULL DEFAULT NULL,
  `estibadores` int NULL DEFAULT NULL,
  `costo_operador_transporte` decimal(13, 2) NULL DEFAULT NULL,
  `notas` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `peso` decimal(13, 2) NULL DEFAULT NULL,
  `volumen` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `piezas` decimal(13, 2) NULL DEFAULT NULL,
  `idtipobulto` int NULL DEFAULT NULL,
  `nodui` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `fechafinalizacion` date NULL DEFAULT NULL,
  `idincoterms` int NULL DEFAULT NULL,
  `email` varchar(350) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idembarque`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17204 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_empresa
-- ----------------------------
DROP TABLE IF EXISTS `t_empresa`;
CREATE TABLE `t_empresa`  (
  `idempresa` int NOT NULL AUTO_INCREMENT,
  `empresa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `titulo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `operaciones` int NULL DEFAULT NULL,
  `contabilidad` int NULL DEFAULT NULL,
  `almacen` int NULL DEFAULT NULL,
  `asgard_operaciones` int NULL DEFAULT NULL,
  `asgard_almacen` int NULL DEFAULT NULL,
  PRIMARY KEY (`idempresa`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_empresadivisa
-- ----------------------------
DROP TABLE IF EXISTS `t_empresadivisa`;
CREATE TABLE `t_empresadivisa`  (
  `idempresadivisa` int NOT NULL AUTO_INCREMENT,
  `idempresa` int NULL DEFAULT NULL,
  `iddivisa` int NULL DEFAULT NULL,
  PRIMARY KEY (`idempresadivisa`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_estado_etapa
-- ----------------------------
DROP TABLE IF EXISTS `t_estado_etapa`;
CREATE TABLE `t_estado_etapa`  (
  `idestado_etapa` int NOT NULL AUTO_INCREMENT,
  `estado_etapa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idestado_etapa`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_estadofactura
-- ----------------------------
DROP TABLE IF EXISTS `t_estadofactura`;
CREATE TABLE `t_estadofactura`  (
  `idestadofactura` int NOT NULL AUTO_INCREMENT,
  `estadofactura` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `estadonotadebito` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idestadofactura`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_etapa
-- ----------------------------
DROP TABLE IF EXISTS `t_etapa`;
CREATE TABLE `t_etapa`  (
  `idetapa` int NOT NULL AUTO_INCREMENT,
  `etapa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `severity` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idetapa`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_evento
-- ----------------------------
DROP TABLE IF EXISTS `t_evento`;
CREATE TABLE `t_evento`  (
  `idevento` int NOT NULL AUTO_INCREMENT,
  `idembarque` int NULL DEFAULT NULL,
  `idtipoevento` int NULL DEFAULT NULL,
  `fechaplanificada` date NULL DEFAULT NULL,
  `fecha` date NULL DEFAULT NULL,
  `con_observacion` int NULL DEFAULT NULL,
  `ideventodescripcion` int NULL DEFAULT NULL,
  `evento` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idusuario` int NULL DEFAULT NULL,
  `fecharegistro` date NULL DEFAULT NULL,
  `enviado` int NULL DEFAULT NULL,
  PRIMARY KEY (`idevento`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1191 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_eventocotizacion
-- ----------------------------
DROP TABLE IF EXISTS `t_eventocotizacion`;
CREATE TABLE `t_eventocotizacion`  (
  `ideventocotizacion` int NOT NULL AUTO_INCREMENT,
  `idcotizacion` int NULL DEFAULT NULL,
  `idtipoevento` int NULL DEFAULT NULL,
  `fechaplanificada` date NULL DEFAULT NULL,
  `evento` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `idusuario` int NULL DEFAULT NULL,
  `fecharegistro` date NULL DEFAULT NULL,
  PRIMARY KEY (`ideventocotizacion`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_eventodescripcion
-- ----------------------------
DROP TABLE IF EXISTS `t_eventodescripcion`;
CREATE TABLE `t_eventodescripcion`  (
  `ideventodescripcion` int NOT NULL AUTO_INCREMENT,
  `eventodescripcion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`ideventodescripcion`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_factura
-- ----------------------------
DROP TABLE IF EXISTS `t_factura`;
CREATE TABLE `t_factura`  (
  `idfactura` int NOT NULL AUTO_INCREMENT,
  `idembarque` int NULL DEFAULT NULL,
  `iddosificacion` int NULL DEFAULT NULL,
  `nrofactura` int NULL DEFAULT NULL,
  `fecha` date NULL DEFAULT NULL,
  `hora` time NULL DEFAULT NULL,
  `idcobrara` int NULL DEFAULT NULL,
  `idcobraratipo` int NULL DEFAULT NULL,
  `nombre` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idtipodocumento` int NULL DEFAULT NULL,
  `nit` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `codigocontrol` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idestadofactura` int NULL DEFAULT NULL,
  `pallets` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `rotacion` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `outIdOrdenFactura` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `outNumeroFactura` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `outCodigoDeControl` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `outNumeroAutorizacion` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `errorOVPFact` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `cuf` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `urlDocumento` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `NombreDocumentoPDF` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `NombreDocumentoXML` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `DocumentoXML` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `leyenda` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `cufd` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `municipio` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `telefono` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `direccion` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `codigoEmision` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `fecha_anulacion` datetime NULL DEFAULT NULL,
  `idusuarios_anulacion` int NULL DEFAULT NULL,
  `idmotivoanulacion` int NULL DEFAULT NULL,
  `otro_motivoanulacion` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `resplado_anulacion` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idfactura`) USING BTREE,
  UNIQUE INDEX `nrodosificacion`(`nrofactura` ASC, `iddosificacion` ASC) USING BTREE,
  INDEX `fecha`(`fecha` ASC) USING BTREE,
  INDEX `idembarque`(`idembarque` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17285 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_facturapago
-- ----------------------------
DROP TABLE IF EXISTS `t_facturapago`;
CREATE TABLE `t_facturapago`  (
  `idfacturapago` int NOT NULL AUTO_INCREMENT,
  `idtipofacturapago` int NULL DEFAULT NULL,
  `fecha` date NULL DEFAULT NULL,
  `numerofactura` int NULL DEFAULT NULL,
  `gestion` int NULL DEFAULT NULL,
  `idtransportista` int NULL DEFAULT NULL,
  `idpagara` int NULL DEFAULT NULL,
  `idpagaradireccion` int NULL DEFAULT NULL,
  `fechadocumento` date NULL DEFAULT NULL,
  `idpagaratipo` int NULL DEFAULT NULL,
  `idcobrara` int NULL DEFAULT NULL,
  `idcobraratipo` int NULL DEFAULT NULL,
  `idembarque` int NULL DEFAULT NULL,
  `idestadofacturapago` int NULL DEFAULT NULL,
  `tipocambio` decimal(13, 2) NULL DEFAULT NULL,
  `observaciones` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `iddivisa` int NULL DEFAULT NULL,
  `tipoop` int NULL DEFAULT NULL COMMENT '1 para costo, 2 para cargo',
  `outNroAsignacion` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `errorOVP` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `fecha_anulacion` datetime NULL DEFAULT NULL,
  `idusuarios_anulacion` int NULL DEFAULT NULL,
  `idmotivoanulacion` int NULL DEFAULT NULL,
  `otro_motivoanulacion` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `resplado_anulacion` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idfacturapago`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 19210 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_formapago
-- ----------------------------
DROP TABLE IF EXISTS `t_formapago`;
CREATE TABLE `t_formapago`  (
  `idformapago` int NOT NULL AUTO_INCREMENT,
  `formapago` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`idformapago`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_horario
-- ----------------------------
DROP TABLE IF EXISTS `t_horario`;
CREATE TABLE `t_horario`  (
  `idhorario` int NOT NULL AUTO_INCREMENT,
  `horario` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idhorario`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_importacion_exportacion
-- ----------------------------
DROP TABLE IF EXISTS `t_importacion_exportacion`;
CREATE TABLE `t_importacion_exportacion`  (
  `importacion_exportacion` int NOT NULL,
  `importacion_exportacion_texto` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `importacion_exportacion_codigo` varchar(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `parametrizacion` int NULL DEFAULT NULL,
  PRIMARY KEY (`importacion_exportacion`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_incobrable
-- ----------------------------
DROP TABLE IF EXISTS `t_incobrable`;
CREATE TABLE `t_incobrable`  (
  `idincobrable` int NOT NULL AUTO_INCREMENT,
  `numero` int NULL DEFAULT NULL,
  `fecha` date NULL DEFAULT NULL,
  `idtipocobro` int NULL DEFAULT NULL,
  `idfacturanotadebito` int NULL DEFAULT NULL,
  `monto` decimal(13, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`idincobrable`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_incoterms
-- ----------------------------
DROP TABLE IF EXISTS `t_incoterms`;
CREATE TABLE `t_incoterms`  (
  `idincoterms` int NOT NULL AUTO_INCREMENT,
  `incoterms` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idincoterms`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_ingreso
-- ----------------------------
DROP TABLE IF EXISTS `t_ingreso`;
CREATE TABLE `t_ingreso`  (
  `idingreso` int NOT NULL AUTO_INCREMENT,
  `numero` int NULL DEFAULT NULL,
  `gestion` int NULL DEFAULT NULL,
  `idalmacen` int NULL DEFAULT NULL,
  `idcliente` int NULL DEFAULT NULL,
  `es_vehiculo` int NULL DEFAULT NULL,
  `idcliente_destino` int NULL DEFAULT NULL,
  `envio_api` datetime NULL DEFAULT NULL,
  `respuesta_api` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `payload_api` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `response_api` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `fecha` datetime NULL DEFAULT NULL,
  `placa` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `contenedor` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idtipoingreso` int NULL DEFAULT NULL,
  `precinto` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idtipodescarga` int NULL DEFAULT NULL,
  `idtipocamion` int NULL DEFAULT NULL,
  `idtipocontenedor` int NULL DEFAULT NULL,
  `idtipoproducto` int NULL DEFAULT NULL,
  `tipo_camion` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `piezas_manifestadas` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `piezas_recibidas` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `peso_total` decimal(13, 2) NULL DEFAULT NULL,
  `proveedor` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `no_contrato` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `delivery_batch` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `rubro_producto` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `project` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `type` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `invoice` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `dui` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `hoja_ruta` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `cantidad_pallet` int NULL DEFAULT NULL,
  `cantidad_cajas` int NULL DEFAULT NULL,
  `num_res_adm` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `hora_inicio` time NULL DEFAULT NULL,
  `hora_fin` time NULL DEFAULT NULL,
  `cantidad_estibadores` int NULL DEFAULT NULL,
  `nota_adicional` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `fechasistema` date NULL DEFAULT NULL,
  `horasistema` time NULL DEFAULT NULL,
  `idusuario_recibido` int NULL DEFAULT NULL,
  `nombre_entrega` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `ci_entrega` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `empresa_entrega` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `partida` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `fecha_cierre_transito` datetime NULL DEFAULT NULL,
  `fecha_emision_parte` datetime NULL DEFAULT NULL,
  `idsalida_origen` int NULL DEFAULT NULL,
  PRIMARY KEY (`idingreso`) USING BTREE,
  INDEX `idalmacen`(`idalmacen` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3685 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_ingresodetalle
-- ----------------------------
DROP TABLE IF EXISTS `t_ingresodetalle`;
CREATE TABLE `t_ingresodetalle`  (
  `idingresodetalle` int NOT NULL AUTO_INCREMENT,
  `idingreso` int NULL DEFAULT NULL,
  `idingresodetallepadre` int NULL DEFAULT NULL,
  `codigo` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `serie` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `descripcion` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `centro_distribucion` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `sustanciascontroladas` int NULL DEFAULT NULL,
  `categoria` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `openbox` int NULL DEFAULT NULL,
  `cantidad` decimal(13, 2) NULL DEFAULT NULL,
  `idembalaje` int NULL DEFAULT NULL,
  `metros` decimal(13, 2) NULL DEFAULT NULL,
  `largo` decimal(13, 2) NULL DEFAULT NULL,
  `ancho` decimal(13, 2) NULL DEFAULT NULL,
  `alto` decimal(13, 2) NULL DEFAULT NULL,
  `lote` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `bultos` decimal(13, 3) NULL DEFAULT NULL,
  `costo_un` decimal(13, 2) NULL DEFAULT NULL,
  `no_conf` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `cantidad_no_conf` decimal(13, 2) NULL DEFAULT NULL,
  `idno_conf` int NULL DEFAULT NULL,
  `idclasificacion` int NULL DEFAULT NULL,
  `idmerma` int NULL DEFAULT NULL,
  `fechaproduccion` date NULL DEFAULT NULL,
  `fechavencimiento` date NULL DEFAULT NULL,
  `relacion_caja` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `volumen` decimal(13, 2) NULL DEFAULT NULL,
  `area` decimal(13, 2) NULL DEFAULT NULL,
  `peso` decimal(13, 2) NULL DEFAULT NULL,
  `pallet` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `temperatura` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `iddoc_errada` int NULL DEFAULT NULL,
  `observaciones` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `dividido` datetime NULL DEFAULT NULL,
  `cerrado` int NULL DEFAULT NULL,
  `modelo` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `marca` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `color` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `partida_especifica` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `invoice` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `no_dim` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `fecha_pase_salida` datetime NULL DEFAULT NULL,
  `kilometraje` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `tiene_danios` int NULL DEFAULT NULL,
  `danios` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `tiene_faltante` int NULL DEFAULT NULL,
  `faltante` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `embarque_id` int NULL DEFAULT NULL,
  `created_by` int NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `edited_by` int NULL DEFAULT NULL,
  `edited_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`idingresodetalle`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 71550 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_ingresodetalle_accesorios
-- ----------------------------
DROP TABLE IF EXISTS `t_ingresodetalle_accesorios`;
CREATE TABLE `t_ingresodetalle_accesorios`  (
  `idingresodetalle_accesorios` int NOT NULL AUTO_INCREMENT,
  `idingresodetalle` int NULL DEFAULT NULL,
  `idaccesorios_vehiculos` int NULL DEFAULT NULL,
  `cantidad` int NULL DEFAULT NULL,
  `observaciones` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `texto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`idingresodetalle_accesorios`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1104 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_ingresodetallecliente
-- ----------------------------
DROP TABLE IF EXISTS `t_ingresodetallecliente`;
CREATE TABLE `t_ingresodetallecliente`  (
  `idingresodetallecliente` int NOT NULL AUTO_INCREMENT,
  `idcliente` int NULL DEFAULT NULL,
  `idalmacen` int NULL DEFAULT NULL,
  `codigo` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `descripcion` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idingresodetallecliente`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5302 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_inter_company
-- ----------------------------
DROP TABLE IF EXISTS `t_inter_company`;
CREATE TABLE `t_inter_company`  (
  `idinter_company` int NOT NULL AUTO_INCREMENT,
  `idcliente_1` int NULL DEFAULT NULL,
  `almacen_1` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `idcliente_2` int NULL DEFAULT NULL,
  `almacen_2` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idinter_company`) USING BTREE,
  INDEX `idcliente_1`(`idcliente_1` ASC) USING BTREE,
  INDEX `idcliente_2`(`idcliente_2` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_inter_company_data
-- ----------------------------
DROP TABLE IF EXISTS `t_inter_company_data`;
CREATE TABLE `t_inter_company_data`  (
  `idinter_company_data` int NOT NULL AUTO_INCREMENT,
  `idinter_company_pasos` int NULL DEFAULT NULL,
  `campo` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `tipo_data` int NULL DEFAULT NULL,
  `dato_fijo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `dato_variable` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idinter_company_data`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_inter_company_pasos
-- ----------------------------
DROP TABLE IF EXISTS `t_inter_company_pasos`;
CREATE TABLE `t_inter_company_pasos`  (
  `idinter_company_pasos` int NOT NULL AUTO_INCREMENT,
  `idinter_company` int NULL DEFAULT NULL,
  `paso` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `template` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idinter_company_pasos`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_inventariofisico
-- ----------------------------
DROP TABLE IF EXISTS `t_inventariofisico`;
CREATE TABLE `t_inventariofisico`  (
  `idinventariofisico` int NOT NULL AUTO_INCREMENT,
  `idcliente` int NULL DEFAULT NULL,
  `idalmacen` int NULL DEFAULT NULL,
  `es_vehiculo` int NULL DEFAULT NULL,
  `idstatus` int NULL DEFAULT NULL,
  `categoria` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `fecha` date NULL DEFAULT NULL,
  `idasignado` int NULL DEFAULT NULL,
  `idapoyo` int NULL DEFAULT NULL,
  `fecha_inicio` datetime NULL DEFAULT NULL,
  `fecha_fin` datetime NULL DEFAULT NULL,
  `fecha_inicio_conteo` datetime NULL DEFAULT NULL,
  `fecha_fin_conteo` datetime NULL DEFAULT NULL,
  `fecha_fin_inventario` datetime NULL DEFAULT NULL,
  `idusuario_fin_inventario` int NULL DEFAULT NULL,
  `idusuario_conteo` int NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`idinventariofisico`) USING BTREE,
  INDEX `idcliente`(`idcliente` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1542 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_inventariofisicoconteo
-- ----------------------------
DROP TABLE IF EXISTS `t_inventariofisicoconteo`;
CREATE TABLE `t_inventariofisicoconteo`  (
  `idinventariofisicoconteo` int NOT NULL AUTO_INCREMENT,
  `idinventariofisico` int NULL DEFAULT NULL,
  `serie` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `codigo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `categoria` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `descripcion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `lote` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `idusuario` int NULL DEFAULT NULL,
  PRIMARY KEY (`idinventariofisicoconteo`) USING BTREE,
  INDEX `idinventariofisico`(`idinventariofisico` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 44491 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_inventariofisicoconteoimagen
-- ----------------------------
DROP TABLE IF EXISTS `t_inventariofisicoconteoimagen`;
CREATE TABLE `t_inventariofisicoconteoimagen`  (
  `idinventariofisicoconteoimagen` int NOT NULL AUTO_INCREMENT,
  `idinventariofisicoconteo` int NULL DEFAULT NULL,
  `imagen` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idinventariofisicoconteoimagen`) USING BTREE,
  INDEX `idinventariofisicoconteo`(`idinventariofisicoconteo` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 61148 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_inventariofisicodetalle
-- ----------------------------
DROP TABLE IF EXISTS `t_inventariofisicodetalle`;
CREATE TABLE `t_inventariofisicodetalle`  (
  `idinventariofisicodetalle` int NOT NULL AUTO_INCREMENT,
  `idinventariofisico` int NULL DEFAULT NULL,
  `codigo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `serie` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `descripcion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `ubicacion` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `categoria` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `cantidad` decimal(13, 2) NULL DEFAULT NULL,
  `cantidad_real` decimal(13, 2) NULL DEFAULT NULL,
  `embalaje` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `lote` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `fecha_vencimiento` date NULL DEFAULT NULL,
  `observaciones` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `idinventariofisicoetiqueta` int NULL DEFAULT NULL,
  PRIMARY KEY (`idinventariofisicodetalle`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 59970 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_inventariofisicodetallearchivo
-- ----------------------------
DROP TABLE IF EXISTS `t_inventariofisicodetallearchivo`;
CREATE TABLE `t_inventariofisicodetallearchivo`  (
  `idinventariofisicodetallearchivo` int NOT NULL AUTO_INCREMENT,
  `idinventariofisicodetalle` int NULL DEFAULT NULL,
  `inventariofisicodetallearchivo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `nombre_fisico` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idinventariofisicodetallearchivo`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2529 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_inventariofisicoetiqueta
-- ----------------------------
DROP TABLE IF EXISTS `t_inventariofisicoetiqueta`;
CREATE TABLE `t_inventariofisicoetiqueta`  (
  `idinventariofisicoetiqueta` int NOT NULL AUTO_INCREMENT,
  `inventariofisicoetiqueta` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idinventariofisicoetiqueta`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_invoice
-- ----------------------------
DROP TABLE IF EXISTS `t_invoice`;
CREATE TABLE `t_invoice`  (
  `idinvoice` int NOT NULL AUTO_INCREMENT,
  `fecha` date NULL DEFAULT NULL,
  `numero` int NULL DEFAULT NULL,
  `gestion` int NULL DEFAULT NULL,
  `idembarque` int NULL DEFAULT NULL,
  `idagentecarga` int NULL DEFAULT NULL,
  `idagentecargadireccion` int NULL DEFAULT NULL,
  `idestadoinvoice` int NULL DEFAULT NULL,
  PRIMARY KEY (`idinvoice`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 839 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_mediotransporte
-- ----------------------------
DROP TABLE IF EXISTS `t_mediotransporte`;
CREATE TABLE `t_mediotransporte`  (
  `idmediotransporte` int NOT NULL AUTO_INCREMENT,
  `mediotransporte` varchar(70) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `parametrizacion` int NULL DEFAULT NULL,
  PRIMARY KEY (`idmediotransporte`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 22 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_menu
-- ----------------------------
DROP TABLE IF EXISTS `t_menu`;
CREATE TABLE `t_menu`  (
  `menu` int NOT NULL,
  `menuname` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `icono` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`menu`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_merma
-- ----------------------------
DROP TABLE IF EXISTS `t_merma`;
CREATE TABLE `t_merma`  (
  `idmerma` int NOT NULL AUTO_INCREMENT,
  `merma` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idmerma`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_metodopago
-- ----------------------------
DROP TABLE IF EXISTS `t_metodopago`;
CREATE TABLE `t_metodopago`  (
  `idmetodopago` int NOT NULL AUTO_INCREMENT,
  `metodopago` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idmetodopago`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_modulo
-- ----------------------------
DROP TABLE IF EXISTS `t_modulo`;
CREATE TABLE `t_modulo`  (
  `idmodulo` int NOT NULL AUTO_INCREMENT,
  `modulo` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `parent_id` int NULL DEFAULT NULL,
  `operaciones` int NULL DEFAULT NULL,
  `contabilidad` int NULL DEFAULT NULL,
  `almacen` int NULL DEFAULT NULL,
  PRIMARY KEY (`idmodulo`) USING BTREE,
  INDEX `parent_id`(`parent_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 110 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_motivoanulacion
-- ----------------------------
DROP TABLE IF EXISTS `t_motivoanulacion`;
CREATE TABLE `t_motivoanulacion`  (
  `idmotivoanulacion` int NOT NULL AUTO_INCREMENT,
  `motivoanulacion` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idmotivoanulacion`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_movilizador
-- ----------------------------
DROP TABLE IF EXISTS `t_movilizador`;
CREATE TABLE `t_movilizador`  (
  `idmovilizador` int NOT NULL AUTO_INCREMENT,
  `idcliente` int NULL DEFAULT NULL,
  `movilizador` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `edited_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`idmovilizador`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 26 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_no_conf
-- ----------------------------
DROP TABLE IF EXISTS `t_no_conf`;
CREATE TABLE `t_no_conf`  (
  `idno_conf` int NOT NULL AUTO_INCREMENT,
  `no_conf` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `no_considerar` int NULL DEFAULT NULL,
  PRIMARY KEY (`idno_conf`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 28 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_notadebito
-- ----------------------------
DROP TABLE IF EXISTS `t_notadebito`;
CREATE TABLE `t_notadebito`  (
  `idnotadebito` int NOT NULL AUTO_INCREMENT,
  `idembarque` int NULL DEFAULT NULL,
  `fecha` date NULL DEFAULT NULL,
  `nronotadebito` int NULL DEFAULT NULL,
  `gestion` int NULL DEFAULT NULL,
  `idcobrara` int NULL DEFAULT NULL,
  `idcobraratipo` int NULL DEFAULT NULL,
  `idcuenta` int NULL DEFAULT NULL,
  `idestadonotadebito` int NULL DEFAULT NULL,
  `observaciones` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `iddivisa` int NULL DEFAULT NULL,
  `fecha_anulacion` datetime NULL DEFAULT NULL,
  `idusuarios_anulacion` int NULL DEFAULT NULL,
  `idmotivoanulacion` int NULL DEFAULT NULL,
  `otro_motivoanulacion` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `resplado_anulacion` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idnotadebito`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1989 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_ordenservicioe
-- ----------------------------
DROP TABLE IF EXISTS `t_ordenservicioe`;
CREATE TABLE `t_ordenservicioe`  (
  `idordenservicioe` int NOT NULL AUTO_INCREMENT,
  `fecha` date NULL DEFAULT NULL,
  `numero` int NULL DEFAULT NULL,
  `gestion` int NULL DEFAULT NULL,
  `idembarque` int NULL DEFAULT NULL,
  `idsolicitadopor` int NULL DEFAULT NULL,
  `iddivisaordenservicio` int NULL DEFAULT NULL,
  `tipocambio` decimal(13, 2) NULL DEFAULT NULL,
  `creditnot` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idestado` int NULL DEFAULT NULL,
  `idusuario` int NULL DEFAULT NULL,
  PRIMARY KEY (`idordenservicioe`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 302 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_ordenservicioi
-- ----------------------------
DROP TABLE IF EXISTS `t_ordenservicioi`;
CREATE TABLE `t_ordenservicioi`  (
  `idordenservicioi` int NOT NULL AUTO_INCREMENT,
  `fecha` date NULL DEFAULT NULL,
  `numero` int NULL DEFAULT NULL,
  `gestion` int NULL DEFAULT NULL,
  `idembarque` int NULL DEFAULT NULL,
  `idsolicitadopor` int NULL DEFAULT NULL,
  `iddivisaordenservicio` int NULL DEFAULT NULL,
  `tipocambio` decimal(13, 2) NULL DEFAULT NULL,
  `creditnot` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idestado` int NULL DEFAULT NULL,
  `idusuario` int NULL DEFAULT NULL,
  PRIMARY KEY (`idordenservicioi`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 91 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_pago
-- ----------------------------
DROP TABLE IF EXISTS `t_pago`;
CREATE TABLE `t_pago`  (
  `idpago` int NOT NULL AUTO_INCREMENT,
  `nropago` int NULL DEFAULT NULL,
  `fecha` date NULL DEFAULT NULL,
  `idcuenta` int NULL DEFAULT NULL,
  `idmetodopago` int NULL DEFAULT NULL,
  `nrotransaccion` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `alaordende` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `concepto` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `pagoa` int NULL DEFAULT NULL,
  `idusuario` int NULL DEFAULT NULL,
  PRIMARY KEY (`idpago`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 847 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_pagodetalle
-- ----------------------------
DROP TABLE IF EXISTS `t_pagodetalle`;
CREATE TABLE `t_pagodetalle`  (
  `idpagodetalle` int NOT NULL AUTO_INCREMENT,
  `idpago` int NULL DEFAULT NULL,
  `idfacturapago` int NULL DEFAULT NULL,
  `monto` decimal(13, 2) NULL DEFAULT NULL,
  `iddivisa` int NULL DEFAULT NULL,
  PRIMARY KEY (`idpagodetalle`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10847 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_pagotmp
-- ----------------------------
DROP TABLE IF EXISTS `t_pagotmp`;
CREATE TABLE `t_pagotmp`  (
  `idpagotmp` int NOT NULL AUTO_INCREMENT,
  `idusuario` int NULL DEFAULT NULL,
  `idfacturapago` int NULL DEFAULT NULL,
  `monto` decimal(13, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`idpagotmp`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 15907 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_pais
-- ----------------------------
DROP TABLE IF EXISTS `t_pais`;
CREATE TABLE `t_pais`  (
  `idpais` varchar(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `pais` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idpais`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_pedido
-- ----------------------------
DROP TABLE IF EXISTS `t_pedido`;
CREATE TABLE `t_pedido`  (
  `idpedido` int NOT NULL AUTO_INCREMENT,
  `idalmacen` int NULL DEFAULT NULL,
  `numero` int NULL DEFAULT NULL,
  `gestion` int NULL DEFAULT NULL,
  `idcliente` int NULL DEFAULT NULL,
  `fecha` datetime NULL DEFAULT NULL,
  `es_no_conf` int NULL DEFAULT NULL,
  `no_pedido` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `fecha_entrega` date NULL DEFAULT NULL,
  `idusuario` int NULL DEFAULT NULL,
  `tipo_pedido` int NULL DEFAULT NULL,
  `idpedido_padre` int NULL DEFAULT NULL,
  `rubro` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idusuario_revisado` int NULL DEFAULT NULL,
  `nota_adicional` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  PRIMARY KEY (`idpedido`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9385 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_pedidodetalle
-- ----------------------------
DROP TABLE IF EXISTS `t_pedidodetalle`;
CREATE TABLE `t_pedidodetalle`  (
  `idpedidodetalle` int NOT NULL AUTO_INCREMENT,
  `idpedido` int NULL DEFAULT NULL,
  `codigo` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `serie` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `descripcion` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `unidadmedida` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `lote` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idpedidodetalle_aux` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idpedidodetalle`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 139852 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_pedidodisponibilidad
-- ----------------------------
DROP TABLE IF EXISTS `t_pedidodisponibilidad`;
CREATE TABLE `t_pedidodisponibilidad`  (
  `idpedidodisponibilidad` int NOT NULL AUTO_INCREMENT,
  `idpedidodetalle` int NULL DEFAULT NULL,
  `idingresodetalle` int NULL DEFAULT NULL,
  `cantidad` decimal(13, 2) NULL DEFAULT NULL,
  `ubicacionalmacen` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `ppt` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `fechavencimiento` date NULL DEFAULT NULL,
  `diasavencer` int NULL DEFAULT NULL,
  `lote` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `fechaingreso` date NULL DEFAULT NULL,
  PRIMARY KEY (`idpedidodisponibilidad`) USING BTREE,
  INDEX `ubicacionalmacen`(`ubicacionalmacen` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 102603 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_pedidolog
-- ----------------------------
DROP TABLE IF EXISTS `t_pedidolog`;
CREATE TABLE `t_pedidolog`  (
  `idpedidolog` int NOT NULL AUTO_INCREMENT,
  `fecha` datetime NULL DEFAULT NULL,
  `idusuario` int NULL DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `cuerpo` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `respuesta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  PRIMARY KEY (`idpedidolog`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10761 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_pedidopreparacion
-- ----------------------------
DROP TABLE IF EXISTS `t_pedidopreparacion`;
CREATE TABLE `t_pedidopreparacion`  (
  `idpedidopreparacion` int NOT NULL AUTO_INCREMENT,
  `idpedido` int NULL DEFAULT NULL,
  `idpreparador` int NULL DEFAULT NULL,
  `sector` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `hora_inicio` time NULL DEFAULT NULL,
  `hora_fin` time NULL DEFAULT NULL,
  `demora` decimal(13, 1) NULL DEFAULT NULL,
  `conforme` int NULL DEFAULT NULL,
  `conforme2` int NULL DEFAULT NULL,
  `conforme3` int NULL DEFAULT NULL,
  `notas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idpedidopreparacion`) USING BTREE,
  INDEX `idpedido`(`idpedido` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 404 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_pedidopreparacionsector
-- ----------------------------
DROP TABLE IF EXISTS `t_pedidopreparacionsector`;
CREATE TABLE `t_pedidopreparacionsector`  (
  `idpedidopreparacionsector` int NOT NULL AUTO_INCREMENT,
  `idpedidopreparacion` int NULL DEFAULT NULL,
  `sector` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idpedidopreparacionsector`) USING BTREE,
  INDEX `idpedidopreparacion`(`idpedidopreparacion` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1297 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_pedidotienda
-- ----------------------------
DROP TABLE IF EXISTS `t_pedidotienda`;
CREATE TABLE `t_pedidotienda`  (
  `idpedidotienda` int NOT NULL AUTO_INCREMENT,
  `idpedido` int NULL DEFAULT NULL,
  `tienda` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idpedidotienda_aux` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `no_pedido` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idpedidotienda`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 63839 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_pediodetalletienda
-- ----------------------------
DROP TABLE IF EXISTS `t_pediodetalletienda`;
CREATE TABLE `t_pediodetalletienda`  (
  `idpediodetalletienda` int NOT NULL AUTO_INCREMENT,
  `idpedidodetalle` int NULL DEFAULT NULL,
  `idpedidotienda` int NULL DEFAULT NULL,
  `cantidad` decimal(13, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`idpediodetalletienda`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 328640 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_planilla
-- ----------------------------
DROP TABLE IF EXISTS `t_planilla`;
CREATE TABLE `t_planilla`  (
  `idplanilla` int NOT NULL AUTO_INCREMENT,
  `numero` int NULL DEFAULT NULL,
  `fecha` date NULL DEFAULT NULL,
  `idembarque` int NULL DEFAULT NULL,
  `textoadicional` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `idestadoplanilla` int NULL DEFAULT NULL,
  `pacenainvoice` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `slginvoice` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `alloginvoice` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idplanilla`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 24 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_preciotimbradoproducto
-- ----------------------------
DROP TABLE IF EXISTS `t_preciotimbradoproducto`;
CREATE TABLE `t_preciotimbradoproducto`  (
  `idpreciotimbradoproducto` int NOT NULL AUTO_INCREMENT,
  `idbaseproductos` int NULL DEFAULT NULL,
  `idtimbradoturno` int NULL DEFAULT NULL,
  `precio` decimal(13, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`idpreciotimbradoproducto`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_prestador
-- ----------------------------
DROP TABLE IF EXISTS `t_prestador`;
CREATE TABLE `t_prestador`  (
  `idprestador` int NOT NULL AUTO_INCREMENT,
  `idempresa` int NULL DEFAULT NULL,
  `prestador` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `numeroidentificacion` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `telefono` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `fax` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `email` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `numerocuenta` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `nombrecontacto` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `plazo` int NULL DEFAULT NULL,
  `id_OVPProv` varchar(11) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idprestador`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 81 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_prestadordireccion
-- ----------------------------
DROP TABLE IF EXISTS `t_prestadordireccion`;
CREATE TABLE `t_prestadordireccion`  (
  `idprestadordireccion` int NOT NULL AUTO_INCREMENT,
  `idprestador` int NULL DEFAULT NULL,
  `direccion` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `ciudad` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idpais` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `nombrecontacto` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `email` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idprestadordireccion`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 91 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_prestadorservicio
-- ----------------------------
DROP TABLE IF EXISTS `t_prestadorservicio`;
CREATE TABLE `t_prestadorservicio`  (
  `idprestadorservicio` int NOT NULL AUTO_INCREMENT,
  `idprestador` int NULL DEFAULT NULL,
  `servicio` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `tarifa` decimal(13, 2) NULL DEFAULT NULL,
  `observaciones` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idprestadorservicio`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_producto
-- ----------------------------
DROP TABLE IF EXISTS `t_producto`;
CREATE TABLE `t_producto`  (
  `idproducto` int NOT NULL AUTO_INCREMENT,
  `idembarque` int NULL DEFAULT NULL,
  `idembalaje` int NULL DEFAULT NULL,
  `descripcion` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `piezas` int NULL DEFAULT NULL,
  `peso` decimal(13, 2) NULL DEFAULT NULL,
  `volumen` decimal(13, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`idproducto`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_productomedida
-- ----------------------------
DROP TABLE IF EXISTS `t_productomedida`;
CREATE TABLE `t_productomedida`  (
  `idproductomedida` int NOT NULL AUTO_INCREMENT,
  `idproducto` int NULL DEFAULT NULL,
  `cantidad` int NULL DEFAULT NULL,
  `largo` decimal(13, 2) NULL DEFAULT NULL,
  `alto` decimal(13, 2) NULL DEFAULT NULL,
  `ancho` decimal(13, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`idproductomedida`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_proveedor
-- ----------------------------
DROP TABLE IF EXISTS `t_proveedor`;
CREATE TABLE `t_proveedor`  (
  `idproveedor` int NOT NULL AUTO_INCREMENT,
  `idempresa` int NULL DEFAULT NULL,
  `proveedor` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `numeroidentificacion` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `telefono` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `fax` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `email` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `numerocuenta` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `nombrecontacto` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `plazo` int NULL DEFAULT NULL,
  `id_OVPProv` varchar(11) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idtipodocumento` int NULL DEFAULT NULL,
  `numerofacturacion` int NULL DEFAULT NULL,
  `razonsocial` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `id_asgard` int NULL DEFAULT NULL,
  PRIMARY KEY (`idproveedor`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 318 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_proveedorcorreofacturacion
-- ----------------------------
DROP TABLE IF EXISTS `t_proveedorcorreofacturacion`;
CREATE TABLE `t_proveedorcorreofacturacion`  (
  `idproveedorcorreofacturacion` int NOT NULL AUTO_INCREMENT,
  `idproveedor` int NULL DEFAULT NULL,
  `correo` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idproveedorcorreofacturacion`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_proveedordireccion
-- ----------------------------
DROP TABLE IF EXISTS `t_proveedordireccion`;
CREATE TABLE `t_proveedordireccion`  (
  `idproveedordireccion` int NOT NULL AUTO_INCREMENT,
  `idproveedor` int NULL DEFAULT NULL,
  `direccion` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `ciudad` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idpais` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `nombrecontacto` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `email` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idproveedordireccion`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 303 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_referencia_salida
-- ----------------------------
DROP TABLE IF EXISTS `t_referencia_salida`;
CREATE TABLE `t_referencia_salida`  (
  `idreferencia_salida` int NOT NULL AUTO_INCREMENT,
  `idcliente` int NULL DEFAULT NULL,
  `contrato_no` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `proyecto_no` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `solicitado_por` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `autorizado_por` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `rubro_producto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `ciudad` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `direccion_entrega` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `transporte` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `placa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `hora_inicio_a` time NULL DEFAULT NULL,
  `hora_fin_a` time NULL DEFAULT NULL,
  `hora_inicio_b` time NULL DEFAULT NULL,
  `hora_fin_b` time NULL DEFAULT NULL,
  `empresa_recibido` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `tipo_pedido` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idreferencia_salida`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 92 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_referenciacolor
-- ----------------------------
DROP TABLE IF EXISTS `t_referenciacolor`;
CREATE TABLE `t_referenciacolor`  (
  `idreferenciacolor` int NOT NULL AUTO_INCREMENT,
  `idcliente` int NULL DEFAULT NULL,
  `color` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `referenciacolor` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idreferenciacolor`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_reporte
-- ----------------------------
DROP TABLE IF EXISTS `t_reporte`;
CREATE TABLE `t_reporte`  (
  `idreporte` int NOT NULL AUTO_INCREMENT,
  `reporte` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `menu` int NULL DEFAULT NULL,
  `enlace` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `icono` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `activo` int NULL DEFAULT NULL,
  PRIMARY KEY (`idreporte`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_rubro
-- ----------------------------
DROP TABLE IF EXISTS `t_rubro`;
CREATE TABLE `t_rubro`  (
  `idrubro` int NOT NULL AUTO_INCREMENT,
  `rubro` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idrubro`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_salida
-- ----------------------------
DROP TABLE IF EXISTS `t_salida`;
CREATE TABLE `t_salida`  (
  `idsalida` int NOT NULL AUTO_INCREMENT,
  `numero` int NULL DEFAULT NULL,
  `gestion` int NULL DEFAULT NULL,
  `idalmacen` int NULL DEFAULT NULL,
  `idcliente` int NULL DEFAULT NULL,
  `es_vehiculo` int NULL DEFAULT NULL,
  `es_no_conf` int NULL DEFAULT NULL,
  `movimiento` int NULL DEFAULT NULL,
  `idcliente_destino` int NULL DEFAULT NULL,
  `idpedido` int NULL DEFAULT NULL,
  `fecha` datetime NULL DEFAULT NULL,
  `inter_company` int NULL DEFAULT NULL,
  `solicitado_por` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `autorizado_por` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idmovilizador` int NULL DEFAULT NULL,
  `delivery_note` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `proyecto_no` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `contrato_no` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `rubro_producto` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `direccion_entrega` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idalmacen_destino` int NULL DEFAULT NULL,
  `transporte` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `placa` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `ciudad` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `num_res_adm` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `cantidad_cajas` int NULL DEFAULT NULL,
  `cantidad_pallet` int NULL DEFAULT NULL,
  `hoja_ruta` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `autorizacion_compra` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `tipo_pedido` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `almacen_destino` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `pedido_consolidado` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idpedido_consolidado` int NULL DEFAULT NULL,
  `partida` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `hora_inicio_a` time NULL DEFAULT NULL,
  `hora_fin_a` time NULL DEFAULT NULL,
  `cantidad_estibadores_a` int NULL DEFAULT NULL,
  `hora_inicio_cable` time NULL DEFAULT NULL,
  `hora_fin_cable` time NULL DEFAULT NULL,
  `hora_inicio_b` time NULL DEFAULT NULL,
  `hora_fin_b` time NULL DEFAULT NULL,
  `cantidad_estibadores_b` int NULL DEFAULT NULL,
  `nota_adicional` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `idusuario_entrega` int NULL DEFAULT NULL,
  `nombre_recibido` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `ci_recibido` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `empresa_recibido` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `fecha_recibido` date NULL DEFAULT NULL,
  `entrega_a_tiempo` int NULL DEFAULT NULL,
  `entrega_completa_conforme` int NULL DEFAULT NULL,
  `finalizado` int NULL DEFAULT NULL,
  PRIMARY KEY (`idsalida`) USING BTREE,
  INDEX `fecha`(`fecha` ASC) USING BTREE,
  INDEX `idalmacen`(`idalmacen` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 20240 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_salidadetalle
-- ----------------------------
DROP TABLE IF EXISTS `t_salidadetalle`;
CREATE TABLE `t_salidadetalle`  (
  `idsalidadetalle` int NOT NULL AUTO_INCREMENT,
  `idsalida` int NULL DEFAULT NULL,
  `idingresodetalle` int NULL DEFAULT NULL,
  `idsectoringresodetalle` int NULL DEFAULT NULL,
  `cantidad` decimal(13, 2) NULL DEFAULT NULL,
  `metros` decimal(13, 2) NULL DEFAULT NULL,
  `area` decimal(13, 2) NULL DEFAULT NULL,
  `peso` decimal(13, 2) NULL DEFAULT NULL,
  `bultos` decimal(13, 2) NULL DEFAULT NULL,
  `cantidad_no_conf` decimal(13, 2) NULL DEFAULT NULL,
  `temperatura` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `observaciones` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `kilometraje` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `tiene_danios` int NULL DEFAULT NULL,
  `danios` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `tiene_faltante` int NULL DEFAULT NULL,
  `faltante` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `kilometraje_pendiente` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `tiene_danios_pendiente` int NULL DEFAULT NULL,
  `danios_pendiente` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `tiene_faltante_pendiente` int NULL DEFAULT NULL,
  `faltante_pendiente` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  PRIMARY KEY (`idsalidadetalle`) USING BTREE,
  INDEX `idsalida`(`idsalida` ASC) USING BTREE,
  INDEX `idingresodetalle`(`idingresodetalle` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 189920 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_salidadetalle_accesorios
-- ----------------------------
DROP TABLE IF EXISTS `t_salidadetalle_accesorios`;
CREATE TABLE `t_salidadetalle_accesorios`  (
  `idsalidadetalle_accesorios` int NOT NULL AUTO_INCREMENT,
  `idsalidadetalle` int NULL DEFAULT NULL,
  `idaccesorios_vehiculos` int NULL DEFAULT NULL,
  `cantidad` int NULL DEFAULT NULL,
  `texto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`idsalidadetalle_accesorios`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_salidadetalle_accesorios_pendiente
-- ----------------------------
DROP TABLE IF EXISTS `t_salidadetalle_accesorios_pendiente`;
CREATE TABLE `t_salidadetalle_accesorios_pendiente`  (
  `idsalidadetalle_accesorios_pendiente` int NOT NULL AUTO_INCREMENT,
  `idsalidadetalle` int NULL DEFAULT NULL,
  `idaccesorios_vehiculos` int NULL DEFAULT NULL,
  `cantidad` int NULL DEFAULT NULL,
  `texto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`idsalidadetalle_accesorios_pendiente`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_salidadetalleimagen
-- ----------------------------
DROP TABLE IF EXISTS `t_salidadetalleimagen`;
CREATE TABLE `t_salidadetalleimagen`  (
  `idsalidadetalleimagen` int NOT NULL AUTO_INCREMENT,
  `idsalidadetalle` int NULL DEFAULT NULL,
  `imagen` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idsalidadetalleimagen`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_salidadetalleimagen_pendiente
-- ----------------------------
DROP TABLE IF EXISTS `t_salidadetalleimagen_pendiente`;
CREATE TABLE `t_salidadetalleimagen_pendiente`  (
  `idsalidadetalleimagen_pendiente` int NOT NULL AUTO_INCREMENT,
  `idsalidadetalle` int NULL DEFAULT NULL,
  `imagen` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idsalidadetalleimagen_pendiente`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_salidadetalleprevio
-- ----------------------------
DROP TABLE IF EXISTS `t_salidadetalleprevio`;
CREATE TABLE `t_salidadetalleprevio`  (
  `idsalidadetalleprevio` int NOT NULL AUTO_INCREMENT,
  `idsalida` int NULL DEFAULT NULL,
  `idsectoringresodetalle` int NULL DEFAULT NULL,
  `codigo` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `cantidad` decimal(13, 2) NULL DEFAULT NULL,
  `metros` decimal(13, 2) NULL DEFAULT NULL,
  `area` decimal(13, 2) NULL DEFAULT NULL,
  `peso` decimal(13, 2) NULL DEFAULT NULL,
  `observaciones` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  PRIMARY KEY (`idsalidadetalleprevio`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17263 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_sector
-- ----------------------------
DROP TABLE IF EXISTS `t_sector`;
CREATE TABLE `t_sector`  (
  `idsector` int NOT NULL AUTO_INCREMENT,
  `nave` int NULL DEFAULT NULL,
  `sector` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idalmacen` int NULL DEFAULT NULL,
  `idtiposector` int NULL DEFAULT NULL,
  `fila` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `columna` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `largo` decimal(13, 2) NULL DEFAULT NULL,
  `ancho` decimal(13, 2) NULL DEFAULT NULL,
  `alto` decimal(13, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`idsector`) USING BTREE,
  INDEX `idalmacen`(`idalmacen` ASC) USING BTREE,
  INDEX `idtiposector`(`idtiposector` ASC) USING BTREE,
  CONSTRAINT `idalmacen` FOREIGN KEY (`idalmacen`) REFERENCES `t_almacen` (`idalmacen`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `idtiposector` FOREIGN KEY (`idtiposector`) REFERENCES `t_tiposector` (`idtiposector`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1976 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_sectoringresodetalle
-- ----------------------------
DROP TABLE IF EXISTS `t_sectoringresodetalle`;
CREATE TABLE `t_sectoringresodetalle`  (
  `idsectoringresodetalle` int NOT NULL AUTO_INCREMENT,
  `idsector` int NULL DEFAULT NULL,
  `nivel` int NULL DEFAULT NULL,
  `idingresodetalle` int NULL DEFAULT NULL,
  `fecha_ingreso` datetime NULL DEFAULT NULL,
  `fecha_salida` datetime NULL DEFAULT NULL,
  `X` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `Y` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `Z` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idsectoringresodetalle`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 41586 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_solicitante
-- ----------------------------
DROP TABLE IF EXISTS `t_solicitante`;
CREATE TABLE `t_solicitante`  (
  `idsolicitante` int NOT NULL AUTO_INCREMENT,
  `idcliente` int NULL DEFAULT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `edited_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`idsolicitante`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_status
-- ----------------------------
DROP TABLE IF EXISTS `t_status`;
CREATE TABLE `t_status`  (
  `idstatus` int NOT NULL AUTO_INCREMENT,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idstatus`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_temperatura
-- ----------------------------
DROP TABLE IF EXISTS `t_temperatura`;
CREATE TABLE `t_temperatura`  (
  `idtemperatura` int NOT NULL AUTO_INCREMENT,
  `temperatura` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `activo` int NULL DEFAULT NULL,
  PRIMARY KEY (`idtemperatura`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_timbrado
-- ----------------------------
DROP TABLE IF EXISTS `t_timbrado`;
CREATE TABLE `t_timbrado`  (
  `idtimbrado` int NOT NULL AUTO_INCREMENT,
  `idcliente` int NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`idtimbrado`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 220 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_timbradodetalle
-- ----------------------------
DROP TABLE IF EXISTS `t_timbradodetalle`;
CREATE TABLE `t_timbradodetalle`  (
  `idtimbradodetalle` int NOT NULL AUTO_INCREMENT,
  `idtimbrado` int NULL DEFAULT NULL,
  `idtimbradoturno` int NULL DEFAULT NULL,
  `iddiasemana` int NULL DEFAULT NULL,
  `fecha` date NULL DEFAULT NULL,
  `codigo_producto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `nro_tcf` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `factura_timbrada` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `cantidad_timbrado` decimal(13, 3) NULL DEFAULT NULL,
  `meta_timbrado` decimal(13, 2) NULL DEFAULT NULL,
  `umcompra` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `umalterna` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `metodo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `precio_timbrado` decimal(13, 2) NULL DEFAULT NULL,
  `precio_metodo` decimal(13, 2) NULL DEFAULT NULL,
  `observaciones` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `personal` int NULL DEFAULT NULL,
  `lotes_permiso_senasag` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `faltantes_senasag` int NULL DEFAULT NULL,
  `lote_senasag` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `faltante` int NULL DEFAULT NULL,
  `lote_faltante` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `danados` int NULL DEFAULT NULL,
  `lote_danados` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `quebrados` int NULL DEFAULT NULL,
  `lote_quebrados` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `sin_tapa` int NULL DEFAULT NULL,
  `lote_sin_tapa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `borrosos` int NULL DEFAULT NULL,
  `lote_borrosos` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `film` decimal(13, 3) NULL DEFAULT NULL,
  `cinta` decimal(13, 3) NULL DEFAULT NULL,
  `silicona` decimal(13, 3) NULL DEFAULT NULL,
  `hora_hinicio` time NULL DEFAULT NULL,
  `hora_fin` time NULL DEFAULT NULL,
  `no_se_cumplio_por` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `lotes_validos` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `lotes_adicionales` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `transportadora` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `total_unidades_timbradas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `total_u_factura` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `carpicola_de_balde_20` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `clasificacion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `abolladas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `lote_abolladas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `mojadas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `lote_mojadas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `mermadas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `lote_mermadas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `tapa_extra` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `lote_tapa_extra` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `legales_extra_portugues` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `lote_legales_extra` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `caja_extra` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `lote_caja_extra` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `sarro_leve` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `lote_sarro_leve` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `sarro_severo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `lote_sarro_severo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `edited_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`idtimbradodetalle`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1337 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_timbradodetalle_bk
-- ----------------------------
DROP TABLE IF EXISTS `t_timbradodetalle_bk`;
CREATE TABLE `t_timbradodetalle_bk`  (
  `idtimbradodetalle` int NOT NULL AUTO_INCREMENT,
  `idtimbrado` int NULL DEFAULT NULL,
  `idtimbradoturno` int NULL DEFAULT NULL,
  `iddiasemana` int NULL DEFAULT NULL,
  `fecha` date NULL DEFAULT NULL,
  `codigo_producto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `nro_tcf` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `factura_timbrada` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `cantidad_timbrado` decimal(13, 3) NULL DEFAULT NULL,
  `meta_timbrado` decimal(13, 2) NULL DEFAULT NULL,
  `umcompra` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `umalterna` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `metodo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `precio_timbrado` decimal(13, 2) NULL DEFAULT NULL,
  `precio_metodo` decimal(13, 2) NULL DEFAULT NULL,
  `observaciones` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `personal` int NULL DEFAULT NULL,
  `lotes_permiso_senasag` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `faltantes_senasag` int NULL DEFAULT NULL,
  `lote_senasag` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `faltante` int NULL DEFAULT NULL,
  `lote_faltante` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `danados` int NULL DEFAULT NULL,
  `lote_danados` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `quebrados` int NULL DEFAULT NULL,
  `lote_quebrados` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `sin_tapa` int NULL DEFAULT NULL,
  `lote_sin_tapa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `borrosos` int NULL DEFAULT NULL,
  `lote_borrosos` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `film` decimal(13, 3) NULL DEFAULT NULL,
  `cinta` decimal(13, 3) NULL DEFAULT NULL,
  `silicona` decimal(13, 3) NULL DEFAULT NULL,
  `hora_hinicio` time NULL DEFAULT NULL,
  `hora_fin` time NULL DEFAULT NULL,
  `no_se_cumplio_por` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `lotes_validos` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `lotes_adicionales` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `transportadora` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `total_unidades_timbradas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `total_u_factura` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `carpicola_de_balde_20` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `clasificacion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `abolladas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `lote_abolladas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `mojadas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `lote_mojadas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `mermadas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `lote_mermadas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `tapa_extra` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `lote_tapa_extra` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `legales_extra_portugues` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `lote_legales_extra` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `caja_extra` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `lote_caja_extra` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `sarro_leve` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `lote_sarro_leve` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `sarro_severo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `lote_sarro_severo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `edited_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`idtimbradodetalle`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 602 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_timbradoturno
-- ----------------------------
DROP TABLE IF EXISTS `t_timbradoturno`;
CREATE TABLE `t_timbradoturno`  (
  `idtimbradoturno` int NOT NULL AUTO_INCREMENT,
  `timbradoturno` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idtimbradoturno`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_timeszones
-- ----------------------------
DROP TABLE IF EXISTS `t_timeszones`;
CREATE TABLE `t_timeszones`  (
  `idtimeszones` int NOT NULL AUTO_INCREMENT,
  `timezone_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `utc_offset_minutos` smallint NULL DEFAULT NULL,
  PRIMARY KEY (`idtimeszones`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 469 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_tipoalmacendetalle
-- ----------------------------
DROP TABLE IF EXISTS `t_tipoalmacendetalle`;
CREATE TABLE `t_tipoalmacendetalle`  (
  `idtipoalmacendetalle` int NOT NULL AUTO_INCREMENT,
  `tipoalmacendetalle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `imagen` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idtipoalmacendetalle`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_tipobulto
-- ----------------------------
DROP TABLE IF EXISTS `t_tipobulto`;
CREATE TABLE `t_tipobulto`  (
  `idtipobulto` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `tipobulto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idtipobulto`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 132 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_tipocambio
-- ----------------------------
DROP TABLE IF EXISTS `t_tipocambio`;
CREATE TABLE `t_tipocambio`  (
  `idtipocambio` int NOT NULL AUTO_INCREMENT,
  `idempresa` int NULL DEFAULT NULL,
  `iddivisaorigen` int NULL DEFAULT NULL,
  `iddivisadestino` int NULL DEFAULT NULL,
  `tipocambio` decimal(13, 6) NULL DEFAULT NULL,
  `fechainicio` date NULL DEFAULT NULL,
  `fechafin` date NULL DEFAULT NULL,
  PRIMARY KEY (`idtipocambio`) USING BTREE,
  INDEX `fechainicio`(`fechainicio` ASC) USING BTREE,
  INDEX `fechafin`(`fechafin` ASC) USING BTREE,
  INDEX `iddivisaorigen`(`iddivisaorigen` ASC) USING BTREE,
  INDEX `iddivisadestino`(`iddivisadestino` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 97 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_tipocamion
-- ----------------------------
DROP TABLE IF EXISTS `t_tipocamion`;
CREATE TABLE `t_tipocamion`  (
  `idtipocamion` int NOT NULL AUTO_INCREMENT,
  `tipocamion` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idtipocamion`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_tipocarga
-- ----------------------------
DROP TABLE IF EXISTS `t_tipocarga`;
CREATE TABLE `t_tipocarga`  (
  `idtipocarga` int NOT NULL AUTO_INCREMENT,
  `tipocarga` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `idtemperatura` int NULL DEFAULT NULL,
  `activo` int NULL DEFAULT NULL,
  `idconceptocargo` int NULL DEFAULT NULL,
  PRIMARY KEY (`idtipocarga`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_tipocobro
-- ----------------------------
DROP TABLE IF EXISTS `t_tipocobro`;
CREATE TABLE `t_tipocobro`  (
  `idtipocobro` int NOT NULL AUTO_INCREMENT,
  `tipocobro` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idtipocobro`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_tipocontenedor
-- ----------------------------
DROP TABLE IF EXISTS `t_tipocontenedor`;
CREATE TABLE `t_tipocontenedor`  (
  `idtipocontenedor` int NOT NULL AUTO_INCREMENT,
  `tipocontenedor` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idtipocontenedor`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_tipodescarga
-- ----------------------------
DROP TABLE IF EXISTS `t_tipodescarga`;
CREATE TABLE `t_tipodescarga`  (
  `idtipodescarga` int NOT NULL AUTO_INCREMENT,
  `tipodescarga` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idtipodescarga`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_tipodocumento
-- ----------------------------
DROP TABLE IF EXISTS `t_tipodocumento`;
CREATE TABLE `t_tipodocumento`  (
  `idtipodocumento` int NOT NULL,
  `tipodocumento` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idtipodocumento`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_tipoembarque
-- ----------------------------
DROP TABLE IF EXISTS `t_tipoembarque`;
CREATE TABLE `t_tipoembarque`  (
  `idtipoembarque` int NOT NULL AUTO_INCREMENT,
  `tipoembarque` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `codigo` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `tipoembarque_en` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idtipoembarque`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_tipoentidad
-- ----------------------------
DROP TABLE IF EXISTS `t_tipoentidad`;
CREATE TABLE `t_tipoentidad`  (
  `idtipoentidad` int NOT NULL AUTO_INCREMENT,
  `tipoentidad` varchar(60) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `tabla` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `columnanombre` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idtipoentidad`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_tipoevento
-- ----------------------------
DROP TABLE IF EXISTS `t_tipoevento`;
CREATE TABLE `t_tipoevento`  (
  `idtipoevento` int NOT NULL AUTO_INCREMENT,
  `tipoevento` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idtipoevento`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_tipofacturapago
-- ----------------------------
DROP TABLE IF EXISTS `t_tipofacturapago`;
CREATE TABLE `t_tipofacturapago`  (
  `idtipofacturapago` int NOT NULL AUTO_INCREMENT,
  `tipofacturapago` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idtipofacturapago`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_tipoingreso
-- ----------------------------
DROP TABLE IF EXISTS `t_tipoingreso`;
CREATE TABLE `t_tipoingreso`  (
  `idtipoingreso` int NOT NULL AUTO_INCREMENT,
  `tipoingreso` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idtipoingreso`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_tipoliquidacion
-- ----------------------------
DROP TABLE IF EXISTS `t_tipoliquidacion`;
CREATE TABLE `t_tipoliquidacion`  (
  `idtipoliquidacion` int NOT NULL AUTO_INCREMENT,
  `tipoliquidacion` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idtipoliquidacion`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_tipopedido
-- ----------------------------
DROP TABLE IF EXISTS `t_tipopedido`;
CREATE TABLE `t_tipopedido`  (
  `idtipopedido` int NOT NULL AUTO_INCREMENT,
  `tipopedido` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idtipopedido`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_tipoplanilla
-- ----------------------------
DROP TABLE IF EXISTS `t_tipoplanilla`;
CREATE TABLE `t_tipoplanilla`  (
  `idtipoplanilla` int NOT NULL AUTO_INCREMENT,
  `tipoplanilla` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `orden` int NULL DEFAULT NULL,
  PRIMARY KEY (`idtipoplanilla`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_tipoproducto
-- ----------------------------
DROP TABLE IF EXISTS `t_tipoproducto`;
CREATE TABLE `t_tipoproducto`  (
  `idtipoproducto` int NOT NULL AUTO_INCREMENT,
  `tipoproducto` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `es_vehiculo` int NULL DEFAULT NULL,
  PRIMARY KEY (`idtipoproducto`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_tiposector
-- ----------------------------
DROP TABLE IF EXISTS `t_tiposector`;
CREATE TABLE `t_tiposector`  (
  `idtiposector` int NOT NULL AUTO_INCREMENT,
  `tiposector` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idtiposector`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_tipotransferencia
-- ----------------------------
DROP TABLE IF EXISTS `t_tipotransferencia`;
CREATE TABLE `t_tipotransferencia`  (
  `idtipotransferencia` int NOT NULL AUTO_INCREMENT,
  `tipotransferencia` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idtipotransferencia`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_tipousuario
-- ----------------------------
DROP TABLE IF EXISTS `t_tipousuario`;
CREATE TABLE `t_tipousuario`  (
  `idtipousuario` int NOT NULL AUTO_INCREMENT,
  `tipousuario` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idtipousuario`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_token
-- ----------------------------
DROP TABLE IF EXISTS `t_token`;
CREATE TABLE `t_token`  (
  `idtoken` int NOT NULL AUTO_INCREMENT,
  `idusuario` int NULL DEFAULT NULL,
  `token` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `fechaingreso` datetime NULL DEFAULT NULL,
  `fechaexpiracion` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`idtoken`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 29 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_token_acceso_clientes
-- ----------------------------
DROP TABLE IF EXISTS `t_token_acceso_clientes`;
CREATE TABLE `t_token_acceso_clientes`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `almacen_id` int NULL DEFAULT NULL,
  `token` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_transportista
-- ----------------------------
DROP TABLE IF EXISTS `t_transportista`;
CREATE TABLE `t_transportista`  (
  `idtransportista` int NOT NULL AUTO_INCREMENT,
  `idempresa` int NULL DEFAULT NULL,
  `transportista` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `numeroidentificacion` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `telefono` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `fax` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `email` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `numerocuenta` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `nombrecontacto` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `plazo` int NULL DEFAULT NULL,
  `id_OVPProv` varchar(11) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idtransportista`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 263 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_transportistadireccion
-- ----------------------------
DROP TABLE IF EXISTS `t_transportistadireccion`;
CREATE TABLE `t_transportistadireccion`  (
  `idtransportistadireccion` int NOT NULL AUTO_INCREMENT,
  `idtransportista` int NULL DEFAULT NULL,
  `direccion` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `ciudad` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idpais` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idtransportistadireccion`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 264 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_transportistatarifa
-- ----------------------------
DROP TABLE IF EXISTS `t_transportistatarifa`;
CREATE TABLE `t_transportistatarifa`  (
  `idtransportistatarifa` int NOT NULL AUTO_INCREMENT,
  `idtransportista` int NULL DEFAULT NULL,
  `descripcion` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idformapago` int NULL DEFAULT NULL,
  `tiempoestimado` int NULL DEFAULT NULL,
  `minimo` decimal(13, 2) NULL DEFAULT NULL,
  `maximo` decimal(13, 2) NULL DEFAULT NULL,
  `numerocontrato` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `numeroenmienda` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `notas` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `fechainicio` date NULL DEFAULT NULL,
  `fechafin` date NULL DEFAULT NULL,
  PRIMARY KEY (`idtransportistatarifa`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_ubicacionitem
-- ----------------------------
DROP TABLE IF EXISTS `t_ubicacionitem`;
CREATE TABLE `t_ubicacionitem`  (
  `idubicacionitem` int NOT NULL AUTO_INCREMENT,
  `idingresodetalle` int NULL DEFAULT NULL,
  `idalmacendetalle` int NULL DEFAULT NULL,
  `fechaingreso` datetime NULL DEFAULT NULL,
  `fechasalida` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`idubicacionitem`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 49692 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_usuario
-- ----------------------------
DROP TABLE IF EXISTS `t_usuario`;
CREATE TABLE `t_usuario`  (
  `idusuario` int NOT NULL AUTO_INCREMENT,
  `idempresa` int NULL DEFAULT NULL,
  `nombre` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `ci` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idciudad` int NULL DEFAULT NULL,
  `idalmacen` int NULL DEFAULT NULL,
  `username` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `contrasena` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `idtipousuario` int NULL DEFAULT NULL,
  `email` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `telefono` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `almacen` int NULL DEFAULT NULL,
  `activo` int NULL DEFAULT NULL,
  `idcliente_almacen` int NULL DEFAULT NULL,
  `fecha_contrasena` date NULL DEFAULT NULL,
  `doble_factor` int NULL DEFAULT NULL,
  `codigo_doble_factor` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `fecha_doble_factor` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`idusuario`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 241 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_usuario_almacenes
-- ----------------------------
DROP TABLE IF EXISTS `t_usuario_almacenes`;
CREATE TABLE `t_usuario_almacenes`  (
  `idusuario_almacenes` int NOT NULL AUTO_INCREMENT,
  `idusuario` int NULL DEFAULT NULL,
  `idalmacen` int NULL DEFAULT NULL,
  PRIMARY KEY (`idusuario_almacenes`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 923 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_usuario_columnas_moverdividir
-- ----------------------------
DROP TABLE IF EXISTS `t_usuario_columnas_moverdividir`;
CREATE TABLE `t_usuario_columnas_moverdividir`  (
  `idusuario_columnas_moverdividir` int NOT NULL AUTO_INCREMENT,
  `idusuario` int NULL DEFAULT NULL,
  `field` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idusuario_columnas_moverdividir`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 476 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_usuario_columnas_pedido
-- ----------------------------
DROP TABLE IF EXISTS `t_usuario_columnas_pedido`;
CREATE TABLE `t_usuario_columnas_pedido`  (
  `idusuario_columnas_pedido` int NOT NULL AUTO_INCREMENT,
  `idusuario` int NULL DEFAULT NULL,
  `field` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  PRIMARY KEY (`idusuario_columnas_pedido`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 29 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_usuariomodulo
-- ----------------------------
DROP TABLE IF EXISTS `t_usuariomodulo`;
CREATE TABLE `t_usuariomodulo`  (
  `idusuariomodulo` int NOT NULL AUTO_INCREMENT,
  `idusuario` int NULL DEFAULT NULL,
  `idmodulo` int NULL DEFAULT NULL,
  `lectura` int NULL DEFAULT NULL,
  `escritura` int NULL DEFAULT NULL,
  PRIMARY KEY (`idusuariomodulo`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2510 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- View structure for v_entidades
-- ----------------------------
DROP VIEW IF EXISTS `v_entidades`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `v_entidades` AS select 1 AS `idtipoentidad`,`t_cliente`.`idempresa` AS `idempresa`,`t_cliente`.`idcliente` AS `identidad`,`t_cliente`.`cliente` AS `entidad`,`t_tipoentidad`.`tipoentidad` AS `tipoentidad` from (`t_cliente` left join `t_tipoentidad` on((1 = `t_tipoentidad`.`idtipoentidad`))) union all select 2 AS `idtipoentidad`,`t_proveedor`.`idempresa` AS `idempresa`,`t_proveedor`.`idproveedor` AS `identidad`,`t_proveedor`.`proveedor` AS `entidad`,`t_tipoentidad`.`tipoentidad` AS `tipoentidad` from (`t_proveedor` left join `t_tipoentidad` on((2 = `t_tipoentidad`.`idtipoentidad`))) union all select 3 AS `idtipoentidad`,`t_prestador`.`idempresa` AS `idempresa`,`t_prestador`.`idprestador` AS `identidad`,`t_prestador`.`prestador` AS `entidad`,`t_tipoentidad`.`tipoentidad` AS `tipoentidad` from (`t_prestador` left join `t_tipoentidad` on((3 = `t_tipoentidad`.`idtipoentidad`))) union all select 4 AS `idtipoentidad`,`t_transportista`.`idempresa` AS `idempresa`,`t_transportista`.`idtransportista` AS `identidad`,`t_transportista`.`transportista` AS `entidad`,`t_tipoentidad`.`tipoentidad` AS `tipoentidad` from (`t_transportista` left join `t_tipoentidad` on((4 = `t_tipoentidad`.`idtipoentidad`))) union all select 5 AS `idtipoentidad`,`t_agentecarga`.`idempresa` AS `idempresa`,`t_agentecarga`.`idagentecarga` AS `identidad`,`t_agentecarga`.`agentecarga` AS `entidad`,`t_tipoentidad`.`tipoentidad` AS `tipoentidad` from (`t_agentecarga` left join `t_tipoentidad` on((5 = `t_tipoentidad`.`idtipoentidad`))) order by `idtipoentidad`,`entidad`;

-- ----------------------------
-- View structure for v_logistico
-- ----------------------------
DROP VIEW IF EXISTS `v_logistico`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `v_logistico` AS select `t_embarque`.`idembarque` AS `idembarque`,`t_embarque`.`embarque` AS `embarque`,`t_embarque`.`idcliente` AS `idcliente`,`t_cliente`.`cliente` AS `cliente`,`t_concepto`.`concepto` AS `concepto`,`t_cargo`.`cantidad` AS `cantidad`,`t_cargo`.`monto` AS `monto`,(`t_cargo`.`cantidad` * `t_cargo`.`monto`) AS `total`,`t_divisa`.`codigo` AS `divisa`,`v_entidades`.`entidad` AS `destinatario`,`t_cargo`.`notas` AS `notas` from (((((`t_cargo` left join `t_embarque` on((`t_cargo`.`idembarque` = `t_embarque`.`idembarque`))) left join `t_cliente` on((`t_embarque`.`idcliente` = `t_cliente`.`idcliente`))) left join `t_concepto` on((`t_cargo`.`idconcepto` = `t_concepto`.`idconcepto`))) left join `t_divisa` on((`t_cargo`.`iddivisa` = `t_divisa`.`iddivisa`))) left join `v_entidades` on(((`t_cargo`.`iddestinatario` = `v_entidades`.`identidad`) and (`t_cargo`.`idtipodestinatario` = `v_entidades`.`idtipoentidad`)))) where ((ifnull(`t_cargo`.`esagente`,0) = 0) and (`t_embarque`.`idcliente` in (789,592)));

-- ----------------------------
-- View structure for v_timbrado
-- ----------------------------
DROP VIEW IF EXISTS `v_timbrado`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `v_timbrado` AS select `t_cliente`.`cliente` AS `cliente`,`t_timbrado`.`idtimbrado` AS `numero_timbrado`,`t_timbradoturno`.`timbradoturno` AS `timbradoturno`,`t_diasemana`.`diasemana` AS `diasemana`,`t_timbradodetalle`.`fecha` AS `fecha`,`t_timbradodetalle`.`codigo_producto` AS `codigo_producto`,`t_timbradodetalle`.`sku` AS `sku`,`t_timbradodetalle`.`nro_tcf` AS `nro_tcf`,`t_timbradodetalle`.`factura_timbrada` AS `factura_timbrada`,`t_timbradodetalle`.`cantidad_timbrado` AS `cantidad_timbrado`,if((ifnull(`t_timbradodetalle`.`umalterna`,0) = 0),0,((ifnull(`t_timbradodetalle`.`cantidad_timbrado`,0) * ifnull(`t_timbradodetalle`.`umcompra`,0)) / `t_timbradodetalle`.`umalterna`)) AS `cantidad_paquetes_timbrado`,`t_timbradodetalle`.`meta_timbrado` AS `meta_timbrado`,if((`t_timbradodetalle`.`meta_timbrado` > 0),((`t_timbradodetalle`.`cantidad_timbrado` / `t_timbradodetalle`.`meta_timbrado`) * 100),NULL) AS `cumplimiento`,`t_timbradodetalle`.`umcompra` AS `umcompra`,`t_timbradodetalle`.`umalterna` AS `umalterna`,(`t_timbradodetalle`.`cantidad_timbrado` * `t_timbradodetalle`.`umcompra`) AS `total_unidades`,`t_timbradodetalle`.`precio_timbrado` AS `precio_timbrado`,if((`t_timbradodetalle`.`umalterna` > 0),(`t_timbradodetalle`.`precio_timbrado` / `t_timbradodetalle`.`umalterna`),NULL) AS `precio_por_timbre`,`t_timbradodetalle`.`metodo` AS `metodo`,`t_timbradodetalle`.`precio_metodo` AS `precio_metodo`,(if((`t_timbradodetalle`.`umalterna` > 0),ifnull((`t_timbradodetalle`.`precio_timbrado` / `t_timbradodetalle`.`umalterna`),0),0) + ifnull(`t_timbradodetalle`.`precio_metodo`,0)) AS `precio_total`,((`t_timbradodetalle`.`cantidad_timbrado` * `t_timbradodetalle`.`umcompra`) * (if((`t_timbradodetalle`.`umalterna` > 0),ifnull((`t_timbradodetalle`.`precio_timbrado` / `t_timbradodetalle`.`umalterna`),0),0) + ifnull(`t_timbradodetalle`.`precio_metodo`,0))) AS `precio`,`t_timbradodetalle`.`observaciones` AS `observaciones`,`t_timbradodetalle`.`personal` AS `personal`,`t_timbradodetalle`.`lotes_permiso_senasag` AS `lotes_permiso_senasag`,`t_timbradodetalle`.`faltantes_senasag` AS `faltantes_senasag`,`t_timbradodetalle`.`lote_senasag` AS `lote_senasag`,`t_timbradodetalle`.`faltante` AS `faltante`,`t_timbradodetalle`.`lote_faltante` AS `lote_faltante`,`t_timbradodetalle`.`danados` AS `danados`,`t_timbradodetalle`.`lote_danados` AS `lote_danados`,`t_timbradodetalle`.`quebrados` AS `quebrados`,`t_timbradodetalle`.`lote_quebrados` AS `lote_quebrados`,`t_timbradodetalle`.`sin_tapa` AS `sin_tapa`,`t_timbradodetalle`.`lote_sin_tapa` AS `lote_sin_tapa`,`t_timbradodetalle`.`borrosos` AS `borrosos`,`t_timbradodetalle`.`lote_borrosos` AS `lote_borrosos`,`t_timbradodetalle`.`film` AS `film`,`t_timbradodetalle`.`cinta` AS `cinta`,`t_timbradodetalle`.`silicona` AS `silicona`,`t_timbradodetalle`.`hora_hinicio` AS `hora_hinicio`,`t_timbradodetalle`.`hora_fin` AS `hora_fin`,sec_to_time(if((timestampdiff(MINUTE,`t_timbradodetalle`.`hora_hinicio`,`t_timbradodetalle`.`hora_fin`) < 0),((timestampdiff(MINUTE,`t_timbradodetalle`.`hora_hinicio`,`t_timbradodetalle`.`hora_fin`) + 1440) * 60),(timestampdiff(MINUTE,`t_timbradodetalle`.`hora_hinicio`,`t_timbradodetalle`.`hora_fin`) * 60))) AS `horas_trabajadas`,((((ifnull(`t_timbradodetalle`.`faltantes_senasag`,0) + ifnull(`t_timbradodetalle`.`faltante`,0)) + ifnull(`t_timbradodetalle`.`danados`,0)) + ifnull(`t_timbradodetalle`.`quebrados`,0)) + ifnull(`t_timbradodetalle`.`sin_tapa`,0)) AS `timbres_sobrantes`,`t_timbradodetalle`.`no_se_cumplio_por` AS `no_se_cumplio_por`,`t_timbradodetalle`.`lotes_validos` AS `lotes_validos`,`t_timbradodetalle`.`lotes_adicionales` AS `lotes_adicionales`,`t_timbradodetalle`.`transportadora` AS `transportadora`,`t_timbradodetalle`.`total_unidades_timbradas` AS `total_unidades_timbradas`,`t_timbradodetalle`.`total_u_factura` AS `total_u_factura`,`t_timbradodetalle`.`carpicola_de_balde_20` AS `carpicola_de_balde_20`,`t_timbradodetalle`.`clasificacion` AS `clasificacion`,`t_timbradodetalle`.`abolladas` AS `abolladas`,`t_timbradodetalle`.`lote_abolladas` AS `lote_abolladas`,`t_timbradodetalle`.`mojadas` AS `mojadas`,`t_timbradodetalle`.`lote_mojadas` AS `lote_mojadas`,`t_timbradodetalle`.`mermadas` AS `mermadas`,`t_timbradodetalle`.`lote_mermadas` AS `lote_mermadas`,`t_timbradodetalle`.`tapa_extra` AS `tapa_extra`,`t_timbradodetalle`.`lote_tapa_extra` AS `lote_tapa_extra`,`t_timbradodetalle`.`legales_extra_portugues` AS `legales_extra_portugues`,`t_timbradodetalle`.`lote_legales_extra` AS `lote_legales_extra`,`t_timbradodetalle`.`caja_extra` AS `caja_extra`,`t_timbradodetalle`.`lote_caja_extra` AS `lote_caja_extra`,`t_timbradodetalle`.`sarro_leve` AS `sarro_leve`,`t_timbradodetalle`.`lote_sarro_leve` AS `lote_sarro_leve`,`t_timbradodetalle`.`sarro_severo` AS `sarro_severo`,`t_timbradodetalle`.`lote_sarro_severo` AS `lote_sarro_severo` from ((((`t_timbradodetalle` left join `t_timbradoturno` on((`t_timbradodetalle`.`idtimbradoturno` = `t_timbradoturno`.`idtimbradoturno`))) left join `t_diasemana` on((`t_timbradodetalle`.`iddiasemana` = `t_diasemana`.`iddiasemana`))) left join `t_timbrado` on((`t_timbradodetalle`.`idtimbrado` = `t_timbrado`.`idtimbrado`))) left join `t_cliente` on((`t_timbrado`.`idcliente` = `t_cliente`.`idcliente`))) where ((`t_timbradodetalle`.`deleted_at` is null) and (`t_cliente`.`idempresa` = 1)) order by `t_timbradodetalle`.`fecha` desc;

-- ----------------------------
-- Function structure for Almacenaje
-- ----------------------------
DROP FUNCTION IF EXISTS `Almacenaje`;
delimiter ;;
CREATE FUNCTION `Almacenaje`(idfacturaint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorcargado DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) INTO valorcargado
	FROM
	t_cargo
  LEFT JOIN t_embarque ON t_cargo.idembarque=t_embarque.idembarque
	LEFT JOIN t_concepto ON t_cargo.idconcepto=t_concepto.idconcepto
	LEFT JOIN t_factura ON t_cargo.idfacturanotadebito=t_factura.idfactura AND t_cargo.idtipofacturanotadebito=1
	LEFT JOIN t_notadebito ON t_cargo.idfacturanotadebito=t_notadebito.idnotadebito AND t_cargo.idtipofacturanotadebito=2
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()))
	WHERE 
	t_factura.idfactura=idfacturaint 
	AND IFNULL(t_cargo.esagente,0)=0
	AND IFNULL(t_cargo.idconcepto,0)=193
  AND ifnull(t_factura.idestadofactura,0) <> 2;

	RETURN ifnull(valorcargado,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for AlmacenajeCargo
-- ----------------------------
DROP FUNCTION IF EXISTS `AlmacenajeCargo`;
delimiter ;;
CREATE FUNCTION `AlmacenajeCargo`(idembarqueint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorcargado DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) INTO valorcargado
	FROM
	t_cargo
  LEFT JOIN t_embarque ON t_cargo.idembarque=t_embarque.idembarque
	LEFT JOIN t_concepto ON t_cargo.idconcepto=t_concepto.idconcepto
	LEFT JOIN t_factura ON t_cargo.idfacturanotadebito=t_factura.idfactura AND t_cargo.idtipofacturanotadebito=1
	LEFT JOIN t_notadebito ON t_cargo.idfacturanotadebito=t_notadebito.idnotadebito AND t_cargo.idtipofacturanotadebito=2
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 2=t_tipocambio.iddivisadestino AND IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()))
	WHERE 
	t_embarque.idembarque=idembarqueint 
	AND IFNULL(t_cargo.esagente,0)=0
	AND IFNULL(t_cargo.idconcepto,0)=5
  AND ifnull(t_factura.idestadofactura,0) <> 2;

	RETURN ifnull(valorcargado,0);
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for balance
-- ----------------------------
DROP PROCEDURE IF EXISTS `balance`;
delimiter ;;
CREATE PROCEDURE `balance`(IN `idembarqueint` int, esagenteint int)
BEGIN
	#Routine body goes here...

	DROP TEMPORARY TABLE IF EXISTS tmp_cargo;
	CREATE TEMPORARY TABLE tmp_cargo (concepto VARCHAR(150), montobs DECIMAL(13,2), montous DECIMAL(13,2), numerodocumento varchar(50));
	INSERT INTO tmp_cargo (concepto, montobs, montous, numerodocumento)
	SELECT
	t_concepto.concepto,
	t_cargo.cantidad*t_cargo.monto*t_tipocambio.tipocambio as montobs,
	t_cargo.cantidad*t_cargo.monto*t_tipocambious.tipocambio as montous,
	CASE IFNULL(t_cargo.idtipofacturanotadebito,0) 
		WHEN 1 THEN t_factura.nrofactura
		WHEN 2 THEN CONCAT(t_notadebito.nronotadebito,'/',t_notadebito.gestion)
		ELSE ''
	END as numerodocumento
	FROM
	t_cargo
	LEFT JOIN t_concepto ON t_cargo.idconcepto=t_concepto.idconcepto
	LEFT JOIN t_factura ON t_cargo.idfacturanotadebito=t_factura.idfactura AND t_cargo.idtipofacturanotadebito=1 AND 1=t_factura.idestadofactura
	LEFT JOIN t_notadebito ON t_cargo.idfacturanotadebito=t_notadebito.idnotadebito AND t_cargo.idtipofacturanotadebito=2 AND 1=t_notadebito.idestadonotadebito
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND CASE IFNULL(t_cargo.idtipofacturanotadebito,0) WHEN 1 THEN t_factura.fecha WHEN 2 THEN t_notadebito.fecha ELSE CURRENT_DATE() END BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,CASE IFNULL(t_cargo.idtipofacturanotadebito,0) WHEN 1 THEN t_factura.fecha WHEN 2 THEN t_notadebito.fecha ELSE CURRENT_DATE() END)
	LEFT JOIN t_tipocambio as t_tipocambious ON t_cargo.iddivisa=t_tipocambious.iddivisaorigen AND 2=t_tipocambious.iddivisadestino AND CASE IFNULL(t_cargo.idtipofacturanotadebito,0) WHEN 1 THEN t_factura.fecha WHEN 2 THEN t_notadebito.fecha ELSE CURRENT_DATE() END BETWEEN t_tipocambious.fechainicio AND ifnull(t_tipocambious.fechafin,CASE IFNULL(t_cargo.idtipofacturanotadebito,0) WHEN 1 THEN t_factura.fecha WHEN 2 THEN t_notadebito.fecha ELSE CURRENT_DATE() END)
	WHERE
	t_cargo.idembarque=idembarqueint
	AND 
	(CASE esagenteint
		WHEN 2 THEN 2
		ELSE IFNULL(t_cargo.esagente,0)
	END)=esagenteint;

	DROP TEMPORARY TABLE IF EXISTS tmp_costo;
	CREATE TEMPORARY TABLE tmp_costo (concepto VARCHAR(150), montobs DECIMAL(13,2), montous DECIMAL(13,2), numerodocumento varchar(50));
	INSERT INTO tmp_costo (concepto, montobs, montous, numerodocumento)
	SELECT
	t_concepto.concepto,
	t_costo.cantidad*t_costo.monto*t_tipocambio.tipocambio as montobs,
	t_costo.cantidad*t_costo.monto*t_tipocambious.tipocambio as montous,
	CASE IFNULL(t_costo.idtipofacturanotadebito,0) 
		WHEN 1 THEN t_facturapago.numerofactura
		ELSE ''
	END as numerodocumento
	FROM
	t_costo
	LEFT JOIN t_concepto ON t_costo.idconcepto=t_concepto.idconcepto
	LEFT JOIN t_facturapago ON t_costo.idfacturanotadebito=t_facturapago.idfacturapago AND 1=t_facturapago.idestadofacturapago
	LEFT JOIN t_tipocambio ON t_costo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND CASE IFNULL(t_costo.idfacturanotadebito,0) WHEN 1 THEN t_facturapago.fecha ELSE CURRENT_DATE() END BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,CASE IFNULL(t_costo.idfacturanotadebito,0) WHEN 1 THEN t_facturapago.fecha ELSE CURRENT_DATE() END)
	LEFT JOIN t_tipocambio as t_tipocambious ON t_costo.iddivisa=t_tipocambious.iddivisaorigen AND 2=t_tipocambious.iddivisadestino AND CASE IFNULL(t_costo.idfacturanotadebito,0) WHEN 1 THEN t_facturapago.fecha ELSE CURRENT_DATE() END BETWEEN t_tipocambious.fechainicio AND ifnull(t_tipocambious.fechafin,CASE IFNULL(t_costo.idfacturanotadebito,0) WHEN 1 THEN t_facturapago.fecha ELSE CURRENT_DATE() END)
	WHERE
	t_costo.idembarque=idembarqueint
	AND t_facturapago.idestadofacturapago=1
	AND 
	(CASE esagenteint
		WHEN 2 THEN 2
		ELSE IFNULL(t_costo.esagente,0)
	END)=esagenteint;

	DROP TEMPORARY TABLE IF EXISTS tmp_balance;
	CREATE TEMPORARY TABLE tmp_balance (montobs DECIMAL(13,2), montous DECIMAL(13,2));
	INSERT INTO tmp_balance (montobs, montous)
	SELECT 
	SUM(ifnull(tmp_cargo.montobs,0)),
	SUM(ifnull(tmp_cargo.montous,0))
	FROM
	tmp_cargo;

	UPDATE tmp_balance SET montobs=montobs-(SELECT SUM(ifnull(montobs,0)) FROM tmp_costo);
	UPDATE tmp_balance SET montous=montous-(SELECT SUM(ifnull(montous,0)) FROM tmp_costo);

END
;;
delimiter ;

-- ----------------------------
-- Function structure for Carguio
-- ----------------------------
DROP FUNCTION IF EXISTS `Carguio`;
delimiter ;;
CREATE FUNCTION `Carguio`(idfacturaint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorcargado DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) INTO valorcargado
	FROM
	t_cargo
  LEFT JOIN t_embarque ON t_cargo.idembarque=t_embarque.idembarque
	LEFT JOIN t_concepto ON t_cargo.idconcepto=t_concepto.idconcepto
	LEFT JOIN t_factura ON t_cargo.idfacturanotadebito=t_factura.idfactura AND t_cargo.idtipofacturanotadebito=1
	LEFT JOIN t_notadebito ON t_cargo.idfacturanotadebito=t_notadebito.idnotadebito AND t_cargo.idtipofacturanotadebito=2
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()))
	WHERE 
	t_factura.idfactura=idfacturaint 
	AND IFNULL(t_cargo.esagente,0)=0
	AND IFNULL(t_cargo.idconcepto,0)=196
  AND ifnull(t_factura.idestadofactura,0) <> 2;

	RETURN ifnull(valorcargado,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for CarguioMedioTransporte
-- ----------------------------
DROP FUNCTION IF EXISTS `CarguioMedioTransporte`;
delimiter ;;
CREATE FUNCTION `CarguioMedioTransporte`(idfacturaint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorcargado DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) INTO valorcargado
	FROM
	t_cargo
  LEFT JOIN t_embarque ON t_cargo.idembarque=t_embarque.idembarque
	LEFT JOIN t_concepto ON t_cargo.idconcepto=t_concepto.idconcepto
	LEFT JOIN t_factura ON t_cargo.idfacturanotadebito=t_factura.idfactura AND t_cargo.idtipofacturanotadebito=1
	LEFT JOIN t_notadebito ON t_cargo.idfacturanotadebito=t_notadebito.idnotadebito AND t_cargo.idtipofacturanotadebito=2
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()))
	WHERE 
	t_factura.idfactura=idfacturaint 
	AND IFNULL(t_cargo.esagente,0)=0
	AND IFNULL(t_cargo.idconcepto,0)=73
  AND ifnull(t_factura.idestadofactura,0) <> 2;

	RETURN ifnull(valorcargado,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for CESTIBAJE
-- ----------------------------
DROP FUNCTION IF EXISTS `CESTIBAJE`;
delimiter ;;
CREATE FUNCTION `CESTIBAJE`(idembarqueint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorcargo DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) INTO valorcargo
	FROM
	t_cargo
  LEFT JOIN t_embarque ON t_cargo.idembarque=t_embarque.idembarque
	LEFT JOIN t_factura ON t_cargo.idfacturanotadebito=t_factura.idfactura AND t_cargo.idtipofacturanotadebito=1
	LEFT JOIN t_notadebito ON t_cargo.idfacturanotadebito=t_notadebito.idnotadebito AND t_cargo.idtipofacturanotadebito=2
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()))
	WHERE 
	t_embarque.idembarque=idembarqueint 
	AND IFNULL(t_cargo.idconcepto,0)=4
  AND ifnull(t_factura.idestadofactura,0) <> 2;
	

	RETURN ifnull(valorcargo,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for COESTIBAJE
-- ----------------------------
DROP FUNCTION IF EXISTS `COESTIBAJE`;
delimiter ;;
CREATE FUNCTION `COESTIBAJE`(idembarqueint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorcosto DECIMAL(13,2);

	SELECT
	SUM(t_costo.monto*t_costo.cantidad*t_tipocambio.tipocambio) INTO valorcosto
	FROM
	t_costo
  LEFT JOIN t_facturapago ON t_costo.idfacturanotadebito=t_facturapago.idfacturapago
  LEFT JOIN t_embarque ON t_costo.idembarque=t_embarque.idembarque
	LEFT JOIN t_tipocambio ON t_costo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(t_facturapago.fecha, CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(t_facturapago.fecha, CURRENT_DATE()))
	WHERE 
	t_embarque.idembarque=idembarqueint 
		AND IFNULL(t_costo.idconcepto,0)=10
  AND ifnull(t_facturapago.idestadofacturapago,0) <> 2;

	RETURN ifnull(valorcosto,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for COFleteUrbajo
-- ----------------------------
DROP FUNCTION IF EXISTS `COFleteUrbajo`;
delimiter ;;
CREATE FUNCTION `COFleteUrbajo`(idembarqueint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorcosto DECIMAL(13,2);

	SELECT
	SUM(t_costo.monto*t_costo.cantidad*t_tipocambio.tipocambio) INTO valorcosto
	FROM
	t_costo
  LEFT JOIN t_facturapago ON t_costo.idfacturanotadebito=t_facturapago.idfacturapago
  LEFT JOIN t_embarque ON t_costo.idembarque=t_embarque.idembarque
	LEFT JOIN t_tipocambio ON t_costo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(t_facturapago.fecha, CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(t_facturapago.fecha, CURRENT_DATE()))
	WHERE 
	t_embarque.idembarque=idembarqueint 
		AND IFNULL(t_costo.idconcepto,0)=9
  AND ifnull(t_facturapago.idestadofacturapago,0) <> 2;

	RETURN ifnull(valorcosto,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for COSTANDBY
-- ----------------------------
DROP FUNCTION IF EXISTS `COSTANDBY`;
delimiter ;;
CREATE FUNCTION `COSTANDBY`(idembarqueint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorcosto DECIMAL(13,2);

	SELECT
	SUM(t_costo.monto*t_costo.cantidad*t_tipocambio.tipocambio) INTO valorcosto
	FROM
	t_costo
  LEFT JOIN t_facturapago ON t_costo.idfacturanotadebito=t_facturapago.idfacturapago
  LEFT JOIN t_embarque ON t_costo.idembarque=t_embarque.idembarque
	LEFT JOIN t_tipocambio ON t_costo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(t_facturapago.fecha, CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(t_facturapago.fecha, CURRENT_DATE()))
	WHERE 
	t_embarque.idembarque=idembarqueint 
		AND IFNULL(t_costo.idconcepto,0)=182
  AND ifnull(t_facturapago.idestadofacturapago,0) <> 2;

	RETURN ifnull(valorcosto,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for CrossDocking
-- ----------------------------
DROP FUNCTION IF EXISTS `CrossDocking`;
delimiter ;;
CREATE FUNCTION `CrossDocking`(idfacturaint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorcargado DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) INTO valorcargado
	FROM
	t_cargo
  LEFT JOIN t_embarque ON t_cargo.idembarque=t_embarque.idembarque
	LEFT JOIN t_concepto ON t_cargo.idconcepto=t_concepto.idconcepto
	LEFT JOIN t_factura ON t_cargo.idfacturanotadebito=t_factura.idfactura AND t_cargo.idtipofacturanotadebito=1
	LEFT JOIN t_notadebito ON t_cargo.idfacturanotadebito=t_notadebito.idnotadebito AND t_cargo.idtipofacturanotadebito=2
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()))
	WHERE 
	t_factura.idfactura=idfacturaint 
	AND IFNULL(t_cargo.esagente,0)=0
	AND IFNULL(t_cargo.idconcepto,0)=197
  AND ifnull(t_factura.idestadofactura,0) <> 2;

	RETURN ifnull(valorcargado,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for CServicioFleteUrbano
-- ----------------------------
DROP FUNCTION IF EXISTS `CServicioFleteUrbano`;
delimiter ;;
CREATE FUNCTION `CServicioFleteUrbano`(idembarqueint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorcargo DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) INTO valorcargo
	FROM
	t_cargo
  LEFT JOIN t_embarque ON t_cargo.idembarque=t_embarque.idembarque
	LEFT JOIN t_factura ON t_cargo.idfacturanotadebito=t_factura.idfactura AND t_cargo.idtipofacturanotadebito=1
	LEFT JOIN t_notadebito ON t_cargo.idfacturanotadebito=t_notadebito.idnotadebito AND t_cargo.idtipofacturanotadebito=2
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()))
	WHERE 
	t_embarque.idembarque=idembarqueint 
	AND IFNULL(t_cargo.idconcepto,0)=3
  AND ifnull(t_factura.idestadofactura,0) <> 2;
	

	RETURN ifnull(valorcargo,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for CSTANDBY
-- ----------------------------
DROP FUNCTION IF EXISTS `CSTANDBY`;
delimiter ;;
CREATE FUNCTION `CSTANDBY`(idembarqueint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorcargo DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) INTO valorcargo
	FROM
	t_cargo
  LEFT JOIN t_embarque ON t_cargo.idembarque=t_embarque.idembarque
	LEFT JOIN t_factura ON t_cargo.idfacturanotadebito=t_factura.idfactura AND t_cargo.idtipofacturanotadebito=1
	LEFT JOIN t_notadebito ON t_cargo.idfacturanotadebito=t_notadebito.idnotadebito AND t_cargo.idtipofacturanotadebito=2
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()))
	WHERE 
	t_embarque.idembarque=idembarqueint 
	AND IFNULL(t_cargo.idconcepto,0)=118
  AND ifnull(t_factura.idestadofactura,0) <> 2;
	

	RETURN ifnull(valorcargo,0);
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for DashBoardFacturacion
-- ----------------------------
DROP PROCEDURE IF EXISTS `DashBoardFacturacion`;
delimiter ;;
CREATE PROCEDURE `DashBoardFacturacion`()
BEGIN
	#Routine body goes here...
	DROP TABLE IF EXISTS pbi_facturacion;
	CREATE TABLE IF NOT EXISTS pbi_facturacion AS
	select
	t_factura.idfactura,
	CASE t_factura.idcobraratipo
		WHEN 1 THEN t_cliente.cliente
		WHEN 1 THEN t_proveedor.proveedor
		WHEN 1 THEN t_prestador.prestador
		WHEN 1 THEN t_transportista.transportista
		WHEN 1 THEN t_agentecarga.agentecarga
	END as cliente,
	t_factura.fecha,
	CASE t_factura.idestadofactura
		WHEN 1 THEN valorfacturado(t_factura.idfactura)
		WHEN 2 THEN 0
	END as monto,
	t_factura.nrofactura,
	t_estadofactura.estadofactura
	FROM
	t_factura
	LEFT JOIN t_cliente ON t_factura.idcobrara=t_cliente.idcliente AND t_factura.idcobraratipo=1
	LEFT JOIN t_proveedor ON t_factura.idcobrara=t_proveedor.idproveedor AND t_factura.idcobraratipo=2
	LEFT JOIN t_prestador ON t_factura.idcobrara=t_prestador.idprestador AND t_factura.idcobraratipo=3
	LEFT JOIN t_transportista ON t_factura.idcobrara=t_transportista.idtransportista AND t_factura.idcobraratipo=4
	LEFT JOIN t_agentecarga ON t_factura.idcobrara=t_agentecarga.idagentecarga AND t_factura.idcobraratipo=5
	LEFT JOIN t_estadofactura ON t_factura.idestadofactura=t_estadofactura.idestadofactura
	WHERE
	YEAR(t_factura.fecha)>=2019
  AND t_cliente.idempresa=1;
	
	ALTER TABLE pbi_facturacion ADD PRIMARY KEY (idfactura);

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for DashBoardIngresos
-- ----------------------------
DROP PROCEDURE IF EXISTS `DashBoardIngresos`;
delimiter ;;
CREATE PROCEDURE `DashBoardIngresos`()
BEGIN
	#Routine body goes here...
	DROP TEMPORARY TABLE IF EXISTS tmp_ultimaubicacion;
  CREATE TEMPORARY TABLE tmp_ultimaubicacion (idubicacionitem INT, idingresodetalle INT);
  INSERT INTO tmp_ultimaubicacion (idubicacionitem, idingresodetalle)
        SELECT
        MAX(idubicacionitem) as idubicacionitem,
        idingresodetalle
        FROM
        t_ubicacionitem
        GROUP BY
        idingresodetalle;
  ALTER TABLE tmp_ultimaubicacion ADD INDEX idubicacionitem (idubicacionitem);
  ALTER TABLE tmp_ultimaubicacion ADD INDEX idingresodetalle (idingresodetalle);
	
	
	DROP TABLE IF EXISTS pbi_ingresos;
	CREATE TABLE IF NOT EXISTS pbi_ingresos AS
	SELECT
        t_ingresodetalle.idingresodetalle,
        t_ingresodetalle.idingreso,
        t_cliente.cliente,
        CONCAT(t_ingreso.numero,'/',t_ingreso.gestion) as numeroingreso,
        t_ingreso.fecha as fechaingreso,
        t_ingreso.placa,
        t_ingreso.contenedor,
        t_ingreso.idtipoingreso,
        t_tipoingreso.tipoingreso,
        t_ingreso.precinto,
        t_ingreso.idtipodescarga,
        t_tipodescarga.tipodescarga,
        t_ingreso.idtipocamion,
        t_tipocamion.tipocamion,
        t_ingreso.idtipocontenedor,
        t_tipocontenedor.tipocontenedor,
        t_ingreso.idtipoproducto,
        t_tipoproducto.tipoproducto,
        t_ingreso.piezas_manifestadas,
        t_ingreso.peso_total,
        t_ingreso.proveedor,
        t_ingreso.no_contrato,
        t_ingreso.delivery_batch,
        t_ingreso.rubro_producto,
        t_ingreso.project,
        t_ingreso.invoice,
        t_ingreso.dui,
        t_ingreso.cantidad_pallet,
        t_ingreso.cantidad_cajas,
        t_ingreso.hora_inicio,
        t_ingreso.hora_fin,
        t_ingreso.cantidad_estibadores,
        t_ingreso.nota_adicional,
        t_ingreso.idusuario_recibido,
        t_usuario.nombre as usuario_recibido,
        CONCAT(t_ingreso.fechasistema,' ',t_ingreso.horasistema) as fechasistema,
        t_ingreso.nombre_entrega,
        t_ingreso.ci_entrega,
        t_ingreso.empresa_entrega,
        t_ingresodetalle.codigo,
        t_ingresodetalle.serie,
        t_ingresodetalle.descripcion,
        t_ingresodetalle.centro_distribucion,
        IFNULL(t_ingresodetalle.sustanciascontroladas,0) as sustanciascontroladas,
        t_ingresodetalle.categoria,
        t_ingresodetalle.cantidad,
        t_ingresodetalle.idembalaje,
        t_embalaje.codigoembalaje,
        t_ingresodetalle.lote,
        t_ingresodetalle.costo_un,
        t_ingresodetalle.cantidad_no_conf,
        t_no_conf.no_conf,
        t_merma.merma,
        CONCAT(t_ingresodetalle.fechaproduccion,' 12:00:00') as fechaproduccion,
        CONCAT(t_ingresodetalle.fechavencimiento,' 12:00:00') as fechavencimiento,
        t_ingresodetalle.relacion_caja,
        t_ingresodetalle.volumen,
        t_ingresodetalle.bultos,
        t_ingresodetalle.peso,
        t_ingresodetalle.temperatura,
        t_ingresodetalle.observaciones,
        t_almacendetalle.nombre as ubicacionalmacen,
        IFNULL(t_embalaje_salida.codigoembalaje,t_embalaje.codigoembalaje) as codigoembalaje_salida,
        IFNULL(t_baseproductos.factor_conversion,1) as factor_conversion,
        t_ingreso.fecha_cierre_transito,
        t_ingreso.fecha_emision_parte
        FROM
        t_ingreso
        LEFT JOIN t_ingresodetalle ON t_ingreso.idingreso=t_ingresodetalle.idingreso
        LEFT JOIN t_cliente ON t_ingreso.idcliente=t_cliente.idcliente
        LEFT JOIN t_tipocamion ON t_ingreso.idtipocamion=t_tipocamion.idtipocamion
        LEFT JOIN t_tipoingreso ON t_ingreso.idtipoingreso=t_tipoingreso.idtipoingreso
        LEFT JOIN t_tipocontenedor ON t_ingreso.idtipocontenedor=t_tipocontenedor.idtipocontenedor
        LEFT JOIN t_tipodescarga ON t_ingreso.idtipodescarga=t_tipodescarga.idtipodescarga
        LEFT JOIN t_tipoproducto ON t_ingreso.idtipoproducto=t_tipoproducto.idtipoproducto
        LEFT JOIN t_usuario ON t_ingreso.idusuario_recibido=t_usuario.idusuario
        LEFT JOIN t_embalaje ON t_ingresodetalle.idembalaje=t_embalaje.idembalaje
        LEFT JOIN t_no_conf ON t_ingresodetalle.idno_conf=t_no_conf.idno_conf
        LEFT JOIN t_merma ON t_ingresodetalle.idmerma=t_merma.idmerma
        LEFT JOIN tmp_ultimaubicacion ON t_ingresodetalle.idingresodetalle=tmp_ultimaubicacion.idingresodetalle
        LEFT JOIN t_ubicacionitem ON tmp_ultimaubicacion.idubicacionitem=t_ubicacionitem.idubicacionitem
        LEFT JOIN t_almacendetalle ON t_ubicacionitem.idalmacendetalle=t_almacendetalle.idalmacendetalle
        LEFT JOIN t_baseproductos ON t_ingresodetalle.codigo=t_baseproductos.codigo AND t_ingreso.idcliente=t_baseproductos.idcliente AND t_ingresodetalle.idembalaje=t_baseproductos.idembalaje
        LEFT JOIN t_embalaje as t_embalaje_salida ON t_baseproductos.idembalaje_salida=t_embalaje_salida.idembalaje
        WHERE
        IFNULL(t_ingresodetalle.idingresodetallepadre,0)=0
        AND t_cliente.idempresa=1;
				
				-- ALTER TABLE pbi_ingresos ADD PRIMARY KEY (idingresodetalle);

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for DashBoardLogistico
-- ----------------------------
DROP PROCEDURE IF EXISTS `DashBoardLogistico`;
delimiter ;;
CREATE PROCEDURE `DashBoardLogistico`()
BEGIN
	DROP TABLE IF EXISTS pbi_logistico;
	CREATE TABLE IF NOT EXISTS pbi_logistico as 
SELECT
t_embarque.idembarque,
t_tipoembarque.tipoembarque,
t_cliente.cliente,
t_ciudad.ciudad,
t_embarque.embarque,
SUBSTRING_INDEX(t_embarque.embarque, '-', 2) prefijo,
CASE
WHEN (SUBSTRING_INDEX(t_embarque.embarque, '-', 2)='SL-T') THEN "Servicio Logístico Terrestre"
WHEN (SUBSTRING_INDEX(t_embarque.embarque, '-', 2)='IMP-A') THEN "Importación Aérea"
WHEN (SUBSTRING_INDEX(t_embarque.embarque, '-', 2)='IMP-T') THEN "Importación Terrestre"
WHEN (SUBSTRING_INDEX(t_embarque.embarque, '-', 2)='SL-A') THEN "Servicio Logístico Aéreo"
WHEN (SUBSTRING_INDEX(t_embarque.embarque, '-', 2)='SL-O') THEN "Servicio Logístico Otros"
WHEN (SUBSTRING_INDEX(t_embarque.embarque, '-', 2)='IMP-MUL') THEN "Importación Multimodal"
WHEN (SUBSTRING_INDEX(t_embarque.embarque, '-', 2)='EXP-A') THEN "Exportación Aérea"
WHEN (SUBSTRING_INDEX(t_embarque.embarque, '-', 2)='SL-M') THEN "Servicio Logístico Marítimo"
WHEN (SUBSTRING_INDEX(t_embarque.embarque, '-', 2)='ALM-O') THEN "Almacenaje Otros"
WHEN (SUBSTRING_INDEX(t_embarque.embarque, '-', 2)='URB-T') THEN "Urbano Terrestre"
ELSE "Nacional Terrestre "
END
AS Tipo,
valorcargado(t_embarque.idembarque) as Cargo,
valorcosteado(t_embarque.idembarque) as Costo,
valorcargado(t_embarque.idembarque)-valorcosteado(t_embarque.idembarque) as Balance,
t_embarque.fecharealizacion,
t_embarque.peso,
t_incoterms.incoterms,
t_mediotransporte.mediotransporte,
t_transportista.transportista,
t_embarque.descripcioncarga,
t_ciudadsalidad.ciudad AS salidade,
t_embarque.fechasalida,
t_ciudadarribo.ciudad AS arriboa,
t_embarque.fechaarribo,
t_ciudadorigen.ciudad AS origen,
t_ciudaddestino.ciudad AS destino,
t_usuario.nombre,
CASE
WHEN t_embarque.fechafinalizacion IS NULL THEN "NO"
ELSE "SI"
END
AS finalizado
FROM t_embarque
LEFT JOIN t_cliente ON t_embarque.idcliente=t_cliente.idcliente
LEFT JOIN t_tipoembarque ON t_embarque.idtipoembarque=t_tipoembarque.idtipoembarque
LEFT JOIN t_ciudad ON t_embarque.idciudad=t_ciudad.idciudad
LEFT JOIN t_incoterms ON t_embarque.idincoterms=t_incoterms.idincoterms
LEFT JOIN t_mediotransporte ON t_embarque.idmediotransporte=t_mediotransporte.idmediotransporte
LEFT JOIN t_transportista ON t_embarque.idtransportista=t_transportista.idtransportista
LEFT JOIN t_ciudad AS t_ciudadsalidad ON t_embarque.idsalida=t_ciudadsalidad.idciudad
LEFT JOIN t_ciudad AS t_ciudadarribo ON t_embarque.idarribo=t_ciudadarribo.idciudad
LEFT JOIN t_ciudad AS t_ciudadorigen ON t_embarque.idorigen=t_ciudadorigen.idciudad
LEFT JOIN t_ciudad AS t_ciudaddestino ON t_embarque.iddestino=t_ciudaddestino.idciudad
LEFT JOIN t_usuario ON t_embarque.idusuario=t_usuario.idusuario
WHERE t_embarque.gestion>=2021
AND t_cliente.idempresa=1;	

ALTER TABLE pbi_logistico ADD PRIMARY KEY(idembarque);

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for DashBoardSalidas
-- ----------------------------
DROP PROCEDURE IF EXISTS `DashBoardSalidas`;
delimiter ;;
CREATE PROCEDURE `DashBoardSalidas`()
BEGIN

	DROP TEMPORARY TABLE IF EXISTS tmp_ultimaubicacion;
  CREATE TEMPORARY TABLE tmp_ultimaubicacion (idubicacionitem INT, idingresodetalle INT);
  INSERT INTO tmp_ultimaubicacion (idubicacionitem, idingresodetalle)
        SELECT
        MAX(idubicacionitem) as idubicacionitem,
        idingresodetalle
        FROM
        t_ubicacionitem
        GROUP BY
        idingresodetalle;
  ALTER TABLE tmp_ultimaubicacion ADD INDEX idubicacionitem (idubicacionitem);
  ALTER TABLE tmp_ultimaubicacion ADD INDEX idingresodetalle (idingresodetalle);

	#Routine body goes here...
	DROP TABLE IF EXISTS pbi_salidas;
	CREATE TABLE IF NOT EXISTS pbi_salidas AS
	SELECT
	t_salidadetalle.idsalidadetalle,
	t_almacen.almacen,
        t_cliente.cliente,
        CONCAT(t_salida.numero,'/',t_salida.gestion) as numerosalida,
        CONCAT(t_salida.fecha,' 12:00:00') as fechasalida,
        t_salida.solicitado_por,
        t_salida.autorizado_por,
        t_salida.delivery_note,
        t_salida.proyecto_no,
        t_salida.contrato_no,
        t_salida.rubro_producto,
        t_salida.ciudad,
        t_salida.direccion_entrega,
        t_salida.transporte,
        t_salida.placa,
        t_salida.cantidad_pallet,
        t_salida.cantidad_cajas,
        t_salida.autorizacion_compra,
        t_salida.hora_inicio_a,
        t_salida.hora_fin_a,
        t_salida.cantidad_estibadores_a,
        t_salida.nota_adicional,
        t_salida.hora_inicio_b,
        t_salida.hora_fin_b,
        t_salida.cantidad_estibadores_b,
        t_usuario.nombre as nombre_entrega,
        t_usuario.ci as ci_entrega,
        t_salida.nombre_recibido,
        t_salida.ci_recibido,
        t_salida.empresa_recibido,
        CONCAT(t_salida.fecha_recibido,' 12:00:00') as fecha_recibido,
        CASE ifnull(t_salida.entrega_a_tiempo,0)
            WHEN 0 THEN 'NO'
            WHEN 1 THEN 'SI'
        END as entrega_a_tiempo,
        CASE ifnull(t_salida.entrega_completa_conforme,0)
            WHEN 0 THEN 'NO'
            WHEN 1 THEN 'SI'
        END as entrega_completa_conforme,
        t_ingresodetalle.codigo,
        t_ingresodetalle.serie,
        t_ingresodetalle.descripcion,
        t_ingresodetalle.categoria,
        t_salidadetalle.cantidad,
        t_embalaje.codigoembalaje,
        t_ingresodetalle.lote,
        t_merma.merma,
        CONCAT(t_ingresodetalle.fechavencimiento,' 12:00:00') as fechavencimiento,
        t_salidadetalle.bultos,
        t_salidadetalle.cantidad_no_conf,
        t_no_conf.no_conf,
        t_salidadetalle.peso,
        t_salidadetalle.temperatura,
        t_salidadetalle.observaciones,
        CONCAT(t_ingreso.numero,'/',t_ingreso.gestion) as numeroingreso,
        t_ingreso.fecha as fechaingreso,
        t_almacendetalle.nombre as ubicacionalmacen
        FROM
        t_salida
				LEFT JOIN t_almacen ON t_salida.idalmacen=t_almacen.idalmacen
        LEFT JOIN t_cliente ON t_salida.idcliente=t_cliente.idcliente
        LEFT JOIN t_usuario ON t_salida.idusuario_entrega=t_usuario.idusuario
        LEFT JOIN t_salidadetalle ON t_salida.idsalida=t_salidadetalle.idsalida
        LEFT JOIN t_ingresodetalle ON t_salidadetalle.idingresodetalle=t_ingresodetalle.idingresodetalle
        LEFT JOIN t_embalaje ON t_ingresodetalle.idembalaje=t_embalaje.idembalaje
        LEFT JOIN t_no_conf ON t_ingresodetalle.idno_conf=t_no_conf.idno_conf
        LEFT JOIN t_ingreso ON t_ingresodetalle.idingreso=t_ingreso.idingreso
        LEFT JOIN tmp_ultimaubicacion ON t_ingresodetalle.idingresodetalle=tmp_ultimaubicacion.idingresodetalle
        LEFT JOIN t_ubicacionitem ON tmp_ultimaubicacion.idubicacionitem=t_ubicacionitem.idubicacionitem
        LEFT JOIN t_almacendetalle ON t_ubicacionitem.idalmacendetalle=t_almacendetalle.idalmacendetalle
        LEFT JOIN t_merma ON t_ingresodetalle.idmerma=t_merma.idmerma
        WHERE
        IFNULL(t_salida.finalizado,0)=1
        AND t_cliente.idempresa=1;
			
			ALTER TABLE pbi_salidas ADD PRIMARY KEY (idsalidadetalle);

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for DashBoardVencimientos
-- ----------------------------
DROP PROCEDURE IF EXISTS `DashBoardVencimientos`;
delimiter ;;
CREATE PROCEDURE `DashBoardVencimientos`()
BEGIN
	#Routine body goes here...
	
	
	DROP TEMPORARY TABLE IF EXISTS tmp_salidas;
	CREATE TEMPORARY TABLE tmp_salidas (idingresodetalle INT, cantidad DECIMAL(13,2));
	INSERT INTO tmp_salidas (idingresodetalle, cantidad)
		SELECT
		t_salidadetalle.idingresodetalle,
		SUM(t_salidadetalle.cantidad)
		FROM
		t_salidadetalle
		LEFT JOIN t_salida ON t_salidadetalle.idsalida=t_salida.idsalida
		WHERE
		IFNULL(t_salida.finalizado,0)=1
		GROUP BY
		t_salidadetalle.idingresodetalle;
                
	ALTER TABLE tmp_salidas ADD INDEX idingresodetalle (idingresodetalle);

	DROP TABLE IF EXISTS pbi_vencimientos;
	CREATE TABLE IF NOT EXISTS pbi_vencimientos AS

	SELECT
	t_ingreso.idalmacen,
	t_ingreso.idcliente,
	t_almacen.almacen,
	t_cliente.cliente,
	t_ingresodetalle.codigo,
	t_ingresodetalle.serie,
	t_ingresodetalle.descripcion,
	t_ingresodetalle.cantidad-IFNULL(tmp_salidas.cantidad,0) as cantidadactual,
	t_embalaje.codigoembalaje,
	DATE_FORMAT(t_ingreso.fecha,'%d/%m/%Y') as fechaingreso,
	DATE_FORMAT(t_ingresodetalle.fechavencimiento,'%d/%m/%Y') as fechavencimiento,
	t_ingresodetalle.lote,
	DATEDIFF(t_ingresodetalle.fechavencimiento,CURRENT_DATE()) as diasvencimiento,
	IF(DATEDIFF(t_ingresodetalle.fechavencimiento,CURRENT_DATE())<0,'VENCIDO',IF(DATEDIFF(t_ingresodetalle.fechavencimiento,CURRENT_DATE())>=0 AND DATEDIFF(t_ingresodetalle.fechavencimiento,CURRENT_DATE())<=45,'POR VENCER',IF(t_ingresodetalle.cantidad-IFNULL(tmp_salidas.cantidad,0)=0,'SIN STOCK',IF(t_ingresodetalle.cantidad-IFNULL(tmp_salidas.cantidad,0)>0 AND t_ingresodetalle.cantidad-IFNULL(tmp_salidas.cantidad,0)<50,'ULTIMOS EN STOCK','OK')))) as estado,
	IF(DATEDIFF(t_ingresodetalle.fechavencimiento,CURRENT_DATE())<0,'#ff0000',IF(DATEDIFF(t_ingresodetalle.fechavencimiento,CURRENT_DATE())>=0 AND DATEDIFF(t_ingresodetalle.fechavencimiento,CURRENT_DATE())<=45,'#ffff00',   IF(t_ingresodetalle.cantidad-IFNULL(tmp_salidas.cantidad,0)=0,'#76943d',  IF(t_ingresodetalle.cantidad-IFNULL(tmp_salidas.cantidad,0)>0 AND t_ingresodetalle.cantidad-IFNULL(tmp_salidas.cantidad,0)<50,'#0070c0','#ffffff')))) as color,
	t_ingresodetalle.idingresodetalle
	FROM 
	t_ingresodetalle
	LEFT JOIN t_embalaje ON t_ingresodetalle.idembalaje=t_embalaje.idembalaje
	LEFT JOIN t_ingreso ON t_ingresodetalle.idingreso=t_ingreso.idingreso
	LEFT JOIN tmp_salidas ON t_ingresodetalle.idingresodetalle=tmp_salidas.idingresodetalle
	LEFT JOIN t_almacen ON t_ingreso.idalmacen=t_almacen.idalmacen
	LEFT JOIN t_cliente ON t_ingreso.idcliente=t_cliente.idcliente

	WHERE
	IFNULL(t_ingresodetalle.dividido,0)=0
	ORDER BY
	t_ingresodetalle.codigo
  AND t_cliente.idempresa=1;
	
	ALTER TABLE pbi_vencimientos ADD PRIMARY KEY (idingresodetalle);

END
;;
delimiter ;

-- ----------------------------
-- Function structure for Descarguio
-- ----------------------------
DROP FUNCTION IF EXISTS `Descarguio`;
delimiter ;;
CREATE FUNCTION `Descarguio`(idfacturaint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorcargado DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) INTO valorcargado
	FROM
	t_cargo
  LEFT JOIN t_embarque ON t_cargo.idembarque=t_embarque.idembarque
	LEFT JOIN t_concepto ON t_cargo.idconcepto=t_concepto.idconcepto
	LEFT JOIN t_factura ON t_cargo.idfacturanotadebito=t_factura.idfactura AND t_cargo.idtipofacturanotadebito=1
	LEFT JOIN t_notadebito ON t_cargo.idfacturanotadebito=t_notadebito.idnotadebito AND t_cargo.idtipofacturanotadebito=2
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()))
	WHERE 
	t_factura.idfactura=idfacturaint 
	AND IFNULL(t_cargo.esagente,0)=0
	AND IFNULL(t_cargo.idconcepto,0)=198
  AND ifnull(t_factura.idestadofactura,0) <> 2;

	RETURN ifnull(valorcargado,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for diasmorafactura
-- ----------------------------
DROP FUNCTION IF EXISTS `diasmorafactura`;
delimiter ;;
CREATE FUNCTION `diasmorafactura`(idfacturaint INT)
 RETURNS int
BEGIN
	DECLARE diasmora INT;
	
	SELECT
	DATEDIFF(CURRENT_DATE(),DATE_ADD(t_factura.fecha, INTERVAL CASE t_factura.idcobraratipo WHEN 1 THEN IFNULL(t_cliente.plazo,0) WHEN 2 THEN IFNULL(t_proveedor.plazo,0) WHEN 3 THEN IFNULL(t_prestador.plazo,0) WHEN 4 THEN IFNULL(t_transportista.plazo,0) WHEN 5 THEN IFNULL(t_agentecarga.plazo,0) END DAY)) INTO diasmora
                
	FROM
	t_factura
	LEFT JOIN t_cliente ON t_factura.idcobrara=t_cliente.idcliente
	LEFT JOIN t_proveedor ON t_factura.idcobrara=t_proveedor.idproveedor
	LEFT JOIN t_prestador ON t_factura.idcobrara=t_prestador.idprestador
	LEFT JOIN t_transportista ON t_factura.idcobrara=t_transportista.idtransportista
	LEFT JOIN t_agentecarga ON t_factura.idcobrara=t_agentecarga.idagentecarga
	WHERE
	t_factura.idfactura=idfacturaint;


	RETURN diasmora;
END
;;
delimiter ;

-- ----------------------------
-- Function structure for DistribucionUrbano
-- ----------------------------
DROP FUNCTION IF EXISTS `DistribucionUrbano`;
delimiter ;;
CREATE FUNCTION `DistribucionUrbano`(idfacturaint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorcargado DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) INTO valorcargado
	FROM
	t_cargo
  LEFT JOIN t_embarque ON t_cargo.idembarque=t_embarque.idembarque
	LEFT JOIN t_concepto ON t_cargo.idconcepto=t_concepto.idconcepto
	LEFT JOIN t_factura ON t_cargo.idfacturanotadebito=t_factura.idfactura AND t_cargo.idtipofacturanotadebito=1
	LEFT JOIN t_notadebito ON t_cargo.idfacturanotadebito=t_notadebito.idnotadebito AND t_cargo.idtipofacturanotadebito=2
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()))
	WHERE 
	t_factura.idfactura=idfacturaint 
	AND IFNULL(t_cargo.esagente,0)=0
	AND IFNULL(t_cargo.idconcepto,0)=200
  AND ifnull(t_factura.idestadofactura,0) <> 2;

	RETURN ifnull(valorcargado,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for Estibaje
-- ----------------------------
DROP FUNCTION IF EXISTS `Estibaje`;
delimiter ;;
CREATE FUNCTION `Estibaje`(idfacturaint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorcargado DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) INTO valorcargado
	FROM
	t_cargo
  LEFT JOIN t_embarque ON t_cargo.idembarque=t_embarque.idembarque
	LEFT JOIN t_concepto ON t_cargo.idconcepto=t_concepto.idconcepto
	LEFT JOIN t_factura ON t_cargo.idfacturanotadebito=t_factura.idfactura AND t_cargo.idtipofacturanotadebito=1
	LEFT JOIN t_notadebito ON t_cargo.idfacturanotadebito=t_notadebito.idnotadebito AND t_cargo.idtipofacturanotadebito=2
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()))
	WHERE 
	t_factura.idfactura=idfacturaint 
	AND IFNULL(t_cargo.esagente,0)=0
	AND IFNULL(t_cargo.idconcepto,0)=201
  AND ifnull(t_factura.idestadofactura,0) <> 2;

	RETURN ifnull(valorcargado,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for INBOUND
-- ----------------------------
DROP FUNCTION IF EXISTS `INBOUND`;
delimiter ;;
CREATE FUNCTION `INBOUND`(idfacturaint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorcargado DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) INTO valorcargado
	FROM
	t_cargo
  LEFT JOIN t_embarque ON t_cargo.idembarque=t_embarque.idembarque
	LEFT JOIN t_concepto ON t_cargo.idconcepto=t_concepto.idconcepto
	LEFT JOIN t_factura ON t_cargo.idfacturanotadebito=t_factura.idfactura AND t_cargo.idtipofacturanotadebito=1
	LEFT JOIN t_notadebito ON t_cargo.idfacturanotadebito=t_notadebito.idnotadebito AND t_cargo.idtipofacturanotadebito=2
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()))
	WHERE 
	t_factura.idfactura=idfacturaint 
	AND IFNULL(t_cargo.esagente,0)=0
	AND IFNULL(t_cargo.idconcepto,0)=202
  AND ifnull(t_factura.idestadofactura,0) <> 2;

	RETURN ifnull(valorcargado,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for invoicefactura
-- ----------------------------
DROP FUNCTION IF EXISTS `invoicefactura`;
delimiter ;;
CREATE FUNCTION `invoicefactura`(idfacturaint INT)
 RETURNS varchar(100) CHARSET utf8mb3
BEGIN
	DECLARE numeroinvoice VARCHAR(100);

	SELECT
	GROUP_CONCAT(DISTINCT CONCAT(t_invoice.numero,'/',t_invoice.gestion) SEPARATOR ', ') INTO numeroinvoice
	FROM
	t_cargo
	LEFT JOIN t_invoice ON t_cargo.idinvoice=t_invoice.idinvoice
	WHERE
	t_cargo.idfacturanotadebito=idfacturaint AND 
	t_cargo.idtipofacturanotadebito=1
	AND IFNULL(t_invoice.idinvoice,0)>0;

	RETURN ifnull(numeroinvoice,'');
END
;;
delimiter ;

-- ----------------------------
-- Function structure for invoicenotadebito
-- ----------------------------
DROP FUNCTION IF EXISTS `invoicenotadebito`;
delimiter ;;
CREATE FUNCTION `invoicenotadebito`(idnotadebitoint INT)
 RETURNS varchar(100) CHARSET utf8mb3
BEGIN
		DECLARE numeroinvoice VARCHAR(100);

		SELECT
		GROUP_CONCAT(DISTINCT CONCAT(t_invoice.numero,'/',t_invoice.gestion) SEPARATOR ', ') INTO numeroinvoice
		FROM
		t_cargo
		LEFT JOIN t_invoice ON t_cargo.idinvoice=t_invoice.idinvoice
		WHERE
		t_cargo.idfacturanotadebito=idnotadebitoint AND 
		t_cargo.idtipofacturanotadebito=2
		AND IFNULL(t_invoice.idinvoice,0)>0;

		RETURN ifnull(numeroinvoice,'');
END
;;
delimiter ;

-- ----------------------------
-- Function structure for OUTBOUND
-- ----------------------------
DROP FUNCTION IF EXISTS `OUTBOUND`;
delimiter ;;
CREATE FUNCTION `OUTBOUND`(idfacturaint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorcargado DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) INTO valorcargado
	FROM
	t_cargo
  LEFT JOIN t_embarque ON t_cargo.idembarque=t_embarque.idembarque
	LEFT JOIN t_concepto ON t_cargo.idconcepto=t_concepto.idconcepto
	LEFT JOIN t_factura ON t_cargo.idfacturanotadebito=t_factura.idfactura AND t_cargo.idtipofacturanotadebito=1
	LEFT JOIN t_notadebito ON t_cargo.idfacturanotadebito=t_notadebito.idnotadebito AND t_cargo.idtipofacturanotadebito=2
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()))
	WHERE 
	t_factura.idfactura=idfacturaint 
	AND IFNULL(t_cargo.esagente,0)=0
	AND IFNULL(t_cargo.idconcepto,0)=203
  AND ifnull(t_factura.idestadofactura,0) <> 2;

	RETURN ifnull(valorcargado,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for PrePedido
-- ----------------------------
DROP FUNCTION IF EXISTS `PrePedido`;
delimiter ;;
CREATE FUNCTION `PrePedido`(idfacturaint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorcargado DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) INTO valorcargado
	FROM
	t_cargo
  LEFT JOIN t_embarque ON t_cargo.idembarque=t_embarque.idembarque
	LEFT JOIN t_concepto ON t_cargo.idconcepto=t_concepto.idconcepto
	LEFT JOIN t_factura ON t_cargo.idfacturanotadebito=t_factura.idfactura AND t_cargo.idtipofacturanotadebito=1
	LEFT JOIN t_notadebito ON t_cargo.idfacturanotadebito=t_notadebito.idnotadebito AND t_cargo.idtipofacturanotadebito=2
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()))
	WHERE 
	t_factura.idfactura=idfacturaint 
	AND IFNULL(t_cargo.esagente,0)=0
	AND IFNULL(t_cargo.idconcepto,0)=204
  AND ifnull(t_factura.idestadofactura,0) <> 2;

	RETURN ifnull(valorcargado,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for Reembalaje
-- ----------------------------
DROP FUNCTION IF EXISTS `Reembalaje`;
delimiter ;;
CREATE FUNCTION `Reembalaje`(idfacturaint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorcargado DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) INTO valorcargado
	FROM
	t_cargo
  LEFT JOIN t_embarque ON t_cargo.idembarque=t_embarque.idembarque
	LEFT JOIN t_concepto ON t_cargo.idconcepto=t_concepto.idconcepto
	LEFT JOIN t_factura ON t_cargo.idfacturanotadebito=t_factura.idfactura AND t_cargo.idtipofacturanotadebito=1
	LEFT JOIN t_notadebito ON t_cargo.idfacturanotadebito=t_notadebito.idnotadebito AND t_cargo.idtipofacturanotadebito=2
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()))
	WHERE 
	t_factura.idfactura=idfacturaint 
	AND IFNULL(t_cargo.esagente,0)=0
	AND IFNULL(t_cargo.idconcepto,0)=114
  AND ifnull(t_factura.idestadofactura,0) <> 2;

	RETURN ifnull(valorcargado,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for ServicioAlmacenaje
-- ----------------------------
DROP FUNCTION IF EXISTS `ServicioAlmacenaje`;
delimiter ;;
CREATE FUNCTION `ServicioAlmacenaje`(idfacturaint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorcargado DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) INTO valorcargado
	FROM
	t_cargo
  LEFT JOIN t_embarque ON t_cargo.idembarque=t_embarque.idembarque
	LEFT JOIN t_concepto ON t_cargo.idconcepto=t_concepto.idconcepto
	LEFT JOIN t_factura ON t_cargo.idfacturanotadebito=t_factura.idfactura AND t_cargo.idtipofacturanotadebito=1
	LEFT JOIN t_notadebito ON t_cargo.idfacturanotadebito=t_notadebito.idnotadebito AND t_cargo.idtipofacturanotadebito=2
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()))
	WHERE 
	t_factura.idfactura=idfacturaint 
	AND IFNULL(t_cargo.esagente,0)=0
	AND IFNULL(t_cargo.idconcepto,0)=5
  AND ifnull(t_factura.idestadofactura,0) <> 2;

	RETURN ifnull(valorcargado,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for ServicioEstibajeMercaderia
-- ----------------------------
DROP FUNCTION IF EXISTS `ServicioEstibajeMercaderia`;
delimiter ;;
CREATE FUNCTION `ServicioEstibajeMercaderia`(idfacturaint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorcargado DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) INTO valorcargado
	FROM
	t_cargo
  LEFT JOIN t_embarque ON t_cargo.idembarque=t_embarque.idembarque
	LEFT JOIN t_concepto ON t_cargo.idconcepto=t_concepto.idconcepto
	LEFT JOIN t_factura ON t_cargo.idfacturanotadebito=t_factura.idfactura AND t_cargo.idtipofacturanotadebito=1
	LEFT JOIN t_notadebito ON t_cargo.idfacturanotadebito=t_notadebito.idnotadebito AND t_cargo.idtipofacturanotadebito=2
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()))
	WHERE 
	t_factura.idfactura=idfacturaint 
	AND IFNULL(t_cargo.esagente,0)=0
	AND IFNULL(t_cargo.idconcepto,0)=4
  AND ifnull(t_factura.idestadofactura,0) <> 2;

	RETURN ifnull(valorcargado,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for ServicioFleteUrbano
-- ----------------------------
DROP FUNCTION IF EXISTS `ServicioFleteUrbano`;
delimiter ;;
CREATE FUNCTION `ServicioFleteUrbano`(idfacturaint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorcargado DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) INTO valorcargado
	FROM
	t_cargo
  LEFT JOIN t_embarque ON t_cargo.idembarque=t_embarque.idembarque
	LEFT JOIN t_concepto ON t_cargo.idconcepto=t_concepto.idconcepto
	LEFT JOIN t_factura ON t_cargo.idfacturanotadebito=t_factura.idfactura AND t_cargo.idtipofacturanotadebito=1
	LEFT JOIN t_notadebito ON t_cargo.idfacturanotadebito=t_notadebito.idnotadebito AND t_cargo.idtipofacturanotadebito=2
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()))
	WHERE 
	t_factura.idfactura=idfacturaint 
	AND IFNULL(t_cargo.esagente,0)=0
	AND IFNULL(t_cargo.idconcepto,0)=3
  AND ifnull(t_factura.idestadofactura,0) <> 2;

	RETURN ifnull(valorcargado,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for ServicioLogistico
-- ----------------------------
DROP FUNCTION IF EXISTS `ServicioLogistico`;
delimiter ;;
CREATE FUNCTION `ServicioLogistico`(idfacturaint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorcargado DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) INTO valorcargado
	FROM
	t_cargo
  LEFT JOIN t_embarque ON t_cargo.idembarque=t_embarque.idembarque
	LEFT JOIN t_concepto ON t_cargo.idconcepto=t_concepto.idconcepto
	LEFT JOIN t_factura ON t_cargo.idfacturanotadebito=t_factura.idfactura AND t_cargo.idtipofacturanotadebito=1
	LEFT JOIN t_notadebito ON t_cargo.idfacturanotadebito=t_notadebito.idnotadebito AND t_cargo.idtipofacturanotadebito=2
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()))
	WHERE 
	t_factura.idfactura=idfacturaint 
	AND IFNULL(t_cargo.esagente,0)=0
	AND IFNULL(t_cargo.idconcepto,0)=6
  AND ifnull(t_factura.idestadofactura,0) <> 2;

	RETURN ifnull(valorcargado,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for ServicioLogisticoInventario
-- ----------------------------
DROP FUNCTION IF EXISTS `ServicioLogisticoInventario`;
delimiter ;;
CREATE FUNCTION `ServicioLogisticoInventario`(idfacturaint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorcargado DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) INTO valorcargado
	FROM
	t_cargo
  LEFT JOIN t_embarque ON t_cargo.idembarque=t_embarque.idembarque
	LEFT JOIN t_concepto ON t_cargo.idconcepto=t_concepto.idconcepto
	LEFT JOIN t_factura ON t_cargo.idfacturanotadebito=t_factura.idfactura AND t_cargo.idtipofacturanotadebito=1
	LEFT JOIN t_notadebito ON t_cargo.idfacturanotadebito=t_notadebito.idnotadebito AND t_cargo.idtipofacturanotadebito=2
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()))
	WHERE 
	t_factura.idfactura=idfacturaint 
	AND IFNULL(t_cargo.esagente,0)=0
	AND IFNULL(t_cargo.idconcepto,0)=227
  AND ifnull(t_factura.idestadofactura,0) <> 2;

	RETURN ifnull(valorcargado,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for sp_workdaydiff
-- ----------------------------
DROP FUNCTION IF EXISTS `sp_workdaydiff`;
delimiter ;;
CREATE FUNCTION `sp_workdaydiff`(b date, a date)
 RETURNS int
  DETERMINISTIC
  COMMENT 'working day difference for 2 dates'
BEGIN
	DECLARE		freedays INT;
	
	SET freedays = 0;
  /*
	select
	COUNT(idferiado) INTO freedays
	FROM
	prm_feriado
	WHERE
	feriado BETWEEN a AND b;
  */
	
	SET @x = DATEDIFF( b, a );
	IF
		@x < 0 THEN
			
			SET @m = a;
		
		SET a = b;
		
		SET b = @m;
		
		SET @m = - 1;
		ELSE 
			SET @m = 1;
		
	END IF;
	
	SET @x = abs( @x ) + 1;
	
	SET @w1 = WEEKDAY( a )+ 1;
	
	SET @wx1 = 8- @w1;
	IF
		@w1 > 5 THEN
			
			SET @w1 = 0;
		ELSE 
			SET @w1 = 6- @w1;
		
	END IF;
	
	SET @wx2 = WEEKDAY( b )+ 1;
	
	SET @w2 = @wx2;
	IF
		@w2 > 5 THEN
			
			SET @w2 = 5;
		
	END IF;
	
	SET @weeks = ( @x - @wx1 - @wx2 )/ 7;
	
	SET @noweekends = ( @weeks * 5 )+ @w1 + @w2;
	
	SET @result = @noweekends - freedays;
	RETURN @result * @m;

END
;;
delimiter ;

-- ----------------------------
-- Function structure for tiempomorafactura
-- ----------------------------
DROP FUNCTION IF EXISTS `tiempomorafactura`;
delimiter ;;
CREATE FUNCTION `tiempomorafactura`(`idfacturaint` int)
 RETURNS varchar(50) CHARSET utf8mb3
BEGIN
	DECLARE tiempomoramora VARCHAR(50);
	
	SELECT
	CASE
		WHEN DATEDIFF(CURRENT_DATE(),DATE_ADD(t_factura.fecha,INTERVAL CASE t_factura.idcobraratipo WHEN 1 THEN IFNULL(t_cliente.plazo,0) WHEN 2 THEN IFNULL(t_proveedor.plazo,0) WHEN 3 THEN IFNULL(t_prestador.plazo,0) WHEN 4 THEN IFNULL(t_transportista.plazo,0) WHEN 5 THEN IFNULL(t_agentecarga.plazo,0) END DAY))<=0 THEN 'v'
		WHEN DATEDIFF(CURRENT_DATE(),DATE_ADD(t_factura.fecha,INTERVAL CASE t_factura.idcobraratipo WHEN 1 THEN IFNULL(t_cliente.plazo,0) WHEN 2 THEN IFNULL(t_proveedor.plazo,0) WHEN 3 THEN IFNULL(t_prestador.plazo,0) WHEN 4 THEN IFNULL(t_transportista.plazo,0) WHEN 5 THEN IFNULL(t_agentecarga.plazo,0) END DAY))>=1 AND DATEDIFF(CURRENT_DATE(),DATE_ADD(t_factura.fecha,INTERVAL CASE t_factura.idcobraratipo WHEN 1 THEN IFNULL(t_cliente.plazo,0) WHEN 2 THEN IFNULL(t_proveedor.plazo,0) WHEN 3 THEN IFNULL(t_prestador.plazo,0) WHEN 4 THEN IFNULL(t_transportista.plazo,0) WHEN 5 THEN IFNULL(t_agentecarga.plazo,0) END DAY))<=5 THEN 's_1_5'
		WHEN DATEDIFF(CURRENT_DATE(),DATE_ADD(t_factura.fecha,INTERVAL CASE t_factura.idcobraratipo WHEN 1 THEN IFNULL(t_cliente.plazo,0) WHEN 2 THEN IFNULL(t_proveedor.plazo,0) WHEN 3 THEN IFNULL(t_prestador.plazo,0) WHEN 4 THEN IFNULL(t_transportista.plazo,0) WHEN 5 THEN IFNULL(t_agentecarga.plazo,0) END DAY))>5 AND DATEDIFF(CURRENT_DATE(),DATE_ADD(t_factura.fecha,INTERVAL CASE t_factura.idcobraratipo WHEN 1 THEN IFNULL(t_cliente.plazo,0) WHEN 2 THEN IFNULL(t_proveedor.plazo,0) WHEN 3 THEN IFNULL(t_prestador.plazo,0) WHEN 4 THEN IFNULL(t_transportista.plazo,0) WHEN 5 THEN IFNULL(t_agentecarga.plazo,0) END DAY))<=30 THEN 's_5_30'
		WHEN DATEDIFF(CURRENT_DATE(),DATE_ADD(t_factura.fecha,INTERVAL CASE t_factura.idcobraratipo WHEN 1 THEN IFNULL(t_cliente.plazo,0) WHEN 2 THEN IFNULL(t_proveedor.plazo,0) WHEN 3 THEN IFNULL(t_prestador.plazo,0) WHEN 4 THEN IFNULL(t_transportista.plazo,0) WHEN 5 THEN IFNULL(t_agentecarga.plazo,0) END DAY))>30 and DATEDIFF(CURRENT_DATE(),DATE_ADD(t_factura.fecha,INTERVAL CASE t_factura.idcobraratipo WHEN 1 THEN IFNULL(t_cliente.plazo,0) WHEN 2 THEN IFNULL(t_proveedor.plazo,0) WHEN 3 THEN IFNULL(t_prestador.plazo,0) WHEN 4 THEN IFNULL(t_transportista.plazo,0) WHEN 5 THEN IFNULL(t_agentecarga.plazo,0) END DAY))<=60 then 's_30_60'
		WHEN DATEDIFF(CURRENT_DATE(),DATE_ADD(t_factura.fecha,INTERVAL CASE t_factura.idcobraratipo WHEN 1 THEN IFNULL(t_cliente.plazo,0) WHEN 2 THEN IFNULL(t_proveedor.plazo,0) WHEN 3 THEN IFNULL(t_prestador.plazo,0) WHEN 4 THEN IFNULL(t_transportista.plazo,0) WHEN 5 THEN IFNULL(t_agentecarga.plazo,0) END DAY))>60 THEN 's_60'
	
	/*
			WHEN (DATEDIFF(CURRENT_DATE(),DATE_ADD(t_factura.fecha, INTERVAL CASE t_factura.idcobraratipo WHEN 1 THEN IFNULL(t_cliente.plazo,0) WHEN 2 THEN IFNULL(t_proveedor.plazo,0) WHEN 3 THEN IFNULL(t_prestador.plazo,0) WHEN 4 THEN IFNULL(t_transportista.plazo,0) WHEN 5 THEN IFNULL(t_agentecarga.plazo,0) END DAY)))<=5 THEN 's_1_5'
			WHEN (DATEDIFF(CURRENT_DATE(),DATE_ADD(t_factura.fecha, INTERVAL CASE t_factura.idcobraratipo WHEN 1 THEN IFNULL(t_cliente.plazo,0) WHEN 2 THEN IFNULL(t_proveedor.plazo,0) WHEN 3 THEN IFNULL(t_prestador.plazo,0) WHEN 4 THEN IFNULL(t_transportista.plazo,0) WHEN 5 THEN IFNULL(t_agentecarga.plazo,0) END DAY)))>5 AND (DATEDIFF(CURRENT_DATE(),DATE_ADD(t_factura.fecha, INTERVAL CASE t_factura.idcobraratipo WHEN 1 THEN IFNULL(t_cliente.plazo,0) WHEN 2 THEN IFNULL(t_proveedor.plazo,0) WHEN 3 THEN IFNULL(t_prestador.plazo,0) WHEN 4 THEN IFNULL(t_transportista.plazo,0) WHEN 5 THEN IFNULL(t_agentecarga.plazo,0) END DAY)))<=30 THEN 's_5_30'
			WHEN (DATEDIFF(CURRENT_DATE(),DATE_ADD(t_factura.fecha, INTERVAL CASE t_factura.idcobraratipo WHEN 1 THEN IFNULL(t_cliente.plazo,0) WHEN 2 THEN IFNULL(t_proveedor.plazo,0) WHEN 3 THEN IFNULL(t_prestador.plazo,0) WHEN 4 THEN IFNULL(t_transportista.plazo,0) WHEN 5 THEN IFNULL(t_agentecarga.plazo,0) END DAY)))>30 AND (DATEDIFF(CURRENT_DATE(),DATE_ADD(t_factura.fecha, INTERVAL CASE t_factura.idcobraratipo WHEN 1 THEN IFNULL(t_cliente.plazo,0) WHEN 2 THEN IFNULL(t_proveedor.plazo,0) WHEN 3 THEN IFNULL(t_prestador.plazo,0) WHEN 4 THEN IFNULL(t_transportista.plazo,0) WHEN 5 THEN IFNULL(t_agentecarga.plazo,0) END DAY)))<=60 THEN 's_30_60'
			WHEN (DATEDIFF(CURRENT_DATE(),DATE_ADD(t_factura.fecha, INTERVAL CASE t_factura.idcobraratipo WHEN 1 THEN IFNULL(t_cliente.plazo,0) WHEN 2 THEN IFNULL(t_proveedor.plazo,0) WHEN 3 THEN IFNULL(t_prestador.plazo,0) WHEN 4 THEN IFNULL(t_transportista.plazo,0) WHEN 5 THEN IFNULL(t_agentecarga.plazo,0) END DAY)))>60 THEN 's_60'
			*/
		END INTO tiempomoramora
		FROM
		t_factura
		-- LEFT JOIN t_cargo ON t_factura.idfactura=t_cargo.idfacturanotadebito
		-- LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND t_factura.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_factura.fecha)
		LEFT JOIN t_cliente ON t_factura.idcobrara=t_cliente.idcliente
	LEFT JOIN t_proveedor ON t_factura.idcobrara=t_proveedor.idproveedor
	LEFT JOIN t_prestador ON t_factura.idcobrara=t_prestador.idprestador
	LEFT JOIN t_transportista ON t_factura.idcobrara=t_transportista.idtransportista
	LEFT JOIN t_agentecarga ON t_factura.idcobrara=t_agentecarga.idagentecarga
                WHERE 
                -- IFNULL(t_cargo.idtipofacturanotadebito,0)=1
                -- AND t_factura.idestadofactura=1
                t_factura.idfactura=idfacturaint;
	

	RETURN tiempomoramora;
END
;;
delimiter ;

-- ----------------------------
-- Function structure for tiempomoranotadebito
-- ----------------------------
DROP FUNCTION IF EXISTS `tiempomoranotadebito`;
delimiter ;;
CREATE FUNCTION `tiempomoranotadebito`(`idnotadebitoint` int)
 RETURNS varchar(50) CHARSET utf8mb3
BEGIN
	DECLARE tiempomora VARCHAR(50);
	
	SELECT
	CASE
		WHEN DATEDIFF(CURRENT_DATE(),DATE_ADD(t_notadebito.fecha,INTERVAL CASE t_notadebito.idcobraratipo WHEN 1 THEN IFNULL(t_cliente.plazo,0) WHEN 2 THEN IFNULL(t_proveedor.plazo,0) WHEN 3 THEN IFNULL(t_prestador.plazo,0) WHEN 4 THEN IFNULL(t_transportista.plazo,0) WHEN 5 THEN IFNULL(t_agentecarga.plazo,0) END DAY))<=0 THEN 'v'
		WHEN DATEDIFF(CURRENT_DATE(),DATE_ADD(t_notadebito.fecha,INTERVAL CASE t_notadebito.idcobraratipo WHEN 1 THEN IFNULL(t_cliente.plazo,0) WHEN 2 THEN IFNULL(t_proveedor.plazo,0) WHEN 3 THEN IFNULL(t_prestador.plazo,0) WHEN 4 THEN IFNULL(t_transportista.plazo,0) WHEN 5 THEN IFNULL(t_agentecarga.plazo,0) END DAY))>=1 AND DATEDIFF(CURRENT_DATE(),DATE_ADD(t_notadebito.fecha,INTERVAL CASE t_notadebito.idcobraratipo WHEN 1 THEN IFNULL(t_cliente.plazo,0) WHEN 2 THEN IFNULL(t_proveedor.plazo,0) WHEN 3 THEN IFNULL(t_prestador.plazo,0) WHEN 4 THEN IFNULL(t_transportista.plazo,0) WHEN 5 THEN IFNULL(t_agentecarga.plazo,0) END DAY))<=5 THEN 's_1_5'
		WHEN DATEDIFF(CURRENT_DATE(),DATE_ADD(t_notadebito.fecha,INTERVAL CASE t_notadebito.idcobraratipo WHEN 1 THEN IFNULL(t_cliente.plazo,0) WHEN 2 THEN IFNULL(t_proveedor.plazo,0) WHEN 3 THEN IFNULL(t_prestador.plazo,0) WHEN 4 THEN IFNULL(t_transportista.plazo,0) WHEN 5 THEN IFNULL(t_agentecarga.plazo,0) END DAY))>5 AND DATEDIFF(CURRENT_DATE(),DATE_ADD(t_notadebito.fecha,INTERVAL CASE t_notadebito.idcobraratipo WHEN 1 THEN IFNULL(t_cliente.plazo,0) WHEN 2 THEN IFNULL(t_proveedor.plazo,0) WHEN 3 THEN IFNULL(t_prestador.plazo,0) WHEN 4 THEN IFNULL(t_transportista.plazo,0) WHEN 5 THEN IFNULL(t_agentecarga.plazo,0) END DAY))<=30 THEN 's_5_30'
		WHEN DATEDIFF(CURRENT_DATE(),DATE_ADD(t_notadebito.fecha,INTERVAL CASE t_notadebito.idcobraratipo WHEN 1 THEN IFNULL(t_cliente.plazo,0) WHEN 2 THEN IFNULL(t_proveedor.plazo,0) WHEN 3 THEN IFNULL(t_prestador.plazo,0) WHEN 4 THEN IFNULL(t_transportista.plazo,0) WHEN 5 THEN IFNULL(t_agentecarga.plazo,0) END DAY))>30 and DATEDIFF(CURRENT_DATE(),DATE_ADD(t_notadebito.fecha,INTERVAL CASE t_notadebito.idcobraratipo WHEN 1 THEN IFNULL(t_cliente.plazo,0) WHEN 2 THEN IFNULL(t_proveedor.plazo,0) WHEN 3 THEN IFNULL(t_prestador.plazo,0) WHEN 4 THEN IFNULL(t_transportista.plazo,0) WHEN 5 THEN IFNULL(t_agentecarga.plazo,0) END DAY))<=60 then 's_30_60'
		WHEN DATEDIFF(CURRENT_DATE(),DATE_ADD(t_notadebito.fecha,INTERVAL CASE t_notadebito.idcobraratipo WHEN 1 THEN IFNULL(t_cliente.plazo,0) WHEN 2 THEN IFNULL(t_proveedor.plazo,0) WHEN 3 THEN IFNULL(t_prestador.plazo,0) WHEN 4 THEN IFNULL(t_transportista.plazo,0) WHEN 5 THEN IFNULL(t_agentecarga.plazo,0) END DAY))>60 THEN 's_60'
		/*
	
	CASE
			WHEN (DATEDIFF(CURRENT_DATE(),DATE_ADD(t_notadebito.fecha, INTERVAL CASE t_notadebito.idcobraratipo WHEN 1 THEN IFNULL(t_cliente.plazo,0) WHEN 2 THEN IFNULL(t_proveedor.plazo,0) WHEN 3 THEN IFNULL(t_prestador.plazo,0) WHEN 4 THEN IFNULL(t_transportista.plazo,0) WHEN 5 THEN IFNULL(t_agentecarga.plazo,0) END DAY)))<=5 THEN 's_1_5'
			WHEN (DATEDIFF(CURRENT_DATE(),DATE_ADD(t_notadebito.fecha, INTERVAL CASE t_notadebito.idcobraratipo WHEN 1 THEN IFNULL(t_cliente.plazo,0) WHEN 2 THEN IFNULL(t_proveedor.plazo,0) WHEN 3 THEN IFNULL(t_prestador.plazo,0) WHEN 4 THEN IFNULL(t_transportista.plazo,0) WHEN 5 THEN IFNULL(t_agentecarga.plazo,0) END DAY)))>5 AND (DATEDIFF(CURRENT_DATE(),DATE_ADD(t_notadebito.fecha, INTERVAL CASE t_notadebito.idcobraratipo WHEN 1 THEN IFNULL(t_cliente.plazo,0) WHEN 2 THEN IFNULL(t_proveedor.plazo,0) WHEN 3 THEN IFNULL(t_prestador.plazo,0) WHEN 4 THEN IFNULL(t_transportista.plazo,0) WHEN 5 THEN IFNULL(t_agentecarga.plazo,0) END DAY)))<=30 THEN 's_5_30'
			WHEN (DATEDIFF(CURRENT_DATE(),DATE_ADD(t_notadebito.fecha, INTERVAL CASE t_notadebito.idcobraratipo WHEN 1 THEN IFNULL(t_cliente.plazo,0) WHEN 2 THEN IFNULL(t_proveedor.plazo,0) WHEN 3 THEN IFNULL(t_prestador.plazo,0) WHEN 4 THEN IFNULL(t_transportista.plazo,0) WHEN 5 THEN IFNULL(t_agentecarga.plazo,0) END DAY)))>30 AND (DATEDIFF(CURRENT_DATE(),DATE_ADD(t_notadebito.fecha, INTERVAL CASE t_notadebito.idcobraratipo WHEN 1 THEN IFNULL(t_cliente.plazo,0) WHEN 2 THEN IFNULL(t_proveedor.plazo,0) WHEN 3 THEN IFNULL(t_prestador.plazo,0) WHEN 4 THEN IFNULL(t_transportista.plazo,0) WHEN 5 THEN IFNULL(t_agentecarga.plazo,0) END DAY)))<=60 THEN 's_30_60'
			WHEN (DATEDIFF(CURRENT_DATE(),DATE_ADD(t_notadebito.fecha, INTERVAL CASE t_notadebito.idcobraratipo WHEN 1 THEN IFNULL(t_cliente.plazo,0) WHEN 2 THEN IFNULL(t_proveedor.plazo,0) WHEN 3 THEN IFNULL(t_prestador.plazo,0) WHEN 4 THEN IFNULL(t_transportista.plazo,0) WHEN 5 THEN IFNULL(t_agentecarga.plazo,0) END DAY)))>60 THEN 's_60'
			*/
		END INTO tiempomora
		FROM
		t_notadebito
		-- LEFT JOIN t_cargo ON t_factura.idfactura=t_cargo.idfacturanotadebito
		-- LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND t_factura.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_factura.fecha)
		LEFT JOIN t_cliente ON t_notadebito.idcobrara=t_cliente.idcliente
	LEFT JOIN t_proveedor ON t_notadebito.idcobrara=t_proveedor.idproveedor
	LEFT JOIN t_prestador ON t_notadebito.idcobrara=t_prestador.idprestador
	LEFT JOIN t_transportista ON t_notadebito.idcobrara=t_transportista.idtransportista
	LEFT JOIN t_agentecarga ON t_notadebito.idcobrara=t_agentecarga.idagentecarga
                WHERE 
                -- IFNULL(t_cargo.idtipofacturanotadebito,0)=1
                -- AND t_factura.idestadofactura=1
                t_notadebito.idnotadebito=idnotadebitoint;
	

	RETURN tiempomora;
END
;;
delimiter ;

-- ----------------------------
-- Function structure for valopagoagenteexterior
-- ----------------------------
DROP FUNCTION IF EXISTS `valopagoagenteexterior`;
delimiter ;;
CREATE FUNCTION `valopagoagenteexterior`(idembarqueint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valopagoagenteexterior DECIMAL(13,2);

	SELECT
    t_costo.cantidad*t_costo.monto*t_tipocambio.tipocambio INTO valopagoagenteexterior
    FROM
    t_facturapago
    LEFT JOIN t_costo ON t_facturapago.idfacturapago=t_costo.idfacturanotadebito
    LEFT JOIN t_tipocambio ON t_costo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND t_facturapago.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_facturapago.fecha)
    WHERE
    t_facturapago.idembarque=idembarqueint
    AND IFNULL(t_costo.idtipofacturanotadebito,0)=1;

	RETURN ifnull(valopagoagenteexterior,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for valoragenteeembarqueus
-- ----------------------------
DROP FUNCTION IF EXISTS `valoragenteeembarqueus`;
delimiter ;;
CREATE FUNCTION `valoragenteeembarqueus`(idembarqueint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valoragentee DECIMAL(13,2);

	SELECT
	SUM(t_costo.cantidad*t_costo.monto*t_tipocambio.tipocambio) INTO valoragentee
	FROM
	t_costo
	LEFT JOIN t_facturapago ON t_costo.idfacturanotadebito=t_facturapago.idfacturapago
	LEFT JOIN t_tipocambio ON t_costo.iddivisa=t_tipocambio.iddivisaorigen AND 2=t_tipocambio.iddivisadestino AND t_facturapago.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_facturapago.fecha)
	WHERE 
	t_facturapago.idembarque=idembarqueint
		AND t_facturapago.idtipofacturapago=2
	AND IFNULL(t_costo.idtipofacturanotadebito,0)=1;

	RETURN ifnull(valoragentee,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for valoraplicado
-- ----------------------------
DROP FUNCTION IF EXISTS `valoraplicado`;
delimiter ;;
CREATE FUNCTION `valoraplicado`(idanticipoint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valoraplicado DECIMAL(13,2);

	SELECT 
	SUM(monto) INTO valoraplicado
	FROM 
	t_cobro
	WHERE idanticipo=idanticipoint;

	RETURN ifnull(valoraplicado,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for valorcargado
-- ----------------------------
DROP FUNCTION IF EXISTS `valorcargado`;
delimiter ;;
CREATE FUNCTION `valorcargado`(idembarqueint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorcargo DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) INTO valorcargo
	FROM
	t_cargo
  LEFT JOIN t_embarque ON t_cargo.idembarque=t_embarque.idembarque
	LEFT JOIN t_factura ON t_cargo.idfacturanotadebito=t_factura.idfactura AND t_cargo.idtipofacturanotadebito=1
	LEFT JOIN t_notadebito ON t_cargo.idfacturanotadebito=t_notadebito.idnotadebito AND t_cargo.idtipofacturanotadebito=2
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()))
	WHERE 
	t_embarque.idembarque=idembarqueint 
  AND ifnull(t_factura.idestadofactura,0) <> 2;

	RETURN ifnull(valorcargo,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for valorcargadoagente
-- ----------------------------
DROP FUNCTION IF EXISTS `valorcargadoagente`;
delimiter ;;
CREATE FUNCTION `valorcargadoagente`(idembarqueint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorcargo DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) INTO valorcargo
	FROM
	t_cargo
  LEFT JOIN t_embarque ON t_cargo.idembarque=t_embarque.idembarque
	LEFT JOIN t_factura ON t_cargo.idfacturanotadebito=t_factura.idfactura AND t_cargo.idtipofacturanotadebito=1
	LEFT JOIN t_notadebito ON t_cargo.idfacturanotadebito=t_notadebito.idnotadebito AND t_cargo.idtipofacturanotadebito=2
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()))
	WHERE 
	t_embarque.idembarque=idembarqueint 
	AND IFNULL(t_cargo.esagente,0)=1
  AND ifnull(t_factura.idestadofactura,0) <> 2;

	RETURN ifnull(valorcargo,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for valorcargadocliente
-- ----------------------------
DROP FUNCTION IF EXISTS `valorcargadocliente`;
delimiter ;;
CREATE FUNCTION `valorcargadocliente`(idembarqueint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorcargo DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) INTO valorcargo
	FROM
	t_cargo
  LEFT JOIN t_embarque ON t_cargo.idembarque=t_embarque.idembarque
	LEFT JOIN t_factura ON t_cargo.idfacturanotadebito=t_factura.idfactura AND t_cargo.idtipofacturanotadebito=1
	LEFT JOIN t_notadebito ON t_cargo.idfacturanotadebito=t_notadebito.idnotadebito AND t_cargo.idtipofacturanotadebito=2
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()))
	WHERE 
	t_embarque.idembarque=idembarqueint 
	AND IFNULL(t_cargo.esagente,0)=0
  AND ifnull(t_factura.idestadofactura,0) <> 2;

	RETURN ifnull(valorcargo,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for valorcargadoclientefleteint
-- ----------------------------
DROP FUNCTION IF EXISTS `valorcargadoclientefleteint`;
delimiter ;;
CREATE FUNCTION `valorcargadoclientefleteint`(idembarqueint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorcargado DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) INTO valorcargado
	FROM
	t_cargo
  LEFT JOIN t_embarque ON t_cargo.idembarque=t_embarque.idembarque
	LEFT JOIN t_concepto ON t_cargo.idconcepto=t_concepto.idconcepto
	LEFT JOIN t_factura ON t_cargo.idfacturanotadebito=t_factura.idfactura AND t_cargo.idtipofacturanotadebito=1
	LEFT JOIN t_notadebito ON t_cargo.idfacturanotadebito=t_notadebito.idnotadebito AND t_cargo.idtipofacturanotadebito=2
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()))
	WHERE 
	t_embarque.idembarque=idembarqueint 
	AND IFNULL(t_cargo.esagente,0)=0
	AND IFNULL(t_cargo.idconcepto,0)=17
  AND ifnull(t_factura.idestadofactura,0) <> 2;

	RETURN ifnull(valorcargado,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for valorcobradofactura
-- ----------------------------
DROP FUNCTION IF EXISTS `valorcobradofactura`;
delimiter ;;
CREATE FUNCTION `valorcobradofactura`(idfacturaint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorcobradofactura DECIMAL(13,2);

	SELECT 
	SUM(monto) INTO valorcobradofactura
	FROM 
	t_cobro
	WHERE idfacturanotadebito=idfacturaint AND idtipocobro=1;

	RETURN ifnull(valorcobradofactura,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for valorcobradonotadebito
-- ----------------------------
DROP FUNCTION IF EXISTS `valorcobradonotadebito`;
delimiter ;;
CREATE FUNCTION `valorcobradonotadebito`(idnotadebitoint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorcobradonotadebito DECIMAL(13,2);

	SELECT 
	SUM(monto) INTO valorcobradonotadebito
	FROM 
	t_cobro
	WHERE idfacturanotadebito=idnotadebitoint AND idtipocobro=2;

	RETURN ifnull(valorcobradonotadebito,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for valorcobradonotadebitous
-- ----------------------------
DROP FUNCTION IF EXISTS `valorcobradonotadebitous`;
delimiter ;;
CREATE FUNCTION `valorcobradonotadebitous`(idnotadebitoint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorcobradonotadebito DECIMAL(13,2);

	SELECT 
	SUM(t_cobro.monto*t_tipocambio.tipocambio) INTO valorcobradonotadebito
	FROM 
	t_cobro
	LEFT JOIN t_tipocambio ON 1=t_tipocambio.iddivisaorigen AND 2=t_tipocambio.iddivisadestino AND t_cobro.fechapago BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_cobro.fechapago)
	WHERE t_cobro.idfacturanotadebito=idnotadebitoint AND t_cobro.idtipocobro=2;

	RETURN ifnull(valorcobradonotadebito,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for valorcosteado
-- ----------------------------
DROP FUNCTION IF EXISTS `valorcosteado`;
delimiter ;;
CREATE FUNCTION `valorcosteado`(idembarqueint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorcosto DECIMAL(13,2);

	SELECT
	SUM(t_costo.monto*t_costo.cantidad*t_tipocambio.tipocambio) INTO valorcosto
	FROM
	t_costo
  LEFT JOIN t_facturapago ON t_costo.idfacturanotadebito=t_facturapago.idfacturapago
  LEFT JOIN t_embarque ON t_costo.idembarque=t_embarque.idembarque
	LEFT JOIN t_tipocambio ON t_costo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(t_facturapago.fecha, CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(t_facturapago.fecha, CURRENT_DATE()))
	WHERE 
	t_embarque.idembarque=idembarqueint 
  AND ifnull(t_facturapago.idestadofacturapago,0) <> 2;

	RETURN ifnull(valorcosto,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for valorcosteadoagente
-- ----------------------------
DROP FUNCTION IF EXISTS `valorcosteadoagente`;
delimiter ;;
CREATE FUNCTION `valorcosteadoagente`(idembarqueint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorcosto DECIMAL(13,2);

	SELECT
	SUM(t_costo.monto*t_costo.cantidad*t_tipocambio.tipocambio) INTO valorcosto
	FROM
	t_costo
  LEFT JOIN t_facturapago ON t_costo.idfacturanotadebito=t_facturapago.idfacturapago
  LEFT JOIN t_embarque ON t_costo.idembarque=t_embarque.idembarque
	LEFT JOIN t_tipocambio ON t_costo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(t_facturapago.fecha, CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(t_facturapago.fecha, CURRENT_DATE()))
	WHERE 
	t_embarque.idembarque=idembarqueint 
	AND IFNULL(t_costo.esagente,0)=1
  AND ifnull(t_facturapago.idestadofacturapago,0) <> 2;

	RETURN ifnull(valorcosto,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for valorcosteadocliente
-- ----------------------------
DROP FUNCTION IF EXISTS `valorcosteadocliente`;
delimiter ;;
CREATE FUNCTION `valorcosteadocliente`(idembarqueint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorcosto DECIMAL(13,2);

	SELECT
	SUM(t_costo.monto*t_costo.cantidad*t_tipocambio.tipocambio) INTO valorcosto
	FROM
	t_costo
  LEFT JOIN t_facturapago ON t_costo.idfacturanotadebito=t_facturapago.idfacturapago
  LEFT JOIN t_embarque ON t_costo.idembarque=t_embarque.idembarque
	LEFT JOIN t_tipocambio ON t_costo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(t_facturapago.fecha, CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(t_facturapago.fecha, CURRENT_DATE()))
	WHERE 
	t_embarque.idembarque=idembarqueint 
	AND IFNULL(t_costo.esagente,0)=0
  AND ifnull(t_facturapago.idestadofacturapago,0) <> 2;

	RETURN ifnull(valorcosto,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for valorcosteadoclientefleteint
-- ----------------------------
DROP FUNCTION IF EXISTS `valorcosteadoclientefleteint`;
delimiter ;;
CREATE FUNCTION `valorcosteadoclientefleteint`(idembarqueint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorcosto DECIMAL(13,2);

	SELECT
	SUM(t_costo.monto*t_costo.cantidad*t_tipocambio.tipocambio) INTO valorcosto
	FROM
	t_costo
  LEFT JOIN t_facturapago ON t_costo.idfacturanotadebito=t_facturapago.idfacturapago
	LEFT JOIN t_concepto ON t_costo.idconcepto=t_concepto.idconcepto
  LEFT JOIN t_embarque ON t_costo.idembarque=t_embarque.idembarque
	LEFT JOIN t_tipocambio ON t_costo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(t_facturapago.fecha, CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(t_facturapago.fecha, CURRENT_DATE()))
	WHERE 
	t_embarque.idembarque=idembarqueint 
	AND IFNULL(t_costo.esagente,0)=0
		AND IFNULL(t_costo.idconcepto,0)=18
  AND ifnull(t_facturapago.idestadofacturapago,0) <> 2;

	RETURN ifnull(valorcosto,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for valordebitado
-- ----------------------------
DROP FUNCTION IF EXISTS `valordebitado`;
delimiter ;;
CREATE FUNCTION `valordebitado`(idnotadebitoint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valordebitado DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) INTO valordebitado
	FROM
	t_notadebito
	LEFT JOIN t_cargo ON t_notadebito.idnotadebito=t_cargo.idfacturanotadebito
	LEFT JOIN t_embarque ON t_notadebito.idembarque=t_embarque.idembarque
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND t_notadebito.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_notadebito.fecha) AND t_tipocambio.idempresa=t_embarque.idempresa
	WHERE 
	t_notadebito.idnotadebito=idnotadebitoint 
	AND IFNULL(t_cargo.idtipofacturanotadebito,0)=2;

	RETURN ifnull(valordebitado,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for valordebitadoembarque
-- ----------------------------
DROP FUNCTION IF EXISTS `valordebitadoembarque`;
delimiter ;;
CREATE FUNCTION `valordebitadoembarque`(idembarqueint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valordebitado DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) INTO valordebitado
	FROM
	t_notadebito
	LEFT JOIN t_cargo ON t_notadebito.idnotadebito=t_cargo.idfacturanotadebito
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND t_notadebito.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_notadebito.fecha)
	WHERE 
	t_notadebito.idembarque=idembarqueint 
	AND IFNULL(t_cargo.idtipofacturanotadebito,0)=2
	AND t_notadebito.idestadonotadebito=1;

	RETURN ifnull(valordebitado,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for valordebitadous
-- ----------------------------
DROP FUNCTION IF EXISTS `valordebitadous`;
delimiter ;;
CREATE FUNCTION `valordebitadous`(idnotadebitoint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valordebitado DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) INTO valordebitado
	FROM
	t_notadebito
	LEFT JOIN t_cargo ON t_notadebito.idnotadebito=t_cargo.idfacturanotadebito
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 2=t_tipocambio.iddivisadestino AND t_notadebito.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_notadebito.fecha)
	WHERE 
	t_notadebito.idnotadebito=idnotadebitoint 
	AND IFNULL(t_cargo.idtipofacturanotadebito,0)=2;

	RETURN ifnull(valordebitado,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for valordevuelto
-- ----------------------------
DROP FUNCTION IF EXISTS `valordevuelto`;
delimiter ;;
CREATE FUNCTION `valordevuelto`(idanticipoint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valordevuelto DECIMAL(13,2);

	SELECT 
	SUM(monto) INTO valordevuelto
	FROM 
	t_devoluciondetalle
	WHERE idanticipo=idanticipoint;

	RETURN ifnull(valordevuelto,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for valorfacturado
-- ----------------------------
DROP FUNCTION IF EXISTS `valorfacturado`;
delimiter ;;
CREATE FUNCTION `valorfacturado`(idfacturaint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorfacturado DECIMAL(13,2);

	SELECT
	SUM(ROUND(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio,2)) INTO valorfacturado
	FROM
	t_factura
	LEFT JOIN t_cargo ON t_factura.idfactura=t_cargo.idfacturanotadebito
	LEFT JOIN t_embarque ON t_factura.idembarque=t_embarque.idembarque
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND t_factura.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_factura.fecha) AND t_tipocambio.idempresa=t_embarque.idempresa
	WHERE 
	t_factura.idfactura=idfacturaint 
	AND IFNULL(t_cargo.idtipofacturanotadebito,0)=1;

	RETURN ifnull(valorfacturado,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for valorfacturadoembarque
-- ----------------------------
DROP FUNCTION IF EXISTS `valorfacturadoembarque`;
delimiter ;;
CREATE FUNCTION `valorfacturadoembarque`(idembarqueint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorfacturado DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) INTO valorfacturado
	FROM
	t_factura
	LEFT JOIN t_cargo ON t_factura.idfactura=t_cargo.idfacturanotadebito
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND t_factura.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_factura.fecha)
	WHERE 
	t_factura.idembarque=idembarqueint 
	AND IFNULL(t_cargo.idtipofacturanotadebito,0)=1
	AND t_factura.idestadofactura=1;

	RETURN ifnull(valorfacturado,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for valorfacturadoiva
-- ----------------------------
DROP FUNCTION IF EXISTS `valorfacturadoiva`;
delimiter ;;
CREATE FUNCTION `valorfacturadoiva`(idfacturaint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorfacturadoiva DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio*0.13) INTO valorfacturadoiva
	FROM
	t_factura
	LEFT JOIN t_cargo ON t_factura.idfactura=t_cargo.idfacturanotadebito
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND t_factura.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_factura.fecha)
	WHERE 
	t_factura.idfactura=idfacturaint 
	AND IFNULL(t_cargo.idtipofacturanotadebito,0)=1;

	RETURN ifnull(valorfacturadoiva,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for valorfacturadoneto
-- ----------------------------
DROP FUNCTION IF EXISTS `valorfacturadoneto`;
delimiter ;;
CREATE FUNCTION `valorfacturadoneto`(idfacturaint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorfacturadoneto DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio*0.87) INTO valorfacturadoneto
	FROM
	t_factura
	LEFT JOIN t_cargo ON t_factura.idfactura=t_cargo.idfacturanotadebito
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND t_factura.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_factura.fecha)
	WHERE 
	t_factura.idfactura=idfacturaint 
	AND IFNULL(t_cargo.idtipofacturanotadebito,0)=1;

	RETURN ifnull(valorfacturadoneto,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for valorinvoice
-- ----------------------------
DROP FUNCTION IF EXISTS `valorinvoice`;
delimiter ;;
CREATE FUNCTION `valorinvoice`(idinvoiceint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorinvoice DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) INTO valorinvoice
	FROM
	t_invoice
	LEFT JOIN t_cargo ON t_invoice.idinvoice=t_cargo.idinvoice
	LEFT JOIN t_embarque ON t_invoice.idembarque=t_embarque.idembarque
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND t_invoice.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_invoice.fecha) AND t_tipocambio.idempresa=t_embarque.idempresa
	WHERE 
	t_invoice.idinvoice=idinvoiceint;

	RETURN ifnull(valorinvoice,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for valorinvoiceembarque
-- ----------------------------
DROP FUNCTION IF EXISTS `valorinvoiceembarque`;
delimiter ;;
CREATE FUNCTION `valorinvoiceembarque`(idembarqueint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorinvoiceembarque DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) INTO valorinvoiceembarque
	FROM
	t_invoice
	LEFT JOIN t_cargo ON t_invoice.idinvoice=t_cargo.idinvoice
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 2=t_tipocambio.iddivisadestino AND t_invoice.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_invoice.fecha)
	WHERE 
	t_invoice.idembarque=idembarqueint;

	RETURN ifnull(valorinvoiceembarque,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for valorinvoiceus
-- ----------------------------
DROP FUNCTION IF EXISTS `valorinvoiceus`;
delimiter ;;
CREATE FUNCTION `valorinvoiceus`(idinvoiceint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorinvoice DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) INTO valorinvoice
	FROM
	t_invoice
	LEFT JOIN t_cargo ON t_invoice.idinvoice=t_cargo.idinvoice
	LEFT JOIN t_embarque ON t_invoice.idembarque=t_embarque.idembarque
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 2=t_tipocambio.iddivisadestino AND t_invoice.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_invoice.fecha) AND t_tipocambio.idempresa=t_embarque.idempresa
	WHERE 
	t_invoice.idinvoice=idinvoiceint;

	RETURN ifnull(valorinvoice,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for valorordendepago
-- ----------------------------
DROP FUNCTION IF EXISTS `valorordendepago`;
delimiter ;;
CREATE FUNCTION `valorordendepago`(idfacturapagoint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorordendepago DECIMAL(13,2);

	SELECT
	t_costo.cantidad*t_costo.monto*t_tipocambio.tipocambio INTO valorordendepago
	FROM
	t_costo
	LEFT JOIN t_facturapago ON t_costo.idfacturanotadebito=t_facturapago.idfacturapago
                            LEFT JOIN t_tipocambio ON t_costo.iddivisa=t_tipocambio.iddivisaorigen AND t_facturapago.iddivisa=t_tipocambio.iddivisadestino AND t_facturapago.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_facturapago.fecha)
                            LEFT JOIN t_divisa ON t_facturapago.iddivisa=t_divisa.iddivisa
	WHERE 
	t_facturapago.idfacturapago=idfacturapagoint 
	AND IFNULL(t_costo.idtipofacturanotadebito,0)=1;

	RETURN ifnull(valorordendepago,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for valorordendepagoembarque
-- ----------------------------
DROP FUNCTION IF EXISTS `valorordendepagoembarque`;
delimiter ;;
CREATE FUNCTION `valorordendepagoembarque`(idembarqueint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorordendepago DECIMAL(13,2);

	SELECT
	SUM(t_costo.cantidad*t_costo.monto*t_tipocambio.tipocambio) INTO valorordendepago
	FROM
	t_costo
	LEFT JOIN t_facturapago ON t_costo.idfacturanotadebito=t_facturapago.idfacturapago
	LEFT JOIN t_tipocambio ON t_costo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND t_facturapago.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_facturapago.fecha)
	WHERE 
	t_facturapago.idembarque=idembarqueint
		AND t_facturapago.idtipofacturapago=1
	AND IFNULL(t_costo.idtipofacturanotadebito,0)=1;

	RETURN ifnull(valorordendepago,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for valorordenservicioe
-- ----------------------------
DROP FUNCTION IF EXISTS `valorordenservicioe`;
delimiter ;;
CREATE FUNCTION `valorordenservicioe`(idordenservicioeint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorordenservicioe DECIMAL(13,2);

	SELECT
	SUM(t_costo.monto*t_costo.cantidad*t_tipocambio.tipocambio) INTO valorordenservicioe
	FROM
	t_ordenservicioe
	LEFT JOIN t_costo ON t_ordenservicioe.idordenservicioe=t_costo.idordenservicioe
	LEFT JOIN t_embarque ON t_ordenservicioe.idembarque=t_embarque.idembarque
	LEFT JOIN t_tipocambio ON t_costo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND t_ordenservicioe.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_ordenservicioe.fecha) AND t_tipocambio.idempresa=t_embarque.idempresa
	WHERE 
	t_ordenservicioe.idordenservicioe=idordenservicioeint;

	RETURN ifnull(valorordenservicioe,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for valorordenservicioi
-- ----------------------------
DROP FUNCTION IF EXISTS `valorordenservicioi`;
delimiter ;;
CREATE FUNCTION `valorordenservicioi`(idordenservicioiint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorordenservicioi DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) INTO valorordenservicioi
	FROM
	t_ordenservicioi
	LEFT JOIN t_cargo ON t_ordenservicioi.idordenservicioi=t_cargo.idordenservicioi
	LEFT JOIN t_embarque ON t_ordenservicioi.idembarque=t_embarque.idembarque
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND t_ordenservicioi.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_ordenservicioi.fecha) AND t_tipocambio.idempresa=t_embarque.idempresa
	WHERE 
	t_ordenservicioi.idordenservicioi=idordenservicioiint;

	RETURN ifnull(valorordenservicioi,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for valororembarquedenservicioe
-- ----------------------------
DROP FUNCTION IF EXISTS `valororembarquedenservicioe`;
delimiter ;;
CREATE FUNCTION `valororembarquedenservicioe`(idembarqueint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valororembarquedenservicioe DECIMAL(13,2);

	SELECT
	SUM(t_costo.monto*t_costo.cantidad*t_tipocambio.tipocambio) INTO valororembarquedenservicioe
	FROM
	t_ordenservicioe
	LEFT JOIN t_costo ON t_ordenservicioe.idordenservicioe=t_costo.idordenservicioe
	LEFT JOIN t_tipocambio ON t_costo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND t_ordenservicioe.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_ordenservicioe.fecha)
	WHERE 
	t_ordenservicioe.idembarque=idembarqueint;

	RETURN ifnull(valororembarquedenservicioe,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for valorpagofacturado
-- ----------------------------
DROP FUNCTION IF EXISTS `valorpagofacturado`;
delimiter ;;
CREATE FUNCTION `valorpagofacturado`(idfacturapagoint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorpagofacturado DECIMAL(13,2);

	SELECT
	SUM(t_costo.monto*t_costo.cantidad*t_tipocambio.tipocambio) INTO valorpagofacturado
	FROM
	t_facturapago
	LEFT JOIN t_costo ON t_facturapago.idfacturapago=t_costo.idfacturanotadebito
	LEFT JOIN t_embarque ON t_facturapago.idembarque=t_embarque.idembarque
	LEFT JOIN t_tipocambio ON t_costo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND t_facturapago.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_facturapago.fecha) AND t_tipocambio.idempresa=t_embarque.idempresa
	WHERE 
	t_facturapago.idfacturapago=idfacturapagoint 
	AND IFNULL(t_costo.idtipofacturanotadebito,0)=1;

	RETURN ifnull(valorpagofacturado,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for valorplanillado
-- ----------------------------
DROP FUNCTION IF EXISTS `valorplanillado`;
delimiter ;;
CREATE FUNCTION `valorplanillado`(idplanillaint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorplanillado DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) INTO valorplanillado
	FROM
	t_planilla
	LEFT JOIN t_cargo ON t_planilla.idplanilla=t_cargo.idplanilla
	LEFT JOIN t_embarque ON t_planilla.idembarque=t_embarque.idembarque
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND t_planilla.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_planilla.fecha) AND t_tipocambio.idempresa=t_embarque.idempresa
	WHERE 
	t_planilla.idplanilla=idplanillaint 
	AND IFNULL(t_cargo.idplanilla,0)>=2;

	RETURN ifnull(valorplanillado,0);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for valorplanilladous
-- ----------------------------
DROP FUNCTION IF EXISTS `valorplanilladous`;
delimiter ;;
CREATE FUNCTION `valorplanilladous`(idplanillaint INT)
 RETURNS decimal(13,2)
BEGIN
	DECLARE valorplanilladous DECIMAL(13,2);

	SELECT
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) INTO valorplanilladous
	FROM
	t_planilla
	LEFT JOIN t_cargo ON t_planilla.idplanilla=t_cargo.idplanilla
	LEFT JOIN t_embarque ON t_planilla.idembarque=t_embarque.idembarque
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 2=t_tipocambio.iddivisadestino AND t_planilla.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_planilla.fecha) AND t_tipocambio.idempresa=t_embarque.idempresa
	WHERE 
	t_planilla.idplanilla=idplanillaint 
	AND IFNULL(t_cargo.idplanilla,0)>=2;

	RETURN ifnull(valorplanilladous,0);
END
;;
delimiter ;

-- ----------------------------
-- Event structure for DashBoardFacturacion
-- ----------------------------
DROP EVENT IF EXISTS `DashBoardFacturacion`;
delimiter ;;
CREATE EVENT `DashBoardFacturacion`
ON SCHEDULE
EVERY '1' DAY STARTS '2022-12-13 21:15:00'
DO CALL DashBoardFacturacion()
;;
delimiter ;

-- ----------------------------
-- Event structure for DashBoardIngresos
-- ----------------------------
DROP EVENT IF EXISTS `DashBoardIngresos`;
delimiter ;;
CREATE EVENT `DashBoardIngresos`
ON SCHEDULE
EVERY '1' DAY STARTS '2022-12-13 21:30:00'
DO CALL DashBoardIngresos()
;;
delimiter ;

-- ----------------------------
-- Event structure for DashBoardLogistico
-- ----------------------------
DROP EVENT IF EXISTS `DashBoardLogistico`;
delimiter ;;
CREATE EVENT `DashBoardLogistico`
ON SCHEDULE
EVERY '1' DAY STARTS '2022-12-13 21:00:00'
DO CALL DashBoardLogistico()
;;
delimiter ;

-- ----------------------------
-- Event structure for DashBoardSalidas
-- ----------------------------
DROP EVENT IF EXISTS `DashBoardSalidas`;
delimiter ;;
CREATE EVENT `DashBoardSalidas`
ON SCHEDULE
EVERY '1' DAY STARTS '2022-12-13 21:45:00'
DO CALL DashBoardSalidas()
;;
delimiter ;

-- ----------------------------
-- Event structure for DashBoardVencimientos
-- ----------------------------
DROP EVENT IF EXISTS `DashBoardVencimientos`;
delimiter ;;
CREATE EVENT `DashBoardVencimientos`
ON SCHEDULE
EVERY '1' DAY STARTS '2022-12-13 22:00:00'
DO CALL DashBoardVencimientos()
;;
delimiter ;

SET FOREIGN_KEY_CHECKS = 1;
