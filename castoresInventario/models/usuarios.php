<?php

class Usuarios
{

    private $idUsuario;
    private $nombre;
    private $correo;
    private $contrasena;
    private $idRol;
    private $estatus;
    private $db;

    public function __construct()
    {
        $this->db = dataBase::conexion();
    }

    /**
     * Get the value of idUsuario
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * Set the value of idUsuario
     *
     * @return  self
     */
    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }

    /**
     * Get the value of nombre
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get the value of correo
     */
    public function getCorreo()
    {
        return $this->correo;
    }

    /**
     * Set the value of correo
     *
     * @return  self
     */
    public function setCorreo($correo)
    {
        $this->correo = $correo;

        return $this;
    }

    /**
     * Get the value of contrasena
     */
    public function getContrasena()
    {
        return $this->contrasena;
    }

    /**
     * Set the value of contrasena
     *
     * @return  self
     */
    public function setContrasena($contrasena)
    {
        $this->contrasena = $contrasena;

        return $this;
    }

    /**
     * Get the value of idRol
     */
    public function getIdRol()
    {
        return $this->idRol;
    }

    /**
     * Set the value of idRol
     *
     * @return  self
     */
    public function setIdRol($idRol)
    {
        $this->idRol = $idRol;

        return $this;
    }

    /**
     * Get the value of estatus
     */
    public function getEstatus()
    {
        return $this->estatus;
    }

    /**
     * Set the value of estatus
     *
     * @return  self
     */
    public function setEstatus($estatus)
    {
        $this->estatus = $estatus;

        return $this;
    }

    /**
     * Get the value of db
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * Set the value of db
     *
     * @return  self
     */
    public function setDb($db)
    {
        $this->db = $db;

        return $this;
    }

    public function identityUser()
    {
        $response   = false;
        $correo     = $this->correo;
        $contrasena = $this->contrasena;

        // Consulta preparada para evitar inyección SQL
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE correo = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("s", $correo);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result && $result->num_rows === 1) {
                $usuario = $result->fetch_object();
                // Comparación de contraseña en texto plano según implementación actual
                if ($contrasena == $usuario->contrasena) {
                    $response = $usuario;
                }
            }
            $stmt->close();
        }
        return $response;

    }
    public function checkEmail()
    {
        $email  = $this->correo; // ya normalizado en el controlador

        // Consulta preparada para verificar existencia del correo
        $stmt = $this->db->prepare("SELECT 1 FROM usuarios WHERE correo = ? LIMIT 1");
        if (!$stmt) {
            // Si falla la preparación, por seguridad asumimos que no existe
            return false;
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows >= 1;
        $stmt->close();
        return $exists;
    }

    public function add() {
    try {
        // Preparar la llamada al Stored Procedure
        $sql = "CALL sp_crear_usuario(?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        // Vincular los parámetros
        $stmt->bind_param("sssi", 
            $this->nombre,
            $this->correo,
            $this->contrasena,
            $this->idRol
        );
        
        // Ejecutar el Stored Procedure
        if ($stmt->execute()) {
            // Obtener el resultado del SP
            $result = $stmt->get_result();
            $response = $result->fetch_assoc();
            
            if (isset($response['idUsuario'])) {
                $this->idUsuario = $response['idUsuario'];
                return true;
            }
        }
        
        return false;
    } catch (mysqli_sql_exception $e) {
        // Manejar el error específico de correo duplicado
        if ($e->getCode() == '45000') {
            throw new Exception("El correo ya existe en el sistema");
        }
        // Otros errores
        throw new Exception("Error al crear el usuario: " . $e->getMessage());
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
    }
}
}
