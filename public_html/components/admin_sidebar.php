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
      <h3 class="control-sidebar-heading">Recent Exams</h3>
      <ul class="control-sidebar-menu" id="sidebar-recent-exmas">
      </ul>
      <!-- /.control-sidebar-menu -->

      <h3 class="control-sidebar-heading">Recent Users</h3>
      <label style="font-weight:400; color:#fff;"><input type="checkbox" id="refresh_users" style="float:left;">Refresh User List Automatically</label>
      <ul class="control-sidebar-menu" id="sidebar-online-users">
      </ul>
      <!-- /.control-sidebar-menu -->
    </div>



    <!-- /.tab-pane -->
    <!-- Stats tab content -->
    <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
    <!-- /.tab-pane -->
    <!-- Settings tab content -->
    <div class="tab-pane" id="control-sidebar-settings-tab">
      <form method="post">
        <h3 class="control-sidebar-heading">General Settings</h3>

        <div class="form-group">
          <label class="control-sidebar-subheading">
            Report panel usage
            <input type="checkbox" class="pull-right" checked>
          </label>

          <p>
            Some information about this general settings option
          </p>
        </div>
        <!-- /.form-group -->
      </form>
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
$.ajax({
  type: "get",
  url: "includes/ajax.php",
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
  $("#sidebar-recent-exmas").append(recent_exams_html);
});

function loadOnline() {
  $.ajax({
    type: "get",
    url: "includes/ajax.php",
    data: {ajax_id : JSON.stringify("sidebar_online_users")},
  }).done(function(data){ 
    var data = jQuery.parseJSON(data);
    var online_users_html = ``;
    for (var i = 0; i < data['num']; i++) {
      if (data['online'][i] == 1) {
        online_users_html +=`
        <li>
        <a href="javascript::;">
        <img src="img/user_images/thumbnails/`+data['user_picture_file'][i]+`.jpg" class="img-circle img-circle-message-online" alt="User Image" style="float:left;">
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
        <img src="img/user_images/thumbnails/`+data['user_picture_file'][i]+`.jpg" class="img-circle img-circle-message-offline" alt="User Image" style="float:left;">
        <div class="menu-info menu-info-name">
        <h4 class="control-sidebar-subheading">`+data['first_name'][i]+` `+data['last_name'][i]+`</h4>
        <p>`+data['student_number'][i]+`</p>
        </div>
        </a>
        </li>
        `;
      }
    }
    $("#sidebar-online-users").html(online_users_html);
  });
}

loadOnline();
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


$('.control-sidebar, .tab-content, .control-sidebar-bg').bind('mousewheel DOMMouseScroll', function(e) {
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