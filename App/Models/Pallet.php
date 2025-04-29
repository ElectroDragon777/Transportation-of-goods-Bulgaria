<?php

namespace App\Models;
use Core\Model;

class Pallet extends Model
{

    var $primaryKey = 'id';
    var $table = 'pallets';

    var $schema = array(
        array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'name', 'type' => 'varchar', 'default' => ''),
        array('name' => 'category', 'type' => 'varchar', 'default' => ''), // Documents, packages.
        array('name' => 'description', 'type' => 'text', 'default' => ':NULL'),
        array('name' => 'stock', 'type' => 'int', 'default' => '0'), // Number of pallets in stock
        // array('name' => 'price', 'type' => 'decimal', 'default' => ':NULL'),
        array('name' => 'size_x_cm', 'type' => 'int', 'default' => ':NULL'), // Length
        array('name' => 'size_y_cm', 'type' => 'int', 'default' => ':NULL'), // Width
        array('name' => 'size_z_cm', 'type' => 'int', 'default' => ':NULL'), // Height
        array('name' => 'weight_kg', 'type' => 'decimal', 'default' => ':NULL'),
        // array('name' => 'code_billlanding', 'type' => 'int', 'default' => '0000000000'), // 10-digit code
        array('name' => 'created_at', 'type' => 'timestamp', 'default' => 'CURRENT_TIMESTAMP')
    );
}
?>