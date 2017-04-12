$(document).ready(function() {

  // Set the title of the Song List page
  var data = JSON.parse(localStorage.getItem('paperlist'));
  var word = localStorage.getItem('word');
  $("#paperListTitle").html(word);

  function sortTable(f,n){
    var rows = $('table > tbody > tr');
    //var rows = document.getElementById("table").rows;

    rows = Array.prototype.slice.call(rows)
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

        console.log(data);
        for(var i = 0; i < data.length; i++){
          var row = tbody.insertRow(-1);

          var title = row.insertCell(0);
          title.innerHTML = data[i]["title"];
          // $paperID = 100;
          // $keywordText = "Keyword"
          // $(title).click(function(){
          //   $.ajax({
          //     type : 'GET',
          //     url: 'http://localhost:8081/api/abstract/' + $paperID,
          //     dataType: 'jsonp',
          //     success: function(data) {
          //       localStorage.setItem('tags', JSON.stringify(data));
          //       localStorage.setItem('keywordText', $keywordText);
          //       tags = data;
          //       update();
          //     },
          //     error: function(err) {
          //       console.log(err);
          //     }
          //   });
          // });


          var author = row.insertCell(1);
          author.innerHTML = data[i]["author"];
          $(author).click(function(){
            $.ajax({
              type : 'GET',
              url: 'http://localhost:8080/api/wordcloud/' + author.innerHTML + '/' + 10,
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

          var conf = row.insertCell(2);
          conf.innerHTML = (i % 2 === 0) ? "ACM" : "IEEE";

          var freq = row.insertCell(3);
          freq.innerHTML = data[i]["frequency"];
          
          var download = row.insertCell(4);
          download.innerHTML = "Download";
          $(download).on('click', { path: data[i]["path"] }, function(event) {
            $.ajax({
              type: 'GET',
              url: 'http://localhost:8080/api/download?' + event.data.path,
              success: function(data) {
                var link = document.createElement('a');
                link.href = "data:application/pdf;base64," + data;
                link.download = event.data.path;
                link.click();
              }
            });
          });

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

// Export to PDF button
function exportToPDF() {
  // converting HTML table to text
  var column = $("table").find("td:first-child");
  console.log(column);
  var mytext = "";
  for(var i = 0; i < column.length; i++){
    mytext += (column[i].innerText) + '\n';
  }
}


// Export to TXT button
function exportToTXT() {
  // converting HTML table to text
  var column = $("table").find("td:first-child");
  console.log(column);
  var mytext = "";
  for(var i = 0; i < column.length; i++){
    mytext += (column[i].innerText) + '\n';
  }
  var element = document.createElement('a');
  element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(mytext));
  element.setAttribute('download', "paperlist");
  element.style.display = 'none';
  document.body.appendChild(element);

  element.click();

  document.body.removeChild(element);
}
