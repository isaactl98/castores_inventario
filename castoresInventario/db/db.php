<?php

class dataBase
{

    public static function conexion()
    {
        $db = new mysqli('localhost', 'castoresinventario', '', 'castoresinventario');
        $db->query("SET NAMES 'utf8'");
        return $db;
        $db->close();
    }
}
