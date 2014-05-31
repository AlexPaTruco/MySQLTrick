<?php

require_once 'cursor.php';

class table {

    private $table_name;

    public function __construct($table_name) {
        $query = "SELECT * FROM $table_name";
        $result = mysql_query($query);

        if ($result) {
            $this->table_name = $table_name;
        } else {
            $error = debug_backtrace();
            die("<strong>[MYSQL_ERROR][LINE: " . $error[0]['line'] . "]:</strong> " . mysql_error());
        }
    }

    public function count() {
        $query = "SELECT * FROM $this->table_name";
        $result = mysql_query($query);

        return mysql_num_rows($result);
    }

    public function insert($columns) {

        $parameters = $values = "";

        if (is_array($columns)) {

            $size = sizeof($columns);
            $counter = 0;

            foreach ($columns as $key => $value) {
                $key = mysql_real_escape_string($key);
                $value = mysql_real_escape_string($value);
                $parameters .= "$key";
                $values .= "'$value'";

                if ($counter + 1 != $size) {
                    $parameters .= ", ";
                    $values .= ", ";
                    $counter++;
                }
            }


            $query = "INSERT INTO $this->table_name ($parameters) VALUES ($values)";

            if (mysql_query($query)) {
                return true;
            } else {
                $error = debug_backtrace();
                die("<strong>[MYSQL_ERROR][LINE: " . $error[0]['line'] . "]:</strong> " . mysql_error());
            }
        } else {
            $error = debug_backtrace();
            die("<strong>[MYSQL_TRICK_ERROR][LINE: " . $error[0]['line'] . "]:</strong> The INSERT values must be passed as an array!");
        }
    }

    public function select($conditions = "", $variables = array()) {

        if ($conditions != "" && !empty($variables)) {
            foreach ($variables as $variable => $value) {
                $value = mysql_real_escape_string($value);
                $conditions = str_replace(":" . $variable, "'" . $value . "'", $conditions);
            }
        }

        $query = "SELECT * FROM $this->table_name " . $conditions;

        $cursor = new cursor($query);

        return $cursor;
    }

    public function update($set, $where = array()) {

        if (!is_array($set)) {
            $error = debug_backtrace();
            die("<strong>[MYSQL_TRICK_ERROR][LINE: " . $error[0]['line'] . "]:</strong> The SET values must be passed as an array!");
        }

        $query = "UPDATE $this->table_name SET ";

        foreach ($set as $column_name => $value) {
            $query .= mysql_real_escape_string($column_name) . " = '" . mysql_real_escape_string($value) . "' ";
        }
        if (!empty($where)) {
            $query .= "WHERE ";

            foreach ($where as $column_name => $value) {
                $query .= mysql_real_escape_string($column_name) . " = '" . mysql_real_escape_string($value) . "' ";
            }
        }

        if (mysql_query($query)) {
            return true;
        } else {
            die(mysql_error());
        }
    }

}

?>
