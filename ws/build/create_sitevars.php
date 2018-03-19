<?php

// Create sitevars.php
// REQUIRES command-line arguments:
//   $domain (string)
//   $name (string)
//   $fallback (string)

if ( count($argv) !== 4 ) {
  echo "Incorrect arguments for creating sitevars.php\n" ;
  echo count($argv) . " arguments provided:\n";
  foreach ($argv as $arg) {
    echo "  ";
    var_dump($arg);
    echo "\n";
  }
  exit(1);
}

$domain = $argv[1];
$name = $argv[2];
$fallback = $argv[3];
if ( strpos($domain, ' ') !== false ) {
  echo "Invalid domain provided";
  exit(1);
}
if ( strpos($fallback, ' ') !== false ) {
  echo "Invalid fallback command provided";
  exit(1);
}

echo "<?php";
echo "\n\n";
echo "$" . "_SITE = [\n";
echo "  'fallback_command' => '" . $fallback . "',\n";
echo "  'name' => '" . $name . "',\n";
echo "  'URL' => '" . $domain . "',\n";
echo "];\n\n";
echo "?>\n";

?>
