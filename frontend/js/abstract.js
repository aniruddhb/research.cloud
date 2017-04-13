$(document).ready(function() {
    var word = localStorage.getItem('word');
    var path = localStorage.getItem('path');
    var abstract;

    $.ajax({
      type: 'GET',
      url: 'http://localhost:8080/api/abstract?' + path,
      dataType: 'jsonp',
      success: function(data) {
        abstract = data["abstract"];
        document.title = "Unknown" + " by " + word;
        document.getElementById("title").innerHTML = "Unknown" + " by " + word;
        abstract = abstract.replace(/(\n|\r|\r\n)/g, "<br />");
        var regex = new RegExp('('+word+')', 'ig');
        abstract = abstract.replace(regex, '<span class="highlight">$1</span>');
        document.getElementById("abstract").innerHTML = abstract;
      }
    });

    // to connect to actual functionality later!
    $.ajax({
      type: 'GET',
      url: 'http://localhost:8080/api/download/highlighted?' + path,
      success: function(data) {
        var link = document.createElement('a');
        link.href = "data:application/pdf;base64," + data;
        link.download = event.data.path;
      },
      error: function(err) {
        console.log(err);
      }
    });
});

function downloadAbstract() {

}
