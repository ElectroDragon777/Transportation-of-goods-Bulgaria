<?php

namespace App\Models;
use Core\Model;

class Deliveries extends Model{

    var $primaryKey = 'id';
    var $table = 'deliveries';
    
    var $schema = array(
        array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'delivery_date', 'type' => 'date', 'default' => ''),
        array('name' => 'bus_id', 'type' => 'int', 'default' => ''),
        array('name' => 'estimated_delivery_datetime', 'type' => 'datetime', 'default' => ''),
        array('name' => 'is_express', 'type' => 'boolean', 'default' => '')
    );
    
    // Create new delivery
    public function createDelivery($data) {
        return $this->create($data);
    }

    // Read delivery (by ID)
    public function getDeliveryById($id) {
        return $this->find($id);
    }

    // Update delivery (by ID)
    public function updateDelivery($id, $data) {
        return $this->update($id, $data);
    }

    // Delete delivery (by ID)
    public function deleteDelivery($id) {
        return $this->delete($id);
    }
}
