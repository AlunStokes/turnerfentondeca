<?php 

session_start();

if (isset($_SESSION['student_number'])) {
    session_destroy();
}

include ('../includes/config.php');
include ('includes/functions.php');

$error = false;

unset($_SESSION);

if (isset($_POST['register'])) {

   /* 
   $err = array (
        0"required" => "All fields must be filled",
        1"email_format" => "Email is improperly formatted",
        2"email_taken" => "Email is already taken",
        3"student_number_length" => "Student number must be 6 characters long",
        4"student_number_number" => "Student number must contain only digits",
        5"student_number_taken" => "Student number is already taken",
        6"password_match" => ""
        );
        */
if (isset($err)) {
    unset($err);
}

$error = false;

$err = new SplFixedArray(8);

$first_name = sanitize ($_POST['first_name']);
$first_name = strtolower($first_name);
$first_name = ucfirst($first_name);
if (!isset($first_name) || $first_name == "") {
    $err[0] = "All fields must be filled";
    $error = true;
}

$last_name = sanitize ($_POST['last_name']);
$last_name = strtolower($last_name);
$last_name = ucfirst($last_name);
if (!isset($last_name) || $last_name == "") {
    $err[0] = "All fields must be filled";
    $error = true;
}

$email = sanitize ($_POST['email']);
if (!isset($email) || $email == "") {
    $err[0] = "All fields must be filled";
    $error = true;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL) && $email != "") {
    $err[1] = "Email is improperly formatted";
    $error = true;
}

$student_number_string = sanitize ($_POST['student_number']);
if (isset($student_number_string)) {
    $student_number = (int)$student_number_string;
}
if (!isset($student_number) || $student_number == "") {
    $err[0] = "All fields must be filled";
    $error = true;
}
if (strlen($student_number_string) != 6) {
    $err[3] = "Student number must be 6 characters long";
    $error = true;
}
if (preg_match('/^[a-zA-Z]+$/', $student_number_string)) {
    $err[4] = "Student number must contain only digits";
    $error = true;
}

$password = sanitize ($_POST['password']);

$password_confirm = sanitize ($_POST['password_confirm']);

$grade = $_POST['grade'];

$alumnus = 0;
if (isset($_POST['alumnus'])) {
    $alumnus = $_POST['alumnus'];
}


//Store values in case of error
$_SESSION['first_name'] = $first_name;
$_SESSION['last_name'] = $last_name;
$_SESSION['email'] = $email;
if ($student_number_string != "") {
    $_SESSION['student_number'] = $student_number;
}



if ($password != $password_confirm) {
    $err[6] = "Passwords must match";
    $error = true;
}


$password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 11]);

$check_email = mysqli_query($dbconfig, "SELECT email FROM members WHERE email='$email'");
$count_email = mysqli_num_rows($check_email);
if ($count_email > 0 && !isset($err[1])) {
    $err[2] = "Email is already taken";
    $error = true;
}

$check_student_number = mysqli_query($dbconfig, "SELECT student_number FROM members WHERE student_number='$student_number'");
$count_student_number = mysqli_num_rows($check_student_number);
if ($count_student_number > 0 && $student_number_string != "") {
    $err[5] = "Student number is already taken";
    $error = true;
}


if (!$error) {
    $query = "INSERT INTO members (email, password, first_name, last_name, student_number, admin, grade, alumnus) VALUES ('$email', '$password','$first_name', '$last_name', '$student_number', '1', '$grade', '$alumnus')";
    $res = mysqli_query($dbconfig, $query) or die("Connection failed: " . mysqli_error($dbconfig));

    if ($res) {
        $add_statistics_entry = "INSERT INTO user_statistics (student_number) values ('$student_number')";
        mysqli_query($dbconfig, $add_statistics_entry);
        $URL="register_success.php";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
        echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
    }
    else {
        $err[7] = "There was a problem connecting to the server, please try again later";
    }
}
}


?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>TFSS DECA | Register</title>

    

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
    <!--Form Validation JavaScript -->
    <script src="js/formValidation.min.js"></script>
    <script src="js/framework/bootstrap.min.js"></script>
</head>

<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">

    <?php if (!isset($_SESSION['first_name'])) { include 'includes/admin_lock.php'; } ?>
    <?php include 'includes/navbar_register.php'; ?>

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
                        <form role="form" class="form-signin" method="post" id="register" autocomplete="off">
                            <div class="input-error">
                                <p style="text-align:center;"><?php if (isset($err[7])) { echo $err[7]; } ?></p>
                            </div>
                            <label class="panel-login">
                                <div class="login_result"></div>
                            </label>
                            <div class="input-error">
                                <p style="text-align:center;"><?php if (isset($err[0])) { echo $err[0]; } ?></p>
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="First Name" name="first_name" type="text" Value=<?php if (isset($_SESSION['first_name'])) { echo $_SESSION['first_name']; } ?>>
                            </div>

                            <div class="form-group">
                                <input class="form-control" placeholder="Last Name" name="last_name" type="text" Value=<?php if (isset($_SESSION['last_name'])) { echo $_SESSION['last_name']; } ?>>
                            </div>

                            <div class="form-group">
                                <input class="form-control" placeholder="Email" name="email" type="text" Value=<?php if (isset($_SESSION['email'])) { echo $_SESSION['email']; } ?>>
                                <p style="text-align:center;"><?php if (isset($err[1])) { echo $err[1]; } ?></p>
                                <p style="text-align:center;"><?php if (isset($err[2])) { echo $err[2]; } ?></p>

                            </div>

                            <div class="form-group">
                                <input class="form-control" placeholder="Student Number" name="student_number" type="text" Value=<?php if (isset($_SESSION['student_number'])) { echo $_SESSION['student_number']; } ?>>
                                <p style="text-align:center;"><?php if (isset($err[3])) { echo $err[3]; } ?></p>
                                <p style="text-align:center;"><?php if (isset($err[4])) { echo $err[4]; } ?></p>
                                <p style="text-align:center;"><?php if (isset($err[5])) { echo $err[5]; } ?></p>
                            </div>

                            <div class="form-group">
                                <select class="form-control" name="grade">
                                    <option value="9">Grade Nine</option>
                                    <option value="10">Grade Ten</option>
                                    <option value="11">Grade Eleven</option>
                                    <option value="12">Grade Twelve</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <input class="form-control" placeholder="Password" name="password" type="password">
                            </div>

                            <div class="form-group">
                                <input class="form-control" placeholder="Password (Confirm)" name="password_confirm" type="password">
                                <p style="text-align:center;"><?php if (isset($err[6])) { echo $err[6]; } ?></p>
                            </div>

                            <div class="form-group">
                                <label class="text" style="text-align:left; margin-left: 0vw;">Are you a DECA Alumnus?</label>
                                <input type="checkbox" name="alumnus" value="1" style="float:right; clear:both; margin-right:1vw;">

                            </div>

                            <div class="form-group">
                                <input class="btn btn-lg btn-primary btn-block" type="submit" value="Register" name="register">
                            </div>
                        </form>
                    </div>
                    <p>
                        <a href="login.php">Already Have an account?</a> 
                        <br>
                        <a href="reset_password.php">Forgot your password?</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>




<script type="text/javascript">
$(document).ready(function() {
    $('#register', '#top-div').formValidation({
        framework: 'bootstrap',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        live: 'enabled',
        fields: {
            first_name: {
                err: 'tooltip',
                validators: {
                    notEmpty: {
                        message: 'First name is required'
                    }
                }
            },
            last_name: {
                err: 'tooltip',
                validators: {
                    notEmpty: {
                        message: 'Last name is required'
                    }
                }
            },
            email: {
                err: 'tooltip',
                verbose: false,
                validators: {
                    notEmpty: {
                        message: 'Email address is required'
                    },
                    emailAddress: {
                        message: 'Input is not a valid email address'
                    }
                }
            },
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
                    },
                    stringLength: {
                        min: 5,
                        max: 36,
                        message: 'Student number must be six characters long'
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

$('#register', '#top-div').on("keyup keypress", function(e) {
    var code = e.keyCode || e.which; 
    if (code === 13) {               
        e.preventDefault();
        return false;
    }
});

</script>


</body>

</html>
