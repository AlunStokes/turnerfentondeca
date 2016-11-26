<?php

include '../../includes/config.php';

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
exit();
?>