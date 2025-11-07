<?php
include '../../db/db.php';
$db = dataBase::conexion();

if ($_POST['funcion'] && $_POST['funcion'] == 'cargarHistorial') {
   /* SE CONSULTA LA VISTA */
   $sql      = "SELECT mt.*, us.nombre FROM movements mt INNER JOIN usuarios us ON mt.user_id = us.idUsuario;";
   $movimientos = mysqli_query($db, $sql);

   $objehistJon = [];
   while ($user = $movimientos->fetch_assoc()) {
      $objehistJon["data"][] = $user;
   }

   print $json = json_encode($objehistJon);
   $archivo    = file_put_contents("datatableHistorial.json", $json);
   # Solo se Guardara la imagen si Existe el Fichero "uploads/imgProducts" y sea de tipo imagens
}
