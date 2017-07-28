<?php

// logQuery.php
// Command-line arguments:
//   query (string) - query to be logged

array_shift($argv);
$query = implode(' ', $argv);
if ( !$query || !strlen($query) ) {
  die('Query needs to be specified');
}
$query = trim(strtolower($query));

require(__DIR__ . '/dbconfig.php');

$connection = mysqli_connect($DBCONFIG['URL'], $DBCONFIG['USERNAME'], $DBCONFIG['PASSWORD'], $DBCONFIG['NAME']);
if (!$connection) {
  die('SQL Error: Could not connect to SQL database');
}

$sqlQuery = "SELECT * FROM QueryLog WHERE Query = ?";
$stmt = mysqli_prepare($connection, $sqlQuery);
mysqli_stmt_bind_param($stmt, "s", $query);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if ( mysqli_stmt_num_rows($stmt) == 1) {
  // The command already exists
  echo "updating query log to increment hits\n";
  mysqli_stmt_close($stmt);
  $sqlQuery = "UPDATE QueryLog SET Hits = Hits + 1 WHERE Query = ?";
  $stmt = mysqli_prepare($connection, $sqlQuery);
  mysqli_stmt_bind_param($stmt, "s", $query);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
}
else {
  // Need to insert the command
  echo "inserting into query log\n";
  mysqli_stmt_close($stmt);
  
  $sqlQuery = "INSERT INTO QueryLog VALUES(?, ?)";
  $stmt = mysqli_prepare($connection, $sqlQuery);
  mysqli_stmt_bind_param($stmt, "si", $query, $count);
  $count = 1;
  
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
}

?>