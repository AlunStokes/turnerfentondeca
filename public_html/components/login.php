<?php

if (isset($_SESSION['student_number'])) {
    session_destroy();
}

session_start();

include ('../includes/config.php');
include ('includes/functions.php');

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    $student_number = sanitize ($_POST['student_number']);
    $password = sanitize($_POST['password']);
    $password_correct = false;
    $_SESSION['member'] = false; 

    $salt_query = "SELECT password FROM members WHERE student_number = $student_number";
    $result = mysqli_query($dbconfig, $salt_query);
    if (mysqli_num_rows($result) == 1) {
        $_SESSION['member'] = true;
    }
    if (mysqli_num_rows($result) == 0) {
        $salt_query = "SELECT password FROM applicants WHERE student_number = $student_number";
        $result = mysqli_query($dbconfig, $salt_query);
        if (mysqli_num_rows($result) == 0) {
            $errmsg="Student Number or Password is invalid";
            //exit();
        }
    }

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $password_salt = $row['password'];
        if (password_verify($password, $password_salt)) {
            $password_correct = true;
        }
        else {
            $errmsg="Student Number or Password is invalid";
        }

        if ($password_correct) {
            $_SESSION['student_number']=$student_number;
            if ($_SESSION['member'] == true) {
                $add_login = "INSERT INTO logins (student_number) VALUES (".$_SESSION['student_number'].");";
                mysqli_query($dbconfig, $add_login);
                $URL="home.php";
            }
            else {
                $URL="applicant_home.php";
            }
            //include 'includes/send_data.php';
            header("Location:".$URL);
        }
        else {
            $errmsg="Student Number or Password is invalid";
        //exit();
        }
    }
}

?>

<!-- IMPORTS -->

<!-- CSS -->

<!--Form Validation CSS -->
<link href="css/formValidation.css" rel = "stylesheet">
<!-- Page CSS -->
<link href="css/login.css" rel="stylesheet">

<!-- JS -->

<!-- jQuery -->
<script src="js/jquery-2.2.3.min.js"></script>
  <script src="components/all_pages.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>
<!--Form Validation JavaScript -->
<script src="js/formValidation.min.js"></script>
<script src="js/framework/bootstrap.min.js"></script>



<div class="panel panel-default">
    <div class="panel-heading">                                
        <div class="row-fluid user-row">
            <img id="profile-img" class="profile-img-card" src="img/exec_images/anon.png"src="img/exec_images/anon.png" />
        </div>
    </div>
    <div class="panel-body">
        <form role="form" class="form-signin" method="POST" action="" id="login" autocomplete="off">

            <label class="panel-login">
                <div class="login_result"></div>
            </label>

            <div class="form-group">
                <input class="form-control" placeholder="Student Number" name="student_number" type="text">
            </div>

            <div class="form-group">
                <input class="form-control" placeholder="Password" name="password" type="password">
            </div>

            <div class="form-group">
                <input class="btn btn-lg btn-primary btn-block" type="submit" value="Login" name="login">
            </div>

        </form>
    </div>
    <p>
        <a href="reset_password.php">Forgot your password?</a>
    </p>
    <p style="color: #FF6656; font-size: 22px;"><?php if (isset($errmsg)) { echo $errmsg; } ?> </p>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#login', '#top-div').formValidation({
        framework: 'bootstrap',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        live: 'submitted',
        fields: {
            student_number: {
                err: 'tooltip',
                verbose: false,
                validators: {
                    notEmpty: {
                        message: 'Student number is required'
                    },
                    stringLength: {
                        min: 6,
                        max: 6,
                        message: 'Student number must be six numbers long'
                    },
                    regexp: {
                        regexp: /^[0-9]*$/,
                        message: 'Student number must only contain nunmbers'
                    }
                }
            },
            password: {
                err: 'tooltip',
                validators: {
                    notEmpty: {
                        message: 'Password is required'
                    }
                }
            }
        }
    });
});


</script>