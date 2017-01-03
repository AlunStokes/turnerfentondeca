<?php

include ('../includes/config.php');
include ('includes/session.php');

$active_page = 'classes';

$exams = array();
$exams['percentage'] = array();
$exams['date'] = array();
$exams['score'] = array();
$exams['total'] = array();

$exam_score_query = "SELECT percentage, DATE_FORMAT(DATE, '%M %Y') AS date, DATE_FORMAT(DATE, '%D %M %Y') AS full_date, score, total FROM exam_results LEFT JOIN created_exams ON created_exams.exam_id = exam_results.exam_id WHERE student_number = ".$_SESSION['student_number']." AND (include_stats = 1 OR exam_results.exam_id = 0) ORDER BY percentage DESC;";
$results = mysqli_query($dbconfig, $exam_score_query);
while ($row = mysqli_fetch_assoc($results)) {
  array_push($exams['percentage'], $row['percentage']);
  array_push($exams['date'], $row['full_date']);
  array_push($exams['score'], $row['score']);
  array_push($exams['total'], $row['total']);
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>TFSS DECA | Exam Statistics</title>
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
  <link rel="stylesheet" href="css/exam_statistics.css">


  <!-- jQuery 2.2.3 -->
  <script src="js/jquery-2.2.3.min.js"></script>
  <script src="components/all_pages.js"></script>
  <!-- Bootstrap 3.3.6 -->
  <script src="js/bootstrap.min.js"></script>
  <!-- dashboard App -->
  <script src="js/core/dashboard.js"></script>
  <script src="js/bootstrap-dialog.min.js"></script>
  <script src="js/Chart.bundle.min.js"></script>
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
        Exam Statistics
        <!-- <small>General Info</small> -->
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-4" id="doughnut_chart_area">
          <canvas id="doughnut_chart" width="1" height="1"></canvas>
        </div>
        <div class="col-md-4 col-md-offset-0">
          <h1 style="font-size:126px;" id="best_score"><h1>
          </div>
          <div class="col-md-4 col-md-offset-0">
            <h1 style="font-size:126px;" id="average_score"><h1>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <canvas id="time_chart" width="5" height="2"></canvas>
            </div>
          </div>
          <div class="row">


            <div class="box">
              <div class="box-header">
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <table id="exam_results" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>Score</th>
                      <th>Total</th>
                      <th>Percentage</th>
                    </tr>
                  </thead>
                  <tbody id="table_body">
                    <?php for ($i = 0 ; $i < count($exams['date']); $i++) {
                      echo '
                      <tr>
                      <td>'.$exams['date'][$i].'</td>
                      <td>'.$exams['score'][$i].'</td>
                      <td>'.$exams['total'][$i].'</td>
                      <td>'.$exams['percentage'][$i].'%</td>
                      </tr>
                      ';
                    }
                    ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th>Date</th>
                      <th>Score</th>
                      <th>Total</th>
                      <th>Percentage</th>
                    </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.box-body -->
            </div>
            <!-- /.box -->


          </div>

        </section>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->
    </div>

    <script>
    $(function () {
  $('#exam_results').DataTable({
    "paging": true,
    "lengthChange": true,
    "searching": false,
    "ordering": true,
    "info": true,
    "autoWidth": false
  });
});

    $.ajax({
      type: "get",
      url: "includes/ajax",
      data: {ajax_id : JSON.stringify("personal_exam_stats")},
    }).done(function(final){ 
      var final = jQuery.parseJSON(final);
      if (final['scores']) {

        $("#best_score").append("<h1>Best Score:</h1>");
        $("#best_score").append(final['best_score']+"%");

        $("#average_score").append("<h1>Average Score:</h1>");
        $("#average_score").append(final['average_score']+"%");

        var doughtnut_chart = $("#doughtnut_chart");
        var doughnut_data = {
          labels: [
          "Marketing",
          "Finance",
          "Business Administration",
          "Hospitality & Tourism"
          ],
          datasets: [
          {
            data: [final['marketing_percent'], final['finance_percent'], final['businessadmin_percent'], final['hospitality_percent']],
            backgroundColor: [
            "#222D32",
            "#255C99",
            "#EE4266",
            "#3C8DBC"
            ],
            hoverBackgroundColor: [
            "#222D32",
            "#255C99",
            "#EE4266",
            "#3C8DBC"
            ]
          }]
        };
        var myDoughnutChart = new Chart(doughnut_chart, {
          type: 'doughnut',
          data: doughnut_data,
          animation:{
            animateScale:true
          }
        });


        var time_chart = $("#time_chart");
        var time_data = {
          labels: ["Sept. 2016", "Oct. 2016", "Nov. 2016", "Dec. 2016", "Jan. 2017", "Feb. 2017", "Mar. 2017", "Apr. 2017", "May 2017"],
          datasets: [
          {
            label: "Exam Score",
            fill: false,
            lineTension: 0.1,
            backgroundColor: "rgba(75,192,192,0.4)",
            borderColor: "rgba(75,192,192,1)",
            borderCapStyle: 'butt',
            borderDash: [],
            borderDashOffset: 0.0,
            borderJoinStyle: 'miter',
            pointBorderColor: "rgba(75,192,192,1)",
            pointBackgroundColor: "#fff",
            pointBorderWidth: 1,
            pointHoverRadius: 5,
            pointHoverBackgroundColor: "rgba(75,192,192,1)",
            pointHoverBorderColor: "rgba(220,220,220,1)",
            pointHoverBorderWidth: 2,
            pointRadius: 3,
            pointHitRadius: 10,
            data: [final['sept16'], final['oct16'], final['nov16'], final['dec16'], final['jan17'], final['feb17'], final['mar17'], final['apr17'], final['may17']],
            spanGaps: false,
          }
          ]
        };
        var time_options = {
          scales: {
            yAxes: [{
              display: true,
              ticks: {
                suggestedMin: 0,    // minimum will be 0, unless there is a lower value.
                // OR //
                beginAtZero: true,   // minimum value will be 0.
                max: 100
              }
            }]
          }
        };
        var myLineChart = new Chart(time_chart, {
          type: 'line',
          data: time_data,
          options: time_options
        });

      }
      else {
        $("#doughnut_chart_area").append("<h1>No exam data yet - <a href='practice'>Try an exam now</a></h1>");
      }
    });

</script>

</body>
</html>