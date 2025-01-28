<?php

namespace App\Models;
use Core\Model;

class Location extends Model{

    var $primaryKey = 'id';
    var $table = 'locations';
    
    var $schema = array(
        array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'delivery_id', 'type' => 'int', 'default' => ''),
        array('name' => 'starting_point', 'type' => 'varchar(255)', 'default' => ''),
        array('name' => 'ending_point', 'type' => 'varchar(255)', 'default' => ''),
    );
    
    // Create new location
    public function createLocation($data) {
        return $this->create($data);
    }

    // Read location (by ID)
    public function getLocationById($id) {
        return $this->find($id);
    }

    // Update location (by ID)
    public function updateLocation($id, $data) {
        return $this->update($id, $data);
    }

    // Delete location (by ID)
    public function deleteLocation($id) {
        return $this->delete($id);
    }
}
