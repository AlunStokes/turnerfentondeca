<?php

include ('includes/session.php');
include ('includes/functions.php');

$active_page = 'messaging';

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>TFSS DECA | Home</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Favicon -->
  <link rel="shortcut icon" href="img/favicon.png`" />
  <!--Open Sans Font -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
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
  <link rel="stylesheet" href="css/home.css">



  <!-- jQuery 2.2.3 -->
  <script src="js/jquery-2.2.3.min.js"></script>
  <script src="components/all_pages.js"></script>
  <!-- Bootstrap 3.3.6 -->
  <script src="js/bootstrap.min.js"></script>
  <!-- AdminLTE App -->
  <script src="js/dashboard.js"></script>

</head>


<body class="hold-transition skin-blue sidebar-mini fixed">
  <!-- IMPORTNATNT -->
  <style>
  .direct-chat-text {
    overflow-wrap: break-word;
  }
  .direct-chat-msg {
    margin-bottom: 0;
  }
  .direct-chat {
    position: absolute;
    max-width: 300px;
    right: 0;
    bottom: 0;
    margin-bottom: 0;
    transition: 0.3s;
    transition-timing-function: ease-in-out;
  }
  .direct-chat-sidebar-open {
    right: 230px;
  }
  .direct-chat-messages {
    bottom: 0;
    min-height: 80vh;
  }
  </style>


  <div class="wrapper">

    <!-- Header and Left Menu -->
    <?php if ($_SESSION['admin_boolean']) { include 'components/admin_menu.php'; }
    else { include 'components/member_menu.php'; } ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Home
          <small>General Info</small>
        </h1>
      </section>

      <!-- Main content -->
      <section class="content">



        <div class="box box-primary direct-chat direct-chat-primary" style="float:bottom;">
          <div class="box-header with-border">
            <h3 class="box-title"></h3>
            <div class="box-tools pull-right">
              <span data-toggle="tooltip" title="" class="badge bg-blue" id="num_messages"></span>
              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>


          </div><!-- /.box-header -->
          <div class="box-body">
            <!-- Conversations are loaded here -->
            <div class="direct-chat-messages">
            </div><!-- /.box-body -->


            <div class="box-footer">
              <div class="input-group">
                <input type="text" name="message" placeholder="Type Message" class="form-control" id="user-message" onkeydown = "if (event.keyCode == 13)$('#send-message').click()">
                <span class="input-group-btn">
                  <button type="button" class="btn btn-primary btn-flat" id="send-message">Send</button>
                </span>
              </div>
            </div><!-- /.box-footer-->
          </div><!--/.direct-chat -->



        </section>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->

      <script>

      var user = <?php echo $_SESSION['student_number']; ?>;
      var user_name = "<?php echo $_SESSION['name']; ?>";
      var partner;
      var num_messages = 0;
      if (user == 498566) {
        partner = 123456;
      }
      else {
        partner = 498566;
      }

      var wsUri = "ws://localhost:9000/turnerfentondeca/public_html/includes/websocket";   
      websocket = new WebSocket(wsUri); 

    //Connected to server
    websocket.onopen = function(ev) {
      console.log('Connected to server ');
      websocket.send(JSON.stringify({'student_number': user}));
    }
    
    //Connection close
    websocket.onclose = function(ev) { 
      console.log('Disconnected');
    };
    
    //Message Receved
    websocket.onmessage = function(ev) { 
      //alert('Message '+ev.data);

      var data = JSON.parse(ev.data); //PHP sends Json data
      console.log(JSON.stringify(data, null, 4));
      if (data.msgtype == "user-msg") {
        var sender = JSON.parse(data.sender); 
        var sender_name = JSON.parse(data.sender_name); 
        var recipient = JSON.parse(data.recipient); 
        var message = JSON.parse(data.message); 
        var sent_date = data.sent_date; 
        var sent_by_user = 0;
        updateMessages(sender, sender_name, message, sent_date, sent_by_user);
      }

      if ($(".box").hasClass("collapsed-box")) {
        num_messages++;
        $("#num_messages").html(num_messages);
        $("#num_messages").attr("title", num_messages + " new messages");
      }
    };

    $(".btn-box-tool").on("click", function() {
      if ($(".btn-box-tool").data("widget") == "collapse") {
        num_messages = 0;
        $("#num_messages").html("");
        $("#num_messages").attr("title","");
      }
    });

    //Error
    websocket.onerror = function(ev) { 
      console.log('Error '+ev.data);
    };

    var last_message_timestamp;
    var user = <?php echo $_SESSION['student_number']; ?>;

    $(document).ready(function() {
      loadMessages();
    });

    $("#send-message").on("click", function() {
      sendMessage();
    });

    function sendMessage() {
      var message = $("#user-message").val();
      if (message != "") {
      var sender = user;
      var sender_name = user_name;
      var recipient = partner;
      //var partner = 123456;
      var websocket_data = {
        sender: JSON.stringify(sender),
        sender_name: JSON.stringify(sender_name),
        recipient: JSON.stringify(recipient),
        message: JSON.stringify(message),
        ajax_id: JSON.stringify("messaging_send")
      };

      $.ajax({
        type: "POST",
        url: "includes/ajax",
        data: websocket_data
      }).done(function(data){
        var data = jQuery.parseJSON(data);
        $("#user-message").val("");
        if (data == "success") {
          websocket.send(JSON.stringify(websocket_data));

          messageHTML = `
          <!-- Message to the right -->
          <div class="direct-chat-msg right">
          <div class="direct-chat-info clearfix">
          <span class="direct-chat-name pull-right">`+user_name+`</span>
          </div><!-- /.direct-chat-info -->
          <img class="direct-chat-img" src="img/user_images/thumbnails/`+sender+`.jpg" alt="message user image"><!-- /.direct-chat-img -->
          <div class="direct-chat-text">
          `+message+`
          </div><!-- /.direct-chat-text -->
          </div><!-- /.direct-chat-msg -->
          </div><!--/.direct-chat-messages-->
          `;
          $(".direct-chat-messages").append(messageHTML);
          scrollToBottom();
        }
        else {

        }
      });
    }
}

function updateMessages(sender, sender_name, message, sent_date, sent_by_user) {
  messageHTML = ``;
  if (sent_by_user == 1) {
    messageHTML = `
    <!-- Message to the right -->
    <div class="direct-chat-msg right" style="margin-top: 10px;">
    <div class="direct-chat-info clearfix">
    <span class="direct-chat-name pull-right">`+sender_name+`</span>
    <span class="direct-chat-timestamp pull-left">`+sent_date+`</span>
    </div><!-- /.direct-chat-info -->
    <img class="direct-chat-img" src="img/user_images/thumbnails/`+sender+`.jpg" alt="message user image"><!-- /.direct-chat-img -->
    <div class="direct-chat-text">
    `+message+`
    </div><!-- /.direct-chat-text -->
    </div><!-- /.direct-chat-msg -->
    </div><!--/.direct-chat-messages-->
    `;
  }
  else {
    messageHTML = `
    <!-- Message. Default to the left -->
    <div class="direct-chat-msg" style="margin-top: 10px;">
    <div class="direct-chat-info clearfix">
    <span class="direct-chat-name pull-left">`+sender_name+`</span>
    <span class="direct-chat-timestamp pull-right">`+sent_date+`</span>
    </div><!-- /.direct-chat-info -->
    <img class="direct-chat-img" src="img/user_images/thumbnails/`+sender+`.jpg" alt="message user image"><!-- /.direct-chat-img -->
    <div class="direct-chat-text">
    `+message+`
    </div><!-- /.direct-chat-text -->
    </div><!-- /.direct-chat-msg -->
    `;
  }
  $(".direct-chat-messages").append(messageHTML);
  scrollToBottom();
}

function scrollToBottom() {
  myscroll = $('.direct-chat-messages');
  myscroll.scrollTop(myscroll.get(0).scrollHeight);
}

function loadMessages() {
  //var partner = 123456;
  $.ajax({
    type: "POST",
    url: "includes/ajax.php",
    data: {partner: JSON.stringify(partner),
      ajax_id: JSON.stringify("messaging_load")}
    }).done(function(data){
      var data = jQuery.parseJSON(data);
      last_message_timestamp = data['sent_date_timestamp'][data['sent_date_timestamp'].length-1];
      messageHTML = ``;
      for (var i = 0; i < data['message'].length; i++) {
        if (data['user_sent'][i] == 1) {
          if (data['user_sent'][i] != data['user_sent'][i-1] || i == 0) {
            messageHTML += `
            <!-- Message to the right -->
            <div class="direct-chat-msg right" style="margin-top: 10px;">
            <div class="direct-chat-info clearfix">
            <span class="direct-chat-name pull-right">`+data['sender_name'][i]+`</span>
            <span class="direct-chat-timestamp pull-left">`+data['sent_date'][i]+`</span>
            </div><!-- /.direct-chat-info -->
            <img class="direct-chat-img" src="img/user_images/thumbnails/`+data['sender_student_number'][i]+`.jpg" alt="message user image"><!-- /.direct-chat-img -->
            <div class="direct-chat-text">
            `+data['message'][i]+`
            </div><!-- /.direct-chat-text -->
            </div><!-- /.direct-chat-msg -->
            </div><!--/.direct-chat-messages--> 
            `;
          }
          else {
            messageHTML += `
            <!-- Message to the right -->
            <div class="direct-chat-msg right">
            <div class="direct-chat-info clearfix">
            </div><!-- /.direct-chat-info -->
            <div class="direct-chat-text">
            `+data['message'][i]+`
            </div><!-- /.direct-chat-text -->
            </div><!-- /.direct-chat-msg -->
            </div><!--/.direct-chat-messages-->
            `;
          }
        }
        else {
          if (data['user_sent'][i] != data['user_sent'][i-1] || i == 0) {
            messageHTML += `
            <!-- Message. Default to the left -->
            <div class="direct-chat-msg" style="margin-top: 10px;">
            <div class="direct-chat-info clearfix">
            <span class="direct-chat-name pull-left">`+data['sender_name'][i]+`</span>
            <span class="direct-chat-timestamp pull-right">`+data['sent_date'][i]+`</span>
            </div><!-- /.direct-chat-info -->
            <img class="direct-chat-img" src="img/user_images/thumbnails/`+data['sender_student_number'][i]+`.jpg" alt="message user image"><!-- /.direct-chat-img -->
            <div class="direct-chat-text">
            `+data['message'][i]+`
            </div><!-- /.direct-chat-text -->
            </div><!-- /.direct-chat-msg -->
            `;
          }
          else {
            messageHTML += `
            <!-- Message. Default to the left -->
            <div class="direct-chat-msg">
            <div class="direct-chat-info clearfix">
            </div><!-- /.direct-chat-info -->
            <div class="direct-chat-text">
            `+data['message'][i]+`
            </div><!-- /.direct-chat-text -->
            </div><!-- /.direct-chat-msg -->
            `;
          }
        }
      }
      $(".direct-chat-messages").prepend(messageHTML);
      myscroll = $('.direct-chat-messages');
      myscroll.scrollTop(myscroll.get(0).scrollHeight);
    });
}

</script>

</body>
</html>