<?php

include ('../includes/config.php');
include ('includes/session.php');

if (!$_SESSION['admin_boolean']) {
  header("Location: member_attendance.php");
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
  <link rel="stylesheet" href="css/attendance.css">



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
      <!--<section class="content-header">
        <h1>
          Home
          <small>General Info</small>
        </h1>
      </section>-->

      <!-- Main content -->
      <section class="content">



       <button class="btn btn-lg btn-primary btn-block btn-full-5" id="start_session_button">Start Session</button>
       <button class="btn btn-lg btn-primary btn-block btn-full-5" id="end_session_button">End Session</button>
       <button class="btn btn-lg btn-primary btn-block btn-full-5" id="check_attendance_button">Check Attendance (Don't Use)</button>


     </section>
     <!-- /.content -->
   </div>
   <!-- /.content-wrapper -->
 </div>


 <!--Start Session Modal -->
 <div class="modal fade" id="start_session_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <form id="start_form" role="form">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Start Session</h4>
          <ul id="start_ul">

          </ul>
          <input type="text" placeholder="Code Word" id="code_word" style="margin-left:10%; min-height: 3vh; min-width: 80%;" />
        </div>
        <div class="modal-footer" id="footer" align:"center">
          <input type="button" class="btn btn-primary" id="begin_session" value="Begin" />
          <button type="button" class="btn btn-default" data-dismiss="modal" id="close">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!--End Session Modal -->
<div class="modal fade" id="end_session_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <form id="end_form" role="form">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="myModalLabel">End Session</h4>
        <ul id="end_ul">

        </ul>
      </div>
      <div class="modal-footer" id="footer" align:"center">
        <input type="button" class="btn btn-primary" id="end_session" value="End" />
        <button type="button" class="btn btn-default" data-dismiss="modal" id="close">Cancel</button>
      </div>
      </form>
    </div>
  </div>
</div>

<script>



$(document).ready(function() {
var result;

  $.ajax({
    type: "get",
    url: "includes/attendance_ajax.php",
    data: {ajax_id : JSON.stringify("load")},
  }).done(function(data){ 
    result = jQuery.parseJSON(data);
    $("#start_ul").html ('');
    for (var i = 0; i < result['closed_classes'].length; i++) {
      $("#start_ul").append (`
        <li class="checkbox">
        <input type="radio" id="`+result['closed_classes'][i]+`" name="selector">
        <label for="`+result['closed_classes'][i]+`">`+result['closed_classes_names'][i]+`</label>

        <div class="check"></div>
        </li>
        `);
    }

    $("#end_ul").html ('');
    for (var i = 0; i < result['open_classes'].length; i++) {
      $("#end_ul").append (`
        <li class="checkbox">
        <input type="radio" id="`+result['open_classes'][i]+`" name="selector">
        <label for="`+result['open_classes'][i]+`">`+result['open_classes_names'][i]+`</label>

        <p>Code is <q>` + result['open_classes_codes'][i] + `</q></p>
        <div class="check"></div>
        </li>
        `);
    }
  $("#start_session_button").click(function() {
    $('#start_session_modal').modal('show');
  });
  $("#end_session_button").click(function() {
    $('#end_session_modal').modal('show');
  });
  $("#begin_session").click(function() {
    start_ajax($('input[name=selector]:checked', '#start_form').attr('id'), result);
  });
  $("#end_session").click(function() {
    end_ajax($('input[name=selector]:checked', '#end_form').attr('id'), result);
  });
  });


});

function start_ajax (cluster, result) {
  if ($("#code_word").val().length == 0) {
    alert ("Enter a code word");
    return;
  }
  var code_word = $("#code_word").val();
  $.ajax({
    type: "get",
    url: "includes/attendance_ajax.php",
    data: {cluster : JSON.stringify(cluster),
      ajax_id : JSON.stringify("start"),
      code_word : JSON.stringify(code_word)},
    }).done(function(data){ 
      location.reload();
      result = jQuery.parseJSON(data);

      $("#start_ul").html ('');
    for (var i = 0; i < result['closed_classes'].length; i++) {
      $("#start_ul").append (`
        <li class="checkbox">
        <input type="radio" id="`+result['closed_classes'][i]+`" name="selector">
        <label for="`+result['closed_classes'][i]+`">`+result['closed_classes_names'][i]+`</label>

        <div class="check"></div>
        </li>
        `);
    }

    $("#end_ul").html ('');
    for (var i = 0; i < result['open_classes'].length; i++) {
      $("#end_ul").append (`
        <li class="checkbox">
        <input type="radio" id="`+result['open_classes'][i]+`" name="selector">
        <label for="`+result['open_classes'][i]+`">`+result['open_classes_names'][i]+`</label>

        <p>Code is <q>` + result['open_classes_codes'][i] + `</q></p>
        <div class="check"></div>
        </li>
        `);
    }
    
    });
    $('#start_session_modal').modal('hide');
  }

  function end_ajax (cluster, result) {
    var id = result['open_classes_id'][result['open_classes'].indexOf(cluster)];
    $.ajax({
    type: "get",
    url: "includes/attendance_ajax.php",
    data: {ajax_id : JSON.stringify("end"),
      id : JSON.stringify(id)},
    }).done(function(data){ 
      result = jQuery.parseJSON(data);

      $("#start_ul").html ('');
    for (var i = 0; i < result['closed_classes'].length; i++) {
      $("#start_ul").append (`
        <li class="checkbox">
        <input type="radio" id="`+result['closed_classes'][i]+`" name="selector">
        <label for="`+result['closed_classes'][i]+`">`+result['closed_classes_names'][i]+`</label>

        <div class="check"></div>
        </li>
        `);
    }

    $("#end_ul").html ('');
    for (var i = 0; i < result['open_classes'].length; i++) {
      $("#end_ul").append (`
        <li class="checkbox">
        <input type="radio" id="`+result['open_classes'][i]+`" name="selector">
        <label for="`+result['open_classes'][i]+`">`+result['open_classes_names'][i]+`</label>

        <div class="check"></div>
        </li>
        <p>Code is <q>` + result['open_classes_codes'][i] + `</q></p>
        `);
    }
    
    });
    $('#end_session_modal').modal('hide');
  }

  </script>

</body>
</html>