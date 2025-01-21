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
}
