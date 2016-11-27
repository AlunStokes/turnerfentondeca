<?php

include '../../includes/config.php';

$ajax_id = json_decode($_GET['ajax_id']);
$json_message = addslashes($_GET['json_message']);
$message = stripslashes(json_decode($_GET['message']));
if ($ajax_id == "post") {
$poster = stripslashes(json_decode($_GET['poster']));
}
$class = stripslashes(json_decode($_GET['post_class']));
if ($ajax_id == "edit") {
$post_id = stripslashes(json_decode($_GET['post_id']));
	}

$message = addslashes($message);
$message = nl2br($message);

if ($ajax_id == "post") {
	$query = 'INSERT INTO class_posts (poster, message, json_message, class) VALUES ("'.$poster.'", "'.$message.'", "'.$json_message.'", "'.$class.'");';
}
else if ($ajax_id == "edit") {
	$query = 'UPDATE class_posts SET message="'.$message.'", json_message="'.$json_message.'", class="'.$class.'" WHERE id='.$post_id.';';
}

if (mysqli_query($dbconfig, $query) or die(mysqli_error($dbconfig))) {
	echo json_encode("success");
}
else {
	echo json_encode("fail");
}

?>