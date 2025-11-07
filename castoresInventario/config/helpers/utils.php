<?php
class Utils
{
    public static function urlActual()
    {
        $protocol   = (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $host       = $_SERVER['HTTP_HOST'];
        $requestUri = $_SERVER['REQUEST_URI'];
        return $protocol . $host . $requestUri;
    }

    public static function isAdmin()
    {
        if (isset($_SESSION['admin']) || isset($_SESSION['user'])) {
            return true;
        } else {
            echo '<script>
            window.location.replace("' . baseUrl . '");
        </script>';

        }
    }
    public static function deleteSession($namesession)
    {

        if (isset($_SESSION[$namesession])) {
            $_SESSION[$namesession] = null; //declaramos la variable vacia
            unset($_SESSION[$namesession]);
        }
        return $namesession;
    }
}
