<?php

final class ECoach implements ICommandController {
  public static function getCommandNames() {
    return [
      'ecoach',
    ];
  }

  public static function executeQuery($query) {
    return (new Result)
      ->setURL('https://ecoach.ai.umich.edu');
  }
}

?>