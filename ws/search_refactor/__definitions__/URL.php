<?php
/**
 * URL.php
 * @author Sesh Sadasivam (sesh.sadasivam@gmail.com)
 * Last Modified: 2018-03-25
 */


/**
 * A class representing a URL
 * Also includes utility functions and special constructors to work with
 * URLs.
 */
class URL {
  private $url;

  /**
   * Constructs a URL object with the given URL.
   * Throws a URL Exception if the URL is invalid.
   * @param string: The URL that is represented by this URL object.
   * @return URL: A URL object representing the given URL.
   */
  public function __construct(string $url) {
    $this->url = $url;
    $this->validateURL();
  }

  /**
   * @return string: The URL represented by this URL object.
   */
  public function getURL() {
    return $this->url;
  }

  private function validateURL() {
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
      throw new URLException($this->url, 'Invalid URL');
    }
  }
}

class URLException extends Exception {
  /**
   * string: The URL causing the exception to be thrown.
   */
  public $url;

  function __construct(string $url, string $message = NULL) {
    parent::__construct($message ?: 'URL Exception');
    $this->url = $url;
  }
}

?>