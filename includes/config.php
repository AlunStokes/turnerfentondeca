<?php

$host = 'localhost';
$username = 'root';
$dbpassword = '';
$database = 'simplelogin';

$dbconfig = mysqli_connect($host, $username, $dbpassword);

// Check connection
if (!$dbconfig) {
	//die("Connection failed: " . mysqli_connect_error());
}
//echo "Connected successfully";

//select a database to work with
$selected = mysqli_select_db($dbconfig, $database) or die("Could not select simplelogin");

?>