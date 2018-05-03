<?php

final class Gradescope implements ICommandController {
  public static function getCommandNames() {
    return [
      'gradescope',
    ];
  }

  private static $GRADESCOPE_CLASS_URLS = [
    "eecs" => [
      "376" => "12299", // FA 17
      "490" => "10420", // FA 17
      "388" => "5595", // WN 17
    ],
    "engr" => [
      
    ],
  ];

  private static $gradescopeMainURL = 'https://gradescope.com';
  private static $gradescopeClassURL = 'https://gradescope.com/courses/';

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
    
    if (!array_key_exists($department, self::$GRADESCOPE_CLASS_URLS)) {
      return self::defaultResult();
    }
    if (!array_key_exists($class, self::$GRADESCOPE_CLASS_URLS[$department])) {
      return self::defaultResult();
    }
    
    return (new Result)
      ->setURL(
        self::$piazzaClassURL . self::$GRADESCOPE_CLASS_URLS[$department][$class]
      );
  }

  private static function defaultResult() {
    return (new Result)
      ->setURL(self::$gradescopeMainURL);
  }
}

?>