<?php

namespace App\Models;
use Core\Model;

class Load extends Model{

    var $primaryKey = 'id';
    var $table = 'loads';
    
    var $schema = array(
        array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'delivery_id', 'type' => 'int', 'default' => ''),
        array('name' => 'description', 'type' => 'varchar(255)', 'default' => ''),
        array('name' => 'weight', 'type' => 'int', 'default' => '')
    );
    
    // Create new load
    public function createLoad($data) {
        return $this->create($data);
    }

    // Read load (by ID)
    public function getLoadById($id) {
        return $this->find($id);
    }

    // Update load (by ID)
    public function updateLoad($id, $data) {
        return $this->update($id, $data);
    }

    // Delete load (by ID)
    public function deleteLoad($id) {
        return $this->delete($id);
    }
}
