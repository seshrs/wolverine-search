<?php

// buildCommandMap.php

final class BuildCommandMap {
  private static $commandMap = []; // dict<Command, Filepath>

  public static function execute() {
    echo "\nBuilding commandMap.json...\n";

    require_once(__DIR__ . '/../search/__definitions__/ICommandController.php');
    require_once(__DIR__ . '/../search/__definitions__/Result.php');

    self::$commandMap = [];
    $outputFile = __DIR__ . '/../search/__build__/commandMap.json';
    $commandsDirectory = __DIR__ . '/../search';

    // Include all controller files
    $itemsQueue = new SplQueue();
    $itemsQueue->enqueue($commandsDirectory);
    while (!$itemsQueue->isEmpty()) {
      $item = $itemsQueue->dequeue();
      if (is_dir($item) && substr($item, 0, 2) !== '__') {
        // It's a directory with commands
        $scanned_directory = array_diff(scandir($item), array('..', '.'));
        foreach ( $scanned_directory as $content ) {
          $itemsQueue->enqueue($item . "/" . $content);
        }
      }
      else if (self::endsWith($item, '.command.php')) {
        $command_controller_name = basename($item, '.command.php');
        $get_command_names_method_executor = [$command_controller_name, 'getCommandNames'];
        require_once($item);
        $command_names = $get_command_names_method_executor();
        foreach ($command_names as $command_name) {
          if (array_key_exists($command_name, self::$commandMap)) {
            echo "ERROR: Duplicate declaration of command `$command_name`. Aborting.\n";
            exit(1);
          }
          self::$commandMap[$command_name] = $item;
        }
      }
    }

    echo "\nWriting to commandFileMap.json...\n";

    file_put_contents($outputFile, json_encode(self::$commandMap));

    echo "\nDone!\n\n";
  }

  /**
   * Determines if a given string has the test substring at its end.
   * 
   * Retrieved from: https://stackoverflow.com/questions/619610/whats-the-most-efficient-test-of-whether-a-php-string-ends-with-another-string
   * 
   * @param {string} The given string
   * @param {string} The substring to check if at the end of the given string
   * @return {bool} Whether the test substring occurs at the end of the given string.
   */
  private static function endsWith($string, $test) {
    $strlen = strlen($string);
    $testlen = strlen($test);
    if ($testlen > $strlen) return false;
    return substr_compare($string, $test, $strlen - $testlen, $testlen) === 0;
  }
}

BuildCommandMap::execute();

?>
