<?php

include("../../includes/config.php");

$cluster = json_decode($_GET['cluster']);
$num = json_decode($_GET['num']);

if ($cluster == 'mix') {
	$question_query = "SELECT questions.question_id, question, option_a, option_b, option_c, option_d, answer, cluster FROM questions LEFT JOIN questions_options ON questions_options.question_id = questions.question_id LEFT JOIN questions_answers ON questions_answers.question_id = questions.question_id LEFT JOIN questions_cluster ON questions_cluster.question_id = questions.question_id ORDER BY RAND() LIMIT $num";
}
else {
	$question_query = "SELECT questions.question_id, question, option_a, option_b, option_c, option_d, answer, cluster FROM questions LEFT JOIN questions_options ON questions_options.question_id = questions.question_id LEFT JOIN questions_answers ON questions_answers.question_id = questions.question_id LEFT JOIN questions_cluster ON questions_cluster.question_id = questions.question_id WHERE cluster = '$cluster' ORDER BY RAND() LIMIT $num";
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

?>

<!DOCTYPE html>
<html>
<body class="hold-transition skin-blue sidebar-mini">
	<div class="wrapper">

		<?php

		echo "<h1>".$cluster."</h1><br>";

		for ($i = 0; $i < $num; $i++) {
			echo "<h2>".($i+1).". ".$data['questions'][$i]."</h2>";
			echo "<h4>A. ".$data['option_a'][$i]."</h4>";
			echo "<h4>B. ".$data['option_b'][$i]."</h4>";
			echo "<h4>C. ".$data['option_c'][$i]."</h4>";
			echo "<h4>D. ".$data['option_d'][$i]."</h4>";
			echo "<i><h4>Answer: ".$data['answers'][$i]."</h4></i><br>";
		}

		?>


	</div>
</body>
</html>