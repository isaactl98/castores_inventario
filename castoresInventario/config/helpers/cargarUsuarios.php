<?php
include '../../db/db.php';
$db = dataBase::conexion();

if ($_POST['funcion'] && $_POST['funcion'] == 'cargarUsuarios') {

    /* SE CONSULTA LA VISTA */
    $sql      = "SELECT us.*, r.name AS rol FROM usuarios us INNER JOIN roles r ON us.idRol = r.id ORDER BY us.idUsuario ASC";
    $usuarios = mysqli_query($db, $sql);

    $objeusersJon = [];
    while ($user = $usuarios->fetch_assoc()) {
        $objeusersJon["data"][] = $user;
    }

    print $json = json_encode($objeusersJon);
    $archivo    = file_put_contents("datatableUsers.json", $json);
    # Solo se Guardara la imagen si Existe el Fichero "uploads/imgProducts" y sea de tipo imagens
}
