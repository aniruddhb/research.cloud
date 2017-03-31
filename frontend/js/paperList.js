$(document).ready(function() {

  // Set the title of the Song List page
  //var paperList = localStorage.getItem('paperlist');
  //var word = localStorage.getItem('word');
  //$("#paperListTitle").html(word);

  var data = [["RachelPaper", "CatherinePaper"], ["Rachel", "Catherine"], ["ACM", "IEEE"], [1, 2], ["MyDownload", "OtherDownload"]];

function sortTable(f,n){
  var rows = $('table > tbody > tr');
  //var rows = document.getElementById("table").rows;

  rows = Array.prototype.slice.call(rows)
 console.log(rows);
  rows.sort(function(a, b) {

    var A = getVal(a);
    var B = getVal(b);

    if(A < B) {
      return -1*f;
    }
    if(A > B) {
      return 1*f;
    }
    return 0;
  });

  function getVal(elm){
    var v = $(elm).children('td').eq(n).text().toUpperCase();
    if($.isNumeric(v)){
      v = parseInt(v,10);
    }
    return v;
  }

  $.each(rows, function(index, row) {
    $('#table').append(row);
  });
}

  function makeOL() {
      // Create the list element

      var table = document.createElement("TABLE");
      table.id = "table";

        //create headers
      var header = table.createTHead();
      var row = header.insertRow(0);


      var titleHeader = row.insertCell(0);
      titleHeader.id = "titleHeader";
      var author = row.insertCell(1);
      var conf = row.insertCell(2);
      var freq = row.insertCell(3);
      var download = row.insertCell(4);

      titleHeader.innerHTML = "Paper";        
      author.innerHTML = "Author";
      conf.innerHTML = "Conference";
      freq.innerHTML = "Frequency";
      download.innerHTML = "Download";

      var tbody = document.createElement('tbody');


      for(var i = 0; i < data[0].length; i++){
        var row = tbody.insertRow(-1);

        var title = row.insertCell(0);
        var author = row.insertCell(1);
        var conf = row.insertCell(2);
        var freq = row.insertCell(3);
        var download = row.insertCell(4);

        title.innerHTML = data[0][i];        
        author.innerHTML = data[1][i];
        conf.innerHTML = data[2][i];
        freq.innerHTML = data[3][i];
        download.innerHTML = data[4][i];
      }

      table.appendChild(tbody);
      var dvTable = document.getElementById("papers");
      dvTable.innerHTML = "";
      dvTable.appendChild(table);




        //iterate through JSON for data

      /* var list = document.createElement('ol');

      $.each(JSON.parse(paperList), function(key, value) {
        // Create the list item
        var item = document.createElement('li');



        // get frequency and keyword name
        var keyword_text = value["keyword_text"];

        // Set its contents
        item.appendChild(document.createTextNode(key));

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
      return list;*/


  }

  makeOL();


      var f_sl = 1;
  var f_nm = 1;
  $(document.getElementById("titleHeader")).click(function(){
    console.log("clicked header");
    f_sl *= -1;
    var n = $(this).prevAll().length;
    sortTable(f_sl,n);
});

  // Add the contents of songs[0] to #paperList:
  //document.getElementById('paperList').appendChild(makeOL());

});

// Back button, returns to Word Cloud Page
function returnWordCloud() {
    window.location.href = "index.html";
}
