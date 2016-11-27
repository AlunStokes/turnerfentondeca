<?php

include ('../includes/config.php');
include ('includes/session.php');

$active_page = 'practice';

unset($_SESSION['exam_id']);

if (isset($_GET['begin_exam'])) {
  $_SESSION['exam_cluster'] = $_GET['cluster'];
  $_SESSION['exam_started'] = false;
  $_SESSION['num_questions'] = 100;
  header('Location: exam.php');
}

if ($_SESSION['member'] == false) {
  header("Location:applicant_home.php");
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>TFSS DECA | Practice</title>
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
  <link rel="stylesheet" href="css/practice.css">



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
      <!-- <section class="content-header">
        <h1>
          Home
          <small>General Info</small>
        </h1>
      </section> -->

      <!-- Main content -->
      <section class="content">

       <button class="btn btn-lg btn-primary btn-block btn-full-5" id="new_exam">Start New Exam</button>
       <button class="btn btn-lg btn-primary btn-block btn-full-5" id="search_exams">Search Exams</button>
       <button class="btn btn-lg btn-primary btn-block btn-full-5" id="question_search">Find Specific Question</button>
       <?php if ($_SESSION['admin_boolean']) { echo '<button class="btn btn-lg btn-primary btn-block btn-full-5" onclick="location.href=`create_exam.php`">Create Exam</button>'; } ?>
       <?php if ($_SESSION['admin_boolean']) { echo '<button class="btn btn-lg btn-primary btn-block btn-full-5" onclick="location.href=`add_question.php`">Add Question</button>'; } ?>


     </section>
     <!-- /.content -->
   </div>
   <!-- /.content-wrapper -->

   <!--New Exam Modal -->
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

  <div class="modal fade" id="question_modal" role="dialog">
    <div class="modal-dialog modal-lg" style="overflow-y: initial;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Question Search</h4>
        </div>
        <div class="modal-body" id="modal-body">
          <h1>Question Contains:</h1>
          <div class="row search-bar">
            <div class="col-md-9">
              <input tpye="text" id="question_contains" class="question-search" placeholder="Write words in question" style="width:100%; font-size:14px; height:30px;" />
              <button tpye="button" id="query_questions" class="btn btn-primary btn-block" style="width:100%; margin-bottom:2vh;">Search</button>
            </div>
            <div class="col-md-3" style="text-align:right; padding:8px;">
              <select id="question_dropdown">
                <option value="mix">All Clusters</option>
                <option value="marketing">Marketing</option>
                <option value="businessadmin">Business Administration</option>
                <option value="finance">Finance</option>
                <option value="hospitality">Hospitality & Tourism</option>
              </select>
            </div>
          </div>
          <div id="question_area" style="height: 60vh; overflow-y: auto; padding:30px;">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="search_modal" role="dialog">
    <div class="modal-dialog modal-lg" style="overflow-y: initial;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Exam List</h4>
        </div>
        <div class="modal-body" id="modal-body">
          <div class="row search-bar">
            <div class="col-md-9">
              <input tpye="text" id="exam_name_contains" class="question-search" placeholder="Write words in exam name" style="width:100%; font-size:14px; height:30px;" />
              <button tpye="button" id="query_exams" class="btn btn-primary btn-block" style="width:100%; margin-bottom:2vh;">Search</button>
            </div>
            <div class="col-md-3" style="text-align:right; padding:8px;">
              <select id="exam_dropdown">
                <option value="all">All Clusters</option>
                <option value="mix">Mixed Clusters</option>
                <option value="marketing">Marketing</option>
                <option value="businessadmin">Business Administration</option>
                <option value="finance">Finance</option>
                <option value="hospitality">Hospitality & Tourism</option>
              </select>
            </div>
          </div>
          <div id="exam_area"style="height: 60vh; overflow-y: auto; padding:30px;" >
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">

$(document).ready(function() {
  $('#new_exam').click(function() {
    $('#new_exam_modal').modal('show');
  });
  $('#question_search').click(function() {
    document.getElementById('question_dropdown').value = "mix";
    document.getElementById('question_contains').value = "";
    $('#question_modal').modal('show');
    question_list_ajax();
  });
  $('#search_exams').click(function() {
    document.getElementById('exam_dropdown').value = "all";
    document.getElementById('exam_name_contains').value = "";
    $('#search_modal').modal('show');
    exam_list_ajax();
  });
  $('#query_questions').click(function() {
   question_list_ajax();
 });
  $('#question_contains').keyup(function() {
    question_list_ajax();
  });
  $('#question_dropdown').on('change', function() {
    question_list_ajax();
  });
  $('#query_exams').click(function() {
   exam_list_ajax();
 });
  $('#exam_name_contains').keyup(function() {
    exam_list_ajax();
  });
  $('#exam_dropdown').on('change', function() {
    exam_list_ajax();
  });
  $(document).on("click", ".btn-choose-exam", function() {
    var exam_id = this.id.match(/\d+/)[0];
    $.ajax({
      type: "get",
      url: "includes/ajax.php",
      data: {ajax_id : JSON.stringify("practice_start_exam"),
      exam_id : JSON.stringify(exam_id)},
      dataType : "json"
    }).done(function(data){ 
      window.location.assign("exam.php");
    });
  });
});

function question_list_ajax() {
  var search_text = document.getElementById('question_contains').value;
  var question_type = document.getElementById('question_dropdown').value;
  $.ajax({
    type: "get",
    url: "includes/ajax.php",
    data: {ajax_id : JSON.stringify("practice_search_questions"),
      search : JSON.stringify(search_text),
      question_type : JSON.stringify(question_type)},
      dataType : "json"
    }).done(function(data){ 
      var results = jQuery.parseJSON(JSON.stringify(data));
      $('#question_area').html('');
      if (typeof results['questions'][0] == 'undefined') {
        $('#question_area').append(`
          <h3>Search found 0 Results</h3>
          `);
      }
      else {
        if (results['count'] == 75) {
          $('#question_area').append("<h2 style='text-align:center; color:#3c8dbc'>More than " + results['count'] + " Results - Narrow your Search</h2>");
        }
        else {
          $('#question_area').append("<h2 style='text-align:center; color:#3c8dbc'>" + results['count'] + " Results</h2>");
        }
        for (var i = 0; i < results['count']; i++) {
          $('#question_area').append(`
            <h3>` + results['questions'][i] + `</h3>
            <p>A: ` + results['option_a'][i] + `</p>
            <p>B: ` + results['option_b'][i] + `</p>
            <p>C: ` + results['option_c'][i] + `</p>
            <p>D: ` + results['option_d'][i] + `</p>
            <p style="font-weight:bold">Answer: ` + results['answers'][i] + `</p>
            `)
        }
      }
    });
}

function exam_list_ajax() {
  var exam_search_text = document.getElementById('exam_name_contains').value;
  var exam_type = document.getElementById('exam_dropdown').value;
  $.ajax({
    type: "get",
    url: "includes/ajax.php",
    data: {ajax_id : JSON.stringify("practice_search_exams"),
      search : JSON.stringify(exam_search_text),
      exam_type : JSON.stringify(exam_type)},
      dataType : "json"
    }).done(function(data){ 
      var results = jQuery.parseJSON(JSON.stringify(data));
      $('#exam_area').html('');
      if (typeof results['exam_name'][0] == 'undefined') {
        $('#exam_area').append(`
          <h3>Search found 0 Results</h3>
          `);
      }
      else {
        if (results['count'] == 75) {
          $('#exam_area').append("<h2 style='text-align:center; color:#3c8dbc'>More than " + results['count'] + " Results - Narrow your Search</h2>");
        }
        else {
          $('#exam_area').append("<h2 style='text-align:center; color:#3c8dbc'>" + results['count'] + " Results</h2>");
        }
        for (var i = 0; i < results['count']; i++) {
          $('#exam_area').append(`
            <div class="row">
            <div class="col-md-8">
            <h3 style="margin-bottom:0px; margin-top:0px;">` + results['exam_name'][i] + `</h3>
            <p style="margin-bottom:0px;">` + results['exam_type'][i] + `</p>
            <p style="margin-bottom:0px;">` + results['num_questions'][i] + ` Questions</p>
            </div>
            <div class="col-md-2 col-md-offset-2">
            <button class="btn btn-choose-exam" id="start_exam_` + results['exam_id'][i] + `"> Start Exam </button>
            </div>
            </div>
            <hr width="60%"></hr>
            `)
        }
      }
    });
}


$('#question_modal').on('hidden.bs.modal', function () {
  $('#question_area').html('');
  $('#question_contains').val('');
})

</script>



</body>
</html>