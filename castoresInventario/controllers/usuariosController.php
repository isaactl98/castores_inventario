<?php
    require_once 'models/usuarios.php';

    class usuariosController
    {
        public function login()
        {

            // Lógica para la acción login
            if (isset($_POST)) {
                $email    = $_POST['useremail'];
                $password = $_POST['usePassword'];

                // Aquí iría la lógica de autenticación, como verificar las credenciales en la base de datos
                $usuario = new Usuarios();
                $usuario->setCorreo($email);
                $usuario->setContrasena($password);
                $identity = $usuario->identityUser();

                if ($identity == false) {
                    $_SESSION['errorLogin'] = true;
                    if (isset($_SESSION['errorLogin']) && $_SESSION['errorLogin'] == true) {
                        echo '<script>
                        alert("ERROR DE IDENTIFICACION!")
            window.location.replace("' . baseUrl . '");
        </script>';

                    }
                } else {
                    if ($identity && is_object($identity)) {

                        //VALIDADMOS EL ROL DEL USUARIO ADMINISTRADOR
                        if ($identity->idRol == 1) {
                            $_SESSION['identity'] = $identity;
                            $_SESSION['admin']    = true;
                            echo '<script>
                            window.location.replace("' . baseUrl . '?controller=home&action=dashboard");
                            </script>';

                        }

                        //VALIDADMOS EL ROL DEL USUARIO ALMACENISTA
                        if ($identity->idRol == 2) {
                            $_SESSION['identity'] = $identity;
                            $_SESSION['user']     = true;
                            echo '<script>
                            window.location.replace("' . baseUrl . '?controller=home&action=dashboard");
                            </script>';

                        }
                    } else {
                        //sino no hace login mostramos una session de error en el login
                        $_SESSION['errorLogin'] = true;
                    if (isset($_SESSION['errorLogin']) && $_SESSION['errorLogin'] == true) {?>
                        <script>
                            var url = location.origin;
                            var path = window.location.pathname;
                            Swal.fire({
                                icon: "warning",
                                title: "Error al Iniciar Session!",
                                text: "El Usuario o la Contraseña son Incorrectos!",
                                showConfirmButton: false
                            })
                            setTimeout(function() {
                                window.location.href = url + path; //URL DEL LOGIN
                            }, 2100); //SI EL USUARIO  NO EXISTE MUESTRA LA ALERTA Y REDIRIQUE A LA URL DE LOGIN
                        </script>
<?php
    }
                    }
                }
            }
        }

        public function logout()
        {
            //Borramos la session del usuario
            if (isset($_SESSION['identity'])) {
                unset($_SESSION['identity']); //asi se elimina la session activa
            }
            if (isset($_SESSION['admin'])) {
                unset($_SESSION['admin']);
            }

            if (isset($_SESSION['user'])) {
                unset($_SESSION['user']);
            }
            echo '<script>
                window.location.replace("' . baseUrl . '");
            </script>';
        }

        public function addUser()
        {
            if (isset($_POST)) {

                $nombre     = isset($_POST['userName']) ? $_POST['userName'] : false;
                $correo     = isset($_POST['useremail']) ? $_POST['useremail'] : false;
                $contrasena = isset($_POST['usePassword']) ? $_POST['usePassword'] : false;
                $idRol      = isset($_POST['userRole']) ? (int) $_POST['userRole'] : false;

                if ($nombre && $correo && $contrasena && $idRol) {
                    $usuario = new Usuarios();
                    $usuario->setNombre(strtoupper(trim($nombre)));
                    // Normalizamos el correo para evitar duplicados por mayúsculas o espacios
                    $correoNormalizado = strtolower(trim($correo));
                    $usuario->setCorreo($correoNormalizado);
                    $usuario->setContrasena($contrasena);
                    $usuario->setIdRol($idRol);

                    // Validar si el correo ya existe antes de intentar crear el usuario
                    if ($usuario->checkEmail()) {
                    ?>
                        <script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'Correo ya registrado',
                                text: 'El correo ingresado ya está en uso. Por favor intenta con otro.',
                                showConfirmButton: true
                            }).then(() => {
                                    window.location.href = '<?php echo baseUrl ?>?controller=home&action=controlUsuarios';
                                });
                        </script>
                        <?php
                            return; // Detenemos la ejecución para no intentar crear el usuario
                                            }

                                            try {
                                                $save = $usuario->add();
                                                if ($save) {
                                                ?>
                            <script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Usuario Creado!',
                                    text: 'El usuario se ha creado correctamente.',
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then(() => {
                                    window.location.href = '<?php echo baseUrl ?>?controller=home&action=controlUsuarios';
                                });
                            </script>
                            <?php
                                } else {
                                                    ?>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'No se pudo crear el usuario.',
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
