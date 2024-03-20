<?php

class DatabaseController {
    private $connection;

    public function __construct() {
        // Establecer la conexión a la base de datos
        $host = 'mariadb';
        $username = 'prueba_web';
        $password = '123456';
        $database = 'prueba';
        $this->connect($host, $username, $password, $database);
    }

    private function connect($host, $username, $password, $database) {
        $this->connection = new mysqli($host, $username, $password, $database);
        if ($this->connection->connect_error) {
            die("Error de conexión: " . $this->connection->connect_error);
        }
    }

    public function create($table, $data) {
        $columns = implode(', ', array_keys($data));
        $values = "'" . implode("', '", array_values($data)) . "'";
        $query = "INSERT INTO $table ($columns) VALUES ($values)";
        return $this->executeQuery($query);
    }

    public function read($table, $id) {
        if ($id !== 0) {
            $query = "SELECT * FROM $table WHERE id = $id";
        } else {
            $query = "SELECT * FROM $table";
        }
        return $this->executeQuery($query);
    }

    public function update($table, $id, $data) {
        $setValues = '';
        foreach ($data as $key => $value) {
            $setValues .= "$key = '$value', ";
        }
        $setValues = rtrim($setValues, ', ');
        $query = "UPDATE $table SET $setValues WHERE id = $id";
        return $this->executeQuery($query);
    }

    public function delete($table, $id) {
        $query = "DELETE FROM $table WHERE id = $id";
        return $this->executeQuery($query);
    }

    private function executeQuery($query) {
        $result = $this->connection->query($query);
        if ($result === false) {
            return "Error: " . $this->connection->error;
        }
        return $result;
    }
}

?>