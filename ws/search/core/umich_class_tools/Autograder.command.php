<?php

final class Autograder implements ICommandController {
  public static function getCommandNames() {
    return [
      'ag',
      'autograder',
    ];
  }

  private static $AG_CLASS_SITES = [
    "eecs" => [
      "280" => "https://autograder.io/web/course/2",
    ],
    "engr" => [
      "101" => "https://autograder.io/web/course/1",
    ],
  ];

  private static $AGDefaultURL = 'https://autograder.io';

  public static function executeQuery($query) {
    if (!$query || !strlen($query)) {
      return self::defaultResult();
    }
    
    $query_terms = explode(' ', strtolower($query));
    
    if (count($query_terms) === 2) {
      $department = $query_terms[0];
      $class = $query_terms[1];
    }
    else if (count($query_terms) === 1) {
      $department = preg_replace('/[0-9]+/', '', $query_terms[0]);
      $class = preg_replace('/[a-z]+/', '', $query_terms[0]);
    }
    else {
      return self::defaultResult();
    }
    
    if (!array_key_exists($department, self::$AG_CLASS_SITES)) {
      return self::defaultResult();
    }
    if (!array_key_exists($class, self::$AG_CLASS_SITES[$department])) {
      return self::defaultResult();
    }
    
    return (new Result)
      ->setURL(
        self::$AG_CLASS_SITES[$department][$class]
      );
  }

  private static function defaultResult() {
    return (new Result)
      ->setURL(self::$AGDefaultURL);
  }
}

?>