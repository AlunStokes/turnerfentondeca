<?php

session_start();

include '../../includes/config.php';

$type = json_decode($_GET['type']);
$answer = json_decode($_GET['answer']);

echo $type;

if ($type=="writtens") {
	$query = "UPDATE applicants SET writtens = $answer WHERE student_number = ".$_SESSION['student_number']."";
}
else {
	$query = "UPDATE applicants SET can_bring_device = $answer WHERE student_number = ".$_SESSION['student_number']."";
}

$result = mysqli_query($dbconfig, $query);

if ($result) {
	echo "success";
}
else {
	echo "failure";
}


?>