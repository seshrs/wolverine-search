<?php

final class LecCap implements ICommandController {
  public static function getCommandNames() {
    return [
      'leccap',
    ];
  }

  private static $LECCAP_CLASS_SITES = [
    "eecs" => [
      "280" => "qhchm4ppg4ujwbovts8",
    ],
    "engr" => [
      
    ],
  ];

  private static $leccapMainURL = 'https://leccap.engin.umich.edu/leccap/';
  private static $leccapClassURL = 'https://leccap.engin.umich.edu/leccap/site/';

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
    
    if (!array_key_exists($department, self::$LECCAP_CLASS_SITES)) {
      return self::defaultResult();
    }
    if (!array_key_exists($class, self::$LECCAP_CLASS_SITES[$department])) {
      return self::defaultResult();
    }
    
    return (new Result)
      ->setURL(
        self::$leccapClassURL . self::$LECCAP_CLASS_SITES[$department][$class]
      );
  }

  private static function defaultResult() {
    return (new Result)
      ->setURL(self::$leccapMainURL);
  }
}

?>