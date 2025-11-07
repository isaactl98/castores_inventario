<?php
session_start();
require_once '../../db/db.php';

header('Content-Type: application/json');

// Requiere sesión y rol admin
if (!isset($_SESSION['identity']) || !isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
   http_response_code(403);
   echo json_encode(['success' => false, 'message' => 'Permisos insuficientes']);
   exit;
}

$db = dataBase::conexion();

$productId = isset($_POST['productId']) ? (int) $_POST['productId'] : 0;
$newStatus = isset($_POST['active']) ? (int) $_POST['active'] : -1; // 0 o 1

if ($productId <= 0 || ($newStatus !== 0 && $newStatus !== 1)) {
   http_response_code(400);
   echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
   exit;
}

$stmt = $db->prepare('UPDATE products SET active = ?, updated_at = NOW() WHERE id = ?');
if (!$stmt) {
   http_response_code(500);
   echo json_encode(['success' => false, 'message' => 'Error preparando la consulta']);
   exit;
}

$stmt->bind_param('ii', $newStatus, $productId);
if (!$stmt->execute()) {
   $stmt->close();
   http_response_code(500);
   echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el estado del producto']);
   exit;
}

$stmt->close();

echo json_encode([
   'success' => true,
   'message' => $newStatus === 1 ? 'Producto activado' : 'Producto desactivado',
   'active'  => $newStatus
]);

$db->close();
