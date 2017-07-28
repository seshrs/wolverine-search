<?php

require_once(__DIR__ . '/../sitevars.php');
require_once(__DIR__ . '/helpers.php');

$_COMMANDS = [];
function register_command($command, $command_processor) {
  global $_COMMANDS;
  
  // Ensure that command does not contain spaces
  if ( strpos($command, ' ') !== false ) {
    throw new Exception("Command '" . $command . "' cannot contain spaces.");
  }
  
  // Ensure that the command hasn't already been registered
  if ( array_key_exists($command, $_COMMANDS) ) {
    throw new Exception("Command '" . $command . "' has already been registered.");
  }
  
  $_COMMANDS[$command] = $command_processor;
}

?>
