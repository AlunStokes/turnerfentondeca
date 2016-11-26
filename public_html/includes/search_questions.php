<?php

include '../../includes/config.php';

if (isset($_GET['search'])) {
	$search = stripslashes(json_decode($_GET['search']));
}
else {
	$search = "";
}

$question_type = json_decode($_GET['question_type']);

if ($question_type == 'mix') {
	$question_query = "SELECT questions.question_id, question, option_a, option_b, option_c, option_d, answer, cluster FROM questions LEFT JOIN questions_options ON questions_options.question_id = questions.question_id LEFT JOIN questions_answers ON questions_answers.question_id = questions.question_id LEFT JOIN questions_cluster ON questions_cluster.question_id = questions.question_id WHERE question LIKE '%".$search."%' OR option_a LIKE '%".$search."%' OR option_b LIKE '%".$search."%' OR option_c LIKE '%".$search."%' OR option_d LIKE '%".$search."%' LIMIT 75";
}
else {
	$question_query = "SELECT questions.question_id, question, option_a, option_b, option_c, option_d, answer, cluster FROM questions LEFT JOIN questions_options ON questions_options.question_id = questions.question_id LEFT JOIN questions_answers ON questions_answers.question_id = questions.question_id LEFT JOIN questions_cluster ON questions_cluster.question_id = questions.question_id WHERE (question LIKE '%".$search."%' OR option_a LIKE '%".$search."%' OR option_b LIKE '%".$search."%' OR option_c LIKE '%".$search."%' OR option_d LIKE '%".$search."%') AND cluster = '$question_type' LIMIT 75";
}

$results = mysqli_query ($dbconfig, $question_query);
$data = array();
$data['question_id'] = array();
$data['questions'] = array();
$data['option_a'] = array();
$data['option_b'] = array();
$data['option_c'] = array();
$data['option_d'] = array();
$data['answers'] = array();
$data['cluster'] = array();

while ($row = mysqli_fetch_assoc($results)) {
	array_push($data['questions'], $row['question']);
	array_push($data['question_id'], $row['question_id']);
	array_push($data['option_a'], $row['option_a']);
	array_push($data['option_b'], $row['option_b']);
	array_push($data['option_c'], $row['option_c']);
	array_push($data['option_d'], $row['option_d']);
	array_push($data['answers'], $row['answer']);
	array_push($data['cluster'], $row['cluster']);
}

$data['count'] = mysqli_num_rows($results);

echo json_encode($data);
exit();

?>