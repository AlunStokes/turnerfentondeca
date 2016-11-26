<?php

//header("Content-Type: application/json");

session_start();

include '../../includes/config.php';


$chosen = $_POST['data'];
$chosen = json_decode($chosen, true);

$data = array();
$data['answers'] = array();
$data['correct'] = array();
$data['num_correct'] = 0;
$data['percent_correct'] = 0;


$query_answers = 'SELECT question_id, answer FROM questions_answers WHERE question_id IN ('.implode(",",$_SESSION['questions_id']).')';

$result = mysqli_query($dbconfig, $query_answers) or die (mysqli_error($dbconfig));

while ($row = mysqli_fetch_assoc($result)) {
	$data['answers'][$row['question_id']] = $row['answer'];
}

foreach ($_SESSION['questions_id'] as $id) {
	if ($data['answers'][$id] == $chosen[$id]) {
		$data['correct'][$id] = 1;
		$data['num_correct'] ++;
	}
	else {
		$data['correct'][$id] = 0;
	}
}

$data['percent_correct'] = round(($data['num_correct']*100)/$_SESSION['num_questions'],1); 

if (isset($_SESSION['exam_id'])) {
	$add_exam_query = 'INSERT INTO exam_results (student_number, exam_id, percentage, score, total) VALUES ('.$_SESSION["student_number"].', '.$_SESSION["exam_id"].', '.$data["percent_correct"].', '.$data["num_correct"].', '.$_SESSION["num_questions"].')';
	unset($_SESSION['exam_id']);
}
else {
	$add_exam_query = 'INSERT INTO exam_results (student_number, percentage, score, total) VALUES ('.$_SESSION["student_number"].', '.$data["percent_correct"].', '.$data["num_correct"].', '.$_SESSION["num_questions"].')';
}
mysqli_query($dbconfig, $add_exam_query) or die(mysqli_error($dbconfig));

echo json_encode($data);

$add_attempted_query = "";
$counter = 0;
foreach ($_SESSION['questions_id'] as $id) {
	$add_attempted_query .= 'INSERT INTO questions_attempted (student_number, question_id, correct) VALUES ('.$_SESSION["student_number"].', '.$_SESSION["questions_id"][$counter].', '.$data["correct"][$id].');';
	$counter++;
}
mysqli_multi_query($dbconfig, $add_attempted_query);
do { 
	mysqli_use_result($dbconfig);
} while(mysqli_more_results($dbconfig) && mysqli_next_result($dbconfig));

//Update statistics Section
$query = array();
$query['correct'] = array();
$query['cluster'] = array();

$get_scores= "SELECT cluster, correct FROM questions_attempted JOIN questions_cluster ON questions_attempted.question_id = questions_cluster.question_id WHERE student_number = ".$_SESSION['student_number']."";
$get_scores_result = mysqli_query($dbconfig, $get_scores) or die (mysqli_error($dbconfig));
while ($row = mysqli_fetch_assoc($get_scores_result)) {
	array_push($query['correct'], $row['correct']);
	array_push($query['cluster'], $row['cluster']);
}

$num = array();
$num['correct'] = array();
$num['attempted'] = array();
$num['correct']['marketing'] = 0;
$num['correct']['businessadmin'] = 0;
$num['correct']['finance'] = 0;
$num['correct']['hospitality'] = 0;
$num['attempted']['marketing'] = 0;
$num['attempted']['businessadmin'] = 0;
$num['attempted']['finance'] = 0;
$num['attempted']['hospitality'] = 0;

for ($i = 0; $i < count($query['correct']); $i++) {
	if ($query['cluster'][$i] == 'marketing') {
		if ($query['correct'][$i] == 1) {
			$num['correct']['marketing']++;
		}
		$num['attempted']['marketing']++;
	}
	else if ($query['cluster'][$i] == 'businessadmin') {
		if ($query['correct'][$i] == 1) {
			$num['correct']['businessadmin']++;
		}
		$num['attempted']['businessadmin']++;
	}
	else if ($query['cluster'][$i] == 'finance') {
		if ($query['correct'][$i] == 1) {
			$num['correct']['finance']++;
		}
		$num['attempted']['finance']++;
	}
	else if ($query['cluster'][$i] == 'hospitality') {
		if ($query['correct'][$i] == 1) {
			$num['correct']['hospitality']++;
		}
		$num['attempted']['hospitality']++;
	}
}

$insert_scores = "UPDATE
user_statistics
SET 
num_correct_marketing = ".$num['correct']['marketing'].", 
num_attempted_marketing = ".$num['attempted']['marketing'].", 
num_correct_businessadmin = ".$num['correct']['businessadmin'].", 
num_attempted_businessadmin = ".$num['attempted']['businessadmin'].", 
num_correct_finance = ".$num['correct']['finance'].", 
num_attempted_finance = ".$num['attempted']['finance'].", 
num_correct_hospitality = ".$num['correct']['hospitality'].", 
num_attempted_hospitality = ".$num['attempted']['hospitality']." 
WHERE
student_number = ".$_SESSION['student_number']."";

mysqli_query($dbconfig, $insert_scores) or die (mysqli_error($dbconfig));

exit();



?>