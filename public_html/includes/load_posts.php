<?php

include ('../../includes/config.php');

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

?>