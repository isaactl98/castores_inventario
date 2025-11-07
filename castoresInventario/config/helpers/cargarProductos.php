<?php
session_start();
require_once '../../db/db.php';

header('Content-Type: application/json; charset=utf-8');

// Verificar autenticación
if (!isset($_SESSION['identity'])) {
   http_response_code(401);
   echo json_encode(['data' => [], 'error' => 'No autorizado']);
   exit;
}

$db = dataBase::conexion();

if (isset($_POST['funcion']) && $_POST['funcion'] == 'cargarProductos') {

   // Determinar rol desde la sesión
   $isAdmin = isset($_SESSION['admin']) && $_SESSION['admin'] === true;
   $isUser  = isset($_SESSION['user']) && $_SESSION['user'] === true; // rol 2

   // Construir consulta según el rol (usuarios con rol 2 no ven estatus 2)
   $sql = "SELECT id, name, price, stock, active FROM products";
   if ($isUser && !$isAdmin) {
      $sql .= " WHERE active <> 2"; // ocultar productos con estatus 2 para rol 2
   }
   $sql .= " ORDER BY id ASC";

   $productos = mysqli_query($db, $sql);

   if (!$productos) {
      http_response_code(500);
      echo json_encode(['data' => [], 'error' => 'Error al cargar productos']);
      exit;
   }

   // Inicializar estructura de respuesta siempre con la clave 'data'
   $response = [
      'data' => []
   ];

   while ($producto = $productos->fetch_assoc()) {
      $response['data'][] = $producto;
   }

   echo json_encode($response);

   // Opcional: guardar cache
   file_put_contents("datatableProducts.json", json_encode($response));

   $db->close();
} else {
   http_response_code(400);
   echo json_encode(['data' => [], 'error' => 'Función no especificada']);
}
