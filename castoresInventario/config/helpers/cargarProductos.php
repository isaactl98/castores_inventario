<?php
include '../../db/db.php';
$db = dataBase::conexion();

if ($_POST['funcion'] && $_POST['funcion'] == 'cargarProductos') {
   /* SE CONSULTA LA VISTA */
   $sql      = "SELECT id, name, price, stock,active FROM products;";
   $productos = mysqli_query($db, $sql);

   $objeusersJon = [];
   while ($user = $productos->fetch_assoc()) {
      $objeusersJon["data"][] = $user;
   }

   print $json = json_encode($objeusersJon);
   $archivo    = file_put_contents("datatableProducts.json", $json);
   # Solo se Guardara la imagen si Existe el Fichero "uploads/imgProducts" y sea de tipo imagens
}
