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

  // function updateProgress(evt) {
  //   if (evt.lengthComputable)
  //   {
  //     //evt.loaded the bytes browser receive
  //     //evt.total the total bytes seted by the header
  //     //
  //    var percentComplete = (evt.loaded / evt.total)*100;
  //
  //    console.log(percentComplete);
  //
  //   //  $('#myBar');
  //   }
  // }
  //
  // var req = new XMLHttpRequest();
  // $('#progressbar').progressbar();
  // req.onprogress=updateProgress;
  // req.open('GET', "http://localhost:8080/api/wordcloud/" + $keywordText + "/" + $search_cap, true);
  // req.onreadystatechange = function (aEvt) {
  //    if (req.readyState == 4)
  //    {
  //         //run any callback here
  //    }
  // };
  // req.send();

  $.ajax({
    xhr: function() {
      var xhr = new window.XMLHttpRequest();
      xhr.withCredentials = true;

      console.log("HELLO BITCH");

      //Download progress
      xhr.addEventListener("progress", function(evt){
        if (evt.lengthComputable) {
          var percentComplete = evt.loaded / evt.total;
          //Do something with download progress
          console.log(percentComplete);
        }
      }, true);
      return xhr;
    },
    type: 'GET',
    url: 'http://localhost:8080/api/wordcloud/' + $keywordText + '/' + $search_cap,
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

  // $.ajax({
  //   xhr: function() {
  //       var xhr = new XMLHttpRequest();
  //       percentComplete = 1;
  //       console.log(xhr);
  //
  //      // Download progress
  //      xhr.upload.addEventListener("progress", function(evt){
  //          if (evt.lengthComputable) {
  //              percentComplete = evt.loaded / evt.total;
  //              // Do something with download progress
  //              console.log('progress');
  //              console.log(percentComplete);
  //          }
  //          else console.log('error');
  //      }, false);
  //
  //       xhr.addEventListener("progress", function(evt) {
  //          if (evt.lengthComputable) {
  //              percentComplete = evt.loaded / evt.total;
  //              // Do something with download progress
  //              console.log('progress');
  //              console.log(percentComplete);
  //          }
  //          else console.log('error');
  //      }, false);
  //
  //      return xhr;
  //   },
  //   type : 'GET',
  //   url: 'http://localhost:8080/api/wordcloud/' + $keywordText + '/' + $search_cap,
  //   dataType: 'jsonp',
  //   success: function(data) {
  //     console.log('datum');
  //     console.log(data);
  //     localStorage.setItem('tags', JSON.stringify(data));
  //     localStorage.setItem('keywordText', $keywordText);
  //     localStorage.setItem('keywordLabelFull', $keywordText);
  //     tags = data;
  //     update();
  //   },
  //   error: function(err) {
  //     console.log(err);
  //   }
  // });

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

});

$(function() {

  var initialArray = new Array();
  localStorage.setItem('recentSearches', JSON.stringify(initialArray));

  $("#searchButton").click(function() {

    var updatedArray = JSON.parse(localStorage.getItem('recentSearches'));
    console.log($("#automplete-1").val());
    updatedArray.push($("#automplete-1").val());

    localStorage.setItem("recentSearches", JSON.stringify(updatedArray));
  });

  $("#automplete-1").autocomplete({
    source: function(request, response) {

      var searches = JSON.parse(localStorage.getItem('recentSearches'));
      console.log(searches);

      response(searches)
    },
    minLength: 3
  });

});
