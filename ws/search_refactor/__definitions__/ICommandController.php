<?php
/**
 * ICommandController.php
 * @author Sesh Sadasivam (sesh.sadasivam@gmail.com)
 * Last Modified: 2018-03-25
 */

/**
 * Implement this interface to create a new Command.
 * Don't forget to `make build` after adding a new command.
 */
interface iCommandController {
  /**
   * Returns a list of command keywords that this Command should respond to.
   * @return array<string>: List of command keywords representing this Command.
   */
  public function getCommandNames();

  /**
   * Given a query, returns a URL.
   * 
   * The query can be an empty string. The user will be redirected to the
   * URL returned by this function.
   * @param string: The user's query. Can be an empty string.
   * @return URL: A URL object
   */
  public function executeQuery(string $query);
}

?>