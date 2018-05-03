<?php

// create_sitevars_v2.php
// Create Sitevars.php
// REQUIRES command-line arguments:
//   $domain (string)
//   $name (string)
//   $fallback (string)

if ( count($argv) !== 4 ) {
  echo "Incorrect arguments for creating Sitevars.php\n" ;
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

echo "<?php\n";

?>

final class Sitevars {
  const FALLBACK_COMMAND = "<?php echo $fallback; ?>";
  const SITE_NAME = "<?php echo $name; ?>";
  const DOMAIN_NAME = "<?php echo $domain; ?>";
}

<?php

echo "?>";

?>
