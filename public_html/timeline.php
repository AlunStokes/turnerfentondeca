<?php

include ('includes/session.php');

$active_page = 'classes';
$page = 'timeline';

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>TFSS DECA | Timeline</title>
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
  <!-- Page Style -->
  <link rel="stylesheet" href="css/timeline.css">
  <!-- Include Quill stylesheet -->
  <link href="https://cdn.quilljs.com/1.0.0/quill.snow.css" rel="stylesheet">
  <link href="https://cdn.quilljs.com/1.0.0/quill.bubble.css" rel="stylesheet">


  <!-- jQuery 2.2.3 -->
  <script src="js/jquery-2.2.3.min.js"></script>
  <script src="components/all_pages.js"></script>
  <!-- Bootstrap 3.3.6 -->
  <script src="js/bootstrap.min.js"></script>
  <!-- dashboard App -->
  <script src="js/admin.min.js"></script>
  <script src="js/bootstrap-dialog.min.js"></script>
  <!-- Include the Quill library -->
  <script src="js/quill.js"></script>

</head>


<body class="hold-transition skin-blue sidebar-mini fixed">
 <div class="wrapper">

  <!-- Header and Left Menu -->
  <?php 
  if ($_SESSION['admin_boolean']) { 
    include 'components/admin_menu.php'; 
  }
  else { 
    include 'components/member_menu.php'; 
  } 
  ?>

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

        <?php include "components/alerts.php"; ?>

        <ul class="timeline">

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
    echo '
    <div class="fixed-action-btn" style="bottom: 115px; right: 24px;">
    <a class="btn-floating btn-large blue" id="new-alert">
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
              <select class="form-control" id="class_post">
                <option value="all">All Classes</option>
                <option value="admin">Admin</option>
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
              <div id="editor_post">
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


  <!--Edit Post Modal -->
  <div class="modal fade" id="edit-post-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form method="post" role="form">
          <div class="modal-body">


            <div class="option">
              <h2 style="text-align: center;">Edit Post</h2>
              <label for="cluster">Class:</label>
              <select class="form-control" id="class_edit">
                <option value="all">All Classes</option>
                <option value="admin">Admin</option>
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
              <div id="editor_edit">
              </div>
            </div>


          </div>
          <div class="modal-footer" id="footer" align:"center">
            <button type="button" class="btn btn-submit" id="edit_message">Edit Message</button>
            <button type="button" class="btn btn-cancel" data-dismiss="modal" id="close">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!--New Alert Modal -->
  <div class="modal fade" id="new-alert-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form method="post" role="form">
          <div class="modal-body">


            <div class="option">
              <h2 style="text-align: center;">New Alert</h2>
              <label for="admin">Who for?</label>
              <select class="form-control" id="admin">
                <option value="0">Everyone</option>
                <option value="1">Admin Only</option>
              </select>
              <br>
              <label for="type">What Type?</label>
              <select class="form-control" id="type">
                <option value="info">Infomation</option>
                <option value="success">Success</option>
                <option value="warning">Warning</option>
                <option value="danger">Danger</option>
              </select>
              <br>
              <label for="editor_alert_title">Title</label>
              <div id="editor_alert_title">
              </div>
              <br>
              <label for="editor_alert_body">Body</label>
              <div id="editor_alert_body">
              </div>
            </div>


          </div>
          <div class="modal-footer" id="footer" align:"center">
            <button type="button" class="btn btn-submit" id="post_alert">Post Alert</button>
            <button type="button" class="btn btn-cancel" data-dismiss="modal" id="close">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>

  $(document).ready(function() {

    var user_class = "<?php echo $_SESSION['class']; ?>";
    var admin = <?php echo $_SESSION['admin']; ?>;

    $.ajax({
      type: "get",
      url: "includes/ajax.php",
      data: {ajax_id : JSON.stringify("timeline_load_posts"),
      user_class : JSON.stringify(user_class),
      admin : JSON.stringify(admin)},
    }).done(function(data){
      var messages = jQuery.parseJSON(data);

      for (var i = 0; i < messages['message'].length; i++) {

        if (i > 0 && messages['date'][i] != messages['date'][i-1]) {
          $(".timeline").append(`
            <li class="time-label">
            <span class="bg-red">
            `+messages['date'][i]+`
            </span>
            </li>
            `);
        }
        else if (i == 0) {
          $(".timeline").append(`
            <li class="time-label">
            <span class="bg-red">
            `+messages['date'][i]+`
            </span>
            </li>
            `);
        }

        var post = `
        <!-- timeline item -->
        <li>
        <!-- timeline icon -->
        <i class="fa fa-circle-o-notch bg-blue"></i>
        <div class="timeline-item">
        `;

        if (admin == 1) {
          post += `
          <span class="delete" id="delete_`+messages["id"][i]+`"><i class="fa fa-times"></i></span>
          `;
        }

        post += `
        <span class="time"><i class="fa fa-clock-o"></i>`+messages['time'][i]+`</span>
        <div class="pull-left image">
        <img src="img/user_images/thumbnails/`+messages['poster_picture_path'][i]+`" class="img-circle img-circle-message" alt="User Image">
        </div>
        <h3 class="timeline-header">`+messages['poster_first_name'][i]+` `+messages['poster_last_name'][i]+`</h3>

        <div class="timeline-body">
        <p id="post_body_`+messages["id"][i]+`" style="font-size:15px;">`+messages['message'][i]+`</p>
        </div>

        <div class="timeline-footer">
        <p style="display: inline; color: #0073b7; margin-bottom: 0;"><b>`+messages['class_proper'][i]+`</b></p>
        `;
        if (messages['json_message'][i] !== null && admin == 1) {
          post += `
          <span class="edit" id="edit_`+messages["id"][i]+`"><i class="fa fa-pencil"></i></span>
          `;
        }
        post += `
        </div>
        </div>
        </li>
        `;

        $(".timeline").append(post);

      }

      $(document).on('click', '.edit',function() {
        var post_id = getNum(this.id);
        var array_index = messages['id'].indexOf(post_id);
        var text = JSON.parse(messages['json_message'][array_index]);
        quill_edit.setContents(text);
        $("#edit_message").attr("id", "edit_message_"+post_id);
        $("#class_edit").val(messages['class'][array_index]);
        $('#edit-post-modal').modal('show');
      });

    });

var toolbarOptions = [
[{ header: [false, 1, 2] }],
["bold", "italic", "underline"],
["blockquote"],
["link"],
['clean']
];

var quill_post = new Quill('#editor_post', {
  modules: {
    toolbar: toolbarOptions
  },
  theme: 'snow'
});

var quill_alert_title = new Quill('#editor_alert_title', {
  modules: {
    toolbar: toolbarOptions
  },
  theme: 'snow'
});
var quill_alert_body = new Quill('#editor_alert_body', {
  modules: {
    toolbar: toolbarOptions
  },
  theme: 'snow'
});

var quill_edit = new Quill('#editor_edit', {
  modules: {
    toolbar: toolbarOptions
  },
  theme: 'snow'
});


$('#new-post').on('click',function() {
  $('#new-post-modal').modal('show');
});

$('#new-alert').on('click',function() {
  $('#new-alert-modal').modal('show');
});


$(document).on('click', '.delete', function() {
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
          url: "includes/ajax.php",
          data: {ajax_id : JSON.stringify("timeline_delete_post"),
          post_id : JSON.stringify(post_id)},
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


$("#post_message").on('click',function() {
  var json_post_message = JSON.stringify(quill_post.getContents());
  var post_message = quill_post.container.firstChild.innerHTML.replace(/\>\s+\</g, '>&nbsp;<');
  var post_class = $("#class_post").val();
  var poster = <?php echo json_encode($_SESSION['student_number']); ?>;
  $.ajax({
    type: "get",
    url: "includes/ajax.php",
    data: {ajax_id : JSON.stringify("timeline_post_message"),
    json_message : json_post_message,
    message : JSON.stringify(post_message),
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
});

$("#post_alert").on('click',function() {
  var title = quill_alert_title.container.firstChild.innerHTML.replace(/\>\s+\</g, '>&nbsp;<');
  var body = quill_alert_body.container.firstChild.innerHTML.replace(/\>\s+\</g, '>&nbsp;<');
  var admin = $("#admin").val();
  var type = $("#type").val();
  $.ajax({
    type: "get",
    url: "includes/ajax.php",
    data: {ajax_id: JSON.stringify("timeline_post_alert"),
    title : JSON.stringify(title),
    body : JSON.stringify(body),
    admin : JSON.stringify(admin),
    type : JSON.stringify(type)},
  }).done(function(data){ 
    result = jQuery.parseJSON(data);
    if (result == "success") {
      location.reload();
    }
    else {
      alert ("Problem posting, please try again");
    }
  });
});

$("#edit_message").on('click',function() {
  var post_id = getNum($(this).attr("id"));
  var json_post_message = JSON.stringify(quill_edit.getContents());
  var post_message = quill_edit.container.firstChild.innerHTML.replace(/\>\s+\</g, '>&nbsp;<');
  var post_class = $("#class_edit").val();
  $.ajax({
    type: "get",
    url: "includes/ajax.php",
    data: {json_message : json_post_message,
      message : JSON.stringify(post_message),
      post_class : JSON.stringify(post_class),
      post_id : JSON.stringify(post_id),
      ajax_id: JSON.stringify("timeline_edit_message")},
    }).done(function(data){ 
      result = jQuery.parseJSON(data);
      if (result == "success") {
        location.reload();
      }
      else {
        alert ("Problem posting, please try again");
      }
    });
  });

});

function getNum(string) {
  var num = string.match(/\d+/)[0];
  return num;
}

</script>

</body>
</html>