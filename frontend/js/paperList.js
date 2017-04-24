$(document).ready(function() {

  // require stuff w/ browserify
  var formattedTableConverter = require('text-table');

  // click functions
  $("#backToWordCloudButton").click(function() {
    window.location.href = "index.html";
  });

  $("#exportToTXTButton").click(function() {
    var rows = $("table").find("tr");
    var allRows = [];
    for(var i = 0; i < rows.length; i++) {
      var cols = $(rows[i]).find("td");
      var rowInfo = [];
      for(var j = 0; j < cols.length; j++) {
        rowInfo.push(cols[j].innerText);
      }
      allRows.push(rowInfo);
    }
    textTable = formattedTableConverter(allRows);
    var link = document.createElement('a');
    link.href = "data:text/plain;charset=utf-8," + encodeURIComponent(textTable);
    link.download = 'paperList';
    link.click();
  });

  $("#exportToPDFButton").click(function() {
    // convert HTML table to pdf-based table
    var pdfDoc = new jsPDF('p', 'pt');
    var myHTMLTable = document.getElementById("table");
    var tableJSON = pdfDoc.autoTableHtmlToJson(myHTMLTable);
    pdfDoc.autoTable(tableJSON.columns, tableJSON.data, {
      theme: 'plain'
    });
    pdfDoc.save("paperList.pdf");
  });

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
        var bibtex = row.insertCell(5);
        bibtex.id = "bibtex";

        titleHeader.innerHTML = "Paper";
        author.innerHTML = "Author";
        conf.innerHTML = "Conference";
        freq.innerHTML = "Frequency";
        download.innerHTML = "Download";
        bibtex.innerHTML = "Bibtex";

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
          $(title).on('click', { path: data[i]["path"] }, function(event) {
            localStorage.setItem('path', event.data.path);
            window.location.href = "abstract.html";
          });


          var author = row.insertCell(1);
          author.innerHTML = data[i]["author"];
          $(author).click(function(){
            window.location.href = "index.html";
            $.ajax({
              type : 'GET',
              url: 'http://localhost:8080/api/wordcloud/' + author.innerText + '/' + 10,
              dataType: 'jsonp',
              success: function(data) {
                console.log('datum');
                console.log(data);
                localStorage.setItem('tags', JSON.stringify(data));
                localStorage.setItem('keywordText', author.innerText);
                localStorage.setItem('keywordLabelFull', author.innerText);
                tags = data;
                update();
              },
              error: function(err) {
                console.log(err);
              }
            });
          });

          var conf = row.insertCell(2);
          conf.innerHTML = data[i]["conference"];

          $(conf).click(function(){
            $.ajax({
              type : 'GET',
              url: 'http://localhost:8080/api/wordcloud/' + conf.innerHTML + '/' + 10,
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

          var bibtex = row.insertCell(5);
          bibtex.innerHTML = "BibTex";
          $(bibtex).on('click', { path: data[i]["path"] }, function(event) {
            window.location.href="http://www.google.com";
            //TODO: add route to bibtex

            // $.ajax({
            //   type: 'GET',
            //   url: 'http://localhost:8080/api/download?' + event.data.path,
            //   success: function(data) {
            //     var link = document.createElement('a');
            //     link.href = "data:application/pdf;base64," + data;
            //     link.download = event.data.path;
            //     link.click();
            //   }
            // });

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
