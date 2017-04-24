<?php

  // unlink current files to stop things from messing up
  @unlink("research_paper.pdf");
  @unlink("research_paper.html");
  @unlink("research_paper_highlighted.html");
  @unlink("research_paper_highlighted.pdf");

  $word_to_search = $argv[1];
  $file_path = $argv[2];

  // copy file at $file_path to local dir and name appropriately
  copy($file_path, "research_paper.pdf");

  // convert pdf to html
  exec("pdf2htmlEX --zoom 1.3 research_paper.pdf");

  // highlight code
  $html = new DOMDocument();
  $html->loadHTMLFile('research_paper.html');

  $script = $html->createElement ( 'script', '');
  $script->setAttribute ('src', 'highlight.js');
  $script->setAttribute ( 'type', 'text/javascript');

  $div = $html->createElement('div', $word_to_search);
  $div->setAttribute('id', 'word_to_search');
  $html->appendChild($div);

  $head = $html->getElementsByTagName('head')->item(0)->appendChild($script);
  $html->saveHTMLFile("research_paper_highlighted.html");

  exec('electron-pdf research_paper_highlighted.html research_paper_highlighted.pdf');
?>
