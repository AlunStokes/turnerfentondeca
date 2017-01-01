<link rel="stylesheet" href="css/sidebar.css">

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
  <!-- Create the tabs -->
  <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
    <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
    <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <!-- Home tab content -->
    <div class="tab-pane active" id="control-sidebar-home-tab">
      <div id="sidebar-recent-exams-section">
      <h3 class="control-sidebar-heading">Recent Exams</h3>
      <ul class="control-sidebar-menu" id="sidebar-recent-exmas">
      </ul>
    </div>
      <!-- /.control-sidebar-menu -->

      <div id="sidebar-attendance-section">
      <h3 class="control-sidebar-heading">Attendance</h3>
      <ul class="control-sidebar-menu" id="sidebar-attendance">
      </ul>
    </div>
      <!-- /.control-sidebar-menu -->

      <div id="sidebar-recent-users-section">
      <h3 class="control-sidebar-heading">Recent Users</h3>
      <label style="font-weight:400; color:#fff;"><input type="checkbox" id="refresh_users" style="float:left;">Update automatically</label>
      <input tpye="text" id="user-name-contains" class="user-search" placeholder="User name" />
      <ul class="control-sidebar-menu" id="sidebar-online-users">
      </ul>
    </div>
      <!-- /.control-sidebar-menu -->
    </div>
    <!-- /.tab-pane -->



    <!-- Stats tab content -->
    <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
    <!-- /.tab-pane -->

    <!-- Settings tab content -->
    <div class="tab-pane" id="control-sidebar-settings-tab">
      <h3 class="control-sidebar-heading">Change Password</h3>
      <div class="form-group">
        <label class="sidebar-label">
          Student Number
          <input type="text" class="sidebar-input" id="change_password_student_number">
        </label>
        <label class="sidebar-label">
          New Password
          <input type="password" class="sidebar-input" id="change_password_password">
        </label>
        <button class="btn btn-large btn-fullwidth" id="change_password_button">Change Password</button>
      </div>
    </div>
    <!-- /.tab-pane -->
  </div>
</aside>
<!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
  immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<script>

function loadRecentExams() {
  $.ajax({
  type: "get",
  url: "includes/ajax",
  data: {ajax_id : JSON.stringify("sidebar_recent_exams")},
}).done(function(data){ 
  var data = jQuery.parseJSON(data);
  var recent_exams_html = ``;
  for (var i = 0; i < data['num']; i++) {
    recent_exams_html +=`
    <li>
    <a href="javascript::;">
    <i class="menu-icon exam-percentage">`+data['percentage'][i]+`%</i>
    <div class="menu-info">
    <h4 class="control-sidebar-subheading">`+data['first_name'][i]+` `+data['last_name'][i]+`</h4>
    <p>`+data['time'][i]+`</p>
    </div>
    </a>
    </li>
    `;
  }
  $("#sidebar-recent-exmas").html(recent_exams_html);
});
}

function loadAttendance() {
  $.ajax({
    type: "get",
    url: "includes/ajax",
    data: {ajax_id : JSON.stringify("sidebar_attendance")},
  }).done(function(data){ 
    var data = jQuery.parseJSON(data);
    var attendance_html = ``;
    for (var i = 0; i < data['num']; i++) {
      if (data['ended'][i] == 0) {
        attendance_html +=`
        <li>
        <a href="javascript::;">
        <div class="circle"></div>
        <div class="menu-info menu-info-name">
        <h4 class="control-sidebar-subheading">`+data['date'][i]+`</h4>
        <p style="font-style:italic;">`+data['attendance_code'][i]+`</p>
        </div>
        </a>
        </li>
        `;
      }
      else {
        attendance_html +=`
        <li>
        <a href="javascript::;">
        <i class="menu-icon attendance-num-users">`+data['num_users'][i]+`</i>
        <div class="menu-info menu-info-name">
        <h4 class="control-sidebar-subheading">`+data['date'][i]+`</h4>
        <p style="font-style:italic;">`+data['attendance_code'][i]+`</p>
        </div>
        </a>
        </li>
        `;
      }
    }
    $("#sidebar-attendance").html(attendance_html);
  });
}

$("#user-name-contains").keyup(function() {
  loadOnline();
});

function loadOnline() {
  if ($("#user-name-contains").val() != "") {
    var user_name_search = $("#user-name-contains").val();
  }
  else {
    var user_name_search = "";
  }
  $.ajax({
    type: "get",
    url: "includes/ajax",
    data: {ajax_id : JSON.stringify("sidebar_online_users"),
    search : JSON.stringify(user_name_search)},
  }).done(function(data){ 
    var data = jQuery.parseJSON(data);
    var online_users_html = ``;
    for (var i = 0; i < data['num']; i++) {
      if (data['online'][i] == 1) {
        online_users_html +=`
        <li>
        <a href="javascript::;">
        <img src="img/user_images/thumbnails/`+data['user_picture_file'][i]+`.jpg" class="img-circle sidebar-img-circle-message-online" alt="User Image" style="float:left;">
        <div class="menu-info menu-info-name">
        <h4 class="control-sidebar-subheading">`+data['first_name'][i]+` `+data['last_name'][i]+`</h4>
        <p>`+data['student_number'][i]+`</p>
        </div>
        </a>
        </li>
        `;
      }
      else {
        online_users_html +=`
        <li>
        <a href="javascript::;">
        <img src="img/user_images/thumbnails/`+data['user_picture_file'][i]+`.jpg" class="img-circle sidebar-img-circle-message-offline" alt="User Image" style="float:left;">
        <div class="menu-info menu-info-name">
        <h4 class="control-sidebar-subheading">`+data['first_name'][i]+` `+data['last_name'][i]+`</h4>
        <p>`+data['student_number'][i]+`</p>
        `;
        var time = Math.floor((new Date).getTime()/1000) - data['unix_time'][i];
        if (time < 86400) {
          if (time < 3600) {
            var time_text = Math.floor(time/60)+" min";
          }
          else {
            var time_text = Math.floor(time/3600)+" hrs";
          }
        }
        else {
          var time_text = data['last_online_formatted'][i]
        }
        online_users_html +=`
        <p>Last Online: <i>`+time_text+`</i></p>
        </div>
        </a>
        </li>
        `;
      }
    }
    $("#sidebar-online-users").html(online_users_html);
  });
}

function changePassword() {
  $.ajax({
    type: "post",
    url: "includes/ajax",
    data: {ajax_id : JSON.stringify("sidebar_change_password"),
    password : JSON.stringify($("#change_password_password").val()),
    student_number : JSON.stringify($("#change_password_student_number").val())},
  }).done(function(data){ 
    var data = jQuery.parseJSON(data);
    if (data) {
      alert ("Password successfully updated");
    }
    else {
      alert ("Error updating password - Check the student number");
    }
  });
}

loadOnline();
loadAttendance();
loadRecentExams();
$("#refresh_users").on("change", function() {
  if ($("#refresh_users").is(':checked')) {
    loadOnline();
  }
});
var stillAlive = setInterval(function () {
  if ($("#refresh_users").is(':checked')) {
    loadOnline();
  }
}, 50000);

$("#change_password_button").on("click", function() {
  if ($("#change_password_password").val().length < 5) {
    alert ("Password must be at least 5 characters");
  }
  else if ($("#change_password_student_number").val().length != 6) {
    alert ("Student number is 6 characters long");
  }
  else {
    changePassword();
  }
});


$('.tab-content, .control-sidebar-bg').bind('mousewheel DOMMouseScroll', function(e) {
  var scrollTo = null;

  if (e.type == 'mousewheel') {
    scrollTo = (e.originalEvent.wheelDelta * -1);
  }
  else if (e.type == 'DOMMouseScroll') {
    scrollTo = 40 * e.originalEvent.detail;
  }

  if (scrollTo) {
    e.preventDefault();
    $(this).scrollTop(scrollTo + $(this).scrollTop());
  }
});

</script>