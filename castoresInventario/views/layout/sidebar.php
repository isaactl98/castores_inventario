<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Castores Almacen</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="index.html">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        MODULOS DEL SISTEMA
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cog"></i>
            <span>Inventario</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">MODULOS:</h6>
                <a class="collapse-item" href="<?php echo baseUrl ?>?controller=home&action=dashboard">Inventario de Productos</a>
                <a class="collapse-item" href="<?php echo baseUrl ?>?controller=home&action=entradas">Entradas</a>
                <a class="collapse-item" href="<?php echo baseUrl ?>?controller=home&action=salidas">Salidas</a>
            </div>
        </div>
    </li>








    <?php if (isset($_SESSION['identity']) && $_SESSION['identity']->idRol == 1): ?>
        <!-- Nav Item - Utilities Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="<?php echo baseUrl ?>?controller=home&action=historial" aria-controls="collapseUtilities">
                <i class="fas fa-fw fa-wrench"></i>
                <span>historial</span>
            </a>
        </li>
        <!-- Divider -->
        <hr class="sidebar-divider">
        <!-- Heading -->
        <div class="sidebar-heading">
            ADMIN PERSONAL
        </div>
        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="<?php echo baseUrl ?>?controller=home&action=controlUsuarios"
                aria-expanded="true">
                <i class="fas fa-fw fa-folder"></i>
                <span>Personal</span>
            </a>
        </li>
    <?php endif; ?>




    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>


</ul>
<!-- End of Sidebar -->