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

    $.ajax({
      type: 'GET',
      url: 'http://localhost:8080/api/download/highlighted?' + path + '&word=' + word,
      success: function(data) {
        var link = document.getElementById('wrapper-a');
        link.href = "data:application/pdf;base64," + data;
        link.download = "research_paper_highlighted.pdf";
      },
      error: function(err) {
        console.log(err);
      }
    });
});
