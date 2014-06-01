<?php

require_once 'database.php';

class mysqlTrickClient {

    private $host;
    private $user;
    private $password;
    private $link;

    public function __construct($host = "localhost", $user = "root", $password = "") {

        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->link = mysqli_connect($host, $user, $password) or die(mysqli_error());
    }

    public function close() {
        return mysqli_close($this->link);
    }

    public function connect() {
        mysqli_close($this->link);
        $this->link = mysqli_connect($this->host, $this->user, $this->password);
        
        return $this->link;
    }

    public function dropDB($db) {

        if (!is_string($db)) {
            $db = $db->getCurrentDB();
        }
        
        $query = "DROP DATABASE $db";
        $result = mysqli_query($this->link, $query);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function listDBs() {
        $db_list = mysqli_query($this->link, "SHOW DATABASES");

        while ($db = mysqli_fetch_array($db_list)) {
            $dbnames[] = $db[0];
        }

        return $dbnames;
    }

    public function selectDB($dbname) {

        $db = new database($this->link, $dbname);

        return $db;
    }

}

?>
