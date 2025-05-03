<?php
namespace App\Models;
use Core\Model;

class CourierTracking extends Model
{
    public $primaryKey = 'id';
    public $table = 'courier_tracking';

    public $schema = array(
        array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'courier_name', 'type' => 'varchar', 'default' => ''),
        array('name' => 'order_id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'user_id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'start_point_lat', 'type' => 'decimal', 'default' => '0.0000000'),
        array('name' => 'start_point_lng', 'type' => 'decimal', 'default' => '0.0000000'),
        array('name' => 'end_destination_lat', 'type' => 'decimal', 'default' => '0.0000000'),
        array('name' => 'end_destination_lng', 'type' => 'decimal', 'default' => '0.0000000'),
        array('name' => 'current_location_lat', 'type' => 'decimal', 'default' => '0.0000000'),
        array('name' => 'current_location_lng', 'type' => 'decimal', 'default' => '0.0000000'),
        array('name' => 'last_updated', 'type' => 'timestamp', 'default' => ':NULL'), //handy default value in the database
        array('name' => 'estimated_arrival_time', 'type' => 'datetime', 'default' => ':NULL'),
        array('name' => 'created_at', 'type' => 'timestamp', 'default' => ':NULL') //handy default value in the database
    );
}
?>