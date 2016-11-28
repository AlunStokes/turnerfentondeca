<?php 

include ('../includes/config.php');
include ('includes/session.php');

$done = mysqli_fetch_assoc(mysqli_query($dbconfig, "SELECT COUNT(*) FROM exam_results WHERE student_number = ".$_SESSION['student_number']." AND exam_id = 1"));
$done = $done['COUNT(*)'];

if ($done > 0) {
    $_SESSION['unlocked_exam'] = 0;
}

unset($_SESSION['exam_id']);

if (isset($_GET['begin_exam'])) {
  $_SESSION['exam_cluster'] = $_GET['cluster'];
  $_SESSION['exam_started'] = false;
  $_SESSION['num_questions'] = 100;
  header('Location:applicant_exam.php');
}
if (isset($_POST['submit_entrance'])) {
    if (password_verify($_POST['password_entrance'], '$2y$10$Id1esQKJK05I6u9V8E2mq.2wxqzBw812KBPjVOz6B3p1P96E9nPDS')) {
      header('Location:entrance_exam.php');
  }
  else {
    header('Location:applicant_home.php');
}
}
if ($_SESSION['member'] == true) {
    header("Location: home.php");
}

switch ($_SESSION['area']) {
    case 'library':
    $area = "the library at 3:00";
    break;
    case '132':
    $area = "room 132 at 3:00";
    break;
    case '133':
    $area = "room 133 at 3:00";
    break;
    case '107':
    $area = "room 107 at 3:00";
    break;
    case '107_lunch':
    $area = "room 107 at lunchtime";
    break;
    case 'north':
    $area = "the North cafeteria at 3:00";
    break;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">

    <meta name="author" content="">

    <title>TFSS DECA | Home</title>

    <!-- Custom Fonts -->
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">

    <!--CSS FILES-->

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet"> 
    <!-- Page CSS -->
    <link href="css/applicant_home.css" rel="stylesheet">
    <!-- Navbar CSS -->
    <link href="css/navbar.css" rel="stylesheet">
    <!-- Practice CSS -->
    <link href="css/practice.css" rel="stylesheet">

    <!--JavaScript Files-->

    <!-- jQuery -->
    <script src="js/jquery-2.2.3.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <!-- Plugin JavaScript -->
    <script src="js/jquery.easing.min.js"></script>
    <script src="js/bootstrap-dialog.min.js"></script>
</head>

<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">

    <?php include "components/navbar_applicant_home.php'; ?>


    <div>
        <div class="row vertical-offset-12">
            <div class="col-md-8 col-md-offset-2">
                <!-- <h1>There's Nothing Here For You...Yet</h1> -->
                <h2 style="color:#d9534f"><?php echo "Interviews are being delayed until 22 September 2016 - Download the schedule below.";  ?></h2>
                <h3><a href="https://docs.google.com/spreadsheets/d/17JAj05Pz0pqazKX_TS9vswARViTulaIyFOHlcSMTmnE/edit#gid=0">Click here to see the interview schedule.</a></h3>
                <h2>Dates to Remember</h2>
                <h3>Entrance Interviews: Thurdsay 22 September - Friday 23 September</h3>
                <br>
                <!-- <p>You will be redirected in a couple seconds <br><a href="login.php">If you are not, click here</a></p> -->
                <!-- <p>You will receive access to the site if you are chosen for the chapter.  Until then, check your email regularly for updates.</p> -->
                <br>
                <h3><a href="files/admissions/writtens_proposal.docx">Click this link to download the writtens proposal document</a> if you're applying for a written event, and haven't done so already.</h3>
                <br>
                <h2>If you are applying for a written event, reports are due in by 21 September 2016</h2>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div align="center" class="buttons" style="margin-top:2vh;">
      <button class="btn btn-lg btn-primary btn-block" id="new_exam" style="margin-bottom:2vh;">Practice Exams</button>
     <!-- <button class="btn btn-lg btn-primary btn-block" id="entrance_exam" style="margin-bottom:2vh;">Entrance Exam</button> -->
  </div>


  <!--Event Selection Modal -->
  <div class="modal fade" id="new_exam_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Exam Setup</h4>
        </div>
        <form method="get" role="form">
            <div class="modal-body">


              <div class="option">
                <h2 style="text-align: center;">Exam Options</h2>
                <label for="cluster">Cluster:</label>
                <select class="form-control" name="cluster">
                  <option value="marketing">Marketing</option>
                  <option value="businessadmin">Business Administration</option>
                  <option value="finance">Finance</option>
                  <option value="hospitality">Hospitality & Tourism</option>
                  <option value="mix">Mix</option>
              </select>
          </div>


      </div>
      <div class="modal-footer" id="footer" align:"center">
          <input type="submit" class="btn btn-primary" name="begin_exam" value="Begin" />
          <button type="button" class="btn btn-default" data-dismiss="modal" id="close">Cancel</button>
      </div>
  </form>
</div>
</div>
</div>

<!--Password Modal -->
<div class="modal fade" id="password_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel"></h4>
    </div>
    <form method="post" id="submit" role="form">
        <div class="modal-body">

          <h2>Enter password: </h2>
          <form role="form" class="form-signin" method="POST" action="" name="entrance_password" autocomplete="off">

            <div class="form-group">
              <input class="form-control" placeholder="Password" name="password_entrance" type="password">
          </div>

          <div class="form-group">
              <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit_entrance">Submit</button>
          </div>

      </form>


  </div>
</div>
</div>
</div>

<script type="text/javascript">

var writtens = <?php echo json_encode($_SESSION['writtens']); ?>;
var can_bring_device = <?php echo json_encode($_SESSION['can_bring_device']); ?>;
var unlocked_exam = <?php echo json_encode($_SESSION['unlocked_exam']); ?>;
$(document).ready(function() {

    if (unlocked_exam == 0) {
        document.getElementById('entrance_exam').disabled = true;
    }
});


if (writtens == null) {
    BootstrapDialog.show({
        type:BootstrapDialog.TYPE_PRIMARY,
        closable:false,
        title:'Writtens',
        message: 'Would you like to apply for a writtens event?  If you don\'t know what this means, you likely aren\'t applying for a written event.',
        setType: BootstrapDialog.TYPE_SUCCESS,
        buttons: [{
            label: 'Yes',
            cssClass: 'btn-success',
            id:'writtens_btn_yes',
            action: function(dialogItself){
                $.ajax({
                    type: "get",
                    url: "includes/applicant_home_ajax.php",
                    data: {type : JSON.stringify("writtens"),
                    answer : JSON.stringify("1")},
                });
                window.location.href = "writtens_register_success.php";
                dialogItself.close();
            }
        }, {
            label: 'No', 
            cssClass: 'btn-danger',
            id:'writtens_btn_no',
            action: function(dialogItself){
                $.ajax({
                    type: "get",
                    url: "includes/applicant_home_ajax.php",
                    data: {type : JSON.stringify("writtens"),
                    answer : JSON.stringify("0")},
                });
                dialogItself.close();
            }
        }]
    });
}

if (can_bring_device == null) {
    BootstrapDialog.show({
        type:BootstrapDialog.TYPE_PRIMARY,
        closable:false,
        title:'Devices',
        message: 'Can you bring a laptop, tablet, or phone to use on 20 September, to write the exam after-school?',
        setType: BootstrapDialog.TYPE_SUCCESS,
        buttons: [{
            label: 'Yes',
            cssClass: 'btn-success',
            id:'devices_btn_yes',
            action: function(dialogItself){
                $.ajax({
                    type: "get",
                    url: "includes/applicant_home_ajax.php",
                    data: {type : JSON.stringify("can_bring_device"),
                    answer : JSON.stringify("1")},
                });
                dialogItself.close();
            }
        }, {
            label: 'No',
            cssClass: 'btn-danger',
            id:'devices_btn_no',
            action: function(dialogItself){
                $.ajax({
                    type: "get",
                    url: "includes/applicant_home_ajax.php",
                    data: {type : JSON.stringify("can_bring_device"),
                    answer : JSON.stringify("0")},
                });
                dialogItself.close();
            }
        }]
    });
}

$('#new_exam').click(function() {
    $('#new_exam_modal').modal('show');
});

$('#entrance_exam').click(function() {
    $('#password_modal').modal('show');
});


</script>

</body>

</html>