<?php

session_start();
include ('../../includes/config.php');

$post_id = json_decode($_GET['post_id']);

$data_query = "DELETE FROM class_posts WHERE id = $post_id";
if (mysqli_query($dbconfig, $data_query)) {
	echo json_encode("success");
}
else {
	echo json_encode("fail");
}

exit();

?>