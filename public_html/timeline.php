<?php

include ('../includes/config.php');
include ('includes/session.php');

$active_page = 'classes';
$page = 'timeline';

$messages = array();
$messages ['id'] = array();
$messages ['poster'] = array();
$messages ['poster_picture_path'] = array();
$messages ['poster_first_name'] = array();
$messages ['poster_last_name'] = array();
$messages ['message'] = array();
$messages ['class'] = array();
$messages ['date'] = array();
$messages ['time'] = array();


$post_query = "SELECT class_posts.id as id, message, poster, UNIX_TIMESTAMP(class_posts.date) AS date_order, DATE_FORMAT(DATE, '%M %D %Y') AS date, DATE_FORMAT(DATE, '%H:%i') AS time, first_name, last_name, class_posts.class FROM class_posts JOIN members ON members.student_number = class_posts.poster WHERE class_posts.class = '".$_SESSION['class']."' OR class_posts.class = 'all' ORDER BY date_order DESC LIMIT 20;";
if ($_SESSION['admin_boolean']) {
  $post_query = "SELECT class_posts.id as id, message, poster, UNIX_TIMESTAMP(class_posts.date) AS date_order, DATE_FORMAT(DATE, '%M %D %Y') AS date, DATE_FORMAT(DATE, '%H:%i') AS time, first_name, last_name, class_posts.class FROM class_posts JOIN members ON members.student_number = class_posts.poster ORDER BY date_order DESC LIMIT 50;";
}
$results = mysqli_query($dbconfig, $post_query);
if ($results != false) {
  while ($row = mysqli_fetch_assoc($results)) {
    array_push($messages ['id'], $row['id']);
    array_push($messages ['poster'], $row['poster']);
    array_push($messages ['poster_picture_path'], $row['poster'].".jpg");
    array_push($messages ['poster_first_name'], $row['first_name']);
    array_push($messages ['poster_last_name'], $row['last_name']);
    array_push($messages['message'], $row['message']);
    array_push($messages['date'], $row['date']);
    array_push($messages['time'], $row['time']);
    array_push($messages['class'], $row['class']);
  }

  for ($i = 0; $i < count($messages['class']); $i++) {

    switch ($messages['class'][$i]) {
      case "marketing":
      $messages['class'][$i] = "Marketing";
      break;
      case "finance":
      $messages['class'][$i] = "Finance";
      break;
      case "businessadmin":
      $messages['class'][$i] = "Business Administration";
      break;
      case "hospitality":
      $messages['class'][$i] = "Hospitality & Tourism";
      break;
      case "marketing_principles":
      $messages['class'][$i] = "Principles of Marketing";
      break;
      case "finance_principles":
      $messages['class'][$i] = "Principles of Finance";
      break;
      case "businessadmin_principles":
      $messages['class'][$i] = "Principles of Business Admin";
      break;
      case "hospitality_principles":
      $messages['class'][$i] = "Principles of Hospitality";
      break;  
      case "writtens":
      $messages['class'][$i] = "Writtens";
      break;    
      case "all":
      $messages['class'][$i] = "All Classes";
      break;        
    }

    if (!file_exists("img/user_images/thumbnails/".$messages['poster_picture_path'][$i])) {
      $messages['poster_picture_path'][$i] = "unresolved.jpg";
    }
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>TFSS DECA | Timeline</title>
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
  <link rel="stylesheet" href="css/timeline.css">
  <!-- Include Quill stylesheet -->
  <link href="https://cdn.quilljs.com/1.0.0/quill.snow.css" rel="stylesheet">
  <link href="https://cdn.quilljs.com/1.0.0/quill.bubble.css" rel="stylesheet">
  <!-- Favicon -->
  <link rel="shortcut icon" href="img/favicon.png`" />


  <!-- jQuery 2.2.3 -->
  <script src="js/jquery-2.2.3.min.js"></script>
  <!-- Bootstrap 3.3.6 -->
  <script src="js/bootstrap.min.js"></script>
  <!-- dashboard App -->
  <script src="js/admin.min.js"></script>
  <script src="js/bootstrap-dialog.min.js"></script>
  <!-- Include the Quill library -->
  <script src="js/quill.js"></script>

</head>


<body class="hold-transition skin-blue sidebar-mini">
 <div class="wrapper">

  <!-- Header and Left Menu -->
  <?php if ($_SESSION['admin_boolean']) { include 'includes/admin_menu.php'; }
  else { include 'includes/member_menu.php'; } ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
      <!--<section class="content-header">
        <h1>
          Home
          <small>General Info</small>
        </h1>
      </section>-->

      <!-- Main content -->
      <section class="content">

        <?php include "includes/alerts.php"; ?>

        <ul class="timeline">

          <?php

          for ($i = 0; $i < count($messages['message']); $i++) {

            if ($i > 0 && $messages['date'][$i] != $messages['date'][$i-1]) {
              echo '
              <!-- timeline time label -->
              <li class="time-label">
              <span class="bg-red">
              '.$messages['date'][$i].'
              </span>
              </li>
              <!-- /.timeline-label -->
              ';
            }
            else if ($i == 0) {
              echo '
              <!-- timeline time label -->
              <li class="time-label">
              <span class="bg-red">
              '.$messages['date'][$i].'
              </span>
              </li>
              <!-- /.timeline-label -->
              ';
            }
            echo '
            <!-- timeline item -->
            <li>
            <!-- timeline icon -->
            <i class="fa fa-circle-o-notch bg-blue"></i>
            <div class="timeline-item">
            ';
            if ($_SESSION['admin_boolean']) {
              echo '
              <span class="delete" id="delete_'.$messages["id"][$i].'"><i class="fa fa-times"></i></span>
              ';
            }
            echo '
            <span class="time"><i class="fa fa-clock-o"></i>'.$messages['time'][$i].'</span>
            <div class="pull-left image">
            <img src="img/user_images/thumbnails/'.$messages['poster_picture_path'][$i].'" class="img-circle img-circle-message" alt="User Image">
            </div>
            <h3 class="timeline-header">'.$messages['poster_first_name'][$i].' '.$messages['poster_last_name'][$i].'</h3>

            <div class="timeline-body">
            <p id="post_body_'.$messages["id"][$i].'" style="font-size:15px;">'.$messages['message'][$i].'</p>
            </div>

            <div class="timeline-footer">
            <p style="display: inline; color: #0073b7; margin-bottom: 0;"><b>'.$messages['class'][$i].'</b></p>
            </div>
            </div>
            </li>
            ';
          }

          ?>
          
        </ul>

      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
  </div>
  <?php
  if ($_SESSION['admin_boolean']) {
    echo '
    <div class="fixed-action-btn" style="bottom: 45px; right: 24px;">
    <a class="btn-floating btn-large red" id="new-post">
    <i class="large material-icons circle-btn" style="font-size:32px;">+</i>
    </a>
    </div>
    ';
  }
  ?>

  <!--New Post Modal -->
  <div class="modal fade" id="new-post-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form method="post" role="form">
          <div class="modal-body">


            <div class="option">
              <h2 style="text-align: center;">New Post</h2>
              <label for="cluster">Class:</label>
              <select class="form-control" id="class">
                <option value="all">All Classes</option>
                <option value="writtens">Writtens</option>
                <option value="marketing">Marketing</option>
                <option value="businessadmin">Business Administration</option>
                <option value="finance">Finance</option>
                <option value="hospitality">Hospitality & Tourism</option>
                <option value="marketing_principles">Principles of Marketing</option>
                <option value="businessadmin_principles">Principles of Business Administration</option>
                <option value="finance_principles">Principles of Finance</option>
                <option value="hospitality_principles">Principles of Hospitality & Tourism</option>
              </select>
              <br>
              <div id="editor">
              </div>
            </div>


          </div>
          <div class="modal-footer" id="footer" align:"center">
            <button type="button" class="btn btn-submit" id="post_message">Post Message</button>
            <button type="button" class="btn btn-cancel" data-dismiss="modal" id="close">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>

  $(document).ready(function() {
/*
    //Upload Picture
    var dialog = new BootstrapDialog({
      message: 'Why not complete your profile, and upload a photo?',
      title: 'Photo Upload',
      buttons: [{
        id: 'sure',
        label: 'Sure!'
      },
      {
        id: 'later',
        label: 'Later'
      }]
    });
    dialog.realize();
    var sure = dialog.getButton('sure');
    var later = dialog.getButton('later');
    sure.click(function(event){
      window.location.href = "account.php";
    });
    later.click(function(event){
      dialog.close();
    });

    if (<?php echo json_encode($_SESSION['image_file']); ?> == "unresolved.jpg") {

      dialog.open();
    }

*/

    var toolbarOptions = [
    [{ header: [false, 1, 2] }],
    ["bold", "italic", "underline"],
    ["blockquote"],
    ["link"],
    ['clean']
    ];

    var quill = new Quill('#editor', {
      modules: {
        toolbar: toolbarOptions
      },
      theme: 'snow'
    });


    $('#new-post').click(function() {
      $('#new-post-modal').modal('show');
    });


    $(".delete").click(function() {
      var post_id = getNum(this.id);

      BootstrapDialog.confirm({
        title: "Delete Post",
        message: "Are you sure you want to delete this post?",
        type: BootstrapDialog.TYPE_WARNING,
        closable: false,
        btnOKLabel: 'Delete it!',
        btnOKClass: "btn-cancel",
        btnCancelLabel: 'Nope',
        btnCancelClass: "btn-submit",
        callback: function(result) {
          if(result) {
            $.ajax({
              type: "get",
              url: "includes/delete_post.php",
              data: {post_id : JSON.stringify(post_id)},
            }).done(function(data){ 
              var data = jQuery.parseJSON(data);
              if (data == "success") {
                location.reload();
              }
              else {
                alert("Cannot delete post at this time");
              }
            });
          }else {

          }
        }
      });
    });

    $(".edit").click(function() {
      var post_id = getNum(this.id);
      var text = $("#post_body_"+post_id).text();
    });

    $("#post_message").click(function() {
      var post_message = quill.container.firstChild.innerHTML.replace(/\>\s+\</g, '>&nbsp;<');
      if (post_message != "") {
        var post_class = $("#class").val();
        var poster = <?php echo json_encode($_SESSION['student_number']); ?>;
        $.ajax({
          type: "get",
          url: "includes/post_message.php",
          data: {message : JSON.stringify(post_message),
            poster : JSON.stringify(poster),
            post_class : JSON.stringify(post_class)},
          }).done(function(data){ 
            result = jQuery.parseJSON(data);
            if (result == "success") {
              location.reload();
            }
            else {
              alert ("Problem posting, please try again");
            }
          });
        }
        else {
          alert ("Please put in some text");
        }
      });

  });

function getNum(string) {
  var num = string.match(/\d+/)[0];
  return num;
}

</script>

</body>
</html>