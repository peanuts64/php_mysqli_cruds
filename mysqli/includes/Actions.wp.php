<?php

class Dbactions {
    private $result = array();
    private $myQuery = "";
    private $numResults = 0;
    private $dev_mode = true; 

    public function __construct() {
        // We don't need to connect; WordPress is already connected.
        global $wpdb;
        if (!$wpdb) {
            $this->diagnostic_mirror("WP_HOST_FAILURE", "Global wpdb object not found. Are we inside WordPress?");
        }
    }

    private function diagnostic_mirror($type, $error, $data = null, $table = null) {
        if (!$this->dev_mode) return;
        // Keep the exact same UI as your PDO version for consistency
        echo "<div style='background:#1a1a1a; color:#00ff00; padding:20px; border:2px solid #ff0000; font-family:monospace;'>";
        echo "<h2 style='color:#ff0000;'>WP-HOST DIAGNOSTIC: $type</h2>";
        echo "<p><strong>Error:</strong> $error</p>";
        if ($data) {
            echo "<h3>DATA BUFFER:</h3><pre>" . print_r($data, true) . "</pre>";
        }
        echo "</div>";
        exit;
    }

    public function insert($table, $params = array()) {
        global $wpdb;
        // WordPress handles the preparation for us
        $success = $wpdb->insert($wpdb->prefix . $table, $params);
        
        if ($success) {
            $this->result = [$wpdb->insert_id];
            return true;
        } else {
            $this->diagnostic_mirror("INSERT_FAILURE", $wpdb->last_error, $params, $table);
            return false;
        }
    }

    public function select($table, $columns = '*', $join = null, $where = null, $order = null, $limit = null) {
        global $wpdb;
        $sql = "SELECT $columns FROM " . $wpdb->prefix . $table;
        if ($join) $sql .= " JOIN $join";
        if ($where) $sql .= " WHERE $where";
        if ($order) $sql .= " ORDER BY $order";
        if ($limit) $sql .= " LIMIT $limit";
        
        $this->myQuery = $sql;
        $this->result = $wpdb->get_results($sql, OBJECT);
        $this->numResults = count($this->result);
        return true;
    }

    public function updates($table, $where_string, $params = array()) {
        global $wpdb;
        // WP update expects the 'WHERE' as an array, but your standalone uses a string.
        // We handle that "noise" here so your core logic stays clean.
        $sql = "UPDATE " . $wpdb->prefix . $table . " SET ";
        $args = [];
        foreach ($params as $field => $value) { $args[] = "`$field` = '" . esc_sql($value) . "'"; }
        $sql .= implode(', ', $args) . " WHERE $where_string";
        
        $this->myQuery = $sql;
        $success = $wpdb->query($sql);
        
        if ($success !== false) {
            return true;
        } else {
            $this->diagnostic_mirror("UPDATE_FAILURE", $wpdb->last_error, $params, $table);
            return false;
        }
    }

    // Pass-through functions to keep the interface identical
    public function getResult() { return $this->result; }
    public function getSql() { return $this->myQuery; }
    public function numRows() { return $this->numResults; }
}
