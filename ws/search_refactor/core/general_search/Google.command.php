<?php

require_once(__DIR__ . '/GeneralSearchBaseController.php');

final class Google
  extends GeneralSearchBaseController
  implements ICommandController {
    public static function getCommandNames() {
      return [
        'google',
        'g'
      ];
    }
    
    protected static function getMainURL() {
      return "https://www.google.com";
    }
    
    protected static function getSearchURL() {
      return "https://www.google.com/search?q=";
    }
}

?>