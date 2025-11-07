<?php

class Productos
{
    private $id;
    private $name;
    private $price;
    private $stock;
    private $active;
    private $created_by;
    private $created_at;
    private $updated_at;
    private $db;

    public function __construct()
    {
        $this->db = dataBase::conexion();
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
 * Get the value of updated_at
 */
    public function getUpdated_at()
    {
        return $this->updated_at;
    }

/**
 * Set the value of updated_at
 *
 * @return  self
 */
    public function setUpdated_at($updated_at)
    {
        $this->updated_at = $updated_at;

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
 * Get the value of created_by
 */
    public function getCreated_by()
    {
        return $this->created_by;
    }

/**
 * Set the value of created_by
 *
 * @return  self
 */
    public function setCreated_by($created_by)
    {
        $this->created_by = $created_by;

        return $this;
    }

/**
 * Get the value of active
 */
    public function getActive()
    {
        return $this->active;
    }

/**
 * Set the value of active
 *
 * @return  self
 */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

/**
 * Get the value of stock
 */
    public function getStock()
    {
        return $this->stock;
    }

/**
 * Set the value of stock
 *
 * @return  self
 */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

/**
 * Get the value of price
 */
    public function getPrice()
    {
        return $this->price;
    }

/**
 * Set the value of price
 *
 * @return  self
 */
    public function setPrice($price)
    {
        $this->price = $price;

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

    public function add()
    {

        $sql  = "INSERT INTO products (id,name,price,stock,active,created_by,created_at,updated_at) VALUES(?,?,?,?,?,?,?,?);";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "isdiisss",
            $this->id,
            $this->name,
            $this->price,
            $this->stock,
            $this->active,
            $this->created_by,
            $this->created_at,
            $this->updated_at
        );
        $save = $stmt->execute();
        $stmt->close();
        return $save;
    }
}
