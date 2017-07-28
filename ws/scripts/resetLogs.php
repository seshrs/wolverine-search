<?php

require('./dbconfig.php');

$connection = mysqli_connect($DBCONFIG['URL'], $DBCONFIG['USERNAME'], $DBCONFIG['PASSWORD'], $DBCONFIG['NAME']);
if (!$connection) {
  die('SQL Error: Could not connect to SQL database');
}

$query = "DELETE FROM CommandLog";
mysqli_query($connection, $query);

$query = "DELETE FROM QueryLog";
mysqli_query($connection, $query);

$query = "DELETE FROM FallbackLog";
mysqli_query($connection, $query);

mysqli_close($connection);

?>