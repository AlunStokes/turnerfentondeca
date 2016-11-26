<?php

include ('../includes/config.php');
include ('includes/session.php');

$active_page = 'classes';

if (!$_SESSION['admin_boolean']) {
  header("Location: exam_statistics.php");
}

$exam_list = array();
$exam_list['exam_id'] = array();
$exam_list['exam_name'] = array();
$exam_list['num_questions'] = array();
$exam_list['exam_type'] = array();
$query_exams = "SELECT * FROM created_exams ORDER BY date_created";
$result = mysqli_query($dbconfig, $query_exams);
while ($row = mysqli_fetch_assoc($result)) {
  array_push($exam_list['exam_id'], $row['exam_id']);
  array_push($exam_list['exam_name'], $row['exam_name']);
  array_push($exam_list['num_questions'], $row['num_questions']);
  array_push($exam_list['exam_type'], $row['exam_type']);
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
  <link rel="stylesheet" href="css/class_exam_results.css">


  <!-- jQuery 2.2.3 -->
  <script src="js/jquery-2.2.3.min.js"></script>
  <!-- Bootstrap 3.3.6 -->
  <script src="js/bootstrap.min.js"></script>
  <!-- dashboard App -->
  <script src="js/admin.min.js"></script>

</head>


<body class="hold-transition skin-blue sidebar-mini">
 <div class="wrapper">

  <!-- Header and Left Menu -->
  <?php if ($_SESSION['admin_boolean']) { include 'includes/admin_menu.php'; }
  else { include 'includes/member_menu.php'; } ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        Exam Results
        <!-- <small>General Info</small> -->
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!--
        <div class="col-md-2 col-md-offset-6">
          <select id="exam_type_dropdown">
            <option value="all">All Clusters</option>
            <option value="mix">Mixed Clusters</option>
            <option value="marketing">Marketing</option>
            <option value="businessadmin">Business Administration</option>
            <option value="finance">Finance</option>
            <option value="hospitality">Hospitality & Tourism</option>
          </select>
        </div>
      -->
        <div class="col-md-2">
          <select id="exam_dropdown">
            <?php 

            for ($i = 0; $i < count($exam_list['exam_id']); $i++) {
              echo "<option value='".$exam_list['exam_id'][$i]."'>".$exam_list['exam_name'][$i]."</option>";
            }

            ?>
          </select>
        </div>
      </div>

      <br>

      <div class="row">
        <div class="col-xs-2">
          <h3>Name</h3>
          <div id="name">
          </div>
        </div>
        <div class="col-xs-4">
          <h3>Student Number</h3>
          <div id="student_number">
          </div>
        </div>
        <div class="col-xs-2">
          <h3>Score</h3>
          <div id="score">
          </div>
        </div>
        <div class="col-xs-1">
          <h3>Total</h3>
          <div id="total">
          </div>
        </div>
        <div class="col-xs-2">
          <h3>Percentage</h3>
          <div id="percentage">
          </div>
        </div>
      </div>


    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
</div>

<script>

$('#exam_dropdown').on('change', function() {
  load_scores();
});
/*
$('#exam_type_dropdown').on('change', function() {
  switch ($("#exam_type_dropdown").val()) {
    case "all":
    replace_options("exam_dropdown", "all");
    break;
    case "mix":
    replace_options("exam_dropdown", "mix");
    break;
    case "marketing":
    replace_options("exam_dropdown", "marketing");
    break;
    case "finance":
    replace_options("exam_dropdown", "finance");
    break;
    case "businessadmin":
    replace_options("exam_dropdown", "businessadmin");
    break;
    case "hospitality":
    replace_options("exam_dropdown", "hospitality");
    break;
  }
  load_scores();
});
*/

$(document).ready(function() {
  load_scores();
});


function load_scores() {
  var exam_type = $("#exam_type_dropdown").val();
  var exam_id = $("#exam_dropdown").val();

  $.ajax({
    type: "get",
    url: "includes/get_exam_results.php",
    data: {exam_id : JSON.stringify(exam_id)},
  }).done(function(data){ 
    var data = jQuery.parseJSON(data);
  //Delete current Scores
  $("#name").html("");
  $("#student_number").html("");
  $("#score").html("");
  $("#total").html("");
  $("#percentage").html("");

    //Add new Scores
    for (var i = 0; i < data['count']; i++) {
      $("#name").append("<h4>"+data['name'][i]+"</h4>");
      $("#student_number").append("<h4>"+data['student_number'][i]+"</h4>");
      $("#score").append("<h4>"+data['score'][i]+"</h4>");
      $("#total").append("<h4>"+data['total'][i]+"</h4>");
      $("#percentage").append("<h4>"+data['percentage'][i]+"%</h4>");
    }
  });
}

/*
function replace_options(dropdown, exam_type) {
  $.ajax({
    type: "get",
    url: "includes/get_exams.php",
    data: {exam_type : JSON.stringify(exam_type)},
  }).done(function(data){ 
    var data = jQuery.parseJSON(data);
    var options = new Array();
    options['exam_id'] = new Array();
    options['exam_name'] = new Array();
    for (var i = 0; i < data['exam_name'].length; i++) {
      options['exam_id'].push (data['exam_id'][i]);
      options['exam_name'].push (data['exam_name'][i]);
    }
    $("#"+dropdown).html('');
    for (var i = 0 ; i < options['exam_id'].length; i++) {
      $("#"+dropdown).append("<option value='"+options['exam_id'][i]+"'>"+options['exam_name'][i]+"</option>");
    }
  });
}
*/

  </script>

</body>
</html>