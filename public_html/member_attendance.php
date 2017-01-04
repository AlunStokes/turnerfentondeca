<?php

include ('../includes/config.php');
include ('includes/session.php');

$active_page = 'attendance';

if ($_SESSION['admin_boolean']) {
 header("Location: attendance");
}

$check_query = "SELECT * FROM attendance_individuals WHERE student_number = ".$_SESSION['student_number']." AND session_id = (SELECT id FROM attendance_sessions WHERE end_time IS NULL);";
$result = mysqli_query($dbconfig, $check_query);
if ($result != false) {
  if (mysqli_num_rows($result) > 0) {
    header("Location: timeline");
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>TFSS DECA | Timeline</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Favicon -->
  <link rel="shortcut icon" href="img/favicon.png`" />
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="css/dashboard.css">
  <link rel="stylesheet" href="css/skin-blue.min.css">
  <!-- Page Style -->
  <link rel="stylesheet" href="css/timeline.css">


  <!-- jQuery 2.2.3 -->
  <script src="js/jquery-2.2.3.min.js"></script>
  <script src="components/all_pages.js"></script>
  <!-- Bootstrap 3.3.6 -->
  <script src="js/bootstrap.min.js"></script>
  <!-- dashboard App -->
  <script src="js/admin.min.js"></script>
  <script src="js/bootstrap-dialog.min.js"></script>

</head>


<body class="hold-transition skin-blue sidebar-mini fixed">
 <div class="wrapper">

  <!-- Header and Left Menu -->
  <?php if ($_SESSION['admin_boolean']) { include 'components/admin_menu.php'; }
  else { include 'components/member_menu.php'; } ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
      <!--<section class="content-header">
        <h1>
          Home
          <small>General Info</small>
        </h1>
      </section>-->

      <!-- Main content -->
      <section class="content">

        <div class="row">

          <div class="col-xs-8 col-xs-offset-2">
            <h1 style="text-align:center;">Enter your room's attendance code</h1>
          </div>
        </div>

        <div class="row">

          <div class="col-xs-6 col-xs-offset-3">

            <input type="text" placeholder="Code Word" id="code_word" autocomplete="off" style="margin-left:10%; min-height: 3vh; min-width: 80%;" />

          </div>

          <div class="col-xs-2">

            <input type="button" class="btn btn-primary" id="submit_code" value="Submit" />

          </div>

        </div>


      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
  </div>

  <script>

  $(document).ready(function() {

    var student_number = <?php echo json_encode($_SESSION['student_number']); ?>;
    var student_class = <?php echo json_encode($_SESSION['class']); ?>;

    $("#submit_code").click(function() {

      if ($("#code_word").val().length == 0) {
        alert ("enter a phrase");
        return;
      }

      var code_word = $("#code_word").val();

      $.ajax({
        type: "get",
        url: "includes/ajax",
        data: {code_word : JSON.stringify(code_word),
          student_number : JSON.stringify(student_number),
         student_class : JSON.stringify(student_class),
         ajax_id : JSON.stringify("attendance_check")},
      }).done(function(data){ 
        result = jQuery.parseJSON(data);
        if (result == true) {
          alert("Your attendance has been logged");
        location.reload();
        }
        else {
          alert ("Wrong code, or class is not open");
        }
      });

    });

  });

  </script>

</body>
</html>