<?php

final class WolverineSearchList implements ICommandController {
  public static function getCommandNames() {
    return [
      'list',
      'commands',
    ];
  }

  public static function executeQuery($query) {
    return (new Result)
      ->setURL(Sitevars::DOMAIN_NAME . '/list');
  }
}

?>