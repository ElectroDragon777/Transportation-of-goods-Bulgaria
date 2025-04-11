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
        array('name' => 'price', 'type' => 'decimal', 'default' => ':NULL'),
        array('name' => 'subtotal', 'type' => 'decimal', 'default' => ':NULL'),
        array('name' => 'mini_tax', 'type' => 'decimal', 'default' => '0.00') // Default to 0.00
    );
}
?>