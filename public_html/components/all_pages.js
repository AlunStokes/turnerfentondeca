function setOnline() {
  $.ajax({
    type: "get",
    url: "includes/ajax",
    data: {ajax_id: JSON.stringify("still_alive")},
  }).done(function(data){
  });
}
setOnline();
var stillAlive = setInterval(function () {
  setOnline();
}, 60000);



function openChat(recipient) {
  alert (recipient);
}