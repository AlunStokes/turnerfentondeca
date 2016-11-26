<?php

if (isset($_POST['submit'])) {
  if (password_verify($_GET['password'], '$2y$11$kgjpDQIaxQ2c6sJFPu8f1u6WOJ97RkobAq7IVAYmPNbpEN9LajR3u')) {
    $correct = true;
  }
}

?>

<style type="text/css">

.modal-dialog {
  color: #6d6d6d;
}

.form-control {
  margin-bottom: 10px;
  color: #6d6d6d;
}

.modal-header {
  background-color: #3c8dbc;
  color: #fff;
}

.modal-footer {
  text-align: center;
}

.btn-default {
  margin-bottom: 10px;
}

</style>

<!--Event Selection Modal -->
<div class="modal fade" id="verify" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
      <form method="post" id="submit" role="form">
        <div class="modal-body">

          <h2>Enter password: </h2>
          <form role="form" class="form-signin" method="POST" action="" id="login" autocomplete="off">

            <div class="form-group">
              <input class="form-control" placeholder="Password" name="password" type="password">
            </div>

            <div class="form-group">
              <input class="btn btn-lg btn-primary btn-block" type="submit" value="Submit" name="submit">
            </div>

          </form>


        </div>
      </div>
    </div>
  </div>
</div>
</div>

<script>

//Load Modal
$(window).load(function(){
  if(!correct) {
    $('#verify').modal('show');
  }
});

</script>