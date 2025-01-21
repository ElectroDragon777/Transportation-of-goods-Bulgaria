<?php

namespace App\Models;
use Core\Model;

class Image extends Model{

    var $primaryKey = 'id';
    var $table = 'images';
    
    var $schema = array(
        array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'gallery_id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'image_name', 'type' => 'varchar', 'default' => '')
    );
}
