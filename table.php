<?php

require_once 'cursor.php';

class table {

    private $table_name;
    private $link;

    public function __construct($link, $table_name) {
        $query = "SELECT * FROM $table_name";
        $result = mysqli_query($link, $query);

        if ($result) {
            $this->table_name = $table_name;
            $this->link = $link;
        } else {
            $error = debug_backtrace();
            die("<strong>[MYSQL_ERROR][LINE: " . $error[0]['line'] . "]:</strong> " . mysqli_error());
        }
    }

    public function count() {
        $query = "SELECT * FROM $this->table_name";
        $result = mysqli_query($this->link, $query);

        return mysqli_num_rows($result);
    }

    public function insert($columns) {

        $parameters = $values = "";

        if (is_array($columns)) {

            $size = sizeof($columns);
            $counter = 0;

            foreach ($columns as $key => $value) {
                $key = mysqli_real_escape_string($key);
                $value = mysqli_real_escape_string($value);
                $parameters .= "$key";
                $values .= "'$value'";

                if ($counter + 1 != $size) {
                    $parameters .= ", ";
                    $values .= ", ";
                    $counter++;
                }
            }


            $query = "INSERT INTO $this->table_name ($parameters) VALUES ($values)";

            if (mysqli_query($this->link, $query)) {
                return true;
            } else {
                $error = debug_backtrace();
                die("<strong>[MYSQL_ERROR][LINE: " . $error[0]['line'] . "]:</strong> " . mysqli_error());
            }
        } else {
            $error = debug_backtrace();
            die("<strong>[MYSQL_TRICK_ERROR][LINE: " . $error[0]['line'] . "]:</strong> The INSERT values must be passed as an array!");
        }
    }

    public function select($conditions = "", $variables = array()) {

        if ($conditions != "" && !empty($variables)) {
            foreach ($variables as $variable => $value) {
                $value = mysqli_real_escape_string($value);
                $conditions = str_replace(":" . $variable, "'" . $value . "'", $conditions);
            }
        }

        $query = "SELECT * FROM $this->table_name " . $conditions;

        $cursor = new cursor($this->link, $query);

        return $cursor;
    }

    public function update($set, $where = array()) {

        if (!is_array($set)) {
            $error = debug_backtrace();
            die("<strong>[MYSQL_TRICK_ERROR][LINE: " . $error[0]['line'] . "]:</strong> The SET values must be passed as an array!");
        }

        $query = "UPDATE $this->table_name SET ";

        foreach ($set as $column_name => $value) {
            $query .= mysqli_real_escape_string($column_name) . " = '" . mysqli_real_escape_string($value) . "' ";
        }
        if (!empty($where)) {
            $query .= "WHERE ";

            foreach ($where as $column_name => $value) {
                $query .= mysqli_real_escape_string($column_name) . " = '" . mysqli_real_escape_string($value) . "' ";
            }
        }

        if (mysqli_query($this->link, $query)) {
            return true;
        } else {
            die(mysqli_error());
        }
    }

}

?>
