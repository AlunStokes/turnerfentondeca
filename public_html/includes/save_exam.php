<?php

include '../../includes/config.php';

$name = json_decode($_GET['name']);
$question_id = json_decode($_GET['question_id']);
$length = json_decode($_GET['length']);
$type = json_decode($_GET['type']);
//print_r($question_id);

if (mysqli_num_rows(mysqli_query($dbconfig, "SELECT exam_name FROM created_exams WHERE exam_name = '$name'")) > 0) {
	echo json_encode("failed");
	exit();
}

$query_insert_name = "INSERT INTO created_exams (exam_name, num_questions, exam_type) VALUES ('$name', $length, '$type')";
mysqli_query($dbconfig, $query_insert_name) or die (mysqli_error($dbconfig));

$key = mysqli_fetch_array(mysqli_query($dbconfig, "SELECT exam_id FROM created_exams WHERE exam_name = '$name'"), MYSQLI_ASSOC)['exam_id'];
//echo $key;

$query_insert_quesitons = "";
foreach ($question_id as $row) {
	$query_insert_quesitons .= "INSERT INTO created_exams_questions (exam_id, question_id) VALUES ('$key', '$row');";
}

if (mysqli_multi_query($dbconfig, $query_insert_quesitons)) {
echo json_encode("success");
exit();
}

?>