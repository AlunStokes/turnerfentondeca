<?php

include ('includes/session.php');

if (isset($_POST['submit'])) {
  $question =  addslashes($_POST['question']);
  $option_a =  addslashes($_POST['option_a']);
  $option_b =  addslashes($_POST['option_b']);
  $option_c =  addslashes($_POST['option_c']);
  $option_d =  addslashes($_POST['option_d']);
  $answer = $_POST['answer'];
  $cluster = $_POST['cluster'];

  if ($question == "" || $option_a == "" || $option_b == "" || $option_c == "" || $option_d == "") {
    echo '<script type="text/javascript">',
     'alert("Input all question details before submitting")',
     '</script>';
  }
  else {

  $query = "INSERT INTO questions (question) VALUES ('$question');";
  $query .= "INSERT INTO questions_options (option_a, option_b, option_c, option_d) VALUES ('$option_a','$option_b','$option_c','$option_d');";
  $query .= "INSERT INTO questions_answers (answer) VALUES ('$answer');";
  $query .= "INSERT INTO questions_cluster (cluster) VALUES ('$cluster');";

mysqli_multi_query($dbconfig, $query) or die(mysqli_error($dbconfig));
  do { 
    mysqli_use_result($dbconfig);
  } while(mysqli_more_results($dbconfig) && mysqli_next_result($dbconfig));
  echo '<script type="text/javascript">',
     'alert("Question submitted successfully")',
     '</script>';
}
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
      <a href="practice.php" class="logo">
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
                    <?php if ($_SESSION['event_boolean']) { echo $_SESSION['first_name'].' '.$_SESSION['last_name'].' - '.$_SESSION['cluster'].' ('.$_SESSION['event'].')'; }
                    else { echo $_SESSION['first_name'].' '.$_SESSION['last_name'].'<br>Undecided'; } ?>
                    <small>Member since June. 2016</small>
                  </p>
                </li>

                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-left">
                    <a href="account.php" class="btn btn-default btn-flat">Account</a>
                  </div>
                  <div class="pull-right">
                    <a href="logout.php" class="btn btn-default btn-flat">Sign out</a>
                  </div>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </nav>
    </header>



    <section class="container-fluid-body" id="top-div">

      <h1>Add a Question</h1>   
      <form method="post" id="add_question" role="form">
        <h3>Question</h3>
        <input name="question" type="type" autocomplete="off" placeholder="Question" style="height:5vh; width:100%; font-size: 16px;" />
        <h3>Option A</h3>
        <input name="option_a" type="type" autocomplete="off" placeholder="Option A" style="height:5vh; width:100%; font-size: 16px;" />
        <h3>Option B</h3>
        <input name="option_b" type="type" autocomplete="off" placeholder="Option B" style="height:5vh; width:100%; font-size: 16px;" />
        <h3>Option C</h3>
        <input name="option_c" type="type" autocomplete="off" placeholder="Option C" style="height:5vh; width:100%; font-size: 16px;" />
        <h3>Option D</h3>
        <input name="option_d" type="type" autocomplete="off" placeholder="Option D" style="height:5vh; width:100%; font-size: 16px;" />
        <h3>Answer</h3>
        <select name="answer" class="dropdown_large">
          <option value="A">A</option>
          <option value="B">B</option>
          <option value="C">C</option>
          <option value="D">D</option>
        </select>
        <h3>Cluster</h3>
        <select name="cluster" class="dropdown_large">
          <option value="marketing">Marketing</option>
          <option value="businessadmin">Business Administration</option>
          <option value="finance">Finance</option>
          <option value="hospitality">Hospitality & Tourism</option>
        </select>
        <input type="submit" class="btn btn-lg btn-primary btn-block" name="submit" style="margin-top:2vh;" value="Submit Question"></input>
      </form>
    </section>

  </body>
  </html>