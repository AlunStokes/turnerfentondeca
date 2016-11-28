<?php

include ('../includes/config.php');
include ('includes/session.php');

if (!$_SESSION['admin_boolean']) {
  header("Location: member_attendance.php");
}

$sessions = array();
$sessions['id'] = array();
$sessions['date'] = array();


//$get_ids_query = "SELECT id, DATE_FORMAT(start_time, '%D %M %Y %h:%i %p') AS date FROM attendance_sessions";
$get_ids_query = "SELECT id, DATE_FORMAT(start_time, '%D %M %Y') AS date FROM attendance_sessions ORDER BY id DESC;";
$result = mysqli_query($dbconfig, $get_ids_query);
while ($row = mysqli_fetch_assoc($result)) {
  array_push($sessions['id'], $row['id']);
  array_push($sessions['date'], $row['date']);
}

if (isset($_GET['id'])) {
  $current_id = json_decode($_GET['id']);
}
else {
  $current_id = $sessions['id'][0];
}

$data['first_name'] = array();
$data['last_name'] = array();
$data['student_number'] = array();
$data['cluster'] = array();
$data['present'] = array();
$data['num_present'] = 0;
$data['num_total'] = 0;


$get_users_query = "SELECT first_name, last_name, student_number, cluster FROM members WHERE admin = 0 AND first_name != 'test';";
$result = mysqli_query($dbconfig, $get_users_query);
while ($row = mysqli_fetch_assoc($result)) {
  array_push($data['first_name'], $row['first_name']);
  array_push($data['last_name'], $row['last_name']);
  array_push($data['cluster'], $row['cluster']);
  array_push($data['student_number'], $row['student_number']);
  $data['num_total']++;
}

$data['present'] = array_fill(0, count($data['first_name']), "");

$present = array();
$get_present_query = "SELECT student_number FROM attendance_individuals WHERE session_id = ".$current_id.";";
$result = mysqli_query($dbconfig, $get_present_query);
while ($row = mysqli_fetch_assoc($result)) {
  array_push($present, $row['student_number']);
}

for ($i = 0; $i < count($present); $i++) {
  $index = array_search($present[$i], $data['student_number']);
  if ($index) {
    $data['present'][$index] = "X";
    $data['num_present']++;
  }
}

$data['count'] = count($data['first_name']);


//Download Attendance Record
if (isset($_GET['download_file']) && $_GET['download_file']==1) {
  $final = array();

  $attendance = array();

  $attendance_date_query = "SELECT DATE_FORMAT(attendance_sessions.start_time, '%D %M %Y') as date FROM attendance_sessions";
  $attendance_date_result = mysqli_query($dbconfig, $attendance_date_query);
  while ($row = mysqli_fetch_assoc($attendance_date_result)) {
    $attendance[$row['date']] = array();
    $final[$row['date']] = array();
  }

  $attendance_indiv_query = "SELECT attendance_individuals.student_number, DATE_FORMAT(attendance_sessions.start_time, '%D %M %Y') as date FROM attendance_individuals JOIN attendance_sessions ON attendance_individuals.session_id = attendance_sessions.id;";
  $attendance_indiv_result = mysqli_query($dbconfig, $attendance_indiv_query);
  while ($row = mysqli_fetch_assoc($attendance_indiv_result)) {
    array_push($attendance[$row['date']], $row['student_number']);
  }

  $members = array();
  $members['first_name'] = array();
  $members['last_name'] = array();
  $members['student_number'] = array();
  $members['cluster'] = array();

  $member_query = "SELECT first_name, last_name, student_number, class as cluster FROM members WHERE admin=0 AND first_name != 'test' ORDER BY last_name;";
  $member_result = mysqli_query($dbconfig, $member_query);
  while ($row = mysqli_fetch_assoc($member_result)) {
    array_push($members['first_name'], $row['first_name']);
    array_push($members['last_name'], $row['last_name']);
    array_push($members['student_number'], $row['student_number']);
    array_push($members['cluster'], $row['cluster']);
  }


// output headers so that the file is downloaded rather than displayed
  header('Content-Type: text/csv; charset=utf-8');
  header('Content-Disposition: attachment; filename=attendance'.time().'.csv');

// create a file pointer connected to the output stream
  $output = fopen('php://output', 'w');

  $absences = array();

  for ($i = 0 ; $i < count($members['student_number']); $i++) {
    $absences[$members['student_number'][$i]] = count(array_keys($attendance));
  }

  foreach (array_keys($attendance) as $date) {
    for ($i = 0 ; $i < count($members['student_number']); $i++) {
      if (in_array($members['student_number'][$i], $attendance[$date])) {
        $absences[$members['student_number'][$i]]--;
      }
    }
  }

  fputcsv($output, array('Total Absences'));
  fputcsv($output, array('First Name', 'Last Name', 'Student Number', 'Cluster', 'Number of Absences'));
  for ($i = 0 ; $i < count($members['student_number']); $i++) {
    fputcsv($output, array($members['first_name'][$i], $members['last_name'][$i], $members['student_number'][$i], $members['cluster'][$i], $absences[$members['student_number'][$i]]));
  }
  fputcsv($output, array(''));

  foreach (array_keys($attendance) as $date) {
    fputcsv($output, array($date));
    fputcsv($output, array(''));
    fputcsv($output, array('First Name', 'Last Name', 'Student Number', 'Cluster', 'Present(X)'));
    for ($i = 0 ; $i < count($members['student_number']); $i++) {
      if (in_array($members['student_number'][$i], $attendance[$date])) {
        fputcsv($output, array($members['first_name'][$i], $members['last_name'][$i], $members['student_number'][$i], $members['cluster'][$i], 'X'));
      }
      else {
        fputcsv($output, array($members['first_name'][$i], $members['last_name'][$i], $members['student_number'][$i], $members['cluster'][$i]));
      }
    }
    fputcsv($output, array(''));
  }
  fclose($output);
  exit();
}



$active_page = 'attendance';

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>TFSS DECA | Attendance</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="css/admin.min.css">
  <link rel="stylesheet" href="css/skin-blue.min.css">
  <!-- Page Style -->
  <link rel="stylesheet" href="css/check_attendance.css">



  <!-- jQuery 2.2.3 -->
  <script src="js/jquery-2.2.3.min.js"></script>
  <!-- Bootstrap 3.3.6 -->
  <script src="js/bootstrap.min.js"></script>
  <!-- AdminLTE App -->
  <script src="js/admin.min.js"></script>
  <!-- Datatables -->
  <script src="js/jquery.dataTables.min.js"></script>
  <script src="js/dataTables.bootstrap.min.js"></script>

</head>


<body class="hold-transition skin-blue sidebar-mini">
 <div class="wrapper">

  <!-- Header and Left Menu -->
  <?php if ($_SESSION['admin_boolean']) { include 'components/admin_menu.php'; }
  else { include 'components/member_menu.php'; } ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Main content -->
    <section class="content">

     <div class="box">
      <div class="box-header">
        <h3><?php echo $data['num_present']."/".$data['num_total']; ?> Present</h3>
        <a href="?download_file=1">Download Spreadsheet</a>
        <div style="text-align:right;">
          <select id="id_dropdown" onchange="change_attendance()">
            <?php 

            for ($i = 0; $i < count($sessions['id']); $i++) {
              echo "<option value='".$sessions['id'][$i]."'>".$sessions['date'][$i]."</option>";
            }

            ?>
          </select>
        </div>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table id="attendance_results_table" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Student Number</th>
              <th>Cluster</th>
              <th>Present</th>
            </tr>
          </thead>
          <tbody id="table_body">
            <?php for ($i = 0 ; $i < $data['count']; $i++) {
              echo '
              <tr>
              <td>'.$data['first_name'][$i].'</td>
              <td>'.$data['last_name'][$i].'</td>
              <td>'.$data['student_number'][$i].'</td>
              <td>'.$data['cluster'][$i].'</td>
              <td>'.$data['present'][$i].'</td>
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
              <th>Cluster</th>
              <th>Present</th>
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
  $('#attendance_results_table').DataTable({
    "paging": true,
    "lengthChange": true,
    "searching": false,
    "ordering": true,
    "info": true,
    "autoWidth": true
  });
});
$(document).ready(function() {
  $("#id_dropdown").val(<?php echo json_encode($current_id); ?>);
});

function change_attendance() {
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
  var id = $("#id_dropdown").val();
  if (url.indexOf('?') > -1){
    url += '&id='+id+''
  }else{
   url += '?id='+id+''
 }
 window.location.href = url;
}

</script>

</body>
</html>