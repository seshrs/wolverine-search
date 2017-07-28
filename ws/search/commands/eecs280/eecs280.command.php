<?php

/* @command eecs280
 * @description Commands related to EECS 280.
 * @usage eecs280 [command]
 *
 */

global $EECS280_COMMANDS;
$EECS280_COMMANDS = [];

require_once(__DIR__ . '/drive.eecs280.php');
require_once(__DIR__ . '/links.eecs280.php');


register_command('eecs280', 'eecs280_process_query');
function eecs280_process_query($query, $fallback) {
  global $EECS280_COMMANDS, $Helpers;
  
  $eecs280_website = "http://eecs280.org/";
  if ( !$query || !strlen($query) ) {
    return $eecs280_website;
  }
  
  $query_terms = explode(' ', strtolower($query));
  $first_term = array_shift($query_terms);
  $rest_of_query = implode(' ', $query_terms);
  if (array_key_exists($first_term, $EECS280_COMMANDS)) {
    return call_user_func($EECS280_COMMANDS[$first_term], $rest_of_query, $fallback);
  }
  else {
    return $Helpers->executeCommandAsWolverineSearchQuery($query, $fallback);
  }
}

?>
