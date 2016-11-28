<?php 



?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
    <link href="css/reset_password.css" rel="stylesheet">
  <!-- Navbar CSS -->
  <link href="css/navbar.css" rel="stylesheet">
    <!-- Input Normalize CSS -->
    <link rel="stylesheet" type="text/css" href="css/normalize.css" />

    <!--JavaScript Files-->

    <!-- jQuery -->
    <script src="js/jquery-2.2.3.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <!-- Classie.js -->
    <script src="js/classie.js"></script>

</head>

<body>

    <?php include "components/navbar_password_reset.php'; ?>

    <div class="row vertical-offset-70">
        <div class="col-md-8 col-md-offset-2">
            <h2>Password Reset</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2" style="text-align:center; margin-top:3vh;">
            <form method="post" id="reset_student_number">
              <span class="input input--madoka">
                <input class="input__field input__field--madoka" type="text" id="student_number" autocomplete="off" />
                <label class="input__label input__label--madoka" for="student_number">
                  <svg class="graphic graphic--madoka" width="100%" height="100%" viewBox="0 0 404 77" preserveAspectRatio="none">
                    <path d="m0,0l404,0l0,77l-404,0l0,-77z"/>
                </svg>
                <span class="input__label-content input__label-content--madoka">Student Number</span>
            </label>
        </span>
        <input class="btn btn-lg btn-primary" value="&emsp;Reset&emsp;" id="reset" type="button" style="margin-top:1vh;" />
    </form>
</div>
</div>


<script type="text/javascript">

$(document).ready(function() {
    $(document).on('click', '#reset', function() {
        this.disabled = true;
        var student_number = document.getElementById('student_number').value;
        $.ajax({
            type: "get",
            url: "includes/send_reset.php",
            data: {student_number : JSON.stringify(student_number)},
            dataType : "json"
        }).done(function(data){ 
            var result = jQuery.parseJSON(JSON.stringify(data));
            if (result == "failed_find_student_number") {
              alert ('No such student number is registered');
              document.getElementById('reset').disabled = false;
              return;
          }
          else if (result == "success") {
              alert ('Check your email for a reset code');
          }
          else {
              alert ('Email unable to send - try again, or contact turnerfentondeca@gmail.com');
              document.getElementById('reset').disabled = false;
              return;
          }
      });
    });
    $("#reset").keypress(function(e){
   if(e.keyCode === 13){
       e.preventDefault();
   }
});
});



(function() {
        // trim polyfill : https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/Trim
        if (!String.prototype.trim) {
          (function() {
            // Make sure we trim BOM and NBSP
            var rtrim = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g;
            String.prototype.trim = function() {
              return this.replace(rtrim, '');
          };
      })();
  }

  [].slice.call( document.querySelectorAll( 'input.input__field' ) ).forEach( function( inputEl ) {
          // in case the input is already filled..
          if( inputEl.value.trim() !== '' ) {
            classie.add( inputEl.parentNode, 'input--filled' );
        }

          // events:
          inputEl.addEventListener( 'focus', onInputFocus );
          inputEl.addEventListener( 'blur', onInputBlur );
      } );

  function onInputFocus( ev ) {
      classie.add( ev.target.parentNode, 'input--filled' );
  }

  function onInputBlur( ev ) {
      if( ev.target.value.trim() === '' ) {
        classie.remove( ev.target.parentNode, 'input--filled' );
    }
}
})();

</script>

</body>

</html>