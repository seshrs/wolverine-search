<?php

/** 
  * EECS 280
  * This is a Default Command because it falls back to Wolverine Search.
  * Read the GitHub Documentation for more information.
  */

final class EECS280 implements ICommandController {
  public static function getCommandNames() {
    return [
      'eecs280',
    ];
  }

  public static function executeQuery($query) {
    if (!$query || !strlen($query)) {
      return self::defaultResult();
    }
    
    $query_terms = explode(' ', strtolower($query));
    if (count($query_terms) === 0) {
      return self::defaultResult();
    }

    $eecs280_command = $query_terms[0];

    /**
     * Command Aliases
     */
    switch ($eecs280_command) {
      case 'ag':
      case 'autograder':
        return (new Result)
          ->setCommand('ag')
          ->setQuery('eecs280');
      
      case 'oh':
      case 'office-hours':
      case 'office_hours':
        return (new Result)
          ->setCommand('oh')
          ->setQuery('eecs280');

      case 'p':
      case 'piazza':
        return (new Result)
          ->setCommand('piazza')
          ->setQuery('eecs280');

      case 'canvas':
      case 'c':
        return (new Result)
          ->setCommand('canvas')
          ->setQuery('eecs280');
      
      case 'leccap':
      case 'lecture-recording':
      case 'lecture-recordings':
      case 'lecture_recording':
      case 'lecture_recordings':
      case 'lectures':
      case 'recordings':
      case 'recording':
        return (new Result)
          ->setCommand('leccap')
          ->setQuery('eecs280');
    }

    /**
     * Fetch other links
     */
    $eecs280_links_json = file_get_contents("https://eecs280.org/data/links.json");
    $eecs280_links = json_decode($eecs280_links_json, true);
    $eecs280_links = $eecs280_links["links"];
    switch ($eecs280_command) {
      case 'drive':
      case 'gdrive':
      case 'google-drive':
      case 'google_drive':
      case 'google':
      case 'folder':
      case 'public':
      case 'files':
        if (!array_key_exists('gDrive', $eecs280_links)) {
          return self::defaultResult();
        }
        return (new Result)
          ->setURL($eecs280_links['gDrive']);
      
      case 'calendar':
        if (!array_key_exists('calendar', $eecs280_links)) {
          return self::defaultResult();
        }
        return (new Result)
          ->setURL($eecs280_links['calendar']);
      
      case 'schedule':
      case 'schedule-of-topics':
      case 'schedule_of_topics':
        if (!array_key_exists('scheduleOfTopics', $eecs280_links)) {
          return self::defaultResult();
        }
        return (new Result)
          ->setURL($eecs280_links['scheduleOfTopics']);
    }

    return (new Result)
      ->setCommand('ws')
      ->setQuery(Result::ORIGINAL_QUERY);
  }

  private static function defaultResult() {
    return (new Result)
      ->setURL('https://eecs280.org');
  }
}

?>