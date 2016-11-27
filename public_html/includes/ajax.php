<?php

include '../../includes/config.php';
session_start();

$ajax_id = json_decode($_GET['ajax_id']);


switch ($ajax_id) {
	//Attendance
	case 'attendance_check_open':
	$data = array();
	$check_query = "SELECT attendance_code, DATE_FORMAT(start_time, '%D %M at %h:%i %p') AS start_time FROM attendance_sessions WHERE end_time IS NULL;";
	$result = mysqli_query($dbconfig, $check_query);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$data['exists'] = true;
		$data['code_word'] = $row['attendance_code'];
		$data['start_time'] = $row['start_time'];
	}
	else {
		$data['exists'] = false;
	}
	echo json_encode($data);
	break;

	case 'attendance_start':
	$insert_query = "INSERT INTO attendance_sessions (attendance_code) VALUES (".stripslashes($_GET['code_word']).");";
	if (mysqli_query($dbconfig, $insert_query)) {
		echo json_encode(true);
	}
	else {
		echo json_encode(false);
	}
	break;

	case 'attendance_end':
	$date = date_create();
	$timestamp = date_timestamp_get($date);
	$update_query = "UPDATE attendance_sessions SET end_time = from_unixtime(".$timestamp.") WHERE end_time IS NULL;";
	if (mysqli_query($dbconfig, $update_query)) {
		echo json_encode(true);
	}
	else {
		echo json_encode(false);
	}
	break;
	
	case 'attendance_check':
	$code_word = stripslashes(json_decode($_GET['code_word']));
	$student_number = stripslashes(json_decode($_GET['student_number']));

	$check_query = "SELECT id FROM attendance_sessions WHERE end_time IS NULL AND attendance_code = '".$code_word."'";
	$result = mysqli_query($dbconfig, $check_query);
	$row = mysqli_fetch_assoc($result);
	if (mysqli_num_rows($result) > 0) {
		$insert_query = "INSERT INTO attendance_individuals (session_id, student_number) VALUES (".$row['id'].", ".$student_number.");";
		mysqli_query($dbconfig, $insert_query);

		echo json_encode(true);
	}
	else {
		echo json_encode(false);
	}
	break;

	
	
	default:
		# code...
	break;
}

exit();

?>