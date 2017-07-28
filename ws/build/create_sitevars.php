<?php

// Create sitevars.php
// REQUIRES command-line arguments:
//   $domain (string)
//   $fallback (string)

if ( count($argv) !== 3 ) {
  die("Incorrect arguments for creating sitevars.php");
}

$domain = $argv[1];
$fallback = $argv[2];
if ( strpos($domain, ' ') !== false ) {
  die("Invalid domain provided");
}
if ( strpos($fallback, ' ') !== false ) {
  die("Invalid fallback command provided");
}

echo "<?php";
echo "\n\n";
echo "$" . "_SITE = [\n";
echo "  'fallback_command' => '" . $fallback . "',\n";
echo "  'URL' => '" . $domain . "',\n";
echo "];\n\n";
echo "?>\n";

?>
