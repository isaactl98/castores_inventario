<?php
require_once 'models/usuarios.php';
require_once 'models/rol.php';
class homeController
{
    public function index()
    {
        // Lógica para la acción index
        require_once 'views/login.php';
    }

    public function dashboard()
    {
        Utils::isAdmin();
        require_once 'modals/addProducto.php';

        require_once 'views/dashboard/index.php';
    }
    public function controlUsuarios()
    {

        $allRoles = new Roles();
        $roles    = $allRoles->getAllRoles();

        $usuarios = new Usuarios();

        Utils::isAdmin();
        require_once 'modals/addUsuario.php';
        require_once 'views/dashboard/usuariosPanel.php';
    }
    public function historial()
    {
        Utils::isAdmin();
        require_once 'views/dashboard/historial.php';
    }
}
