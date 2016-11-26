<?php

session_start();
include ('../../includes/config.php');

$exam_id = json_decode($_GET['exam_id']);

$data = array();

$data_query = "SELECT first_name, last_name, exam_results.student_number, percentage, score, total FROM exam_results JOIN members ON members.student_number = exam_results.student_number WHERE exam_id = ".$exam_id." ORDER BY percentage DESC;";
$results = mysqli_query($dbconfig, $data_query);
if ($results != false) {
$name['first_name'] = array();
$name['last_name'] = array();
$data['name'] = array();
$data['student_number'] = array();
$data['score'] = array();
$data['total'] = array();
$data['percentage'] = array();
$data['success'] = true;
	while ($row = mysqli_fetch_assoc($results)) {
		array_push($name['first_name'], $row['first_name']);
		array_push($name['last_name'], $row['last_name']);
		array_push($data['student_number'], $row['student_number']);
		array_push($data['score'], $row['score']);
		array_push($data['total'], $row['total']);
		array_push($data['percentage'], $row['percentage']);
	}

	for ($i = 0; $i < count($name['first_name']); $i++) {
		$data['name'][$i] = $name['first_name'][$i]." ".$name['last_name'][$i];
	}
	$data['count'] = mysqli_num_rows($results);
}
else {
	$data['success'] = false;
}

echo json_encode($data);
exit();

?>