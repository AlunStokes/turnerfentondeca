<?php

ini_set("display_errors", 0);

include '../../includes/config.php';
session_start();

if (isset($_GET['ajax_id'])) {
	$ajax_id = json_decode($_GET['ajax_id']);
}
else {
	$ajax_id = json_decode($_POST['ajax_id']);
}


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

	
	//Class Exam Results
	case 'class_exam_results':
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
	break;


	//Personal Exam Statistics
	case 'personal_exam_stats':
	$data_query = "SELECT * FROM user_statistics WHERE student_number = ".$_SESSION['student_number'].";";
	$results = mysqli_query($dbconfig, $data_query);
	if ($results != false) {
		$row = mysqli_fetch_assoc($results);
		$data = array();
		$data['num_correct_marketing'] = $row['num_correct_marketing'];
		$data['num_correct_finance'] = $row['num_correct_finance'];
		$data['num_correct_businessadmin'] = $row['num_correct_businessadmin'];
		$data['num_correct_hospitality'] = $row['num_correct_hospitality'];
		$data['num_marketing'] = $row['num_attempted_marketing'];
		$data['num_finance'] = $row['num_attempted_finance'];
		$data['num_businessadmin'] = $row['num_attempted_businessadmin'];
		$data['num_hospitality'] = $row['num_attempted_hospitality'];

		$final = array();

		if ($data['num_marketing'] == 0 && $data['num_finance'] == 0 && $data['num_businessadmin'] == 0 && $data['num_hospitality'] == 0) {
			$final['scores'] = false;
		}
		else {
			$percents = array();
			$percents['marketing'] = $data['num_correct_marketing']/$data['num_marketing'];
			$percents['finance'] = $data['num_correct_finance']/$data['num_finance'];
			$percents['businessadmin'] = $data['num_correct_businessadmin']/$data['num_businessadmin'];
			$percents['hospitality'] = $data['num_correct_hospitality']/$data['num_hospitality'];
			$percents['total'] = $percents['marketing'] + $percents['finance'] + $percents['businessadmin'] + $percents['hospitality'];

			$final['marketing_percent'] = round($percents['marketing']/$percents['total']*100, 2, PHP_ROUND_HALF_UP);
			$final['finance_percent'] = round($percents['finance']/$percents['total']*100, 2, PHP_ROUND_HALF_UP);
			$final['businessadmin_percent'] = round($percents['businessadmin']/$percents['total']*100, 2, PHP_ROUND_HALF_UP);
			$final['hospitality_percent'] = round($percents['hospitality']/$percents['total']*100, 2, PHP_ROUND_HALF_UP);
			$final['scores'] = true;


			$all_scores = array();
			$all_scores['total'] = 0;
			$all_scores['sept16'] = array();
			$all_scores['oct16'] = array();
			$all_scores['nov16'] = array();
			$all_scores['dec16'] = array();
			$all_scores['jan17'] = array();
			$all_scores['feb17'] = array();
			$all_scores['mar17'] = array();
			$all_scores['apr17'] = array();
			$all_scores['may17'] = array();
			$final['exams'] = array();
			$final['exams']['percentage'] = array();
			$final['exams']['date'] = array();
			$final['exams']['score'] = array();
			$final['exams']['total'] = array();
			$exam_score_query = "SELECT percentage, DATE_FORMAT(DATE, '%M %Y') AS date, DATE_FORMAT(DATE, '%D %M %Y') AS full_date, score, total FROM exam_results WHERE student_number = ".$_SESSION['student_number']." ORDER BY percentage DESC;";
			$results = mysqli_query($dbconfig, $exam_score_query);
			$final['best_score'] = mysqli_fetch_assoc($results)['percentage'];
			mysqli_data_seek($results, 0);
			while ($row = mysqli_fetch_assoc($results)) {
				array_push($final['exams']['percentage'], $row['percentage']);
				array_push($final['exams']['date'], $row['full_date']);
				array_push($final['exams']['score'], $row['score']);
				array_push($final['exams']['total'], $row['total']);
				switch($row['date']) {
					case "September 2016":
					array_push($all_scores['sept16'], $row['percentage']);
					break;
					case "October 2016":
					array_push($all_scores['oct16'], $row['percentage']);
					break;
					case "November 2016":
					array_push($all_scores['nov16'], $row['percentage']);
					break;
					case "December 2016":
					array_push($all_scores['dec16'], $row['percentage']);
					break;
					case "January 2017":
					array_push($all_scores['jan17'], $row['percentage']);
					break;
					case "February 2017":
					array_push($all_scores['feb17'], $row['percentage']);
					break;
					case "March 2017":
					array_push($all_scores['mar17'], $row['percentage']);
					break;
					case "April 2017":
					array_push($all_scores['apr17'], $row['percentage']);
					break;
					case "May 2017":
					array_push($all_scores['may17'], $row['percentage']);
					break;
				}
				$all_scores['total'] += $row['percentage'];
			}
			$final['average_score'] = round($all_scores['total'] / mysqli_num_rows($results),1);

			$month_array = array("sept16", "oct16", "nov16", "dec16", "jan17", "feb17", "mar17", "apr17", "may17");

			foreach ($month_array as $month) {
				$final[$month] = 0;
				if (!empty($all_scores)) {
					for ($i = 0; $i < count($all_scores[$month]); $i++) {
						$final[$month] += $all_scores[$month][$i];
					}
					$final[$month] /= count($all_scores[$month]);
				}
				else {
					$final[$month] = null;
				}
			}
		}
	}

	echo json_encode($final);
	break;


	//Create Exam
	case 'create_exam_save':
	$name = json_decode($_GET['name']);
	$question_id = json_decode($_GET['question_id']);
	$length = json_decode($_GET['length']);
	$type = json_decode($_GET['type']);
	if (mysqli_num_rows(mysqli_query($dbconfig, "SELECT exam_name FROM created_exams WHERE exam_name = '$name'")) > 0) {
		echo json_encode("failed");
		exit();
	}
	$query_insert_name = "INSERT INTO created_exams (exam_name, num_questions, exam_type) VALUES ('$name', $length, '$type')";
	mysqli_query($dbconfig, $query_insert_name) or die (mysqli_error($dbconfig));
	$key = mysqli_fetch_array(mysqli_query($dbconfig, "SELECT exam_id FROM created_exams WHERE exam_name = '$name'"), MYSQLI_ASSOC)['exam_id'];
	$query_insert_quesitons = "";
	foreach ($question_id as $row) {
		$query_insert_quesitons .= "INSERT INTO created_exams_questions (exam_id, question_id) VALUES ('$key', '$row');";
	}
	if (mysqli_multi_query($dbconfig, $query_insert_quesitons)) {
		echo json_encode("success");
	}
	break;

	case 'create_exam_search_question':
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
	break;

	//Exam
	case 'exam_submit':
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
	break;



	//Practice
	case 'practice_start_exam':
	$id = json_decode($_GET['exam_id']);
	$_SESSION['exam_id'] = $id;
	$data = "success";
	echo json_encode($data);
	break;

	case 'practice_search_questions':
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
	break;

	case 'practice_search_exams':
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
	break;


	//Password Reset
	case 'reset_send_reset':
	$student_number = json_decode($_GET['student_number']);
	$random_hash = md5(openssl_random_pseudo_bytes(32));
	$member = false;
	$student_number_query = "SELECT email FROM members WHERE student_number = $student_number";
	$result = mysqli_query($dbconfig, $student_number_query);
	if (mysqli_num_rows($result) == 1) {
		$email = mysqli_fetch_assoc($result)['email'];
		$data = "success";
		$member = true;
	}
	else {
		$student_number_query = "SELECT email FROM applicants WHERE student_number = $student_number";
		$result = mysqli_query($dbconfig, $student_number_query);
		if (mysqli_num_rows($result) == 1) {
			$email = mysqli_fetch_assoc($result)['email'];
			$member = false;
		}
		else {
			$data = "failed_find_student_number";
			echo json_encode($data);
			exit();
		}
	}
	if ($member) {
		$insert_code = "UPDATE members SET password_reset_code='$random_hash' WHERE student_number=$student_number";
		mysqli_query($dbconfig, $insert_code) or die (mysqli_error($dbconfig));
	}
	else {
		$insert_code = "UPDATE applicants SET password_reset_code='$random_hash' WHERE student_number=$student_number";
		mysqli_query($dbconfig, $insert_code) or die (mysqli_error($dbconfig));
	}
	$to = $email;
	$subject = "Password Reset";
	$headers = "From: webmaster@turnerfentondeca.com\r\n";
	$headers .= "Reply-To: tfssdeca@gmail.com\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$message = "<h2>A password reset has been requested for your account.</h2>";
	$message .= "<h4>If you have not requested this reset, please contact us at webmaster@turnerfentondeca.com.</h4>";
	$message .= "<br>";
	$message .= "<h4>Otherwise, please <a href ='turnerfentondeca.com/reset_password_form.php?reset_code=".$random_hash."' >click the here to finish your password reset.</a></h4>";
	$message .= "<br>";
	$message .= "<h4>Or, if the above doesn't work, click the following link: turnerfentondeca.com/reset_password_form.php?reset_code=".$random_hash."";
	if (mail($to, $subject, $message, $headers)) {
		$data = "success";
	}
	else {
		$data = "failed";
	}
	echo json_encode($data);
	break;



	//Timeline
	case 'timeline_load_posts':
	$class = json_decode($_GET['user_class']);
	$admin = json_decode($_GET['admin']);
	$messages = array();
	$messages ['id'] = array();
	$messages ['poster'] = array();
	$messages ['poster_picture_path'] = array();
	$messages ['poster_first_name'] = array();
	$messages ['poster_last_name'] = array();
	$messages ['message'] = array();
	$messages ['json_message'] = array();
	$messages ['class'] = array();
	$messages ['class_proper'] = array();
	$messages ['date'] = array();
	$messages ['time'] = array();
	$post_query = "SELECT class_posts.id as id, message, poster, UNIX_TIMESTAMP(class_posts.date) AS date_order, DATE_FORMAT(DATE, '%M %D %Y') AS date, DATE_FORMAT(DATE, '%H:%i') AS time, first_name, last_name, class_posts.class FROM class_posts JOIN members ON members.student_number = class_posts.poster WHERE class_posts.class = '".$class."' OR class_posts.class = 'all' ORDER BY date_order DESC LIMIT 150;";
	if ($admin == 1) {
		$post_query = "SELECT class_posts.id as id, message, json_message, poster, UNIX_TIMESTAMP(class_posts.date) AS date_order, DATE_FORMAT(DATE, '%M %D %Y') AS date, DATE_FORMAT(DATE, '%H:%i') AS time, first_name, last_name, class_posts.class FROM class_posts JOIN members ON members.student_number = class_posts.poster ORDER BY date_order DESC LIMIT 150;";
	}
	$results = mysqli_query($dbconfig, $post_query);
	if ($results != false) {
		while ($row = mysqli_fetch_assoc($results)) {
			array_push($messages ['id'], $row['id']);
			array_push($messages ['poster'], $row['poster']);
			array_push($messages ['poster_picture_path'], $row['poster'].".jpg");
			array_push($messages ['poster_first_name'], $row['first_name']);
			array_push($messages ['poster_last_name'], $row['last_name']);
			array_push($messages['message'], $row['message']);
			if ($admin == 1) {
				array_push($messages['json_message'], $row['json_message']);
			}
			array_push($messages['date'], $row['date']);
			array_push($messages['time'], $row['time']);
			array_push($messages['class'], $row['class']);
		}
		for ($i = 0; $i < count($messages['class']); $i++) {
			switch ($messages['class'][$i]) {
				case "marketing":
				$messages['class_proper'][$i] = "Marketing";
				break;
				case "finance":
				$messages['class_proper'][$i] = "Finance";
				break;
				case "businessadmin":
				$messages['class_proper'][$i] = "Business Administration";
				break;
				case "hospitality":
				$messages['class_proper'][$i] = "Hospitality & Tourism";
				break;
				case "marketing_principles":
				$messages['class_proper'][$i] = "Principles of Marketing";
				break;
				case "finance_principles":
				$messages['class_proper'][$i] = "Principles of Finance";
				break;
				case "businessadmin_principles":
				$messages['class_proper'][$i] = "Principles of Business Admin";
				break;
				case "hospitality_principles":
				$messages['class_proper'][$i] = "Principles of Hospitality";
				break;  
				case "writtens":
				$messages['class_proper'][$i] = "Writtens";
				break;    
				case "all":
				$messages['class_proper'][$i] = "All Classes";
				break;        
				case "admin":
				$messages['class_proper'][$i] = "Admin";
				break;    
			}
			if (!file_exists("../img/user_images/thumbnails/".$messages['poster_picture_path'][$i])) {
				$messages['poster_picture_path'][$i] = "unresolved.jpg";
			}
		}
	}
	echo json_encode($messages);
	break;

	case 'timeline_delete_post':
	$post_id = json_decode($_GET['post_id']);
	$data_query = "DELETE FROM class_posts WHERE id = $post_id";
	if (mysqli_query($dbconfig, $data_query)) {
		echo json_encode("success");
	}
	else {
		echo json_encode("fail");
	}
	break;

	case 'timeline_post_message':
	$json_message = addslashes($_GET['json_message']);
	$message = stripslashes(json_decode($_GET['message']));
	$poster = stripslashes(json_decode($_GET['poster']));
	$class = stripslashes(json_decode($_GET['post_class']));
	$message = addslashes($message);
	$message = nl2br($message);
	$query = 'INSERT INTO class_posts (poster, message, json_message, class) VALUES ("'.$poster.'", "'.$message.'", "'.$json_message.'", "'.$class.'");';
	if (mysqli_query($dbconfig, $query) or die(mysqli_error($dbconfig))) {
		echo json_encode("success");
	}
	else {
		echo json_encode("fail");
	}
	break;

	case 'timeline_edit_message':
	$json_message = addslashes($_GET['json_message']);
	$message = stripslashes(json_decode($_GET['message']));
	$class = stripslashes(json_decode($_GET['post_class']));
	$post_id = stripslashes(json_decode($_GET['post_id']));
	$message = addslashes($message);
	$message = nl2br($message);
	$query = 'UPDATE class_posts SET message="'.$message.'", json_message="'.$json_message.'", class="'.$class.'" WHERE id='.$post_id.';';
	if (mysqli_query($dbconfig, $query) or die(mysqli_error($dbconfig))) {
		echo json_encode("success");
	}
	else {
		echo json_encode("fail");
	}
	break;

	case 'timeline_post_alert':
	$body = stripslashes(json_decode($_GET['body']));
	$title = stripslashes(json_decode($_GET['title']));
	$type = stripslashes(json_decode($_GET['type']));
	$admin = stripslashes(json_decode($_GET['admin']));
	$body = addslashes($body);
	$body = nl2br($body);
	$title = addslashes($title);
	$title = nl2br($title);
	$query = 'INSERT INTO alerts (type, title, body, page, admin) VALUES ("'.$type.'", "'.$title.'", "'.$body.'", "timeline", '.$admin.');';
	if (mysqli_query($dbconfig, $query) or die(mysqli_error($dbconfig))) {
		echo json_encode("success");
	}
	else {
		echo json_encode("fail");
	}
	break;



	//Alerts
	case 'alerts_add_seen':
	$student_number = $_SESSION['student_number'];
	$alert_id = json_decode($_GET['alert_id']);

	$insert_query = "INSERT INTO seen_alert (student_number, alert_id) VALUES ($student_number, $alert_id);";
	if (mysqli_query($dbconfig, $insert_query)) {
		echo json_encode("success");
	}
	else {
		echo json_encode("fail");
	}
	break;


	default:
		# code...
	break;
}

exit();

?>