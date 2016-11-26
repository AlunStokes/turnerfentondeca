<style>

  .close {
  color: #fff !important;
  opacity: .8 !important;
}

</style>

<?php 

$check_alerts = "SELECT id, title, type, body, admin FROM alerts WHERE id NOT IN (SELECT alert_id FROM seen_alert WHERE student_number = ".$_SESSION['student_number'].") AND page = '".$page."';";
$results = mysqli_query($dbconfig, $check_alerts);
if ($results) {
  while ($row = mysqli_fetch_assoc($results)) {
    if ($row['admin'] == 1) {
      if ($_SESSION['admin_boolean']) {
        echo '
        <div class="alert alert-'.$row['type'].' alert-dismissible">
        <abbr title="Click to Dismiss"><button type="button" id="'.$row['id'].'" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></abbr>
        <h4><i class="icon fa fa-exclamation"></i>'.$row['title'].'</h4>
        <p>'.$row['body'].'</p>
        </div>
        ';
      }
      else {

      }
    }
    else {
      echo '
      <div class="alert alert-'.$row['type'].' alert-dismissible">
      <button type="button" id="'.$row['id'].'" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <h4><i class="icon fa fa-exclamation"></i>'.$row['title'].'</h4>
      <p>'.$row['body'].'</p>
      </div>
      ';
    }
  }
}

?>


<script>

$(document).ready(function() {

  $(".close").click(function() {
    $.ajax({
      type: "get",
      url: "includes/add_alert_seen.php",
      data: {alert_id : JSON.stringify($(this).attr('id'))},
    }).done(function(data){ 
    });
  });
});

</script>