<?php

session_start();

include '../../includes/config.php';

$exam_type = json_decode($_GET['exam_type']);


if ($exam_type == "all") {
	$exam_query = "SELECT exam_id, exam_name FROM created_exams LIMIT 75";
}
else {
	$exam_query = "SELECT exam_id, exam_name FROM created_exams WHERE exam_type = '$exam_type' LIMIT 75";
}

$results = mysqli_query ($dbconfig, $exam_query);
$data = array();
$data['exam_id'] = array();
$data['exam_name'] = array();

while ($row = mysqli_fetch_assoc($results)) {
	array_push($data['exam_id'], $row['exam_id']);
	array_push($data['exam_name'], $row['exam_name']);
}

$data['count'] = mysqli_num_rows($results);

echo json_encode($data);
exit();

?>