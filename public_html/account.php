<?php

include ('../includes/config.php');
include ('includes/session.php');
include ('includes/functions.php');

$active_page = 'account';

$ses_sql = mysqli_query($dbconfig, "SELECT email FROM members WHERE student_number = ".$_SESSION['student_number']." ");

$row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);

$_SESSION['email'] = $row['email'];

if (isset($_POST['email'])) {
  mysqli_query($dbconfig, "UPDATE members SET email = '".$_POST['email']."' WHERE student_number = '".$_SESSION['student_number']."'");
}

if (isset($_POST['password'])) {
  $password = sanitize ($_POST['password']);
  $password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 11]);

  mysqli_query($dbconfig, "UPDATE members SET password = '$password' WHERE student_number = '".$_SESSION['student_number']."'");
}
if ($_SESSION['member'] == false) {
  header("Location:applicant_home");
}



?>

<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>TFSS DECA | Account</title>
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
  <!--Form Validation CSS -->
  <link href="css/formValidation.css" rel = "stylesheet">
  <!-- Page Style -->
  <link rel="stylesheet" href="css/home.css">



  <!-- jQuery 2.2.3 -->
  <script src="js/jquery-2.2.3.min.js"></script>
  <script src="components/all_pages.js"></script>
  <!-- Bootstrap 3.3.6 -->
  <script src="js/bootstrap.min.js"></script>
  <!-- AdminLTE App -->
  <script src="js/core/dashboard.js"></script>
  <!--Form Validation JavaScript -->
  <script src="js/formValidation.min.js"></script>
  <script src="js/framework/bootstrap.min.js"></script>

</head>


<body class="hold-transition skin-blue sidebar-mini" style="background-color: #222d32;">
  <!-- Header and Left Menu -->
  <?php if ($_SESSION['admin_boolean']) { include 'components/admin_menu.php'; }
  else { include 'components/member_menu.php'; } ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Account
        <small>Edit</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">



      <div class="row">
        <div class="col-md-2">
          <label>Email: </label>
        </div>
        <form method="post" id="email">
          <div class="col-md-4">
            <div class="form-group">
              <input class="form-control" placeholder="Email" name="email" type="text" value=<?php echo $_SESSION['email']; ?>>
            </div>
          </div>
          <div class="col-md-1">
            <div class="form-group">
              <input class="btn btn-success" type="submit" value="Update" name="update_student_number">
            </div>
          </div>
        </form>
      </div>

      <br>

      <div class="row">
        <div class="col-md-2">
          <label>Password: </label>
        </div>
        <form method="post" id="password">
          <div class="col-md-4">
            <div class="form-group">
              <input class="form-control" placeholder="Password" name="password" type="password">
            </div>
            <div class="form-group">
              <input class="form-control" placeholder="Password Confirm" name="password_confirm" type="password">
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <input class="btn btn-success" type="submit" value="Update" name="update_password">
            </div>
          </div>
        </form>
      </div>

      <br>

      <div class="row">
        <div class="col-md-2">
          <label>Profile Picture: </label>
        </div>
        <form action="includes/upload_photo" method="post" enctype="multipart/form-data" id="upload_form">
        <div class="col-md-4">
          <div class="form-wrap">
              <input name="__files[]" type="file" />
              
            <div id="output"><!-- error or success results --></div>
          </div>
        </div>
        <div class="col-md-1">
          <input name="__submit__" type="submit" value="Upload" class="btn btn-success" />
        </div>  
        <div class="col-md-3 col-md-offset-1" id="image_success">
          
        </div>  
        </form>
      </div>

      <script type="text/javascript">

      //configuration
var max_file_size           = 2048576; //allowed file size. (1 MB = 1048576)
var allowed_file_types      = ['image/png', 'image/gif', 'image/jpeg', 'image/pjpeg']; //allowed file types
var result_output           = '#output'; //ID of an element for response output
var my_form_id              = '#upload_form'; //ID of an element for response output
var total_files_allowed     = 3; //Number files allowed to upload

//on form submit
$(my_form_id).on( "submit", function(event) { 
  event.preventDefault();
    var proceed = true; //set proceed flag
    var error = []; //errors
    var total_files_size = 0;
    
    if(!window.File && window.FileReader && window.FileList && window.Blob){ //if browser doesn't supports File API
        error.push("Your browser does not support new File API! Please upgrade."); //push error text
    }else{
        var total_selected_files = this.elements['__files[]'].files.length; //number of files
        
        //limit number of files allowed
        if(total_selected_files > total_files_allowed){
            error.push( "You have selected "+total_selected_files+" file(s), " + total_files_allowed +" is maximum!"); //push error text
            proceed = false; //set proceed flag to false
          }
         //iterate files in file input field
         $(this.elements['__files[]'].files).each(function(i, ifile){
            if(ifile.value !== ""){ //continue only if file(s) are selected
                if(allowed_file_types.indexOf(ifile.type) === -1){ //check unsupported file
                    error.push( "<b>"+ ifile.name + "</b> is unsupported file type!"); //push error text
                    proceed = false; //set proceed flag to false
                  }

                total_files_size = total_files_size + ifile.size; //add file size to total size
              }
            });

        //if total file size is greater than max file size
        if(total_files_size > max_file_size){ 
            error.push( "You have "+total_selected_files+" file(s) with total size "+total_files_size+", Allowed size is " + max_file_size +", Try smaller file!"); //push error text
            proceed = false; //set proceed flag to false
          }

        var submit_btn  = $(this).find("input[type=submit]"); //form submit button  
        
        //if everything looks good, proceed with jQuery Ajax
        if(proceed){
            submit_btn.val("Please Wait...").prop( "disabled", true); //disable submit button
            var form_data = new FormData(this); //Creates new FormData object
            var post_url = $(this).attr("action"); //get action URL of form
            
            //jQuery Ajax to Post form data
            $.ajax({
              url : post_url,
              type: "POST",
              data : form_data,
              contentType: false,
              cache: false,
              processData:false,
              mimeType:"multipart/form-data"
            }).done(function(res){ //
                $(my_form_id)[0].reset(); //reset form
                $(result_output).html(res); //output response from server
                submit_btn.val("Upload").prop( "disabled", false); //enable submit button once ajax is done
                $('#image_success').append('<p>Image Saved Successfully.</p>')
                setTimeout(function() { location.reload(); }, 300);
              });
          }
        }

    $(result_output).html(""); //reset output 
    $(error).each(function(i){ //output any error to output element
      $(result_output).append('<div class="error">'+error[i]+"</div>");
    });

  });

</script>


</div>


</section>
<!-- /.content -->

<script type="text/javascript">


$(document).ready(function() {
  $('#email', '#top-div').formValidation({
    framework: 'bootstrap',
    icon: {
      valid: 'glyphicon glyphicon-ok',
      invalid: 'glyphicon glyphicon-remove',
      validating: 'glyphicon glyphicon-refresh'
    },
    live: 'enabled',
    fields: {
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
      }
    }
  });
});

$(document).ready(function() {
  $('#password', '#top-div').formValidation({
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
            message: 'Must be at least 5 characters'
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


$('#email', '#top-div').on("keyup keypress", function(e) {
  var code = e.keyCode || e.which; 
  if (code === 13) {               
    e.preventDefault();
    return false;
  }
});

$('#password', '#top-div').on("keyup keypress", function(e) {
  var code = e.keyCode || e.which; 
  if (code === 13) {               
    e.preventDefault();
    return false;
  }
});

</script>



</body>
</html>
