<?php

require_once 'table.php';

class database {
    
    private $dbname;
    private $link;
    
    public function __construct($link, $dbname) {
        $this->dbname = $dbname;
        $this->link = $link;
        
        mysqli_select_db($this->link, $this->dbname) or die(mysqli_error());
    }
    
    public function drop() {
        $query = "DROP DATABASE $this->dbname";
        $result = mysqli_query($this->link, $query);
        
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
        $result = mysqli_query($this->link, $query);
        
        while ($row = mysqli_fetch_array($result)) {
            $table_list[] = $row[0];
        }
        
        return $table_list;
    }
    
    public function selectTable($table_name) {
        $table = new table($this->link, $table_name);
        
        return $table;
    }
   
}
?>
