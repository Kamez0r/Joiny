<?php


require 'db.php';

//echo $MYSQL_DB;

$mysqli = new mysqli($MYSQL_HOST, $MYSQL_USER, $MYSQL_PASS, $MYSQL_DB);

//$mysqli = new mysqli("localhost", "joiny", "joiny", "joiny");
// Check connection

if ($mysqli -> connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
    exit();
}
