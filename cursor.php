<?php

class cursor {

    private $result;
    private $counter;

    public function __construct($query) {
        $result = mysql_query($query);

        if ($result) {
            $this->result = $result;
            $this->counter = 0;
        } else {
            $error = debug_backtrace();
            die("<strong>[MYSQL_ERROR][LINE: " . $error[0]['line'] . "]:</strong> " . mysql_error());
        }
    }

    public function hasNext() {
        return ($this->counter < mysql_num_rows($this->result));
    }

    public function getNext() {
        $row = mysql_fetch_assoc($this->result);
        $this->counter++;

        return $row;
    }

}

?>
