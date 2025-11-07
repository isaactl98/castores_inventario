<?php
$ip = substr($_SERVER['HTTP_HOST'], 0, 3);

// Verificar si es una conexión segura (HTTPS)
$es_https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';

// Obtener el host del servidor
$host = $_SERVER['HTTP_HOST'];

// Obtener la URL base completa
$url_base = $es_https . '://' . $host . "/castoresInventario/";

define("baseUrl", $url_base);


define("controller_default", "homeController");
define("action_default", "index");
