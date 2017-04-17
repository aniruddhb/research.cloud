<?php
  $html = new DOMDocument();
  $html->loadHTMLFile('id=28445.html');

  $script = $html->createElement ( 'script', '' );
  $script->setAttribute ('src', 'cheerio.js');
  $script->setAttribute ( 'type', 'text/javascript' );

  $head = $html->getElementsByTagName('head')->item(0)->appendChild($script);
  $html->saveHTMLFile("newtest.html");

?>
