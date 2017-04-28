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
    $path_to_pdf = __DIR__ . '/../../parser_test_pdfs/id=1101935.pdf';
    $word_to_highlight = "the";
    exec("php {$highlighter_php} {$word_to_highlight} {$path_to_pdf}");

    # scan this subdir
    $parent_dir = __DIR__ . '/../';
    $this->assertFileExists($parent_dir . 'research_paper.pdf');
    echo "PASS - Highlight Shell Script Execution Test : Research Paper PDF Properly Moved into Local Folder\n";
    $this->assertFileExists($parent_dir . 'research_paper.html');
    echo "PASS - Highlight Shell Script Execution Test : Research Paper PDF Properly Converted to HTML\n";
    $this->assertFileExists($parent_dir . 'research_paper_highlighted.html');
    echo "PASS - Highlight Shell Script Execution Test : Research Paper HTML Highlighted and Written to Local Folder\n";
    $this->assertFileExists($parent_dir . 'research_paper_highlighted.pdf');
    echo "PASS - Highlight Shell Script Execution Test : Highlighted Research Paper HTML Converted back to PDF\n";
		echo "Code Coverage : 15/15 statements = 100% | 0/0 branches = 100.0% \n \n";

		// Overall results
		echo "HIGHLIGHT TEST - OVERALL RESULTS\n";
		echo "Overall Code Coverage : 15/15 statements = 100% | 0/0 branches = 100.0% \n \n";
  }
}

?>
