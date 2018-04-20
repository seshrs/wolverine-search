<?php

final class OfficeHours implements ICommandController {
  public static function getCommandNames() {
    return [
      'oh',
      'office-hours',
      'office_hours',
    ];
  }

  private static $OH_CLASS_SITES = [
    "eecs" => [
      "280" => "https://lobster.eecs.umich.edu/queue",
      "281" => "https://oh.eecs.umich.edu/courses/eecs281",
      "398" => "https://oh.eecs.umich.edu/courses/eecs398",
      "482" => "https://oh.eecs.umich.edu/courses/eecs482",
      "484" => "https://oh.eecs.umich.edu/courses/eecs484",
      "485" => "https://lobster.eecs.umich.edu/queue",
      "489" => "https://oh.eecs.umich.edu/courses/eecs489",
      "490" => "https://lobster.eecs.umich.edu/queue",
      "498" => "https://oh.eecs.umich.edu/courses/eecs498",
    ],
    "engr" => [
      "101" => "https://lobster.eecs.umich.edu/queue",
      "151" => "https://lobster.eecs.umich.edu/queue",
    ],
  ];

  private static $OHDefaultURL = 'https://oh.eecs.umich.edu/';

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
    
    if (!array_key_exists($department, self::$OH_CLASS_SITES)) {
      return self::defaultResult();
    }
    if (!array_key_exists($class, self::$OH_CLASS_SITES[$department])) {
      return self::defaultResult();
    }
    
    return (new Result)
      ->setURL(
        self::$OH_CLASS_SITES[$department][$class]
      );
  }

  private static function defaultResult() {
    return (new Result)
      ->setURL(self::$OHDefaultURL);
  }
}

?>