<?php

include '../../includes/config.php';

if (isset($_GET['search'])) {
	$search = stripslashes(json_decode($_GET['search']));
}
else {
	$search = "";
}


	$user_query = "SELECT first_name, last_name FROM applicants WHERE (first_name LIKE '%".$search."%' OR last_name LIKE '%".$search."%') AND first_name IS NOT NULL LIMIT 15";


$results = mysqli_query ($dbconfig, $user_query);
$data = array();
$data['first_name'] = array();
$data['last_name'] = array();

while ($row = mysqli_fetch_assoc($results)) {
	array_push($data['first_name'], $row['first_name']);
	array_push($data['last_name'], $row['last_name']);
}

$data['count'] = mysqli_num_rows($results);

echo json_encode($data);
exit();

?>