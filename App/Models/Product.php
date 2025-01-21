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
}
