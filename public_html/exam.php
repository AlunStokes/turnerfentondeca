<?php

ini_set("display_errors", 0);

include ('../includes/config.php');
include ('includes/session.php');

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>TFSS DECA | Practice</title>
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
  <link rel="stylesheet" href="css/exam.css">
  <!-- Spinner Style -->
  <link rel="stylesheet" href="css/please-wait.css">


  <!-- jQuery 2.2.3 -->
  <script src="js/jquery-2.2.3.min.js"></script>
  <script src="components/all_pages.js"></script>
  <!-- Bootstrap 3.3.6 -->
  <script src="js/bootstrap.min.js"></script>
  <!-- AdminLTE App -->
  <script src="js/admin.min.js"></script>
  <!-- jQuery Easing Javascript -->
  <script src="js/jquery.easing.min.js"></script>
  <!-- jQuery Timer Javascript -->
  <script src="js/jquery.simple.timer.js"></script>
  <!-- Please Wait JS -->
  <script src="js/please-wait.min.js"></script>

</head>


<body class="hold-transition skin-blue sidebar-mini fixed">
  <div class="wrapper">
    <!-- Main Header -->
    <header class="main-header">

      <!-- Logo -->
      <a href="practice.php" class="logo">
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><i class="glyphicon glyphicon-chevron-left"></i> Back to Practice</span>
      </a>

      <!-- Header Navbar -->
      <nav class="navbar navbar-static-top" role="navigation">

      </nav>
    </header>


    <div style="padding-left:1vw; padding-right:1vw;">
      <section class="container-fluid-body">
        <div class="navbar-top-buffer"></div>
        <div id="timer_container">
        </div>
      </section>

      <section class="container-fluid-submit">
        <h1>Once finished, submit the exam below.

          <div id="errors">
          </div>

          <form id="submit" role="form">
            <button type="submit" class="btn btn-lg btn-primary btn-block" id="submit_button" style="margin-top:2vh;">Submit</button>
          </form>

        </section>
      </div>
    </div>
    <!-- /.content-wrapper -->


    <script type="text/javascript">
    var spinner = `
    <div class="spinner">
    <div class="bounce1"></div>
    <div class="bounce2"></div>
    <div class="bounce3"></div>
    </div>
    `;
    window.loading_screen = window.pleaseWait({
      logo: "img/logo_medium_white.png",
      backgroundColor: '#222d32',
      loadingHtml: spinner
    });


    var submitted = false;
    var chosen;
    var data;

    $(document).ready(function(){
      var admin = <?php echo $_SESSION['admin']; ?>;
      var done = <?php if (isset($_POST['done'])) { echo json_encode(intval($_POST['done'])); } else { echo "null"; } ?>;
      if (done == 1 && admin == 0) {
        window.location.assign("practice.php");
      }
      var exam_id = <?php if (isset($_POST['exam_id'])) { echo json_encode(intval($_POST['exam_id'])); } else { echo "null"; } ?>;
      if (exam_id == null) {
        window.location.assign("practice.php");
      }
      if (exam_id == 0) {
        var exam_cluster = <?php if (isset($_POST['exam_cluster'])) { echo json_encode($_POST['exam_cluster']); } else { echo "null"; } ?>;
      }
      if (exam_cluster == "null") {
        exam_cluster = null;
      }

//Load questions
$.ajax({
  type: "post",
  url: "includes/ajax.php",
  data: {ajax_id : JSON.stringify("exam_get_questions"),
  exam_cluster : JSON.stringify(exam_cluster),
  exam_id : JSON.stringify(exam_id)}
}).done(function(data){
  data = jQuery.parseJSON(data);
  $("#timer_container").append(`<div class="timer" style="text-align:center; color:#333; font-size:40px;"data-seconds-left=`+data['time']+`></div>`);
  if (data['unlocked'] == 1) {
    if (admin == 0 || exam_id == 0) {
      $('.timer').startTimer({
        onComplete: function() {
          submit_exam();
        }
      });
    }
  }
  if (data['unlocked'] == 0 && admin == 0) {
    window.location.assign("practice.php");
  }
  chosen = new Array(data['num_questions']);
  $('.container-fluid-body').append(`
    <h1 style="text-align:center">Exam - ` + data['num_questions'] + ` Questions</h1>
    <h1 id="score" style="text-align:center;"></h1>
    <hr width="100%" height="2px">
    `);
  var counter = 0;
  data['question_id'].forEach(writeQuestions);

    loading_screen.finish();

//Display Quesitons
function writeQuestions(item,index) {
  $('.container-fluid-body').append(`
    <div class="row">


    <div class="col-md-5 question_box_` + (item) + `">
    <h1 class="question_number">`+(counter+1)+`.</h1>
    <p class="question">` + data['question'][item] + `</p>
    </div>


    <div class="col-md-5 options_box">
    <label>
    <input type="radio" value="A" id="radiobutton_` + (item) + `_A" name="radiobutton_` + (item) + `"><p class="option">A: ` + data['option_a'][item] + `</p><br>
    </label>
    <br>
    <label>
    <input type="radio" value="B" id="radiobutton_` + (item) + `_B" name="radiobutton_` + (item) + `"><p class="option">B: ` + data['option_b'][item] + `</p><br>
    </label>
    <br>
    <label>
    <input type="radio" value="C" id="radiobutton_` + (item) + `_C" name="radiobutton_` + (item) + `"><p class="option">C: ` + data['option_c'][item] + `</p><br>
    </label>
    <br>
    <label>
    <input type="radio" value="D" id="radiobutton_` + (item) + `_D" name="radiobutton_` + (item) + `"><p class="option">D: ` + data['option_d'][item] + `</p><br>
    </label>
    <br>
    </div>


    <div class="col-md-2">
    <p id="answer_text_` + (item) + `" style="margin-bottom:0px; margin-top: 15px"></p>
    <h1 id="answer_` + (item) + `" style="margin-top:2px; margin-bottom:4vh; color:#d9534f;">-</h1>
    <p id="correct_answer_text_` + (item) + `" style="margin-bottom:0px;"></p>
    <h1 id="correct_answer_` + (item) + `" style="margin-top:2px;"></h1>
    </div>


    </div>
    <hr width="70%"></hr>
    `);

if (admin == 1 && exam_id != 0) {
  $('.question_box_' + (item)).append(`
    <i><p>Answer: `+data['answer'][item]+`</p></i>
    `);
}
counter++;
}

$('input[type=radio]').change(function() {
  var num = parseInt(this.id.replace(/\D/g, ''), 10);
  chosen[num] = this.value;
  $('#answer_'+num).html(this.value);
  $('#answer_'+num).css('color', '#333');
});

$('#submit_button').on('click', function(evt) {
  var chosen_unanswered = new Array (data['num_questions']);
  this.disabled = true;
  $("input:radio").attr('disabled', 'disabled');
  var completed = true;
  data['question_id'].forEach(checkAnswered)
  function checkAnswered(item,index) {
    if (typeof chosen[item] == 'undefined') {
        //alert (chosen[item]);
        completed = false;
        chosen_unanswered.push(item);
      }
    }

    evt.preventDefault();
    if (!completed) {
      $('#errors').append(`
        <div id="error_modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Error</h4>
        </div>
        <div class="modal-body">
        <p>Please answer all questions before submitting.</p>
        </div>
        </div>

        </div>
        </div>
        `
        );
      $('#error_modal').modal('show');
      this.disabled = false;
      $("input:radio").removeAttr('disabled');
    }
    else {
      if (!submitted) {
        submit_exam();
      }
      else {
        alert ("You cannot resubmit your exam");
      }
    }
  });

function submit_exam() {
  if (submitted) {
    return;
  }
  $('#submit_button').disabled = true;
  $("input:radio").attr('disabled', 'disabled');
  submitted = true;
  $.ajax({
    type: "post",
    url: "includes/ajax.php",
    data: {ajax_id : JSON.stringify("exam_submit"),
    chosen : JSON.stringify(chosen),
    exam_id : JSON.stringify(exam_id),
    num_questions : JSON.stringify(data['num_questions']),
    question_id : JSON.stringify(data['question_id']),
    include_stats : JSON.stringify(data['include_stats'])},
  }).done(function(data_submit){
    $("html, body").animate({ scrollTop: 0 }, "slow");
    alert("Your exam has been submitted.  You may leave the page.");
    $(".timer").stop();
    var data_submit = jQuery.parseJSON(data_submit);

    if (data['show_score'] == 1) {
      data['question_id'].forEach(writeAnswers);
    }
    function writeAnswers(item,index) {
      if (data_submit['correct'][item] == 1) {
        document.getElementById("answer_text_" + (item)).innerHTML = "Correct!";
        document.getElementById("answer_text_" + (item)).style.color = "#5cb85c";
        document.getElementById("answer_" + (item)).style.color = "#5cb85c";
      }
      else {
        document.getElementById("answer_text_" + (item)).innerHTML = "Your Answer:";
        document.getElementById("answer_text_" + (item)).style.color = "#d9534f";
        document.getElementById("answer_" + (item)).style.color = "#d9534f";
        document.getElementById("correct_answer_text_" + (item)).innerHTML = "Correct Answer:";
        document.getElementById("correct_answer_text_" + (item)).style.color = "#5cb85c";
        document.getElementById("correct_answer_" + (item)).innerHTML = data_submit['answers'][item];
        document.getElementById("correct_answer_" + (item)).style.color = "#5cb85c";
      }
    }
    if (data['show_score'] == 1) {
      document.getElementById("score").innerHTML = data_submit['num_correct'] + "/" + data['num_questions'] + " Answered Correctly - " + data_submit['percent_correct'] + "%";
      if (data_submit['percent_correct'] >= 80) {
        document.getElementById("score").style.color = "#5cb85c";
      }
      else if (data_submit['percent_correct'] >= 65) {
        document.getElementById("score").style.color = "#f0ad4e";
      }
      else {
        document.getElementById("score").style.color = "#d9534f";
      }
    }

  });
}

});
});
/*
  $(window).focus(function() {
    clearTimeout(window_timer);
  });
  $(window).blur(function() {
    if (!submitted) {
      alert ("If you leave, your exam will be submitted automatically");
      window_timer = setTimeout(function(){ submit_exam() }, 3000);
    }
  });
*/

</script>


</html>
