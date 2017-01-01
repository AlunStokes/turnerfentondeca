<!-- Page Style -->
<link rel="stylesheet" href="css/admin_menu.css">

<!-- Main Header -->
<header class="main-header">

  <!-- Logo -->
  <a href="home" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><b>TF</b>D</span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><b>TFSS</b>DECA</span>
  </a>

  <!-- Header Navbar -->
  <nav class="navbar navbar-static-top" role="navigation">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>
    <!-- Navbar Right Menu -->
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">

        <!-- User Account Menu -->
        <li class="dropdown user user-menu">
          <!-- Menu Toggle Button -->
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <!-- The user image in the navbar-->
            <img src="img/user_images/thumbnails/<?php echo $_SESSION['image_file']; ?>" class="user-image" alt="User Image" width="160px" height="160px">
            <!-- hidden-xs hides the username on small devices so only the image appears. -->
            <span class="hidden-xs"><?php echo $_SESSION['first_name'].' '.$_SESSION['last_name']; ?></span>
          </a>
          <ul class="dropdown-menu">
            <!-- The user image in the menu -->
            <li class="user-header">
              <img src="img/user_images/thumbnails/<?php echo $_SESSION['image_file']; ?>" class="img-circle" alt="User Image">

              <p>
                <?php echo $_SESSION['first_name'].' '.$_SESSION['last_name'].' <br> '.$_SESSION['cluster'].' ('.$_SESSION['event'].')'; ?>
                <!-- <small>Member since June. 2016</small> -->
              </p>
            </li>

            <!-- Menu Footer-->
            <li class="user-footer">
              <div class="pull-left">
                <a href="account" class="btn btn-default btn-menu">Account</a>
              </div>
              <div class="pull-right">
                <a href="logout" class="btn btn-default btn-menu">Sign out</a>
              </div>
            </li>
          </ul>
        </li>
        <!-- Control Sidebar Toggle Button -->
        <li>
          <a href="account"><i class="fa fa-gears"></i></a>
        </li>
      </ul>
    </div>
  </nav>
</header>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">

    <!-- Sidebar user panel (optional) -->
    <div class="user-panel">
      <div class="pull-left image">
        <img src="img/user_images/thumbnails/<?php echo $_SESSION['image_file']; ?>" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p><?php echo $_SESSION['first_name'].' '.$_SESSION['last_name']; ?></p>
        <!-- Status -->
        <small><?php echo $_SESSION['cluster'].'<br>'.$_SESSION['event']; ?></small>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <ul class="sidebar-menu">
      <li class="header">Main</li>
      <li id="home"><a href="home"><i class="fa fa-home"></i> <span>Home</span></a></li>
      <li id="practice"><a href="practice"><i class="fa fa-pencil"></i> <span>Practice</span></a></li>
      <li id="attendance"><a href="attendance"><i class="fa fa-calendar"></i> <span>Attendance</span></a></li>
      <li class="classes" id="classes">
        <a href="timeline"><i class="fa fa-book"></i><span>Classes</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
          <li><a href="timeline">Timeline</a></li>
          <li><a href="exam_statistics">Exam Statistics</a></li>
        </ul>
      </li>
      <li id="account"><a href="account"><i class="fa fa-user"></i> <span>Account</span></a></li>
    </ul>
    <!-- /.sidebar-menu -->
  </section>
  <!-- /.sidebar -->
</aside>

<script type="text/javascript">

var active_page = <?php echo json_encode($active_page); ?>;

document.getElementById(active_page).className += " active";

</script>