<?php

// search.php
// WARNING: Assumes that commandFileMap.json is in a good state.

require_once('../sitevars.php');
require_once('helpers.php');
require_once('registerCommands.php');
require_once('../scripts/analytics.php');

// Analytics
Analytics::runAnalytics(Analytics::$USER_ACTION['SEARCH']);

// Returns a URL
function processQuery($query) {
  global $_SITE, $commandFileMap, $Helpers;
  
  if (!$query || !strlen($query)) {
    return $_SITE['URL'];
  }
  
  $query_terms = explode(' ', strtolower($query));
  $first_term = array_shift($query_terms);
  $rest_of_query = implode(' ', $query_terms);
  
  $originalQuery = $first_term . ' ' . $rest_of_query;
  // Logging
  logQuery($originalQuery);
  logCommand($first_term);
  return $Helpers->executeCommandWithQuery($first_term, $rest_of_query);
}

function logCommand($command) {
  $logCommandScriptPath = "../scripts/logCommand.php";
  $shellCommand = "php " . $logCommandScriptPath . " " . $command;
  shell_exec(escapeshellcmd($shellCommand));
}

function logQuery($query) {
  $logQueryScriptPath = "../scripts/logQuery.php";
  $shellCommand = "php " . $logQueryScriptPath . " " . $query;
  shell_exec(escapeshellcmd($shellCommand));
}

$post = isset($_GET['post']) ? $_GET['post'] : null;
if ($post && $post == '1') {
  $query = isset($_POST['q']) ? $_POST['q'] : null;
}
else {
  $query = isset($_GET['q']) ? $_GET['q'] : null;
}

$debug = isset($_GET['debug']) ? $_GET['debug'] : '';
if ($debug && $debug == '1') {
  die( processQuery($query) );
}

header('Location: ' . processQuery($query));

?>