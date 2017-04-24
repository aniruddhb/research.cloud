const NO_SEARCH = "false";
const YES_SEARCH = "true";
const MIN_LENGTH = 3;
var percentComplete = 1;

$(document).ready(function() {
  $.ajax({
    type : 'GET',
    url: 'http://localhost:8080/',
    dataType: 'jsonp',
  });

  $("#vis").hide();

  if(localStorage.getItem('searchState') == null || localStorage.getItem('searchState') == NO_SEARCH) {
    localStorage.setItem('searchState', NO_SEARCH);

    // initial states
    $("#searchButton").prop("disabled", false);
    $("#downloadButton").hide();
    $("#keywordLabel").html("Keyword(s): ");
  }
  else if(localStorage.getItem('searchState') == YES_SEARCH) {
    $("#searchButton").prop("disabled", false);
    $("#searchButton").removeClass("btn-class");
    $("#searchButton").addClass("btn-class-disabled");

    $("#downloadButton").show();

    $("#vis").show();
    $("#keywordLabel").show();
    $("#keywordLabel").html("Keyword(s): " + localStorage.getItem('keywordLabelFull'));

    tags = JSON.parse(localStorage.getItem('tags'));
    update();
  }

$("#keywordLabel").hide();

$("#searchButton").click(function() {
  $('#vis').hide();
  document.getElementById("myBar").style.display = "inline-block";
  move();

  searchState = YES_SEARCH;
  $("#keywordLabel").show();
  $("#downloadButton").show();

  var $keywordText = $("#automplete-1").val();
  // need error checking for # of papers
  var $search_cap = $("#numPapers").val();
  $("#keywordLabel").html("Keyword(s): " + $keywordText);

  $.ajax({
    xhr: function() {
        var xhr = new XMLHttpRequest();
        percentComplete = 1;
        console.log(xhr);

       // Download progress
       xhr.upload.addEventListener("progress", function(evt){
           if (evt.lengthComputable) {
               percentComplete = evt.loaded / evt.total;
               // Do something with download progress
               console.log('progress');
               console.log(percentComplete);
           }
           else console.log('error');
       }, false);

        xhr.addEventListener("progress", function(evt){
           if (evt.lengthComputable) {
               percentComplete = evt.loaded / evt.total;
               // Do something with download progress
               console.log('progress');
               console.log(percentComplete);
           }
           else console.log('error');
       }, false);

       return xhr;
    },
    type : 'GET',
    url: 'http://localhost:8080/api/wordcloud/' + $keywordText + '/' + $search_cap,
    dataType: 'jsonp',
    success: function(data) {
      console.log('datum');
      console.log(data);
      localStorage.setItem('tags', JSON.stringify(data));
      localStorage.setItem('keywordText', $keywordText);
      localStorage.setItem('keywordLabelFull', $keywordText);
      tags = data;
      update();
    },
    error: function(err) {
      console.log(err);
    }
  });

});

$("#downloadButton").click(function() {

  html2canvas(document.getElementById('vis')).then(function(canvas) {
      // convert the div that contains the word cloud into a png
      var a = document.createElement('a');
      var img = canvas.toDataURL("image/jpeg");
      a.href = img.replace("image/jpeg", "image/octet-stream");
      a.download = 'image.jpg';
      a.click();







      /*img = img.replace(/^data:image\/\w+;base64,/,"");

      // AJAX function to turn image into a url
      $.ajax({
        url: "http://data-uri-to-img-url.herokuapp.com/images.json",
        type : 'POST',
        data: {'image': {'data_uri':img}},
        xhrFields: {
          withCredentials: false
        },
        success: function(data) {
          // download image
        },
        error: function(err) {
          console.log(err);
        }
      });*/
  });
});

// adding any extra characters
//$("#automplete-1").keyup(function() {

$("#searchButton").prop("disabled", false);

$("#searchButton").removeClass("btn-class-disabled");
$("#searchButton").addClass("btn-class");

});

function move() {
    var elem = document.getElementById("myBar"); 
    var width = 1;
    var id = setInterval(frame, 10);
    function frame() {
        if (percentComplete >= 100) {
            clearInterval(id);
        } else {
            elem.style.width = percentComplete + '%'; 
            elem.innerHTML = percentComplete * 1 + '%';
        }
    }
}

// call AJAX function
/*$("#automplete-1").autocomplete({
  source: function(request, response) {
    var keywordText = $("#automplete-1").val();
    $.ajax({
      type : 'GET',
      url: 'http://localhost:8080/api/dropdown/suggestions/' + keywordText,
      dataType: 'jsonp',
      success: function(data) {
        var stringArray = $.map(data, function(item) {
          return {
            keyword: item.keyword,
            id: item.id,
            img: item.img
          }
        });
        response(stringArray);
      },
      error: function(err) {
        console.log(err);
      }
    });
  },
  focus: function(event, ui) {
    event.preventDefault();
  },
  select: function(event, ui) {
    event.preventDefault();
    $("#automplete-1").val(ui.item.keyword);

    $("#searchButton").prop("disabled", false);
    $("#searchButton").removeClass("btn-class-disabled");
    $("#searchButton").addClass("btn-class");

  },
  minLength: 3
}).data("ui-autocomplete")._renderItem=function(ul, item) {

    var $li = $('<li>'),
    $img = $('<img>');
    $header = $("<h3>" + item.keyword + "</h3>");

    $img.attr({
     src: item.img,
     alt: item.keyword
   });

    $li.append('<a href="#">');
    $li.find('a').append($img).append($header);
    $li.addClass("searchresults");

    return $li.appendTo(ul);
  };

});*/
