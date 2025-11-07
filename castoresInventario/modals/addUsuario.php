<!-- Logout Modal-->
<div class="modal fade" id="addUsuariotModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
   aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <form action="<?php echo baseUrl ?>?controller=usuarios&action=addUser" method="post">
            <div class="modal-header">
               <h5 class="modal-title" id="exampleModalLabel">Agregar nuevo usuario</h5>
               <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
               </button>
            </div>
            <div class="modal-body">
               <div class="form-group">
                  <input type="text" class="form-control form-control-user"
                     id="userName" aria-describedby="emailHelp"
                     placeholder="Nombre Completo" name="userName">
               </div>
               <div class="form-group">
                  <input type="email" class="form-control form-control-user"
                     id="useremail" aria-describedby="emailHelp"
                     placeholder="Correo Electrónico" name="useremail">
               </div>
               <div class="form-group">
                  <input type="password" class="form-control form-control-user"
                     id="usePassword" placeholder="Contraseña" name="usePassword">
               </div>
               <div class="form-group">
                  <?php while ($rol = $roles->fetch_object()): ?>
                  <input type="radio" class="p-5 m-2" name="userRole" value="<?php echo $rol->id; ?>"><?php echo $rol->name; ?>
                  <?php endwhile; ?>

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