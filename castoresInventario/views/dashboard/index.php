<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <!-- Begin Page Content -->
        <div class="container-fluid">

            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Inventario</h1>
                <?php if (isset($_SESSION['admin'])): ?>
                    <a type="button" class="btn btn-primary" href="#" data-toggle="modal" data-target="#addProductoModal">Agregar Producto +</a>
                <?php endif; ?>
            </div>

            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Productos del Inventario</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tablaProductos" class="table table-bordered table-hover" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Precio</th>
                                    <th>Stock</th>
                                    <th>Estatus</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Precio</th>
                                    <th>Stock</th>
                                    <th>Estatus</th>
                                    <th>Opciones</th>
                                </tr>
                            </tfoot>

                        </table>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.container-fluid -->

    </div>
</div>