<!doctype html>
<html>
<head>
  <style>
    table, th, td {
      border: 1px solid black;
      border-collapse: collapse;
    }
  </style>
</head>
<body>

<?php

require_once(__DIR__ . '/../__util__/Sitevars.php');
require_once(__DIR__ . '/../scripts/dbconfig.php');

$mysqli = new mysqli(DBConfig::URL, DBConfig::USERNAME, DBConfig::PASSWORD, DBConfig::NAME);
if (mysqli_connect_errno()) {
  printf("Connect failed: %s\n", mysqli_connect_error());
  exit();
}

?>

<h1>Command Log</h1>
<table>
  <thead>
    <tr>
      <th>Command</th>
      <th>Init Hits</th>
      <th>Resolved Hits</th>
    </tr>
  </thead>
  <tbody>

<?php

if ($stmt = $mysqli->prepare("SELECT Command, InitHits, ResolvedHits FROM CommandLog ORDER BY ResolvedHits DESC  LIMIT 50")) {
  $stmt->execute();

  /* bind variables to prepared statement */
  $stmt->bind_result($command, $init_hits, $resolved_hits);
  /* fetch values */
  while ($stmt->fetch()) {

?>

    <tr>
      <td><?php echo $command; ?></td>
      <td><?php echo $init_hits; ?></td>
      <td><?php echo $resolved_hits; ?></td>
    </tr>

<?php

  }
  /* close statement */
  $stmt->close();
}

?>

  </tbody>
</table>
<br><hr><br>

<h1>Fallback Log</h1>
<table>
  <thead>
    <tr>
      <th>Fallback Command</th>
      <th>Init Hits</th>
      <th>Resolved Hits</th>
    </tr>
  </thead>
  <tbody>

<?php

if ($stmt = $mysqli->prepare("SELECT Command, InitHits, ResolvedHits FROM FallbackLog ORDER BY ResolvedHits DESC  LIMIT 50")) {
  $stmt->execute();

  /* bind variables to prepared statement */
  $stmt->bind_result($command, $init_hits, $resolved_hits);
  /* fetch values */
  while ($stmt->fetch()) {

?>

    <tr>
      <td><?php echo $command; ?></td>
      <td><?php echo $init_hits; ?></td>
      <td><?php echo $resolved_hits; ?></td>
    </tr>

<?php

  }
  /* close statement */
  $stmt->close();
}

?>

  </tbody>
</table>
<br><hr><br>

<h1>Query Log</h1>
<table>
  <thead>
    <tr>
      <th>Query</th>
      <th>Init Hits</th>
      <th>Resolved Hits</th>
    </tr>
  </thead>
  <tbody>

<?php

if ($stmt = $mysqli->prepare("SELECT Query, InitHits, ResolvedHits FROM QueryLog ORDER BY ResolvedHits DESC  LIMIT 50")) {
  $stmt->execute();

  /* bind variables to prepared statement */
  $stmt->bind_result($query, $init_hits, $resolved_hits);
  /* fetch values */
  while ($stmt->fetch()) {

?>

    <tr>
      <td><?php echo $query; ?></td>
      <td><?php echo $init_hits; ?></td>
      <td><?php echo $resolved_hits; ?></td>
    </tr>

<?php

  }
  /* close statement */
  $stmt->close();
}

?>

  </tbody>
</table>
<br><hr><br>

<h1>Unique Visitors</h1>
<table>
  <thead>
    <tr>
      <th>Device ID</th>
      <th>Landing Page Hits</th>
      <th>About Page Hits</th>
      <th>List Page Hits</th>
      <th>Search Page Hits</th>
      <th>Date Last Modified</th>
    </tr>
  </thead>
  <tbody>

<?php

if ($stmt = $mysqli->prepare("SELECT DeviceID, LandingPageHits, AboutPageHits, ListPageHits, SearchHits, DateLastModified FROM UniqueVisitorDevices ORDER BY DateLastModified DESC LIMIT 50")) {
  $stmt->execute();

  /* bind variables to prepared statement */
  $stmt->bind_result($device_id, $landing_hits, $about_hits, $list_hits, $search_hits, $last_modified);
  /* fetch values */
  while ($stmt->fetch()) {

?>

    <tr>
      <td><?php echo $device_id; ?></td>
      <td><?php echo $landing_hits; ?></td>
      <td><?php echo $about_hits; ?></td>
      <td><?php echo $list_hits; ?></td>
      <td><?php echo $search_hits; ?></td>
      <td><?php echo $last_modified; ?></td>
    </tr>

<?php

  }
  /* close statement */
  $stmt->close();
}

?>

  </tbody>
</table>
<br><hr><br>

</body>
</html>
