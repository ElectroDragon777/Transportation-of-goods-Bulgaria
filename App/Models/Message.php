<?php

namespace App\Models;

use Core\Model;

class Message extends Model
{

    var $primaryKey = 'id';
    var $table = 'messages';

    var $schema = array(
        array('name' => 'id', 'type' => 'int', 'default' => ''),
        array('name' => 'sender_id', 'type' => 'int', 'default' => ''),
        array('name' => 'recipient_id', 'type' => 'int', 'default' => ''),
        array('name' => 'message', 'type' => 'text', 'default' => ''),
        array('name' => 'is_read', 'type' => 'int', 'default' => 0),
        array('name' => 'created_at', 'type' => 'varchar', 'default' => '')
    );
}

?>