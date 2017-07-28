<?php

// logFallbackCommand.php
// Command-line arguments:
//   fallbackCommand (string) - fallback command to be logged

$fallbackCommand = $argv[1];
if ( !$fallbackCommand || !strlen($fallbackCommand) ) {
  die('Command needs to be specified');
}

require(__DIR__ . '/dbconfig.php');

$connection = mysqli_connect($DBCONFIG['URL'], $DBCONFIG['USERNAME'], $DBCONFIG['PASSWORD'], $DBCONFIG['NAME']);
if (!$connection) {
  die('SQL Error: Could not connect to SQL database');
}

$query = "SELECT * FROM FallbackLog WHERE Command = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "s", $fallbackCommand);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if ( mysqli_stmt_num_rows($stmt) == 1) {
  // The command already exists
  echo "updating fallback log to increment hits\n";
  mysqli_stmt_close($stmt);
  $query = "UPDATE FallbackLog SET Hits = Hits + 1 WHERE Command = ?";
  $stmt = mysqli_prepare($connection, $query);
  mysqli_stmt_bind_param($stmt, "s", $fallbackCommand);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
}
else {
  // Need to insert the command
  echo "inserting into fallback log\n";
  mysqli_stmt_close($stmt);
  
  $query = "INSERT INTO FallbackLog VALUES(?, ?)";
  $stmt = mysqli_prepare($connection, $query);
  mysqli_stmt_bind_param($stmt, "si", $fallbackCommand, $count);
  $count = 1;
  
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
}

?>