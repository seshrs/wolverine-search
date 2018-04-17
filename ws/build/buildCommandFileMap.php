<?php

// buildCommandFileMap.php

echo "\nBuilding commandFileMap...\n";

require_once(__DIR__ . '/../search/helpers.php');
require_once(__DIR__ . '/../search/registerCommands.php');

$commandFileMap = []; // Map<Command, Filepath>
$outputFile = __DIR__ . '/../data/commandFileMap.json';
$commandsDirectory = __DIR__ . '/../search/commands';

// Include all controller files
$itemsQueue = new SplQueue();
$itemsQueue->enqueue($commandsDirectory);
while ( !$itemsQueue->isEmpty() ) {
  $item = $itemsQueue->dequeue();
  if ( is_dir($item) ) {
    // It's a directory
    $scanned_directory = array_diff(scandir($item), array('..', '.'));
    foreach ( $scanned_directory as $content ) {
      $itemsQueue->enqueue($item . "/" . $content);
    }
  }
  else if ( $Helpers->endsWith($item, '.command.php') ) {
    $_COMMANDS = [];
    require_once($item);
    foreach ( array_keys($_COMMANDS) as $command ) {
      if (array_key_exists($command, $commandFileMap)) {
        echo "ERROR: Duplicate definition of command `$command`. Aborting.\n";
        exit(1);
      }
      $commandFileMap[$command] = $item;
    }
  }
}

echo "\nWriting to commandFileMap.json...\n";

file_put_contents($outputFile, json_encode($commandFileMap));

echo "Done!\n\n";

?>
