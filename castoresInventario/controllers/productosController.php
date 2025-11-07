<?php

require_once 'models/productos.php';

class productosController
{
   public function addProduct()
   {
      Utils::isAdmin();
      // Lógica para agregar un producto
      if ($_POST) {
         $nombre = isset($_POST['productoNombre']) ? $_POST['productoNombre'] : false;
         $precio = isset($_POST['productoPrecio']) ? $_POST['productoPrecio'] : false;

         if ($nombre && $precio) {
            $producto = new Productos();
            $producto->setName($nombre);
            $producto->setPrice($precio);
            $producto->setStock(0);  // Inicializamos el stock en 0
            $producto->setActive(1); // Activamos el producto por defecto
            $producto->setCreated_by($_SESSION['identity']->idUsuario);
            $producto->setCreated_at(date('Y-m-d H:i:s'));
            $producto->setUpdated_at(date('Y-m-d H:i:s'));

            try {
               $save = $producto->add();
               if ($save) {
?>
                  <script>
                     Swal.fire({
                        icon: 'success',
                        title: 'Producto Creado!',
                        text: 'El producto se ha creado correctamente.',
                        showConfirmButton: false,
                        timer: 2000
                     }).then(() => {
                        window.location.href = '<?php echo baseUrl ?>?controller=home&action=dashboard';
                     });
                  </script>
               <?php
               } else {
               ?>
                  <script>
                     Swal.fire({
                        icon: 'success',
                        title: 'Producto Creado!',
                        text: 'El producto se ha creado correctamente.',
                        showConfirmButton: false,
                        timer: 2000
                     }).then(() => {
                        window.location.href = '<?php echo baseUrl ?>?controller=home&action=dashboard';
                     });
                  </script>
               <?php
               }
            } catch (Exception $e) {
               ?>
               <script>
                  Swal.fire({
                     icon: 'error',
                     title: 'Error',
                     text: '<?php echo $e->getMessage(); ?>',
                  });
               </script>
            <?php
            }
         } else {
            ?>
            <script>
               Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: 'Faltan datos obligatorios para crear el usuario.',
               });
            </script>
<?php
         }
      }
   }
}
