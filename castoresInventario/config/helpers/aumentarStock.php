<?php
session_start();
require_once '../../db/db.php';

header('Content-Type: application/json; charset=utf-8');

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['identity'])) {
   http_response_code(401);
   echo json_encode(['success' => false, 'message' => 'No autorizado']);
   exit;
}

// Verificar que sea un almacenista o administrador
if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) {
   http_response_code(403);
   echo json_encode(['success' => false, 'message' => 'Permisos insuficientes']);
   exit;
}

$db = dataBase::conexion();

// Soporta dos formas:
// 1) funcion = 'aumentarStock' (compatibilidad) => ENTRADA
// 2) funcion = 'movimientoStock' + type ('ENTRADA' | 'SALIDA')

$funcion = isset($_POST['funcion']) ? $_POST['funcion'] : '';
$type = 'ENTRADA';
if ($funcion === 'movimientoStock') {
   $tipoPost = isset($_POST['type']) ? strtoupper(trim($_POST['type'])) : 'ENTRADA';
   if (in_array($tipoPost, ['ENTRADA', 'SALIDA'], true)) {
      $type = $tipoPost;
   }
} elseif ($funcion === 'aumentarStock') {
   $type = 'ENTRADA';
} else {
   http_response_code(400);
   echo json_encode(['success' => false, 'message' => 'Función no especificada']);
   exit;
}

$productId = isset($_POST['productId']) ? (int) $_POST['productId'] : 0;
$cantidad = isset($_POST['cantidad']) ? (int) $_POST['cantidad'] : 0;

// Validaciones
if ($productId <= 0 || $cantidad <= 0) {
   http_response_code(400);
   echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
   exit;
}

// Transacción para registrar movimiento e items; los triggers actualizan stock
$db->begin_transaction();

// Validar producto activo y obtener precio
$stmtProd = $db->prepare("SELECT price FROM products WHERE id = ? AND active = 1 FOR UPDATE");
if (!$stmtProd) {
   $db->rollback();
   http_response_code(500);
   echo json_encode(['success' => false, 'message' => 'Error preparando consulta de producto']);
   exit;
}
$stmtProd->bind_param('i', $productId);
$stmtProd->execute();
$stmtProd->bind_result($price);
if (!$stmtProd->fetch()) {
   $stmtProd->close();
   $db->rollback();
   http_response_code(404);
   echo json_encode(['success' => false, 'message' => 'Producto no encontrado o inactivo']);
   exit;
}
$stmtProd->close();

$userId = $_SESSION['identity']->idUsuario;
$notes = ($type === 'ENTRADA') ? "Entrada de stock: +{$cantidad} unidades" : "Salida de stock: -{$cantidad} unidades";

$stmtMovement = $db->prepare("INSERT INTO movements (movement_type, user_id, notes) VALUES (?, ?, ?)");
if (!$stmtMovement) {
   $db->rollback();
   http_response_code(500);
   echo json_encode(['success' => false, 'message' => 'Error registrando movimiento']);
   exit;
}
$stmtMovement->bind_param('sis', $type, $userId, $notes);
$stmtMovement->execute();
$movementId = $stmtMovement->insert_id;
$stmtMovement->close();

// Insertar detalle; los triggers ajustan el stock y validan SALIDA
$stmtItem = $db->prepare("INSERT INTO movement_items (movement_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)");
if (!$stmtItem) {
   $db->rollback();
   http_response_code(500);
   echo json_encode(['success' => false, 'message' => 'Error registrando detalle de movimiento']);
   exit;
}
$stmtItem->bind_param('iiid', $movementId, $productId, $cantidad, $price);

if (!$stmtItem->execute()) {
   // Puede fallar por el trigger (stock insuficiente)
   $errorMsg = $db->error ?: 'No se pudo completar el movimiento';
   $stmtItem->close();
   $db->rollback();
   http_response_code(400);
   echo json_encode(['success' => false, 'message' => $errorMsg]);
   exit;
}
$stmtItem->close();

$db->commit();

echo json_encode([
   'success' => true,
   'message' => ($type === 'ENTRADA') ? 'Entrada registrada' : 'Salida registrada',
   'type' => $type,
   'cantidad' => $cantidad
]);

$db->close();
