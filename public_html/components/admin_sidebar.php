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

      <h3 class="control-sidebar-heading">Tasks Progress</h3>
      <ul class="control-sidebar-menu">
        <li>
          <a href="javascript::;">
            <h4 class="control-sidebar-subheading">
              Custom Template Design
              <span class="pull-right-container">
                <span class="label label-danger pull-right">70%</span>
              </span>
            </h4>

            <div class="progress progress-xxs">
              <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
            </div>
          </a>
        </li>
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

$(document).ready(function() {

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
});

</script>