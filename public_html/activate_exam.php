<?php

include ('includes/session.php');
include ('includes/functions.php');

$active_page = 'activate_exam';

if ($_SESSION['member'] == false) {
  header("Location: applicant_home.php");
}

if($_SESSION['student_number'] != 498566 && $_SESSION['student_number'] != 573033 && $_SESSION['student_number'] != 579803) {
  header("Location: home.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $chosen = $_POST['room_dropdown'];
  if ($chosen != 'all') {
  $query = "UPDATE applicants SET unlocked_exam = 1 WHERE area = '".$chosen."'";
}
else {
  $query = "UPDATE applicants SET unlocked_exam = 1";
}
  mysqli_query($dbconfig, $query);
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>TFSS DECA | Active Exam</title>
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
          Activate Exam
        </h1>
      </section>

      <!-- Main content -->
      <section class="content">


        <form id="submit" method="post">
          <div class="row search-bar">
            <div class="col-md-4 col-md-offset-3" style="text-align:right; padding:8px;">
              <select name="room_dropdown">
                <option value="library">Library</option>
                <option value="132">Room 132</option>
                <option value="133">Room 133</option>
                <option value="107">Room 107</option>
                <option value="107_lunch">Room 107 - Lunch</option>
                <option value="north">North Cafeteria</option>
                <option value="all">All</option>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 col-md-offset-3">
              <button class="btn btn-block" type="submit">Unlock</button>

            </div>
          </div>
        </form>


      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

  </body>
  </html>