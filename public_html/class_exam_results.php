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
array_push($exam_list['exam_id'], 0);
array_push($exam_list['exam_name'], "Random");
array_push($exam_list['num_questions'], 100);
array_push($exam_list['exam_type'], "All");
$query_exams = "SELECT * FROM created_exams ORDER BY date_created";
$result = mysqli_query($dbconfig, $query_exams);
while ($row = mysqli_fetch_assoc($result)) {
  array_push($exam_list['exam_id'], $row['exam_id']);
  array_push($exam_list['exam_name'], $row['exam_name']);
  array_push($exam_list['num_questions'], $row['num_questions']);
  array_push($exam_list['exam_type'], $row['exam_type']);
}

if (isset($_GET['exam_id'])) {
  $exam_id = json_decode($_GET['exam_id']);
}
else {
  $exam_id = 0;
}
$data = array();
$data_query = "SELECT first_name, last_name, exam_results.student_number, percentage, score, total, UNIX_TIMESTAMP(exam_results.date) as time, DATE_FORMAT(DATE, '%d %M %Y') AS date, DATE_FORMAT(DATE, '%d %M %Y %H:%i:%s') AS timestamp FROM exam_results JOIN members ON members.student_number = exam_results.student_number WHERE exam_id = ".$exam_id." ORDER BY time DESC;";
$results = mysqli_query($dbconfig, $data_query);
if ($results != false) {
  $data['first_name'] = array();
  $data['last_name'] = array();
  $data['student_number'] = array();
  $data['score'] = array();
  $data['total'] = array();
  $data['percentage'] = array();
  $data['date'] = array();
  $data['timestamp'] = array();
  while ($row = mysqli_fetch_assoc($results)) {
    array_push($data['first_name'], $row['first_name']);
    array_push($data['last_name'], $row['last_name']);
    array_push($data['student_number'], $row['student_number']); 
    array_push($data['score'], $row['score']);
    array_push($data['total'], $row['total']);
    array_push($data['percentage'], $row['percentage']);
    array_push($data['date'], $row['date']);
    array_push($data['timestamp'], $row['timestamp']);
  }
  $data['count'] = mysqli_num_rows($results);
}


//Download Exam Scores
if (isset($_GET['download_file']) && $_GET['download_file']==1) {

// output headers so that the file is downloaded rather than displayed
  header('Content-Type: text/csv; charset=utf-8');
  header('Content-Disposition: attachment; filename='.$exam_list['exam_name'][$exam_id].'_'.time().'.csv');

// create a file pointer connected to the output stream
  $output = fopen('php://output', 'w');
    fputcsv($output, array($exam_list['exam_name'][$exam_id]));
    fputcsv($output, array("First Name", "Last Name", "Student Number", "Score", "Total Questions", "Percentage", "Time"));
  for ($i = 0; $i < $data['count']; $i++) {
    fputcsv($output, array($data['first_name'][$i],$data['last_name'][$i],$data['student_number'][$i],$data['score'][$i],$data['total'][$i],$data['percentage'][$i],$data['timestamp'][$i]));
  }
  fclose($output);
  exit();
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
  <link rel="stylesheet" href="css/admin.min.css">
  <link rel="stylesheet" href="css/skin-blue.min.css">
  <!-- Page Style -->
  <link rel="stylesheet" href="css/class_exam_results.css">


  <!-- jQuery 2.2.3 -->
  <script src="js/jquery-2.2.3.min.js"></script>
  <script src="components/all_pages.js"></script>
  <!-- Bootstrap 3.3.6 -->
  <script src="js/bootstrap.min.js"></script>
  <!-- dashboard App -->
  <script src="js/admin.min.js"></script>
  <!-- Datatables -->
  <script src="js/jquery.dataTables.min.js"></script>
  <script src="js/dataTables.bootstrap.min.js"></script>

</head>


<body class="hold-transition skin-blue sidebar-mini fixed">
 <div class="wrapper">

  <!-- Header and Left Menu -->
  <?php if ($_SESSION['admin_boolean']) { include 'components/admin_menu.php'; }
  else { include 'components/member_menu.php'; } ?>

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

      <div class="box">
        <div class="box-header">
          <?php
          echo '
          <a href="?exam_id='.$exam_id.'&download_file=1">Download Spreadsheet</a>
          ';
          ?>
          <div style="text-align:right;">
            <select id="exam_id_dropdown" onchange="change_exam()">
              <?php 

              for ($i = 0; $i < count($exam_list['exam_id']); $i++) {
                echo "<option value='".$exam_list['exam_id'][$i]."'>".$exam_list['exam_name'][$i]."</option>";
              }

              ?>
            </select>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <table id="exam_results_table" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Student Number</th>
                <th>Score</th>
                <th>Total</th>
                <th>Percentage</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody id="table_body">
              <?php for ($i = 0 ; $i < $data['count']; $i++) {
                echo '
                <tr>
                <td>'.$data['first_name'][$i].'</td>
                <td>'.$data['last_name'][$i].'</td>
                <td>'.$data['student_number'][$i].'</td>
                <td>'.$data['score'][$i].'</td>
                <td>'.$data['total'][$i].'</td>
                <td>'.$data['percentage'][$i].'</td>
                <td>'.$data['date'][$i].'</td>
                </tr>
                ';
              }
              ?>
            </tbody>
            <tfoot>
              <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Student Number</th>
                <th>Score</th>
                <th>Total</th>
                <th>Percentage</th>
                <th>Date</th>
              </tr>
            </tfoot>
          </table>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->


    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
</div>

<script>


$(function () {
  $('#exam_results_table').DataTable({
    "paging": true,
    "lengthChange": true,
    "searching": false,
    "ordering": true,
    "info": true,
    "autoWidth": true,
    "iDisplayLength": 100,
    "lengthMenu": [ [50, 100, -1], [50, 100, "All"] ]
  });
});
$(document).ready(function() {
  $("#exam_id_dropdown").val(<?php echo json_encode($exam_id); ?>);
});

function change_exam() {
  var current_url = window.location.href;
  var index = 0;
  var url = current_url;
  index = current_url.indexOf('?');
  if(index == -1){
    index = current_url.indexOf('#');
  }
  if(index != -1){
    url = current_url.substring(0, index);
  }
  var id = $("#exam_id_dropdown").val();
  if (url.indexOf('?') > -1){
    url += '&exam_id='+id+''
  }else{
   url += '?exam_id='+id+''
 }
 window.location.href = url;
}

</script>

</body>
</html>