<?php

final class WolverineAccess implements ICommandController {
  public static function getCommandNames() {
    return [
      'wa',
      'wolverineaccess',
      'wolverine_access',
      'wolverine-access',
      'wolverine',
    ];
  }

  private static $WA_URLS = [
    'main' => 'https://wolverineaccess.umich.edu',
    'student' => 'https://csprod.dsc.umich.edu/services/student',
    'employee' => 'https://hcmprod.dsc.umich.edu/services/employee/',
    'faculty' => 'https://csprod.dsc.umich.edu/services/faculty/',
  ];

  public static function executeQuery($query) {
    if (!$query || !strlen($query)) {
      return self::defaultResult();
    }
    
    // Extract first term from query
    $query_terms = explode(' ', strtolower($query));
    $option = (count($query_terms >= 1)) ? $query_terms[0] : 'main';
    // Since we support "wolverine access" as a keyword:
    if (count($query_terms == 2) && $query_terms[0] === 'access') {
      $option = $query_terms[1];
    }

    // Aliases
    switch ($option) {
      case 'sb':
        $option = 'student';
        break;
      
      case 'eb':
        $option = 'employee';
        break;
    }

    // Final check
    if (!array_key_exists($option, self::$WA_URLS)) {
      $option = 'main';
    }
    
    return (new Result)
      ->setURL(self::$WA_URLS[$option]);
  }
}

?>