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
    exec("php highlight.php");

  }
}

?>
