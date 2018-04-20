<?php

final class Piazza implements ICommandController {
  public static function getCommandNames() {
    return [
      'p',
      'piazza',
    ];
  }

  private static $PIAZZA_CLASS_URLS = [
    "eecs" => [
      "280" => "j6xqzm6faq53n7", // FA 17
      "485" => "iwqks3pw4ek1is", // WN 17
      "442" => "ixiaqagoao37oi", // WN 17
      "486" => "ixja7lvhrv57eo", // WN 17
    ],
    "engr" => [
      
    ],
  ];

  private static $piazzaMainURL = 'https://piazza.com';
  private static $piazzaClassURL = 'https://piazza.com/class/';

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
    
    if (!array_key_exists($department, self::$PIAZZA_CLASS_URLS)) {
      return self::defaultResult();
    }
    if (!array_key_exists($class, self::$PIAZZA_CLASS_URLS[$department])) {
      return self::defaultResult();
    }
    
    return (new Result)
      ->setURL(
        self::$piazzaClassURL . self::$PIAZZA_CLASS_URLS[$department][$class]
      );
  }

  private static function defaultResult() {
    return (new Result)
      ->setURL(self::$piazzaMainURL);
  }
}

?>