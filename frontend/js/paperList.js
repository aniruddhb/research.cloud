$(document).ready(function() {

  // Set the title of the Song List page
  var paperList = localStorage.getItem('paperlist');
  var word = localStorage.getItem('word');
  $("#paperListTitle").html(word);

  function makeOL() {
      // Create the list element
      var list = document.createElement('ol');

      $.each(JSON.parse(paperList), function(key, value) {
        // Create the list item
        var item = document.createElement('li');

        // get frequency and keyword name
        var freq = value["frequency"];
        var keyword_text = value["keyword_text"];

        // Set its contents
        item.appendChild(document.createTextNode(key + " " + "(" + freq + ")"));

        (function (key, keyword_text, word) {
          item.addEventListener('click', function (event) {
            localStorage.setItem('paperName', key);
            localStorage.setItem('keyword', keyword_text);
            window.location.href = "abstract.html";
          },
          false);
        }(key, keyword_text, word));

        // Add it to the list
        list.appendChild(item);
      });

      // Return the constructed list
      return list;
  }

  // Add the contents of songs[0] to #paperList:
  document.getElementById('paperList').appendChild(makeOL());

});

// Back button, returns to Word Cloud Page
function returnWordCloud() {
    window.location.href = "index.html";
}
