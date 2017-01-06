
<?php
include ('../includes/config.php');

session_start();

if (!isset($_SESSION['student_number'])) {
	header("Location:login");
}

if (!isset($_SESSION['fist_name'])) {
	$ses_sql = mysqli_query($dbconfig, "SELECT * FROM members WHERE student_number = ".$_SESSION['student_number']." ");

	$row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);

	$_SESSION['first_name'] = $row['first_name'];
	$_SESSION['last_name'] = $row['last_name'];
	$_SESSION['name'] = $_SESSION['first_name']." ".$_SESSION['last_name'];
	$_SESSION['email'] = $row['email'];
	$_SESSION['student_number'] = $row['student_number'];
	$_SESSION['cluster'] = $row['cluster'];
	$_SESSION['event'] = $row['event'];
	$_SESSION['admin'] = $row['admin'];
	$_SESSION['class'] = $row['class'];


	switch ($_SESSION['cluster']) {
		case "marketing":
		$_SESSION['cluster'] = "Marketing";
		break;
		case "finance":
		$_SESSION['cluster'] = "Finance";
		break;
		case "businessadmin":
		$_SESSION['cluster'] = "Business Administration";
		break;
		case "hospitality":
		$_SESSION['cluster'] = "Hospitality";
		break;
		case "marketing_principles":
		$_SESSION['cluster'] = "Principles of Marketing";
		break;
		case "finance_principles":
		$_SESSION['cluster'] = "Principles of Finance";
		break;
		case "businessadmin_principles":
		$_SESSION['cluster'] = "Principles of Business Admin";
		break;
		case "hospitality_principles":
		$_SESSION['cluster'] = "Principles of Hospitality";
		break;	
		case "writtens":
		$_SESSION['cluster'] = "Writtens";
		break;				
	}

	$_SESSION['admin_boolean'] = false;
	if ($_SESSION['admin'] == 1) {
		$_SESSION['admin_boolean'] = true;
	}
	if (file_exists("img/user_images/thumbnails/".$_SESSION['student_number'].".jpg")) {
		$_SESSION['image_file'] = $_SESSION['student_number'].".jpg";
	}
	else {
		$_SESSION['image_file'] = "unresolved.jpg";
	}
}



?>