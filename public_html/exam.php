<?php

include ('../includes/config.php');
include ('includes/session.php');
include ('includes/functions.php');

if (isset($_SESSION['exam_id'])) {
 $id_query = 'SELECT created_exams_questions.question_id, question, option_a, option_b, option_c, option_d, answer FROM created_exams_questions LEFT JOIN questions ON questions.question_id = created_exams_questions.question_id LEFT JOIN questions_answers ON questions_answers.question_id = questions.question_id LEFT JOIN questions_options ON questions_options.question_id = created_exams_questions.question_id WHERE exam_id = '.$_SESSION["exam_id"].' ORDER BY RAND()';
 $num_questions_query = 'SELECT num_questions FROM created_exams WHERE exam_id = '.$_SESSION['exam_id'].'';
 $num_questions_result = mysqli_query($dbconfig, $num_questions_query);
 $row = mysqli_fetch_assoc($num_questions_result);
 $_SESSION['num_questions'] = $row['num_questions'];
}
else if ($_SESSION['exam_cluster'] == 'mix') {
  $id_query = 'SELECT questions.question_id, question, option_a, option_b, option_c, option_d, answer, cluster FROM questions LEFT JOIN questions_options ON questions_options.question_id = questions.question_id LEFT JOIN questions_answers ON questions_answers.question_id = questions.question_id LEFT JOIN questions_cluster ON questions_cluster.question_id = questions.question_id  ORDER BY RAND() LIMIT '.$_SESSION["num_questions"].'';
}
else if (isset($_SESSION['exam_cluster'])) {
  $id_query = 'SELECT questions.question_id, question, option_a, option_b, option_c, option_d, answer, cluster FROM questions LEFT JOIN questions_options ON questions_options.question_id = questions.question_id LEFT JOIN questions_answers ON questions_answers.question_id = questions.question_id LEFT JOIN questions_cluster ON questions_cluster.question_id = questions.question_id WHERE cluster = "'.$_SESSION["exam_cluster"].'" ORDER BY RAND() LIMIT '.$_SESSION['num_questions'].'';
}
else {
  header("Location: practice_simple.php");
}

$time = floor($_SESSION['num_questions'] / 0.0222222222222222);

unset($_SESSION['exam_cluster']);
$_SESSION['questions_id'] = array();
$_SESSION['questions'] = array();
$_SESSION['option_a'] = array();
$_SESSION['option_b'] = array();
$_SESSION['option_c'] = array();
$_SESSION['option_d'] = array();
$_SESSION['answer'] = array();

$id_result = mysqli_query($dbconfig, $id_query) or die (mysqli_error($dbconfig));

while ($row = mysqli_fetch_assoc($id_result)) {
  array_push($_SESSION['questions_id'], $row['question_id']);
  $_SESSION['questions'][$row['question_id']] = $row['question'];
  $_SESSION['option_a'][$row['question_id']] = $row['option_a'];
  $_SESSION['option_b'][$row['question_id']] = $row['option_b'];
  $_SESSION['option_c'][$row['question_id']] = $row['option_c'];
  $_SESSION['option_d'][$row['question_id']] = $row['option_d'];
  $_SESSION['answer'][$row['question_id']] = $row['answer'];
}
//sort($_SESSION['exam_questions_id']);

if ($_SESSION['member'] == false) {
  header("Location:applicant_home.php");
}

//print_r($_SESSION['answer']);


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
  <link rel="stylesheet" href="css/exam.css">



  <!-- jQuery 2.2.3 -->
  <script src="js/jquery-2.2.3.min.js"></script>
  <!-- Bootstrap 3.3.6 -->
  <script src="js/bootstrap.min.js"></script>
  <!-- AdminLTE App -->
  <script src="js/admin.min.js"></script>
  <!-- jQuery Easing Javascript -->
  <script src="js/jquery.easing.min.js"></script>
  <!-- jQuery Timer Javascript -->
  <script src="js/jquery.simple.timer.js"></script>

</head>


<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">





    <!-- Main Header -->
    <header class="main-header">

      <!-- Logo -->
      <a href="practice_simple.php" class="logo">
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><i class="glyphicon glyphicon-chevron-left"></i> Back to Practice</span>
      </a>

      <!-- Header Navbar -->
      <nav class="navbar navbar-static-top" role="navigation">

      </nav>
    </header>



    <section class="container-fluid-body">

      <div class="timer" style="text-align:center; color:#333; font-size:40px;"data-seconds-left=<?php echo $time; ?>></div>

    </section>

    <section class="container-fluid-submit">
      <h1>Once finished, submit the exam below.

        <div id="errors">
        </div>

        <form method="post" id="submit" role="form">
          <button type="submit" class="btn btn-lg btn-primary btn-block" id="submit_button" style="margin-top:2vh;">Submit</button>
        </form>

      </section>
    </div>
    <!-- /.content-wrapper -->

    <script type="text/javascript">

    var submitted = false;

    var student_number = <?php echo json_encode($_SESSION['student_number']); ?>;

    var num_questions = <?php echo json_encode($_SESSION['num_questions']); ?>;

    var question_id = <?php echo json_encode($_SESSION['questions_id']); ?>;
    var questions = <?php echo json_encode($_SESSION['questions']); ?>;
    var option_a = <?php echo json_encode($_SESSION['option_a']); ?>;
    var option_b = <?php echo json_encode($_SESSION['option_b']); ?>;
    var option_c = <?php echo json_encode($_SESSION['option_c']); ?>;
    var option_d = <?php echo json_encode($_SESSION['option_d']); ?>;
    if (student_number == 498566) {
      var answer = <?php echo json_encode($_SESSION['answer']); ?>;
    }
    var chosen = new Array(num_questions);

    $(document).ready(function(){

      $('.container-fluid-body').append(`<h1 style="text-align:center">Exam - ` + num_questions + ` Questions</h1>
        <h1 id="score" style="text-align:center;"></h1>
        <hr width="100%" height="2px">`);
      var counter = 0;
      question_id.forEach(writeQuestions);

//Display Quesitons
function writeQuestions(item,index) {
  if (student_number == 498566) {
    $('.container-fluid-body').append(`<div class="row">


      <div class="col-md-5 question_box">
      <h1 class="question_number"><abbr title="`+answer[item]+`" style="border-bottom: 0px;">`+(counter+1)+`.</abbr></h1>
      <p class="question">` + questions[item] + `</p>
      </div>


      <div class="col-md-5 options_box">
      <label>
      <input type="radio" value="A" id="radiobutton_` + (item) + `_A" name="radiobutton_` + (item) + `"><p class="option">A: ` + option_a[item] + `</p><br>
      </label>
      <br>
      <label>
      <input type="radio" value="B" id="radiobutton_` + (item) + `_B" name="radiobutton_` + (item) + `"><p class="option">B: ` + option_b[item] + `</p><br>
      </label>
      <br>
      <label>
      <input type="radio" value="C" id="radiobutton_` + (item) + `_C" name="radiobutton_` + (item) + `"><p class="option">C: ` + option_c[item] + `</p><br>
      </label>
      <br>
      <label>
      <input type="radio" value="D" id="radiobutton_` + (item) + `_D" name="radiobutton_` + (item) + `"><p class="option">D: ` + option_d[item] + `</p><br>
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
      <hr width="70%"></hr>`);
counter++;
}
else {
  $('.container-fluid-body').append(`<div class="row">


    <div class="col-md-5 question_box">
    <h1 class="question_number">`+(counter+1)+`.</h1>
    <p class="question">` + questions[item] + `</p>
    </div>


    <div class="col-md-5 options_box">
    <label>
    <input type="radio" value="A" id="radiobutton_` + (item) + `_A" name="radiobutton_` + (item) + `"><p class="option">A: ` + option_a[item] + `</p><br>
    </label>
    <br>
    <label>
    <input type="radio" value="B" id="radiobutton_` + (item) + `_B" name="radiobutton_` + (item) + `"><p class="option">B: ` + option_b[item] + `</p><br>
    </label>
    <br>
    <label>
    <input type="radio" value="C" id="radiobutton_` + (item) + `_C" name="radiobutton_` + (item) + `"><p class="option">C: ` + option_c[item] + `</p><br>
    </label>
    <br>
    <label>
    <input type="radio" value="D" id="radiobutton_` + (item) + `_D" name="radiobutton_` + (item) + `"><p class="option">D: ` + option_d[item] + `</p><br>
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
    <hr width="70%"></hr>`);
counter++;
}
}

});

$(document).ready(function() {
  $('input[type=radio]').change(function() {
    var num = parseInt(this.id.replace(/\D/g, ''), 10);
    chosen[num] = this.value;
    $('#answer_'+num).html(this.value);
    $('#answer_'+num).css('color', '#333');
  });


  window.onbeforeunload = function() {
    if (!submitted) {
      return ("Reloading the page will cause your exam to be submitted as is - take caution");
    }
  }

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

var chosen_unanswered = new Array (num_questions);
$('#submit_button').on('click', function(evt) {
  this.disabled = true;
  var completed = true;
  question_id.forEach(checkAnswered)
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
});

function submit_exam() {
  $('#submit_button').disabled = true;
  submitted = true;
  $.ajax({
    type: "post",
    url: "includes/submit_exam.php",
    data: {data : JSON.stringify(chosen)},
  }).done(function(data){ 
    $("html, body").animate({ scrollTop: 0 }, "slow");
    var data = jQuery.parseJSON(data);
    question_id.forEach(writeAnswers);
    function writeAnswers(item,index) {
      if (data['correct'][item] == 1) {
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
        document.getElementById("correct_answer_" + (item)).innerHTML = data['answers'][item];
        document.getElementById("correct_answer_" + (item)).style.color = "#5cb85c";
      }
    }
    document.getElementById("score").innerHTML = data['num_correct'] + "/" + num_questions + " Answered Correctly - " + data['percent_correct'] + "%";
    if (data['percent_correct'] >= 80) {
      document.getElementById("score").style.color = "#5cb85c";
    }
    else if (data['percent_correct'] >= 65) {
      document.getElementById("score").style.color = "#f0ad4e";
    }
    else {
      document.getElementById("score").style.color = "#d9534f";
    }

  });
}
/*
$('.timer').startTimer({
  onComplete: function() {
    submit_exam();
  }
});
*/

</script>


</html>