<?php

namespace App\Models;
use Core\Model;

class User extends Model{

    var $primaryKey = 'id';
    var $table = 'users';
    
    var $schema = array(
        array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'first_name', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'second_name', 'type' => 'varchar', 'default' => ''),
        array('name' => 'phone', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'email ', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'address', 'type' => 'varchar', 'default' => ''),
        array('name' => 'city', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'state', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'password', 'type' => 'varchar', 'default' => ''),
        array('name' => 'last_login', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'type', 'type' => 'varchar', 'default' => ':NULL')
    );
}
