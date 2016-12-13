<?php

include("../../includes/config.php");

$exam_id = json_decode($_GET['exam_id']);


	$question_query = "SELECT created_exams_questions.question_id, question, option_a, option_b, option_c, option_d, answer FROM created_exams_questions LEFT JOIN questions ON questions.question_id = created_exams_questions.question_id LEFT JOIN questions_answers ON questions_answers.question_id = questions.question_id LEFT JOIN questions_options ON questions_options.question_id = created_exams_questions.question_id WHERE exam_id = ".$exam_id."";
$results = mysqli_query ($dbconfig, $question_query);
$data = array();
$data['question'] = array();
$data['option_a'] = array();
$data['option_b'] = array();
$data['option_c'] = array();
$data['option_d'] = array();
$data['answer'] = array();

while ($row = mysqli_fetch_assoc($results)) {
	array_push($data['question'], $row['question']);
	array_push($data['option_a'], $row['option_a']);
	array_push($data['option_b'], $row['option_b']);
	array_push($data['option_c'], $row['option_c']);
	array_push($data['option_d'], $row['option_d']);
	array_push($data['answer'], $row['answer']);
}

?>

<!DOCTYPE html>
<html>
<body class="hold-transition skin-blue sidebar-mini fixed">
	<div class="wrapper">

		<?php

		for ($i = 0; $i < count($data['question']); $i++) {
			echo "<h2>".($i+1).". ".$data['question'][$i]."</h2>";
			echo "<h4>A. ".$data['option_a'][$i]."</h4>";
			echo "<h4>B. ".$data['option_b'][$i]."</h4>";
			echo "<h4>C. ".$data['option_c'][$i]."</h4>";
			echo "<h4>D. ".$data['option_d'][$i]."</h4>";
			echo "<i><h4>Answer: ".$data['answer'][$i]."</h4></i><br>";
		}

		?>


	</div>
</body>
</html>