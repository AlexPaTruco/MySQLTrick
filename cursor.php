<?php

class cursor {

    private $result;
    private $counter;
    private $link;

    public function __construct($link, $query) {
        $result = mysqli_query($link, $query);

        if ($result) {
            $this->result = $result;
            $this->counter = 0;
            $this->link = $link;
        } else {
            $error = debug_backtrace();
            die("<strong>[MYSQL_ERROR][LINE: " . $error[0]['line'] . "]:</strong> " . mysqli_error());
        }
    }

    public function hasNext() {
        return ($this->counter < mysqli_num_rows($this->result));
    }

    public function getNext() {
        $row = mysqli_fetch_assoc($this->result);
        $this->counter++;

        return $row;
    }

}

?>
