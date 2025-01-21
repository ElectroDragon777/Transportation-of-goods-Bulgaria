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
}
