<?php

final class Canvas implements ICommandController {
  public static function getCommandNames() {
    return [
      'canvas',
      'c',
    ];
  }

  private static $CANVAS_CLASS_URLS = [
    "eecs" => [
      "280" => "126498",
      "485" => "117266",
    ],
    "engr" => [
      
    ],
  ];

  private static $canvasMainURL = 'https://umich.instructure.com/';
  private static $canvasClassURL = 'https://umich.instructure.com/courses/';

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
    
    if (!array_key_exists($department, self::$CANVAS_CLASS_URLS)) {
      return self::defaultResult();
    }
    if (!array_key_exists($class, self::$CANVAS_CLASS_URLS[$department])) {
      return self::defaultResult();
    }
    
    return (new Result)
      ->setURL(
        self::$canvasClassURL . self::$CANVAS_CLASS_URLS[$department][$class]
      );
  }

  private static function defaultResult() {
    return (new Result)
      ->setURL(self::$canvasMainURL);
  }
}

?>