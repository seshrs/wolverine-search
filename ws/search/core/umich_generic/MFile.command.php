<?php

final class MFile implements ICommandController {
  public static function getCommandNames() {
    return [
      'mfile',
    ];
  }

  public static function executeQuery($query) {
    return (new Result)
      ->setURL('https://mfile.umich.edu');
  }
}

?>