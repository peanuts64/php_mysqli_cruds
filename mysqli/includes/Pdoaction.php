<?php

class Dbactions {
    private $db_host = "127.0.0.1";
    private $db_user = "admin";
    private $db_pass = "taco";
    private $db_name = "asatech";
    
    private $pdo = null;
    private $result = array();
    private $myQuery = "";
    private $numResults = 0;
    private $dev_mode = true; // Set to true for the "Mirror" output

    public function __construct() {
        if (!$this->pdo) {
            try {
                $dsn = "mysql:host={$this->db_host};dbname={$this->db_name};charset=utf8mb4";
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ];
                $this->pdo = new PDO($dsn, $this->db_user, $this->db_pass, $options);
            } catch (PDOException $e) {
                $this->diagnostic_mirror("CONNECTION_FAILURE", $e->getMessage());
            }
        }
    }

    private function diagnostic_mirror($type, $error, $data = null, $table = null) {
        if (!$this->dev_mode) return;

        echo "<div style='background:#1a1a1a; color:#00ff00; padding:20px; border:2px solid #ff0000; font-family:monospace; margin:20px;'>";
        echo "<h2 style='color:#ff0000;'>DEVELOPER DIAGNOSTIC: $type</h2>";
        echo "<p><strong>Error:</strong> $error</p>";
        
        if ($data) {
            echo "<h3>RAW DATA BUFFER:</h3><pre>" . print_r($data, true) . "</pre>";
            if ($table) {
                echo "<h3>SUGGESTED SQL TO CREATE/FIX TABLE:</h3>";
                echo "<code>" . $this->generate_schema_string($table, $data) . "</code>";
            }
        }
        
        if ($this->myQuery) {
            echo "<h3>FAILED QUERY:</h3><code>{$this->myQuery}</code>";
        }
        echo "</div>";
        exit; 
    }

    private function generate_schema_string($table, $data) {
        $sql = "CREATE TABLE IF NOT EXISTS `$table` (\n";
        $sql .= "  `id` INT AUTO_INCREMENT PRIMARY KEY,\n";
        foreach ($data as $key => $value) {
            $type = is_numeric($value) ? "INT" : "TEXT";
            $sql .= "  `$key` $type,\n";
        }
        return rtrim($sql, ",\n") . "\n) ENGINE=InnoDB;";
    }

    public function insert($table, $params = array()) {
        try {
            $keys = array_keys($params);
            $fields = "`" . implode("`, `", $keys) . "`";
            $placeholders = ":" . implode(", :", $keys);
            
            $sql = "INSERT INTO `$table` ($fields) VALUES ($placeholders)";
            $this->myQuery = $sql;

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            
            $this->result = [$this->pdo->lastInsertId()];
            return true;
        } catch (PDOException $e) {
            $this->diagnostic_mirror("INSERT_FAILURE", $e->getMessage(), $params, $table);
            return false;
        }
    }

    public function select($table, $columns = '*', $join = null, $where = null, $order = null, $limit = null) {
        $sql = "SELECT $columns FROM $table";
        if ($join) $sql .= " JOIN $join";
        if ($where) $sql .= " WHERE $where";
        if ($order) $sql .= " ORDER BY $order";
        if ($limit) $sql .= " LIMIT $limit";
        
        $this->myQuery = $sql;

        try {
            $stmt = $this->pdo->query($sql);
            $this->result = $stmt->fetchAll(PDO::FETCH_OBJ);
            $this->numResults = count($this->result);
            return true;
        } catch (PDOException $e) {
            $this->diagnostic_mirror("SELECT_FAILURE", $e->getMessage());
            return false;
        }
    }

    public function updates($table, $where, $params = array()) {
        try {
            $args = [];
            foreach ($params as $field => $value) {
                $args[] = "`$field` = :$field";
            }
            
            $sql = "UPDATE `$table` SET " . implode(', ', $args) . " WHERE $where";
            $this->myQuery = $sql;

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            
            $this->result = [$stmt->rowCount()];
            return true;
        } catch (PDOException $e) {
            $this->diagnostic_mirror("UPDATE_FAILURE", $e->getMessage(), $params, $table);
            return false;
        }
    }

    public function delete($table, $where = null) {
        $sql = ($where == null) ? "DELETE FROM $table" : "DELETE FROM $table WHERE $where";
        $this->myQuery = $sql;

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $this->result = [$stmt->rowCount()];
            return true;
        } catch (PDOException $e) {
            $this->diagnostic_mirror("DELETE_FAILURE", $e->getMessage());
            return false;
        }
    }

    public function getResult() { return $this->result; }
    public function getSql() { return $this->myQuery; }
    public function numRows() { return $this->numResults; }
}

$sadb = new Dbactions();
