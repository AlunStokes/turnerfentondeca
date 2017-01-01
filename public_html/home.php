<?php

include ('includes/session.php');
include ('includes/functions.php');

$active_page = 'home';

if ($_SESSION['member'] == false) {
  header("Location: applicant_home");
}

header("Location: timeline");

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>TFSS DECA | Home</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Favicon -->
  <link rel="shortcut icon" href="img/favicon.png`" />
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
  <script src="components/all_pages.js"></script>
  <!-- Bootstrap 3.3.6 -->
  <script src="js/bootstrap.min.js"></script>
  <!-- AdminLTE App -->
  <script src="js/admin.min.js"></script>

</head>


<body class="hold-transition skin-blue sidebar-mini fixed">
  <div class="wrapper">

    <!-- Header and Left Menu -->
    <?php if ($_SESSION['admin_boolean']) { include 'components/admin_menu.php'; }
  else { include 'components/member_menu.php'; } ?>

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