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
}
