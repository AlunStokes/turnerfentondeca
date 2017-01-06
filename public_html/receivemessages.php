<?php

include ('includes/functions.php');

$active_page = 'home';

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


  <!-- jQuery 2.2.3 -->
  <script src="js/jquery-2.2.3.min.js"></script>

</head>


<body id="body">


  <script>

  var wsUri = "ws://localhost:9000/turnerfentondeca/public_html/includes/websocket";   
  websocket = new WebSocket(wsUri); 

    //Connected to server
    websocket.onopen = function(ev) {
      alert('Connected to server ');
      websocket.send(JSON.stringify({'student_number': 123456}));
    }
    
    //Connection close
    websocket.onclose = function(ev) { 
      alert('Disconnected');
    };
    
    //Message Receved
    websocket.onmessage = function(ev) { 
      //alert('Message '+ev.data);
      var data = JSON.parse(ev.data); //PHP sends Json data
      console.log(JSON.stringify(data, null, 4));
      if (data.msgtype == "user-msg") {
    var user = data.user; //message type
    var partner = data.partner; //message text
    var message = JSON.parse(data.message); //user name

    html = `
    <p>`+user+` said: `+message+` to `+partner+`</p>
    `;
    $("body").append(html);
  }
};

    //Error
    websocket.onerror = function(ev) { 
      alert('Error '+ev.data);
    };


    </script>

  </body>
  </html>