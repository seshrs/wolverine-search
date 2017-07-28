<?php

// logCommand.php
// Command-line arguments:
//   command (string) - command to be logged

$command = $argv[1];
if ( !$command || !strlen($command) ) {
  die('Command needs to be specified');
}

require(__DIR__ . '/dbconfig.php');

$connection = mysqli_connect($DBCONFIG['URL'], $DBCONFIG['USERNAME'], $DBCONFIG['PASSWORD'], $DBCONFIG['NAME']);
if (!$connection) {
  die('SQL Error: Could not connect to SQL database');
}

$query = "SELECT * FROM CommandLog WHERE Command = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "s", $command);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if ( mysqli_stmt_num_rows($stmt) == 1) {
  // The command already exists
  echo "updating command log to increment hits\n";
  mysqli_stmt_close($stmt);
  $query = "UPDATE CommandLog SET Hits = Hits + 1 WHERE Command = ?";
  $stmt = mysqli_prepare($connection, $query);
  mysqli_stmt_bind_param($stmt, "s", $command);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
}
else {
  // Need to insert the command
  echo "inserting into command log\n";
  mysqli_stmt_close($stmt);
  
  $query = "INSERT INTO CommandLog VALUES(?, ?)";
  $stmt = mysqli_prepare($connection, $query);
  mysqli_stmt_bind_param($stmt, "si", $command, $count);
  $count = 1;
  
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
}

?>