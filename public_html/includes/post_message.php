<?php

include '../../includes/config.php';

$message = stripslashes(json_decode($_GET['message']));
$poster = stripslashes(json_decode($_GET['poster']));
$class = stripslashes(json_decode($_GET['post_class']));


$message = addslashes($message);
$message = nl2br($message);

$insert_query = "INSERT INTO class_posts (poster, message, class) VALUES ('".$poster."', '".$message."', '".$class."');";

if (mysqli_query($dbconfig, $insert_query)) {
	echo json_encode("success");
}
else {
	echo json_encode("fail");
}

?>