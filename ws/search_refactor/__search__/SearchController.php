<?php
/**
 * SearchController.php
 * @author Sesh Sadasivam (sesh.sadasivam@gmail.com)
 * Last Modified: 2018-03-25
 */

class SearchController {
  /**
   * This is the entry point for the search controller. It parses the request
   * from GET/POST parameters (or from the custom query), sends the redirection
   * header and logs the request.
   * 
   * @param {string} [$custom_request = null] - An optional custom delegate query
   * @return {void}
   */
  public static function search($custom_request = null) {
    $command_map = self::fetchCommandMap();
    $request = self::parseRequest($command_map, $request);
    $redirect_url = $request['command_executor']();
    self::redirectUser($redirect_url, $request);
    self::endConnection();
    self::logRequest($request, $request);
  }

  /**
   * Fetches the pre-built command-metadata map.
   * 
   * @return {dict<string, string>}
   */
  private static function fetchCommandMap() {
    $command_map_json_filepath = __DIR__ . '/../__build__/commandMap.json';
    $command_map_JSON = file_get_contents($command_file_map_JSON);
    return json_decode($commandFileMapJSON);
  }

  private static function parseRequest($command_map, $custom_request = null) {
    require_once(__DIR__ . '/../../__util__/Sitevars.php');

    if ($custom_request && strlen($custom_request)) {
      // 1.1 If a custom request has been provided, validate and use it.
      $raw_query = $custom_request;
    }
    else {
      // 1.2. Get the raw query from the HTTP Request
      $post = isset($_GET['post']) ? $_GET['post'] : null;
      if ($post && $post == '1') {
        $raw_query = isset($_POST['q']) ? $_POST['q'] : '';
      }
      else {
        $raw_query = isset($_GET['q']) ? $_GET['q'] : '';
      }
    }

    // 2. Parse the command and rest of the query.
    $query_terms = explode(' ', strtolower($query));
    $command = array_shift($query_terms);
    $query = implode(' ', $query_terms);

    // 3. Retrieve the fallback command
    $fallback_command = isset($_GET['fallback']) ? $_GET['fallback'] : null;
    if (!$fallback_command || !property_exists($command_map, $fallback_command)) {
      $fallback_command = Sitevars::FALLBACK_COMMAND;
    }
    $fallback_used = false;

    // 4. Check whether the command exists. If not, use fallback.
    if (!property_exists($command_map, $command)) {
      $command = $fallback_command;
      $query = $raw_query;
      $fallback_used = true;
    }

    // 5. Retrieve the command's executeQuery function
    $command_controller_filepath = $command_map->{$command};
    require_once(__DIR__ . '/../__definitions__/ICommandController.php');
    require_once(__DIR__ . '/../__definitions__/Result.php');
    require_once($command_controller_filepath);
    $command_controller_name = basename($command_controller_filepath, '.command.php');
    $command_executor = [$command_controller_name, 'executeQuery'];

    // 6. Check for debug mode
    $debug = isset($_GET['debug']) ? ($_GET['debug'] == '1') : false;

    return [
      'debug' => $debug,
      'command' => $command,
      'command_executor' => $command_executor,
      'fallback_used' => $fallback_used,
      'raw_query' => $raw_query,
      'query' => $query
    ];
  }

  private static function redirectUser($redirect_url, $request) {
    if ($request['debug']) {
      return self::printDebugOutput($redirect_url, $request);
    }
    header('Location: ' . $redirect_url->getURL());
  }

  private static function printDebugOutput($redirect_url, $request) {
    $debug_info = [
      'request' => $request,
      'result' => $redirect_url->getURL()
    ];
    header('Content-type: application/json');
    echo json_encode($debug_info);
  }

  private static function endConnection() {
    header('Connection: close');
    header('Content-Length: ' . ob_get_length());
    // TODO: Two out of these three are supposedly not required.
    ob_end_flush();
    ob_flush();
    flush();
  }

  private static function logRequest($request, $request) {
    // TODO: Implement the logging
  }
}

SearchController::search();

?>