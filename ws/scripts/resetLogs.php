<?php

require_once(__DIR__ . '/dbconfig.php');

$connection = mysqli_connect(DBConfig::URL, DBConfig::USERNAME, DBConfig::PASSWORD, DBConfig::NAME);
if (!$connection) {
  die('SQL Error: Could not connect to SQL database');
}

$query = "DELETE FROM CommandLog";
mysqli_query($connection, $query);

$query = "DELETE FROM QueryLog";
mysqli_query($connection, $query);

$query = "DELETE FROM FallbackLog";
mysqli_query($connection, $query);

$query = "DELETE FROM UniqueVisitorDevices";
mysqli_query($connection, $query);

mysqli_close($connection);

?>