<?php

class Dbactions {
  
  /* Variables for DB Connect */
  private $db_host = "127.0.0.1";    // Hostname
  private $db_user = "admin";         // Username
  private $db_pass = "taco";             // Password
  private $db_name = "asatech";         // Database
  
  /* Extra variables that are required by other functions */
  private $con = false;              // Checks connection is active
  private $result = array();         // Returns the query result
  private $myQuery = "";             // Process SQL Query
  private $numResults = "";          // Returns the number of rows
  private $conn;
  /* Function to make connection to database */
  public function __construct() {
    if (!$this->con) {
      // mysql_connect() with variables defined at the start of Database class
      $myconn = mysqli_connect($this->db_host,$this->db_user,$this->db_pass);
      if ($myconn) {
        // Credentials have been pass through mysql_connect() now select the database
        $seldb = $myconn->select_db($this->db_name);
        if ($seldb) {
          $this->con = true;
	  $this->conn = $myconn;
          // Return TRUE, when the Connection has been made 
          return true;                              
        } else {
          array_push($this->result,mysqli_error()); 
          // Return FALSE, when there's a problem in selecting database
          return false;
        }  
      } else {
        array_push($this->result,mysqli_error());
        // Return FALSE, Problem in connecting
        return false;
      }  
    } else {
      // Return TRUE, When connection has already been made
      return true;
    }   
  }
  
  /* Function to disconnect the database */
  public function disconnect() {
    if ($this->con) {
      if (mysqli_close()) {
        $this->con = false;
        return true;
      } else {
        return false;
      }
    }
  }
  
  /* Function to process an sql query */
  public function sql($sql) {
    $query = mysqli_query($sql);
    $this->myQuery = $sql;

    // If the query returns >= 1 assign the number of rows to numResults                              
    if ($query) {
      $this->numResults = mysqli_num_rows($query); 

      // Loop through the query results by the number of rows returned
      for ($i = 0; $i < $this->numResults; $i++) {
        $r = mysqli_fetch_array($query);
        $key = array_keys($r);
        for ($x = 0; $x < count($key); $x++) {                                      
          // Sanitizes keys so only alphavalues are allowed
          if (!is_int($key[$x])) {
            if (mysqli_num_rows($query) >= 1) {
              $this->result[$i][$key[$x]] = $r[$key[$x]];
            } else {
              $this->result = null;
            }
          }
        }
      }
      return true;
    } else {
      array_push($this->result,mysqli_error());
      return false;
    }
  }
  
  /* Private function to check if table exists for use with queries */
  private function tableExists($table) {
    $tablesInDb = $this->conn->query('SHOW TABLES FROM '.$this->db_name.' LIKE "'.$table.'"');
    if ($tablesInDb) {
      if (mysqli_num_rows($tablesInDb)==1) {
        return true; // The table exists
      } else {
        array_push($this->result,$table." does not exist in this database");
        return false; // The table does not exist
      }
    }
  }
  
  /* Function to insert into the database */
  public function insert($table,$params=array()) {
    // If the table exists
    if ($this->tableExists($table)) {
      $sql='INSERT INTO `'.$table.'` (`'.implode('`, `',array_keys($params)).'`) VALUES (\'' . implode('\', \'', $params) . '\')';
      $this->myQuery = $sql; 
      // The data has been inserted      
      if ($ins = mysqli_query($this->conn, $sql)) {
        array_push($this->result,mysqli_insert_id($this->conn));
        return true;
      } else {
        // The data has not been inserted
echo $sql;
#        array_push($this->result,mysqli_error());
        return false;
      }
    } else {
      return false;
    }
  }
  
  /* Function to SELECT from the database */
  public function select($table, $columns = '*', $join = null, $where = null, $order = null, $limit = null) {
    $q = 'SELECT '.$columns.' FROM '.$table;
    if ($join != null) {
      $q .= ' JOIN '.$join;
    }
    if ($where != null) {
      $q .= ' WHERE '.$where;
    }
    if ($order != null) {
      $q .= ' ORDER BY '.$order;
    }
    if ($limit != null){
      $q .= ' LIMIT '.$limit;
    }
    $this->myQuery = $q; 
    
    // If the table exists
    if ($this->tableExists($table)) {
      $query = mysqli_query($this->conn, $q);
      // If the query returns >= 1 assign the number of rows to numResults
      if ($query) {
        $this->numResults = mysqli_num_rows($query);
        while( $results[] = mysqli_fetch_object($query));
        array_pop ( $results );
        $this->result = $results;
        return true; // Query was successful
      } else {
        array_push($this->result,mysqli_error());
        return false; // No rows where returned
      }
    } else{
      return false;
    }
  }
  
  /* Function to update row in database */
  public function updates($table,$where,$params=array()){
    // If the table exists
    if ($this->tableExists($table)) {
      $args=array();
      print_r($params);
      foreach ($params as $field=>$value) {
        $args[]=$field.'="'.$value.'"'; // Seperate each column out with it's corresponding value
      }
      print_r($args);
      // Create the query
      $sql='UPDATE '.$table.' SET ' . implode(',', $args) .' WHERE '.$where;
      $this->myQuery = $sql; 
      echo $sql;
      if ($query = mysqli_query($this->conn, $sql)) {
        array_push($this->result,mysqli_affected_rows($this->conn));
        return true; // Update has been successful
      } else {
        array_push($this->result,mysqli_error());
        return false; // Update has not been successful
      }
    } else {
      return false; // The table does not exist
    }
  }
  
  /* Function to delete table or row(s) from database */
  public function delete($table,$where = null) {
    // If the table exists
    if ($this->tableExists($table)) {
      if ($where == null) {
        // Create query to delete table
        $delete = 'DELETE '.$table;
      } else {
        // Create query to delete rows
        $delete = 'DELETE FROM '.$table.' WHERE '.$where;
      }
            
      if ($del = mysqli_query($this->conn, $delete)) {
        array_push($this->result,mysqli_affected_rows($this->conn));
        $this->myQuery = $delete; 
        // The query exectued correctly
        return true;
      } else {
        array_push($this->result,mysqli_error());
        // The query did not execute correctly
        return false;
      }
    } else {
      return false;
    }
  }
  
  
  /* Function to import csv data into mysql databse */
  public function csv_imports($file_name, $table_name, $file_heading, $columns=array() ){
    $field_separate_char = ",";   // separation character   
    $field_enclose_char  = "\"";  // enclose character
    $field_escape_char   = "\\";  // escape character
    
    if (!empty($columns)) {
      // If table exists
      if ($this->tableExists($table_name)) {
        $sql = "LOAD DATA INFILE '".@mysqli_escape_string($this->conn, $file_name).
         "' INTO TABLE `".$table_name.
         "` FIELDS TERMINATED BY '".@mysqli_escape_string($this->conn, $field_separate_char).
         "' OPTIONALLY ENCLOSED BY '".@mysqli_escape_string($this->conn, $field_enclose_char).
         "' ESCAPED BY '".@mysqli_escape_string($this->conn, $field_escape_char).
         "' ".
         ($file_heading ? " IGNORE 1 LINES " : "")
         ."(`".implode("`,`", $columns)."`)";
        
        $res = @mysqli_query($this->conn, $sql);
        return $res;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
  
  /* Function to export csv from mysql database */
  public function csv_export($table, $columns = '*', $join = null, $where = null, $order = null){
    $q = 'SELECT '.$columns.' FROM '.$table;
    if ($join != null) {
      $q .= ' JOIN '.$join;
    }
    if ($where != null) {
      $q .= ' WHERE '.$where;
    }
    if ($order != null) {
      $q .= ' ORDER BY '.$order;
    }
    $this->myQuery = $q; 
    
    // If the table exists
    if ($this->tableExists($table)) {
          
      function gen_csv($fields) {
        $separator = '';
        foreach ($fields as $field) {
          if (preg_match('/\\r|\\n|,|"/', $field)) {
            $field = '"' . str_replace('"', '""', $field) . '"';
          }
          echo $separator . $field;
          $separator = ',';
        }
        echo "\r\n";
      }
  
      $query = @mysqli_query($q);
      if ($query) {
        //Following headers instruct the browser to treat the data as a csv file called export.csv
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename=data.csv');

        $row = mysqli_fetch_assoc($query);
        if ($row) {
          gen_csv(array_keys($row));
          while ($row) {
            gen_csv($row);
            $row = mysqli_fetch_assoc($query);
          }
          exit;
        }
      } else {
        return false;
      } 
    } else {
      return false;
    }
  }
  
  
  /* Public function to return the data to the user */
  public function getResult() {
    $val = $this->result;
    $this->result = array();
    return $val;
  }

  /* Pass the SQL back for debugging */
  public function getSql() {
    $val = $this->myQuery;
    $this->myQuery = array();
    return $val;
  }

  /* Pass the number of rows back */
  public function numRows() {
    $val = $this->numResults;
    $this->numResults = array();
    return $val;
  }
  
} // End of class

$sadb = new Dbactions(); 
  
?>
