$(document).ready(function() {

  // Set the title of the Song List page
  //var paperList = localStorage.getItem('paperlist');
  //var word = localStorage.getItem('word');
  //$("#paperListTitle").html(word);

  var data = [["RachelPaper", "CatherinePaper", "ChickenPaper"], ["Rachel", "Catherine", "Chicken"], ["ACM", "IEEE", "ACM"], [1, 2, 3], ["MyDownload", "OtherDownload", "ChickenDownload"]];

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
        author.id = "author";
        var conf = row.insertCell(2);
        conf.id = "conf";
        var freq = row.insertCell(3);
        freq.id = "freq";
        var download = row.insertCell(4);
        download.id = "download";

        titleHeader.innerHTML = "Paper";        
        author.innerHTML = "Author";
        conf.innerHTML = "Conference";
        freq.innerHTML = "Frequency";
        download.innerHTML = "Download";

        var tbody = document.createElement('tbody');

        for(var i = 0; i < data[0].length; i++){
          var row = tbody.insertRow(-1);

          var title = row.insertCell(0);
          $paperID = 100;
          $keywordText = "Keyword"
          $(title).click(function(){
            $.ajax({
              type : 'GET',
              url: 'http://localhost:8081/api/abstract/' + $paperID,
              dataType: 'jsonp',
              success: function(data) {
                localStorage.setItem('tags', JSON.stringify(data));
                localStorage.setItem('keywordText', $keywordText);
                tags = data;
                update();
              },
              error: function(err) {
                console.log(err);
              }
            });
          });


          var author = row.insertCell(1);
          $(author).click(function(){
            window.location.href="http://google.com";
          });

          var conf = row.insertCell(2);
          var freq = row.insertCell(3);
          var download = row.insertCell(4);
          $(download).click(function(){
            window.location.href="http://google.com";
          });


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


    var f_t = 1;
    var f_a = 1;
    var f_c = 1;
    var f_f = 1;
    var f_d = 1;
    $(document.getElementById("titleHeader")).click(function(){
      f_t *= -1;
      var n = $(this).prevAll().length;
      sortTable(f_t,n);
    });

    $(document.getElementById("author")).click(function(){
      f_a *= -1;
      var n = $(this).prevAll().length;
      sortTable(f_a,n);
    });

    $(document.getElementById("conf")).click(function(){
      f_c *= -1;
      var n = $(this).prevAll().length;
      sortTable(f_c,n);
    });

    $(document.getElementById("freq")).click(function(){
      f_f *= -1;
      var n = $(this).prevAll().length;
      sortTable(f_f,n);
    });

    $(document.getElementById("download")).click(function(){
      f_d *= -1;
      var n = $(this).prevAll().length;
      sortTable(f_d,n);
    });

  // Add the contents of songs[0] to #paperList:
  //document.getElementById('paperList').appendChild(makeOL());

});

// Back button, returns to Word Cloud Page
function returnWordCloud() {
  window.location.href = "index.html";
}
