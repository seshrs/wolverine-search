<?php

require_once(__DIR__ . '/GeneralSearchBaseController.php');

/**
 * This command is included for the sake of documentation.
 * The SearchController optimizes Results that delegate calls to this command
 * and processes them in the current request cycle.
 */
final class WolverineSearch
  extends GeneralSearchBaseController
  implements ICommandController {
    public static function getCommandNames() {
      return [
        'ws',
      ];
    }
    
    protected static function getMainURL() {
      return Sitevars::DOMAIN_NAME;
    }
    
    protected static function getSearchURL() {
      return Sitevars::DOMAIN_NAME . "/search?q=";
    }
}

?>
