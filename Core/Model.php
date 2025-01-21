<?php

namespace Core;

class Model {

    private $mysqli;
    private $debug = false;
    public $host = '';
    public $database = '';
    public $user = '';
    public $pass = '';
    public $table = null;
    public $primaryKey = null;

    public function __construct() {
        // Инициализиране на mysqli връзката
        $this->host = DEFAULT_HOST;
        $this->user = DEFAULT_USER;
        $this->pass = DEFAULT_PASS;
        $this->database = DEFAULT_DB;

        $this->connect();
    }

    public function connect() {
        // Създаване на връзка с базата данни
        $this->mysqli = new \mysqli($this->host, $this->user, $this->pass, $this->database);

        // Проверка за грешка при връзка
        if ($this->mysqli->connect_error) {
            die("Connection failed: " . $this->mysqli->connect_error);
        }
    }

    public function getAll($options = null, $column = null, $limit = null) {
        // Създаване на основна SELECT заявка
        $query = "SELECT * FROM " . $this->getTable();

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

    public function get($id) {
        // Връща един запис по primary key
        $primaryKeyName = $this->primaryKey ?: 'id';
        $query = "SELECT * FROM " . $this->getTable() . " WHERE `$primaryKeyName` = ?";
        $arr = $this->executeQuery($query, [$id], 'i'); // 'i' за integer

        return $arr[0];
    }

    public function save($data) {
        // Вставка на нов запис

        $save = array();

        foreach ($this->schema as $field) {
            if (isset($data[$field['name']])) {
                if (!is_array($data[$field['name']])) {
                    $save["`" . $field['name'] . "`"] = $data[$field['name']];
                } else {
                    if (isset($data[$field['name']][0])) {
                        $save["`" . $field['name'] . "`"] = $data[$field['name']][0];
                    }
                }
            }
        }

        $fields = array_keys($save);
        $values = array_values($save);

        $placeholders = implode(',', array_fill(0, count($fields), '?'));
        $query = "INSERT INTO " . $this->getTable() . " (" . implode(',', $fields) . ") VALUES ($placeholders)";

        return $this->executeQuery($query, $values, str_repeat('s', count($values))); // 's' за string
    }

    public function update($data) {
        // Обновяване на съществуващ запис
        $save = array();

        foreach ($this->schema as $field) {

            if (isset($data[$field['name']])) {

                if (!is_array($data[$field['name']])) {
                    $save["`" . $field['name'] . "`"] = $data[$field['name']];
                } else {
                    if (isset($data[$field['name']][0])) {
                        $save["`" . $field['name'] . "`"] = $data[$field['name']][0];
                    }
                }
            }
        }

        $fields = array_keys($save);
        $values = array_values($save);

        $primaryKeyName = $this->primaryKey ?: 'id';

        $set = [];
        foreach ($fields as $field) {
            $set[] = "$field = ?";
        }

        $query = "UPDATE " . $this->getTable() . " SET " . implode(',', $set) . " WHERE `$primaryKeyName` = ?";
        $values[] = $data[$primaryKeyName]; // Добавяме стойността за primary key накрая

        return $this->executeQuery($query, $values, str_repeat('s', count($values) - 1) . 'i'); // Добавяме 'i' за integer
    }

    public function delete($id) {
        // Изтриване на запис
        $primaryKeyName = $this->primaryKey ?: 'id';
        $query = "DELETE FROM " . $this->getTable() . " WHERE `$primaryKeyName` = ?";
        return $this->executeQuery($query, [$id], 'i'); // 'i' за integer
    }

    public function executeQuery($query, $params = [], $types = '') {
        // Подготовка на заявката
        $stmt = $this->mysqli->prepare($query);

        // Проверка дали заявката е успешна
        if (!$stmt) {
            if ($this->debug) {
                echo "Error preparing query: " . $this->mysqli->error;
            }
            return false;
        }

        // Привързване на параметрите към заявката
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        // Изпълнение на заявката
        $stmt->execute();

        // Връщане на резултати
        $result = $stmt->get_result();
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC); // Връщаме резултатите като асоциативен масив
        }
        return true; // За не-заявки с резултати, като UPDATE или DELETE
    }

    public function getTable() {
        // Връща името на таблицата
        return $this->table;
    }

    public function close() {
        // Затваряне на връзката с базата данни
        $this->mysqli->close();
    }
}
