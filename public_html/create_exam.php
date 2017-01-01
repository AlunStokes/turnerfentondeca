<?php

include ('includes/session.php');

if ($_SESSION['member'] == false) {
  header("Location:applicant_home");
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
  <!-- Favicon -->
  <link rel="shortcut icon" href="img/favicon.png`" />
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="css/admin.min.css">
  <link rel="stylesheet" href="css/skin-blue.min.css">
  <!-- Page Style -->
  <link rel="stylesheet" href="css/create_exam.css">



  <!-- jQuery 2.2.3 -->
  <script src="js/jquery-2.2.3.min.js"></script>
  <script src="components/all_pages.js"></script>
  <!-- Bootstrap 3.3.6 -->
  <script src="js/bootstrap.min.js"></script>
  <!-- AdminLTE App -->
  <script src="js/admin.min.js"></script>


</head>


<body class="hold-transition skin-blue sidebar-mini fixed">
  <div class="wrapper">





    <!-- Main Header -->
    <header class="main-header">

      <!-- Logo -->
      <a href="practice" class="logo">
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><i class="glyphicon glyphicon-chevron-left"></i> Back to Practice</span>
      </a>

      <!-- Header Navbar -->
      <nav class="navbar navbar-static-top" role="navigation">
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <!-- User Account Menu -->
            <li class="dropdown user user-menu">
              <!-- Menu Toggle Button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <!-- The user image in the navbar-->
                <img src="img/user_images/thumbnails/<?php echo $_SESSION['image_file']; ?>" class="user-image" alt="User Image" width="160px" height="160px">
                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                <span class="hidden-xs"><?php echo $_SESSION['first_name'].' '.$_SESSION['last_name']; ?></span>
              </a>
              <ul class="dropdown-menu">
                <!-- The user image in the menu -->
                <li class="user-header">
                  <img src="img/user_images/thumbnails/<?php echo $_SESSION['image_file']; ?>" class="img-circle" alt="User Image">

                  <p>
                    <?php echo $_SESSION['first_name'].' '.$_SESSION['last_name'].' <br> '.$_SESSION['cluster'].' ('.$_SESSION['event'].')'; ?>
                    <!-- <small>Member since June. 2016</small> -->
                  </p>
                </li>

                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-left">
                    <a href="account" class="btn btn-default btn-menu">Account</a>
                  </div>
                  <div class="pull-right">
                    <a href="logout" class="btn btn-default btn-menu">Sign out</a>
                  </div>
                </li>
              </ul>
            </li>
            <!-- Control Sidebar Toggle Button -->
            <li>
            </ul>
          </div>
        </nav>
      </header>



      <section class="container-fluid-body" id="top-div">

        <h1 style="text-align:center">Create An Exam</h1>
        <h1>1. Add Questions</h1>
        <p>   Toggle switch to add question to exam</p>
        <div class="container-fluid">
          <div class="row" style="margin-top:1vh;">
            <div class="col-md-6">
              <input tpye="text" id="question_contains" class="question-search" placeholder="Write words in question" style="width:100%; font-size:14px; height:30px;" />
            </div>
            <div class="col-md-6" style="text-align:right; padding:8px;">
              <select id="dropdown">
                <option value="mix">All Clusters</option>
                <option value="marketing">Marketing</option>
                <option value="businessadmin">Business Administration</option>
                <option value="finance">Finance</option>
                <option value="hospitality">Hospitality & Tourism</option>
              </select>
            </div>
          </div>

          <br>
          <br>
          <div class-"row" id="question_area">
          </div>
        </div>


        <h1>2. Review Questions</h1>
        <p>   Make sure all questions are meant to be there</p>
        <div class="container-fluid">
          <div class="row" id="num_questions" style="align:center; text-align:center;">
          </div>
          <div class-"row" id="review_area">
          </div>
        </div>

        <h1>3. What Type of Exam is This?</h1>
        <p>   Choose a cluster, or mixed</p>
        <div class="row" id="num_questions" style="align:center; text-align:center;">
          <select id="dropdown_large">
            <option value="mix">Mixed Clusters</option>
            <option value="marketing">Marketing</option>
            <option value="businessadmin">Business Administration</option>
            <option value="finance">Finance</option>
            <option value="hospitality">Hospitality & Tourism</option>
            <option value="principles">Principles</option>
            <option value="writtens">Writtens</option>
          </select>
        </div>

        <h1>4. Do you want this exam to be unlocked?</h1>
        <div class="row" id="num_questions" style="align:center; text-align:center;">
          <select id="dropdown_unlocked" class="dropdown_large">
            <option value="1">Yes</option>
            <option value="0">No</option>
          </select>
        </div>

        <h1>5. Do you want this exam to show a score when finished?</h1>
        <div class="row" id="num_questions" style="align:center; text-align:center;">
          <select id="dropdown_show_score" class="dropdown_large">
            <option value="1">Yes</option>
            <option value="0">No</option>
          </select>
        </div>

        <h1>6. Name Your Exam</h1>
        <p>   Make sure to make it creative!</p>
        <form method="post" id="create" role="form">
          <input id="exam_name" type="type" maxlength=100 style="height:5vh; width:100%; font-size: 24px;" />
          <button type="submit" class="btn btn-lg btn-primary btn-block" id="submit_button" style="margin-top:2vh;">Create Exam</button>
          <button class="btn btn-lg btn-danger btn-block" id="submit_button" style="margin-top:2vh;">Start Another Exam</button>
        </form>
      </section>

    </body>

    <script type="text/javascript">
    var results;
    var num_questions = new Array();
    num_questions['marketing'] = 0;
    num_questions['finance'] = 0;
    num_questions['businessadmin'] = 0;
    num_questions['hospitality'] = 0;
    var selected = new Array();
    selected['question_id'] = new Array();
    selected['question'] = new Array();
    selected['cluster'] = new Array();
    $(document).ready(function() {
      ajax_call();

      $('#submit_button').click(function(e) {
        this.disabled = true;
        e.preventDefault();
        var name = document.getElementById('exam_name').value;
        if (typeof name == 'undefined' || name =="") {
          alert ("Your exam must have a name");
          this.disabled = false;
        }
        else if (selected['question_id'].length == 0) {
          alert("Your exam must contain at least one question");
          this.disabled = false;
        }
        else {
          console.log(name);
          console.log(selected['question_id'].toString());
          var type = document.getElementById('dropdown_large').value;
          var unlocked = document.getElementById('dropdown_unlocked').value;
          var show_score = document.getElementById('dropdown_show_score').value;
          console.log (type);
          $.ajax({
            type: "get",
            url: "includes/ajax",
            data: {ajax_id : JSON.stringify("create_exam_save"),
            name : JSON.stringify(name),
            unlocked : JSON.stringify(unlocked),
            show_score : JSON.stringify(show_score),
            question_id : JSON.stringify(selected['question_id']),
            length : JSON.stringify(selected['question_id'].length),
            type : JSON.stringify(type)}
          }).done(function(data){
            var data = jQuery.parseJSON(data);
            if (data == "failed") {
              alert ("Exam name already exists - Pick another");
              this.disabled = false;
            }
            else if (data == "success") {
              alert("Exam has been saved!");
            }
            else {
              this.disabled = false;
            }
          });
        }
      });

$('#reset').click(function() {
  location.reload();
})
});

$('#question_contains').keyup(function() {
  ajax_call();
});
$('#dropdown').on('change', function() {
  ajax_call();
});
$(document).on("click", ".btn-add-question", function() {
  var current_id = this.id.match(/\d+/)[0];
  console.log(current_id);
  var current_index = $.inArray(current_id, results['question_id']);
  console.log($.inArray(current_id, results['question_id']));
  selected['question_id'].push(current_id);
  selected['question'].push(results['questions'][current_index]);
  selected['cluster'].push(results['cluster'][current_index]);
  //console.log (selected['question_id'][current_index] + " - " + selected['question'][current_index]);
  if (results['cluster'][current_index] == 'marketing') {
    num_questions['marketing']++;
  }
  else if (results['cluster'][current_index] == 'businessadmin') {
    num_questions['businessadmin']++;
  }
  else if (results['cluster'][current_index] == 'finance') {
    num_questions['finance']++;
  }
  else if (results['cluster'][current_index] == 'hospitality') {
    num_questions['hospitality']++;
  }
  else {
    throw new Error("Question " + current_id + " didn't affect cluster count - Given cluster: " + results['cluster'][current_index] + " - " + results['question'][current_index]);
  }
  update();
});
$(document).on("click", ".btn-remove-question", function() {
  var current_id = this.id.match(/\d+/)[0];
  console.log(current_id);
  var index = $.inArray(current_id, selected['question_id']);
  console.log(index);
  if (selected['cluster'][index] == 'marketing') {
    num_questions['marketing']--;
  }
  else if (selected['cluster'][index] == 'businessadmin') {
    num_questions['businessadmin']--;
  }
  else if (selected['cluster'][index] == 'finance') {
    num_questions['finance']--;
  }
  else if (selected['cluster'][index] == 'hospitality') {
    num_questions['hospitality']--;
  }
  else {
    throw new Error("Question " + current_id + " didn't affect cluster count - Given cluster: " + selected['cluster'][index]);
  }
  selected['question_id'].splice(index, 1);
  selected['question'].splice(index, 1);
  selected['cluster'].splice(index, 1);
  update();
});

function ajax_call() {
  $('#question_area').html('');
  var search_text = document.getElementById('question_contains').value;
  var question_type = document.getElementById('dropdown').value;
  selected['']
  $.ajax({
    type: "get",
    url: "includes/ajax",
    data: {ajax_id : JSON.stringify("create_exam_search_question"),
    search : JSON.stringify(search_text),
    question_type : JSON.stringify(question_type)},
    dataType : "json"
  }).done(function(data){
    results = jQuery.parseJSON(JSON.stringify(data));
    update();
  });
}

function update() {
      //Add Questions Section
      if (typeof results['questions'][0] == 'undefined') {
        $('#question_area').html(`
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
        $('#question_area').html("");
        for (var i = 0; i < results['count']; i++) {
          if (jQuery.inArray(results['question_id'][i], selected['question_id']) == -1) {
            $('#question_area').append(`
              <div class="question_row" id="question_select_` + results['question_id'][i] + `">
              <div class="row">
              <div class="col-md-10" style="padding-left:3vw;">
              <h3>` + results['questions'][i] + `</h3>
              <p>A: ` + results['option_a'][i] + `</p>
              <p>B: ` + results['option_b'][i] + `</p>
              <p>C: ` + results['option_c'][i] + `</p>
              <p>D: ` + results['option_d'][i] + `</p>
              <i><p>Answer: ` + results['answers'][i] + `</p></i>
              <br>
              </div>
              <div class="col-md-2" style="padding-top:7vh; padding-left:2vw;">
              <button class="btn btn-add-question" id="button_add_` + results['question_id'][i] + `"> Add to Exam </button>
              </div>
              <hr width="60%"></hr>
              <br>
              </div>
              </div>
              `)
          }
        }
        for (var i = 0; i < results['count']; i++) {

        }
      }

      //Rearrange Questions Section
      $('#review_area').html("");
      $('#num_questions').html("");
      if (selected['question_id'].length == 1) {
        $('#num_questions').append('<h1> ' + selected['question_id'].length + ' Question</h1>');
      }
      else if (selected['question_id'].length > 1) {
        $('#num_questions').append('<h1> ' + selected['question_id'].length + ' Questions</h1>');
      }
      if (selected['question_id'].length > 0) {
        $('#num_questions').append('<h2 style="display:inline" >Marketing: ' + num_questions['marketing'] + ' &#124; &emsp;</h2>');
        $('#num_questions').append('<h2 style="display:inline" >Business Admin: ' + num_questions['businessadmin'] + ' &#124; &emsp;</h2>');
        $('#num_questions').append('<h2 style="display:inline" >Finance: ' + num_questions['finance'] + ' &#124; &emsp;</h2>');
        $('#num_questions').append('<h2 style="display:inline" >Hospitality & Tourism: ' + num_questions['hospitality'] + '</h2>');
      }
      for (var i = 0; i < selected['question_id'].length; i++) {
       $('#review_area').append(`
        <div class="question_row" id="question_review_` + i + `">
        <div class="row">
        <div class="col-md-1" style="padding-left:1vw;">
        <h1> ` + (i+1) + `. </h1>
        </div>
        <div class="col-md-9">
        <h3 style="font-size:18px;">` + selected['question'][i] + `</h3>
        <br>
        </div>
        <div class="col-md-2" style="padding-top:3vh; padding-left:2vw;">
        <button class="btn btn-remove-question" id="button_remove_` + selected['question_id'][i] + `"> Remove from Exam </button>
        </div>
        </div>
        </div>
        `)
     }
   }

   </script>



   </html>
