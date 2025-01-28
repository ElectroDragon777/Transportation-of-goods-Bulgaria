<?php

namespace App\Models;
use Core\Model;

class Product extends Model{

    var $primaryKey = 'id';
    var $table = 'product';
    
    var $schema = array(
        array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'product_name', 'type' => 'varchar', 'default' => ''),
        array('name' => 'product_description', 'type' => 'varchar', 'default' => '')
    );
    
    // Create new product
    public function createProduct($data) {
        return $this->create($data);
    }

    // Read product (by ID)
    public function getProductById($id) {
        return $this->find($id);
    }

    // Update product (by ID)
    public function updateProduct($id, $data) {
        return $this->update($id, $data);
    }

    // Delete product (by ID)
    public function deleteProduct($id) {
        return $this->delete($id);
    }
}
