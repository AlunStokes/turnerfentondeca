<?php

session_start();
session_destroy();

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Favicon -->
  <link rel="shortcut icon" href="img/favicon.png`" />
  <meta name="description" content="">
  <meta name="author" content="">

  <title>TFSS DECA | Landing</title>

  <!-- Custom Fonts -->
  <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,800" rel="stylesheet">

  <!--CSS FILES-->

  <!-- Bootstrap Core CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <!-- Theme CSS -->
  <link href="css/grayscale.css" rel="stylesheet">
  <!-- Page CSS -->
  <link href="css/landing.css" rel="stylesheet">
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


</head>

<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">

  <?php include "components/navbar_landing.php"; ?>

  <!-- Intro Header -->
  <header class="intro" id="home">
    <div class="intro-body">
      <div class="container">
        <div class="row">
          <div class="col-md-8 col-md-offset-2">
            <img src="img/deca_logo_large_circle_image.png" align="middle" height="100%" width="100%" style="margin-top:7vh;"></img>
          </div>
          <div class="col-md-12" id="button">
            <a href="#about" class="btn btn-circle-blue page-scroll">
              <i class="fa fa-long-arrow-down"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- About Section -->
  <section id="about" class="container-fluid content-section-about text-center" style="padding-left:0px; padding-right:0px;">
    <div class="row">
      <div class="col-lg-10 col-lg-offset-1" style="text-align:left;">
        <h2 style="font-size:48px; font-weight: 100; color: #fff; text-align:center;">Turner Fenton's Largest Club</h2>
      </div>
    </div>

    <div class="row landing-text-container">
      <div class="col-md-12">
        <h1 style="text-align:left; font-weight: 600; color:#fff;">WHAT IS DECA?</h1>
        <p>&emsp;&emsp;DECA is an international business competition that allows it's members to compete at a regional, provincial, and international level.  Turner Fenton DECA
          is a leading and innovative extra-curricular program that allows students to develop confidence and employability skills, and demonstrate leadership.  We foster a climate that encourages
          innovation and dilligence amongst our members, and always strive for the utmost from our entire chapter.</p>
        </div>
      </div>

      <div class="landing-image-fullwidth">
        <img src="img/deca_chapter_2_strip.jpg" style="" class="hover_image">
      </div>

      <div class="row landing-text-container">
        <div class="col-md-12">
          <h1 style="text-align:left; font-weight: 600; color:#fff;">WHAT DO YOU DO?</h1>
          <p style="text-align:left;">&emsp;&emsp;Turner Fenton DECA is a leading and innovative extra-curricular program that allows students to develop confidence and employability skills, and 
            demonstrate leadership.  Each member chooses an event of their liking, and will spend the proceeding months being taught in their subject area at our weekly meetings.  
            They will compete at the regional level, and potentially the provincial and international after that.</p>
          </div>
        </div>

        <div class="landing-image-fullwidth">
          <img src="img/deca_chapter_3_strip.jpg" style="" class="hover_image">
        </div>


        <div class="row landing-text-container">
          <div class="col-md-12" id="button">
            <a href="#team" class="btn btn-circle-white page-scroll">
              <i class="fa fa-long-arrow-down"></i>
            </a>
          </div>
        </div>
      </section>




      <!-- Team Section -->
      <section id="team" class="container-fluid content-section-about text-center">
        <div class="container-fluid" style="margin-left:2vw;">
          <h1 style="text-align: center;">Executive Team 2016</h1>

          <div class="row">
            <div class="col-md-3 col-sm-4">
              <div class="flip">
                <div class="card">
                  <div class="face front">
                    <div class="person">
                      <img src="img/exec_images/arooba.jpg" alt="Texto Alternativo" class="img-circle img-thumbnail">
                      <h3>Arooba Muhammad</h3>
                      <h5>VP of Education</h5>
                    </div>
                  </div>
                  <div class="face back">
                    <div class="person">
                      <img src="img/exec_images/saba.jpg" alt="Texto Alternativo" class="img-circle img-thumbnail">
                      <h3>Saba Manzoor</h3>
                      <h5>Writtens Director</h5>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-3 col-sm-4">
              <div class="person">
                <img src="img/exec_images/zoey.jpg" alt="Texto Alternativo" class="img-circle img-thumbnail">
                <h3>Zoey Aliasgari</h3>
                <h5>President</h5>
              </div>
            </div>

            <div class="col-md-3 col-sm-4" style="margin-bottom:-1vh;">
              <div class="person">
                <img src="img/exec_images/pranjan.jpg" alt="Texto Alternativo" class="img-circle img-thumbnail">
                <h3>Pranjan Gandhi</h3>
                <h5>Vice-President</h5>
              </div>
            </div>

            <div class="col-md-3 col-sm-4">
              <div class="flip">
                <div class="card">
                  <div class="face front">
                    <div class="person">
                      <img src="img/exec_images/alun.jpg" alt="Texto Alternativo" class="img-circle img-thumbnail">
                      <h3>Alun Stokes</h3>
                      <h5>VP of Communications</h5>
                    </div>
                  </div>
                  <div class="face back">
                    <div class="person">
                      <img src="img/exec_images/sonali.jpg" alt="Texto Alternativo" class="img-circle img-thumbnail">
                      <h3>Sonali Puri</h3>
                      <h5>VP of Communications</h5>
                      <br>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-3 col-sm-4">
              <div class="person">
                <img src="img/exec_images/khubi.jpg" alt="Texto Alternativo" class="img-circle img-thumbnail">
                <h3>Khubi Shah</h3>
                <h5>VP of Finance</h5>
              </div>
            </div>

            <div class="col-md-3 col-sm-4">
              <div class="person">
                <img src="img/exec_images/kavya.jpg" alt="Texto Alternativo" class="img-circle img-thumbnail">
                <h3>Kavya Kadi</h3>
                <h5>Business Associate</h5>
              </div>
            </div>

            <div class="col-md-3 col-sm-4">
              <div class="person">
                <img src="img/exec_images/milind.jpg" alt="Texto Alternativo" class="img-circle img-thumbnail">
                <h3>Milind Waghrey</h3>
                <h5>Business Associate</h5>
              </div>
            </div>

            <div class="col-md-3 col-sm-4">
              <div class="person">
                <img src="img/exec_images/bani.jpg" alt="Texto Alternativo" class="img-circle img-thumbnail">
                <h3>Bani Arora</h3>
                <h5>Business Associate</h5>
              </div>
            </div>
          </div>


          <script type="text/javascript">

          window.setInterval(flip, 2500);

          function flip() {
            $('.flip').find('.card').toggleClass('flipped');
          }

          </script>

          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12" id="button">
              <a href="#join" class="btn btn-circle-blue page-scroll">
                <i class="fa fa-long-arrow-down"></i>
              </a>
            </div>
          </div>

        </div>
      </section>


      <!-- Join Us Section -->
      <section id="join" class="container content-section text-center">
        <div class="row">
          <div class="col-lg-8 col-lg-offset-2">
            <a href="login.php" class="btn btn-default btn-lg apply-btn" style="margin-bottom: 26vh;">Login</a>
          </div>
        </div>
      </section>



    </body>

    </html>
