<!-- Logout Modal-->
<div class="modal fade" id="addProductoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
   aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <form action="<?php echo baseUrl ?>?controller=productos&action=addProduct" method="post">
            <div class="modal-header">
               <h5 class="modal-title" id="exampleModalLabel">Agregar nuevo Producto</h5>
               <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
               </button>
            </div>
            <div class="modal-body">
               <div class="form-group">
                  <input type="text" class="form-control form-control-user"
                     id="productoNombre" aria-describedby="emailHelp"
                     placeholder="Nombre del Producto" name="productoNombre">
               </div>
               <div class="form-group">
                  <input type="number" step="any" class="form-control form-control-user"
                     id="productoPrecio" aria-describedby="emailHelp"
                     placeholder="Precio del Producto" name="productoPrecio">
               </div>


            </div>
            <div class="modal-footer">
               <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
               <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
         </form>
      </div>
   </div>
</div>