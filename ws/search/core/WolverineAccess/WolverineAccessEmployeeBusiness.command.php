<?php

final class WolverineAccessEmployeeBusiness implements ICommandController {
  public static function getCommandNames() {
    return [
      'eb',
      'employee',
    ];
  }

  public static function executeQuery($query) {
    return (new Result)
      ->setCommand('wa')
      ->setQuery('eb');
  }
}

?>