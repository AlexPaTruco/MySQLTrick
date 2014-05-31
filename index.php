<?php

require_once 'mysqltrick.php';

$connection = new mysqlTrickClient();

$trickhub = $connection->selectDB("tokenspot");

//$notes = $trickhub->selectTable("notes");


print_r($trickhub->getCurrentDB());

