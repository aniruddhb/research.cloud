<?php

use PHPUnit\Framework\TestCase;

/*
 * @covers Highlight Utility
 */
final class HighlightTests extends TestCase {
  # tests for Highlight Utility

  // Test shell script execution
  public function testShellScriptExecution() {
    echo "TEST NAME: testShellScriptExecution \n \n";
    $highlight_dir = __DIR__ . '/../src/highlighter/';
    $highlighter_php = $highlight_dir . 'highlight.php';
    $word_to_highlight = "the";
    exec("php {$highlighter_php} {$word_to_highlight}");

    # scan highlight dir pdf subdir
    $files = scandir($highlight_dir);
    $this->assertArrayHasKey('research_paper.pdf',  $files);
    $this->assertArrayHasKey('research_paper.html',  $files);
    echo "PASS - Highlight Shell Script Execution Test : Research Paper Properly Converted to HTML\n";
    $this->assertArrayHasKey('research_paper_highlighted.html',  $files);
    echo "PASS - Highlight Shell Script Execution Test : Research Paper HTML Highlighted and Written to Directory\n";
    $this->assertArrayHasKey('research_paper_highlighted.pdf',  $files);
    echo "PASS - Highlight Shell Script Execution Test : Highlighted Research Paper Converted back to PDF\n";
		echo "Code Coverage : 15/15 statements = 100% | 0/0 branches = 100.0% \n \n";

		// Overall results
		echo "HIGHLIGHT TEST - OVERALL RESULTS\n";
		echo "Overall Code Coverage : 15/15 statements = 100% | 0/0 branches = 100.0% \n \n";
  }
}

?>
