<?php

/* helpers.php
 * All of these functions are available to all commands.
 * This file will automatically be included.
 *
 * Usage:
 * $Helpers->helper_func()
 *
 * WARNING: Assumes that commandFileMap.json is in a good state
 */

require_once(__DIR__ . '/../__util__/Sitevars.php');

$commandFileMapJSON = file_get_contents(__DIR__ . '/../data/commandFileMap.json');
$commandFileMap = json_decode($commandFileMapJSON);

class Helpers_class {
  
  // Retrieved from: https://stackoverflow.com/questions/619610/whats-the-most-efficient-test-of-whether-a-php-string-ends-with-another-string
  // REQUIRES: $string (string), $test (string)
  // EFFECTS : Returns true if $string [exactly] ends with $test. Returns false otherwise.
  public function endsWith($string, $test) {
    $strlen = strlen($string);
    $testlen = strlen($test);
    if ($testlen > $strlen) return false;
    return substr_compare($string, $test, $strlen - $testlen, $testlen) === 0;
  }
  
  
  // REQUIRES: $query (string), $fallback (string, optional)
  // EFFECTS : Returns a URL that would effectively queries $query on Wolverine Search.
  //           If $fallback is invalid or not specified, the site default will be used.
  public function executeCommandAsWolverineSearchQuery($query, $fallback = null) {
    global $commandFileMap;
    if ( !$fallback || !strlen($fallback) || !property_exists($commandFileMap, $fallback) ) {
      $fallback = Sitevars::FALLBACK_COMMAND;
    }
    return Sitevars::DOMAIN_NAME . '/search?q=' . $query . '&fallback=' . $fallback;
  }
  
  
  // REQUIRES: $query(string)
  // EFFECTS : Calls the fallback command with $query and returns the resulting URL
  public function executeFallbackCommand($query) {
    global $commandFileMap;
    $fallbackCommand = $this->getFallbackCommand();
    $this->logFallback($fallbackCommand);
    return $this->executeCommandWithQuery($fallbackCommand, $query);
  }
  
  // REQUIRES: $command (string), $query (string), $fallback (string, optional)
  // EFFECTS : Returns a URL that would effectively execute $query with $command.
  public function executeCommandWithQuery($command, $query, $fallback = null) {
    global $_COMMANDS, $commandFileMap;
    if ( !$fallback || !strlen($fallback) || !property_exists($commandFileMap, $fallback) ) {
      $fallback = $this->getFallbackCommand();
    }
    if ( !$command || !strlen($command) || !property_exists($commandFileMap, $command) ) {
      $this->logFallback($fallback);
      return $this->executeCommandWithQuery($fallback, $command . ' ' . $query);
    }
    
    require_once($commandFileMap->{$command});
    return call_user_func($_COMMANDS[$command], $query, $fallback);
  }
  
  private function getFallbackCommand() {
    global $commandFileMap;
    $fallbackCommand = isset($_GET['fallback']) ? $_GET['fallback']: Sitevars::FALLBACK_COMMAND;
    if ( !property_exists($commandFileMap, $fallbackCommand) ) {
      $fallbackCommand = Sitevars::FALLBACK_COMMAND;
    }
    return $fallbackCommand;
  }
  
  private function logFallback($fallbackCommand) {
    $logFallbackScriptPath = __DIR__ . "/../scripts/logFallbackCommand.php";
    $shellCommand = "php " . $logFallbackScriptPath . " " . $fallbackCommand;
    shell_exec(escapeshellcmd($shellCommand));
  }
}

$Helpers = new Helpers_class();

?>
