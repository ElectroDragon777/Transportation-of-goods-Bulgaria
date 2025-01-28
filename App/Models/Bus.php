<?php

namespace App\Models;
use Core\Model;

class Bus extends Model{

    var $primaryKey = 'id';
    var $table = 'buses';
    
    var $schema = array(
        array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'bus_number', 'type' => 'varchar(255)', 'default' => ''),
        array('name' => 'capacity', 'type' => 'int', 'default' => '')
    );
    
    // Create new bus
    public function createBus($data) {
        return $this->create($data);
    }

    // Read bus (by ID)
    public function getBusById($id) {
        return $this->find($id);
    }

    // Update bus (by ID)
    public function updateBus($id, $data) {
        return $this->update($id, $data);
    }

    // Delete bus (by ID)
    public function deleteBus($id) {
        return $this->delete($id);
    }
}
