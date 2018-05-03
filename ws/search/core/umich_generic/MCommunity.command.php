<?php

require_once(__DIR__ . '/../general_search/GeneralSearchBaseController.php');

final class MCommunity
  extends GeneralSearchBaseController
  implements ICommandController {
    public static function getCommandNames() {
      return [
        'mc',
        'mcommunity',
      ];
    }
    
    protected static function getMainURL() {
      return "https://mcommunity.umich.edu";
    }
    
    protected static function getSearchURL() {
      return "https://mcommunity.umich.edu/#search:";
    }
}

?>