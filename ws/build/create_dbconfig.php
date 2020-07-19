<?php

// create_dbconfig.php
// REQUIRES command-line arguments:
//   $dbname (string)
//   $dbuser (string)
//   $dbpwd (string)

// if ( count($argv) !== 3 && count($argv) !== 4 ) {
if ( count($argv) !== 4 ) {
  die('Invalid arguments provided to create_dbconfig.php');
}

$dbname = $argv[1];
$dbuser = $argv[2];
$dbpwd = $argv[3];

echo "<?php\n";

?>

final class DBConfig {
  const URL = "localhost";
  const NAME = "<?php echo $dbname; ?>";
  const USERNAME = "<?php echo $dbuser; ?>";
  const PASSWORD = "<?php echo $dbpwd; ?>";
}

<?php

echo "?>";

?>
