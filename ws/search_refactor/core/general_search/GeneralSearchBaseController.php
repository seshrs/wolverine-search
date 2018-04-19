<?php

class GeneralSearchBaseController {
  /**
   * Returns the homepage of the Search Engine.
   * Override this method in derived classes.
   * 
   * @return {string} The URL of the homepage of the search engine.
   */
  protected static function getMainURL() {
    return "https://www.google.com";
  }
  
  /**
   * Returns the search URL of the Search Engine so that the query can be
   * appended to the end.
   * Override this method in derived classes.
   * 
   * @return {string} The URL of the homepage of the search engine.
   */
  protected static function getSearchURL() {
    return "https://www.google.com/search?q=";
  }

  public static function executeQuery($query) {
    if (!$query || !strlen($query)) {
      return (new Result)
        ->setURL(static::getMainURL());
    }
    
    return (new Result)
      ->setURL(static::getSearchURL() . $query);
  }
}

?>