<?php

namespace App\Models;

use Core\Model;

class Gallery extends Model {

    var $primaryKey = 'id';
    var $table = 'gallery';
    var $schema = array(
        array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'gallery_name', 'type' => 'varchar', 'default' => '')
    );

    public function getAllWithImages($options = null, $column = null, $limit = null) {
        $query = "SELECT * FROM " . $this->getTable() . " as t1 LEFT JOIN `images` as t2 ON t1.id = t2.gallery_id ";

        // Проверка дали има подаден масив с условия
        if ($options && is_array($options)) {
            $conditions = [];
            foreach ($options as $field => $value) {
                // Изграждане на условията за WHERE
                $conditions[] = "$field = '$value'";
            }
            // Добавяне на WHERE частта към заявката
            $query .= " WHERE " . implode(" AND ", $conditions);
        } elseif ($options) {
            // Ако $options не е масив, добавяме директно
            $query .= " WHERE " . $options;
        }

        // Добавяне на ORDER BY частта, ако е подаден $column
        if ($column) {
            $query .= " ORDER BY " . $column;
        }

        // Добавяне на LIMIT частта, ако е подаден $limit
        if ($limit) {
            $query .= " LIMIT " . $limit;
        }

        // Изпълняване на заявката
        return $this->executeQuery($query);
    }
}
