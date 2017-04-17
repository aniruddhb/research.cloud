<?php

  $word_to_search = $argv[1];
  exec('pdf2htmlEX --zoom 1.3 research_paper.pdf');

  $html = new DOMDocument();
  $html->loadHTMLFile('research_paper.html');

  $script = $html->createElement ( 'script', '');
  $script->setAttribute ('src', 'highlight.js');
  $script->setAttribute ( 'type', 'text/javascript');

  $div = $html->createElement('div', $word_to_search);
  $div->setAttribute('id', 'word_to_search');
  $html->appendChild($div);

  $head = $html->getElementsByTagName('head')->item(0)->appendChild($script);
  $html->saveHTMLFile("research_paper_converted.html");

  exec('electron-pdf research_paper_converted.html research_paper_highlighted.pdf');
?>
