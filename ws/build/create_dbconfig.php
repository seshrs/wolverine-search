<?php

// create_dbconfig.php
// REQUIRES command-line arguments:
//   $dbname (string)
//   $dbuser (string)
//   $dbpwd (string)

if ( count($argv) !== 4 ) {
  die('Invalid arguments provided to create_dbconfig.php');
}

$dbname = $argv[1];
$dbuser = $argv[2];
$dbpwd = $argv[3];

echo "<?php\n\n";
echo "$" . "DBCONFIG = [\n";
echo "  'URL' => 'localhost',\n";
echo "  'NAME' => '" . $dbname . "',\n";
echo "  'USERNAME' => '" . $dbuser . "',\n";
echo "  'PASSWORD' => '" . $dbpwd . "',\n";
echo "];\n\n";
echo "?>\n";

?>
