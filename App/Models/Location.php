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
}
