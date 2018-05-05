<?php

final class WolverineSearchAbout implements ICommandController {
  public static function getCommandNames() {
    return [
      'about',
      'readme',
    ];
  }

  public static function executeQuery($query) {
    return (new Result)
      ->setURL(Sitevars::DOMAIN_NAME . '/about');
  }
}

?>