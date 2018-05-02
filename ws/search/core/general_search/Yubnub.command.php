<?php

require_once(__DIR__ . '/GeneralSearchBaseController.php');

final class Yubnub
  extends GeneralSearchBaseController
  implements ICommandController {
    public static function getCommandNames() {
      return [
        'yubnub',
        'yn',
      ];
    }
    
    protected static function getMainURL() {
      return "http://yubnub.org";
    }
    
    protected static function getSearchURL() {
      return "http://yubnub.org/parser/parse?command=";
    }
}

?>