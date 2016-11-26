<?php

include ("../../includes/config.php");

session_start();

$student_number = $_SESSION['student_number'];
$alert_id = json_decode($_GET['alert_id']);

$insert_query = "INSERT INTO seen_alert (student_number, alert_id) VALUES ($student_number, $alert_id);";
if (mysqli_query($dbconfig, $insert_query)) {
	echo json_encode("success");
}
else {
	echo json_encode("fail");
}

exit();

?>