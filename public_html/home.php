<?php

include ('includes/session.php');
include ('includes/functions.php');

$active_page = 'home';

if ($_SESSION['member'] == false) {
  header("Location: applicant_home.php");
}

header("Location: timeline.php");

//include 'includes/admin_sidebar.php';
//if (!$_SESSION['event_boolean']) { include 'includes/event_selection_modal.php'; }

/*
//Load messages and notifications
$message_query = mysqli_query($dbconfig, "SELECT * FROM notifications WHERE receiver = '$student_number' AND notification_type = 'message' AND is_read = '0'");
if(mysqli_num_rows($message_query) > 0 ){
  $message_array = array();
  while($row = mysqli_fetch_array($message_query, MYSQLI_ASSOC)){
    $message_array[] = $row ;
  }
  $num_messages = sizeof($message_array);
} 

$reminder_query = mysqli_query($dbconfig, "SELECT * FROM notifications WHERE receiver = '$student_number' AND notification_type = 'reminder'");
if(mysqli_num_rows($reminder_query) > 0 ){
  while($row = mysqli_fetch_array($reminder_query, MYSQLI_ASSOC)){
    $reminder_array[] = $row ;
  }
} 
*/

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>TFSS DECA | Home</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!--Open Sans Font -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="css/admin.min.css">
  <link rel="stylesheet" href="css/skin-blue.min.css">
  <!-- Page Style -->
  <link rel="stylesheet" href="css/home.css">



  <!-- jQuery 2.2.3 -->
  <script src="js/jquery-2.2.3.min.js"></script>
  <!-- Bootstrap 3.3.6 -->
  <script src="js/bootstrap.min.js"></script>
  <!-- AdminLTE App -->
  <script src="js/admin.min.js"></script>

</head>


<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">

    <!-- Header and Left Menu -->
    <?php if ($_SESSION['admin_boolean']) { include 'includes/admin_menu.php'; }
  else { include 'includes/member_menu.php'; } ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Home
          <small>General Info</small>
        </h1>
      </section>

      <!-- Main content -->
      <section class="content">





      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

  </body>
  </html>