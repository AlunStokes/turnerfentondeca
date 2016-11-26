<?php

include '../../includes/config.php';

$ajax_id = json_decode($_GET['ajax_id']);

	$data = array();

if ($ajax_id == "if_open") {
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
}
else if ($ajax_id == "start") {

	$insert_query = "INSERT INTO attendance_sessions (attendance_code) VALUES (".stripslashes($_GET['code_word']).");";
	if (mysqli_query($dbconfig, $insert_query)) {
		$data = true;
	}
	else {
		$data = false;
	}
	echo json_encode($data);
}
else if ($ajax_id == "end") {

	$date = date_create();
	$timestamp = date_timestamp_get($date);
	$update_query = "UPDATE attendance_sessions SET end_time = from_unixtime(".$timestamp.") WHERE end_time IS NULL;";
	if (mysqli_query($dbconfig, $update_query)) {
		$data = true;
	}
	else {
		$data = false;
	}
	echo json_encode($data);

}
else if ($ajax_id == "check") {

	$code_word = stripslashes(json_decode($_GET['code_word']));
	$student_number = stripslashes(json_decode($_GET['student_number']));

	$check_query = "SELECT id FROM attendance_sessions WHERE end_time IS NULL AND attendance_code = '".$code_word."'";
	$result = mysqli_query($dbconfig, $check_query);
	$row = mysqli_fetch_assoc($result);
	if (mysqli_num_rows($result) > 0) {
		$insert_query = "INSERT INTO attendance_individuals (session_id, student_number) VALUES (".$row['id'].", ".$student_number.");";
		mysqli_query($dbconfig, $insert_query);

		echo json_encode(true);
		exit();
	}
	else {
		echo json_encode(false);
		exit();
	}
}
else if ($ajax_id == "check_attendance") {

	$id = json_decode($_GET['id']);

	$data['first_name'] = array();
	$data['last_name'] = array();
	$data['student_number'] = array();
	$data['cluster'] = array();
	$data['present'] = array();


	$get_users_query = "SELECT first_name, last_name, student_number, cluster FROM members WHERE admin = 0;";
	$result = mysqli_query($dbconfig, $get_users_query);
	while ($row = mysqli_fetch_assoc($result)) {
		array_push($data['first_name'], $row['first_name']);
		array_push($data['last_name'], $row['last_name']);
		array_push($data['cluster'], $row['cluster']);
		array_push($data['student_number'], $row['student_number']);
	}

	$data['present'] = array_fill(0, count($data['first_name']), "");

	$present = array();
	$get_present_query = "SELECT student_number FROM attendance_individuals WHERE session_id = $id;";
	$result = mysqli_query($dbconfig, $get_present_query);
	while ($row = mysqli_fetch_assoc($result)) {
		array_push($present, $row['student_number']);
	}

	for ($i = 0; $i < count($present); $i++) {
		$index = array_search($present[$i], $data['student_number']);
		if ($index) {
			$data['present'][$index] = "X";
		}
	}

	$data['count'] = count($data['first_name']);

	echo json_encode($data);

}

exit();

?>