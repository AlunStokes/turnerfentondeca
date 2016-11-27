<?php

include '../../includes/config.php';


$body = stripslashes(json_decode($_GET['body']));
$title = stripslashes(json_decode($_GET['title']));
$type = stripslashes(json_decode($_GET['type']));
$admin = stripslashes(json_decode($_GET['admin']));

$body = addslashes($body);
$body = nl2br($body);

$title = addslashes($title);
$title = nl2br($title);

	$query = 'INSERT INTO alerts (type, title, body, page, admin) VALUES ("'.$type.'", "'.$title.'", "'.$body.'", "timeline", '.$admin.');';

if (mysqli_query($dbconfig, $query) or die(mysqli_error($dbconfig))) {
	echo json_encode("success");
}
else {
	echo json_encode("fail");
}

?>