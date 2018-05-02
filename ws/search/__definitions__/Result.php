<?php
/**
 * Result.php
 * @author Sesh Sadasivam (sesh.sadasivam@gmail.com)
 * Last Modified: 2018-04-08
 */

/**
 * A class representing a Query Result.
 */
class Result {
  private $url; // ?string
  private $command; // ?string
  private $query; // ?string

  const FALLBACK_COMMAND = '__fallback_command__';
  const ORIGINAL_QUERY = '__original_query__';

  /**
   * Constructs an empty (unresolved) Result object.
   * Use the setter functions to populate the object.
   * @return {Result} $this
   */
  public function __construct() {
    $this->url = null;
    $this->command = null;
    $this->query = null;
    return $this;
  }

  /**
   * Sets the result object to represent a redirection to the given URL.
   * @param {string} $url - The URL that the user should be redirected to.
   * @return {Result} $this
   * @throws {ResultException} if the URL is invalid.
   */
  public function setURL($url) {
    $this->command = null;
    $this->query = null;

    self::validateURLOrThrow($url);
    $this->url = $url;

    return $this;
  }

  /**
   * Sets the result object to represent a command-query pair with the given
   * command. If the command is not specified, the user's fallback is used.
   * 
   * If the query was not previously set, the original query made by the user
   * (including the command keyword) will be used.
   * 
   * @param {string} [$command = self::FALLBACK_COMMAND] - The delegate command
   * @return {Result} $this
   */
  public function setCommand($command = self::FALLBACK_COMMAND) {
    $this->url = null;
    $this->command = $command;
    if ($this->query === null) {
      $this->query = self::ORIGINAL_QUERY;
    }
    return $this;
  }

  /**
   * Sets the result object to represent a command-query pair with the given
   * query. If the query is not specified, the user's original query is used.
   * 
   * If the command was not previously set, the user's fallback command will
   * be used.
   * 
   * @param {string} [$query = self::ORIGINAL_QUERY] - The delegate query
   * @return {Result} $this
   */
  public function setQuery($query = self::ORIGINAL_QUERY) {
    $this->url = null;
    $this->query = $query;
    if ($this->command === null) {
      $this->command = self::FALLBACK_COMMAND;
    }
    return $this;
  }

  /**
   * Returns if the result object is invalid (represents an unresolved result).
   * @return {bool} Whether the result object is unresolved.
   */
  public function isResultUnresolved() {
    return
      $this->url === null && (
        $this->command === null || $this->query === null
      );
  }

  /**
   * Returns whether the result object represents a URL.
   * @return {bool} Whether the result object represents a URL.
   */
  public function isURL() {
    return !$this->isResultUnresolved() && $this->url !== null;
  }

  /**
   * Returns whether the result object represents a delegate command-query pair
   * @return {bool} whether the result object represents a command-query pair.
   */
  public function isCommandQueryPair() {
    return !$this->isResultUnresolved() && $this->command !== null;
  }

  /**
   * Returns the URL represented by this result object.
   * @return {string} The URL represented by the result object.
   * @throws {ResultException} if the result does not represent a URL.
   */
  public function getURL() {
    if (!$this->isURL()) {
      throw new ResultException('Result does not represent a URL');
    }
    return $this->url;
  }

  /**
   * Returns the delegate command represented by this result object.
   * @return {string} The delegate command represented by the result object.
   * @throws {ResultException} if the result is not a delegate command.
   */
  public function getCommand() {
    if (!$this->isCommandQueryPair()) {
      throw new ResultException(
        'Result does not represent a delegate command-query pair'
      );
    }
    return $this->command;
  }

  /**
   * Returns the delegate query represented by this result object.
   * @return {string} The delegate query represented by the result object.
   * @throws {ResultException} if the result is not a delegate command.
   */
  public function getQuery() {
    if (!$this->isCommandQueryPair()) {
      throw new ResultException(
        'Result does not represent a delegate command-query pair'
      );
    }
    return $this->query;
  }

  /**
   * @param {string} $url - The URL to be validated.
   * @throws {ResultException} if the given URL is invalid.
   */
  private static function validateURLOrThrow($url) {
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
      throw new ResultException('Invalid URL: ' . $url);
    }
  }
}

/**
 * A class representing Excpetions thrown by the Result class.
 */
class ResultException extends Exception {
  function __construct($message = null) {
    parent::__construct('ResultException: ' . $message);
  }
}

?>