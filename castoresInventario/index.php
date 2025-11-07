<?php
// Establecer la duración de la sesión en segundos
$session_lifetime = 3600; // 1 hora
// Establecer los parámetros de la cookie de la sesión
session_set_cookie_params($session_lifetime);
session_start(); #SIEMPRE SE INICIA UNA SESSION PARA EL MANEJO DE LAS ACTIVIDADES DEL USUARIO EN LA PAGINA
/* ARCHIVOS GESTORES DEL CONTENIDO */
require_once 'config/helpers/utils.php';
require_once 'config/parameters.php';
require_once 'views/layout/head.php';
require_once 'autoload.php';
require_once 'db/db.php';
/* VALIDASION DE LA SESSION ERRORLOGIN */

$urlActual = Utils::urlActual();
header("Refresh: 1439; URL='" . $urlActual . "'");

if (isset($_SESSION['identity'])) {
?>

    <body id="page-top">
        <!-- Page Wrapper -->
        <div id="wrapper">
            <script>
                // Variable global con el rol del usuario para uso en DataTables
                var userRole = <?php
                                if (isset($_SESSION['admin']) && $_SESSION['admin'] == true) {
                                    echo 1; // Administrador
                                } elseif (isset($_SESSION['user']) && $_SESSION['user'] == true) {
                                    echo 2; // Almacenista
                                } else {
                                    echo 0; // Sin rol
                                }
                                ?>;
            </script>
            <?php
            require_once 'views/layout/sidebar.php';
            ?>
            <div id="content-wrapper" class="d-flex flex-column">
                <!-- Main Content -->
                <div id="content">
                <?php
                require_once 'views/layout/navbar.php';
                require_once 'modals/logout.php';
            }


            /* ARCHIVOS GESTORES DEL CONTENIDO */
            if (isset($_GET['controller'])) {
                $nameController = $_GET['controller'] . 'Controller';
            } else {
                $nameController = controller_default;
            }
            if (class_exists($nameController)) {
                $controller = new $nameController();
                if (isset($_GET['action']) && method_exists($controller, $_GET['action'])) {
                    $action = $_GET['action'];
                    $controller->$action();
                } else {
                    $action_defauld = action_default;
                    $controller->$action_defauld();
                }
            }
            Utils::deleteSession('errorLogin');
                ?>

                <?php
                require_once 'views/layout/footer.php';
                ?>