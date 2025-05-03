<?php
namespace App\Models;
use Core\Model;

class CourierLocationHistory extends Model
{
    public $primaryKey = 'id';
    public $table = 'courier_location_history';

    public $schema = array(
        array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'courier_name', 'type' => 'varchar', 'default' => ''),
        array('name' => 'user_id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'current_lat', 'type' => 'decimal', 'default' => '0.0000000'),
        array('name' => 'current_lng', 'type' => 'decimal', 'default' => '0.0000000'),
        array('name' => 'order_id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'timestamp', 'type' => 'timestamp', 'default' => ':NULL') // Corrected default
    );
}
?>