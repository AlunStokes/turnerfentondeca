<?php

ini_set("display_errors", 0);

session_start();
include ('../../includes/config.php');

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
exit();

?>