<?php

require_once 'table.php';

class database {
    
    private $dbname;
    
    public function __construct($dbname) {
        $this->dbname = $dbname;
        
        mysql_select_db($this->dbname) or die(mysql_error());
    }
    
    public function drop() {
        $query = "DROP DATABASE $this->dbname";
        $result = mysql_query($query);
        
        if($result) {
            return true;
        }else {
            return false;
        }
    }

    public function getCurrentDB() {
        return $this->dbname;
    }
    
    public function listTables() {
        $query = "SHOW TABLES FROM $this->dbname";
        $result = mysql_query($query);
        
        while ($row = mysql_fetch_array($result)) {
            $table_list[] = $row[0];
        }
        
        return $table_list;
    }
    
    public function selectTable($table_name) {
        $table = new table($table_name);
        
        return $table;
    }
   
}
?>
