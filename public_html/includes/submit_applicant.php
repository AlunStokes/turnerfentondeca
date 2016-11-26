<?php

include '../../includes/config.php';

$email = json_decode($_GET['email']);
$random_hash = md5(openssl_random_pseudo_bytes(32));

$data = array();
$data['registered'] = NULL;

$search_email = "SELECT email, password, confirm_code FROM applicants WHERE email = '$email'";
$results = mysqli_query($dbconfig, $search_email);
$row = mysqli_fetch_assoc($results);
if (mysqli_num_rows($results) > 0) {
	$data['status'] = "already_registered";
	if (is_null($row['password'])) {
		$data['registered'] = 0;
		$random_hash = $row['confirm_code'];
	}
	else {
		$data['registered'] = 1;
		echo json_encode($data);
		exit();
	}
}
else {
	$insert_email = "INSERT INTO applicants (email, confirm_code) values ('$email', '$random_hash')";
	mysqli_query($dbconfig, $insert_email) or die (mysqli_error($dbconfig));
	$data['status'] = "success";
}

$data['link'] = 'register.php?confirm_code='.$random_hash.'';

echo json_encode($data);
exit();

?>