<?php 

session_start();

include ('../includes/config.php');
include ('includes/functions.php');

if(!isset($_GET['reset_code'])) {
    header("Location: login");
}
else {
$reset_code = $_GET['reset_code'];
}

$reset_code_query = "SELECT password_reset_code FROM members WHERE password_reset_code = '$reset_code'";
$result = mysqli_query($dbconfig, $reset_code_query);
if (mysqli_num_rows($result) == 1) {
}

if (isset($_POST['set_password'])) {

    $password = sanitize ($_POST['password']);

    $password_confirm = sanitize ($_POST['password_confirm']);

    $password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 11]);

    $query = "UPDATE members SET password='$password', password_reset_code = NULL WHERE password_reset_code = '$reset_code'";
    $res = mysqli_query($dbconfig, $query) or die("Connection failed: " . mysqli_error($dbconfig));

    if ($res) {
        header("Location: login");
    }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Favicon -->
  <link rel="shortcut icon" href="img/favicon.png`" />
    <meta name="description" content="">
    <meta name="author" content="">

    <title>TFSS DECA | Reset Password</title>

    

    <!-- Custom Fonts -->
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">

    <!--CSS FILES-->

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Theme CSS -->
    <link href="css/grayscale.css" rel="stylesheet">
    <!-- Page CSS -->
    <link href="css/register.css" rel="stylesheet">
  <!-- Navbar CSS -->
  <link href="css/navbar.css" rel="stylesheet">
    <!--Form Validation CSS -->
    <link href="css/formValidation.css" rel="stylesheet">

    <!--JavaScript Files-->

    <!-- jQuery -->
    <script src="js/jquery-2.2.3.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <!-- Plugin JavaScript -->
    <script src="js/jquery.easing.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="js/grayscale.min.js"></script>
    <!--Form Validation JavaScript -->
    <script src="js/formValidation.min.js"></script>
    <script src="js/framework/bootstrap.min.js"></script>
</head>

<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">

    <?php include "components/navbar_register.php"; ?>


    <div id="top-div">
        <div class="row vertical-offset-100">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-heading">                                
                        <div class="row-fluid user-row">
                            <img id="profile-img" class="profile-img-card" src="img/exec_images/anon.png" />
                        </div>
                    </div>
                    <div class="panel-body">
                        <form role="form" class="form-signin" method="post" id="set_password" autocomplete="off">
                            <div class="form-group">
                                <input class="form-control" placeholder="Password" name="password" type="password">
                            </div>

                            <div class="form-group">
                                <input class="form-control" placeholder="Password (Confirm)" name="password_confirm" type="password">
                            </div>
                            <div class="form-group">
                                <input class="btn btn-lg btn-primary btn-block" type="submit" value="Set Password" name="set_password">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<script type="text/javascript">
$(document).ready(function() {
    $('#set_password', '#top-div').formValidation({
        framework: 'bootstrap',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        live: 'enabled',
        fields: {
            password: {
                err: 'tooltip',
                validators: {
                    notEmpty: {
                        message: 'Password is required'
                    },
                    stringLength: {
                        min: 5,
                        max: 36,
                        message: 'Student number must be five characters long'
                    }
                }
            },
            password_confirm: {
                err: 'tooltip',
                validators: {
                    notEmpty: {
                        message: 'Password is required'
                    },
                    identical: {
                        field: 'password',
                        message: 'Passwords must match'
                    }
                }
            }
        }
    });
});

$('#set_password', '#top-div').on("keyup keypress", function(e) {
    var code = e.keyCode || e.which; 
    if (code === 13) {               
        e.preventDefault();
        return false;
    }
});

</script>


</body>

</html>
