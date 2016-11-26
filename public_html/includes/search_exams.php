<?php

session_start();

include '../../includes/config.php';

if (isset($_GET['search'])) {
	$search = json_decode($_GET['search']);
}
else {
	$search = "";
}
$exam_type = json_decode($_GET['exam_type']);


if ($exam_type == "all") {
	$exam_query = "SELECT exam_id, exam_name, num_questions, exam_type FROM created_exams WHERE NOT EXISTS (SELECT * FROM exam_results WHERE student_number = ".$_SESSION['student_number']." AND exam_id = created_exams.exam_id) AND exam_name LIKE '%".$search."%' LIMIT 75";
}
else {
	$exam_query = "SELECT exam_id, exam_name, num_questions, exam_type FROM created_exams WHERE NOT EXISTS (SELECT * FROM exam_results WHERE student_number = ".$_SESSION['student_number']." AND exam_id = created_exams.exam_id) AND exam_name LIKE '%".$search."%' AND exam_type = '$exam_type' LIMIT 75";
}

$results = mysqli_query ($dbconfig, $exam_query);
$data = array();
$data['exam_id'] = array();
$data['exam_name'] = array();
$data['num_questions'] = array();
$data['exam_type'] = array();

while ($row = mysqli_fetch_assoc($results)) {
	array_push($data['exam_id'], $row['exam_id']);
	array_push($data['exam_name'], $row['exam_name']);
	array_push($data['num_questions'], $row['num_questions']);
	if ($row['exam_type'] == 'marketing') {
		array_push($data['exam_type'], 'Marketing');
	}
	else if ($row['exam_type'] == 'businessadmin') {
		array_push($data['exam_type'], 'Business Administration');
	}
	else if ($row['exam_type'] == 'finance') {
		array_push($data['exam_type'], 'Finance');
	}
	else if ($row['exam_type'] == 'hospitality') {
		array_push($data['exam_type'], 'Hospitality');
	}
	else {
		array_push($data['exam_type'], 'Mixed Clusters');
	}

}

$data['count'] = mysqli_num_rows($results);

echo json_encode($data);
exit();

?>