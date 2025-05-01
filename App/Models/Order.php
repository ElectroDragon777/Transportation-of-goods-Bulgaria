<?php

namespace App\Models;
use Core\Model;

class Order extends Model
{

    var $primaryKey = 'id';
    var $table = 'orders';

    var $schema = array(
        array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'user_id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'start_point', 'type' => 'varchar', 'default' => ''),
        array('name' => 'end_destination', 'type' => 'varchar', 'default' => ''),
        array('name' => 'status', 'type' => 'varchar', 'default' => ''),
        array('name' => 'product_name', 'type' => 'varchar', 'default' => ''),
        array('name' => 'product_price', 'type' => 'decimal', 'default' => ':NULL'),
        array('name' => 'quantity', 'type' => 'int', 'default' => '0'),
        array('name' => 'total_amount', 'type' => 'decimal', 'default' => ':NULL'),
        array('name' => 'created_at', 'type' => 'varchar', 'default' => ''),
        array('name' => 'last_processed', 'type' => 'varchar', 'default' => ''),
        array('name' => 'courier_id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'tracking_number', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'delivery_date', 'type' => 'varchar', 'default' => ':NULL')
    );
}
?>