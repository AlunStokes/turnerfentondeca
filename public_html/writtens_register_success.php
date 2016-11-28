<?php 

session_start();

include '../includes/config.php';

$ses_sql = mysqli_query($dbconfig, "SELECT can_bring_device FROM applicants WHERE student_number = ".$_SESSION['student_number']." ");

$row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
$_SESSION['can_bring_device'] = $row['can_bring_device'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>TFSS DECA | Success</title>

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
    <link href="css/register_success.css" rel="stylesheet">
    <!-- Navbar CSS -->
    <link href="css/navbar.css" rel="stylesheet">

    <!--JavaScript Files-->

    <!-- jQuery -->
    <script src="js/jquery-2.2.3.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <!-- Plugin JavaScript -->
    <script src="js/jquery.easing.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="js/grayscale.min.js"></script>
    <script src="js/bootstrap-dialog.min.js"></script>
</head>

<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">

    <?php include "components/navbar_register.php"; ?>

    <div>
        <div class="row vertical-offset-100">
            <div class="col-md-8 col-md-offset-2">
                <h1>Your registrtion was Successful!</h1>
                <!-- <p>You will be redirected in a couple seconds <br><a href="login.php">If you are not, click here</a></p> -->
                <h3>Because you've chosen to apply for a written event, you need to do a little more paperwork.</h3>
                <h3><a href="files/admissions/writtens_proposal.docx">Click this link to download the writtens proposal document,</a> and follow the instructions therein.  Your final step will be to email your completed proposal to tfssdeca@gmail.com.</p>
                </div>
            </div>
        </div>

        <script type="text/javascript">

    var can_bring_device = <?php echo json_encode($_SESSION['can_bring_device']); ?>;


    if (can_bring_device == null) {
        BootstrapDialog.show({
            type:BootstrapDialog.TYPE_PRIMARY,
            closable:false,
            title:'Devices',
            message: 'Can you bring a laptop, tablet, or phone to use on 20 September, to write the exam after-school?',
            setType: BootstrapDialog.TYPE_SUCCESS,
            buttons: [{
                label: 'Yes',
                cssClass: 'btn-success',
                id:'devices_btn_yes',
                action: function(dialogItself){
                    $.ajax({
                        type: "get",
                        url: "includes/applicant_home_ajax.php",
                        data: {type : JSON.stringify("can_bring_device"),
                        answer : JSON.stringify("1")},
                    });
                    dialogItself.close();
                }
            }, {
                label: 'No',
                cssClass: 'btn-danger',
                id:'devices_btn_no',
                action: function(dialogItself){
                    $.ajax({
                        type: "get",
                        url: "includes/applicant_home_ajax.php",
                        data: {type : JSON.stringify("can_bring_device"),
                        answer : JSON.stringify("0")},
                    });
                    dialogItself.close();
                }
            }]
        });
}

</script>

    </body>

    </html>