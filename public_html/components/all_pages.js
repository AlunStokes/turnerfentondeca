function setOnline() {
  $.ajax({
    type: "get",
    url: "includes/ajax.php",
    data: {ajax_id: JSON.stringify("still_alive")},
  }).done(function(data){
  });
}
setOnline();
var stillAlive = setInterval(function () {
  setOnline();
}, 60000);