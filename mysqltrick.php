<?php

require_once 'database.php';

class mysqlTrickClient {

    private $host;
    private $user;
    private $password;

    public function __construct($host = "localhost", $user = "root", $password = "") {

        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        mysql_connect($host, $user, $password) or die(mysql_error());
    }

    public function close() {
        return mysql_close();
    }

    public function connect() {
        mysql_close();
        return mysql_connect($this->host, $this->user, $this->password);
    }

    public function dropDB($db) {

        if (!is_string($db)) {
            $db = $db->getCurrentDB();
        }
        
        $query = "DROP DATABASE $db";
        $result = mysql_query($query);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function listDBs() {
        $db_list = mysql_list_dbs();

        while ($db = mysql_fetch_array($db_list)) {
            $dbnames[] = $db[0];
        }

        return $dbnames;
    }

    public function selectDB($dbname) {

        $db = new database($dbname);

        return $db;
    }

}

?>
