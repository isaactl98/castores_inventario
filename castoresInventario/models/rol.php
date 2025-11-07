<?php

class Roles
{

    private $id;
    private $name;
    private $description;
    private $created_at;
    private $db;

    public function __construct()
    {
        $this->db = Database::conexion();
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

/**
 * Get the value of created_at
 */
    public function getCreated_at()
    {
        return $this->created_at;
    }

/**
 * Set the value of created_at
 *
 * @return  self
 */
    public function setCreated_at($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

/**
 * Get the value of description
 */
    public function getDescription()
    {
        return $this->description;
    }

/**
 * Set the value of description
 *
 * @return  self
 */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

/**
 * Get the value of name
 */
    public function getName()
    {
        return $this->name;
    }

/**
 * Set the value of name
 *
 * @return  self
 */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

/**
 * Get the value of id
 */
    public function getId()
    {
        return $this->id;
    }

/**
 * Set the value of id
 *
 * @return  self
 */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getAllRoles()
    {
        $roles = $this->db->query("SELECT * FROM roles ORDER BY id ASC");
        return $roles;
    }

    public function getRoleById()
    {
        $sql  = "SELECT * FROM roles WHERE id = {$this->getId()};";
        $role = $this->db->query($sql);
        return $role->fetch_object();
    }
}
