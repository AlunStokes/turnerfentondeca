<?php 

function sanitize ($string) {

	$string = htmlspecialchars($string);
	$string = trim($string);
	$string = stripslashes($string);

	return $string;
}


?>