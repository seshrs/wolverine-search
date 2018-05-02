<?php

require_once(__DIR__ . '/GeneralSearchBaseController.php');

final class Bing
  extends GeneralSearchBaseController
  implements ICommandController {
    public static function getCommandNames() {
      return [
        'bing',
      ];
    }
    
    protected static function getMainURL() {
      return "https://www.bing.com";
    }
    
    protected static function getSearchURL() {
      return "https://www.bing.com/search?q=";
    }
}

?>