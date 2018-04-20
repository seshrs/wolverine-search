<?php

final class WolverineAccessStudentBusiness implements ICommandController {
  public static function getCommandNames() {
    return [
      'sb',
    ];
  }

  public static function executeQuery($query) {
    return (new Result)
      ->setCommand('wa')
      ->setQuery('sb');
  }
}

?>