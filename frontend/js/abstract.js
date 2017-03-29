$(document).ready(function() {
    var paperName = localStorage.getItem('paperName');
    var keyword = localStorage.getItem('keyword');
    var word = localStorage.getItem('word');
    var abstract;

    $.ajax({
      type: 'GET',
      url: 'http://localhost:8080/api/abstract/' + keyword + '/' + paperName,
      dataType: 'jsonp',
      success: function(data) {
        abstract = data;
        document.title = paperName + " by " + keyword;
        document.getElementById("title").innerHTML = paperName + " by " + keyword;
        abstract = abstract.replace(/(\n|\r|\r\n)/g, "<br />");
        var regex = new RegExp('('+word+')', 'ig');
        abstract = abstract.replace(regex, '<span class="highlight">$1</span>');
        document.getElementById("abstract").innerHTML = abstract;
      },
      error: function(err) {
        console.log(err);
      }
    });
});
