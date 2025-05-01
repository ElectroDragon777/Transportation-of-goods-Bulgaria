<?php

namespace App\Models;

use Core\Model;

class OrderPallets extends Model
{
    var $primaryKey = 'id';
    var $table = 'order_pallets';

    var $schema = array(
        array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'order_id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'pallet_id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'quantity', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'category', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'price', 'type' => 'decimal', 'default' => ':NULL'),
        array('name' => 'subtotal', 'type' => 'decimal', 'default' => ':NULL'),
    );
}
?>