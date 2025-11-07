<?php

class dataBase
{

    public static function conexion()
    {
        //AQUI CAMBIAR LA CONFIGURACION DE LA BASE DE DATOS SEGUN EL SERVIDOR DONDE SE IMPLANTE
        $db = new mysqli('localhost', 'castoresinventario', '', 'castoresinventario');
        $db->query("SET NAMES 'utf8'");
        return $db;
        $db->close();
    }
}
