<?php

session_start();

$id = json_decode($_GET['exam_id']);

$_SESSION['exam_id'] = $id;

$data = "success";

echo json_encode($data);
exit();

?>