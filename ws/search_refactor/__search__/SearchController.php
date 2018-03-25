<?php
/**
 * SearchController.php
 * @author Sesh Sadasivam (sesh.sadasivam@gmail.com)
 * Last Modified: 2018-03-25
 */

class SearchController {
  public static function search() {
    $command_map = self::fetchCommandMap();
    $request = self::parseRequest($command_map);
    $redirect_url = $request['command_executor']();
    self::redirectUser($redirect_url, $request);
    self::endConnection();
    self::logRequest($request, $request);
  }

  private static function fetchCommandMap() {
    $command_map_json_filepath = __DIR__ . '/../__build__/commandMap.json';
    $command_map_JSON = file_get_contents($command_file_map_JSON);
    return json_decode($commandFileMapJSON);
  }

  private static function parseRequest($command_map) {
    require_once(__DIR__ . '/../../__util__/Sitevars.php');

    // 1. Fetch and parse the query

    // 1.1. Get the raw query
    $post = isset($_GET['post']) ? $_GET['post'] : null;
    if ($post && $post == '1') {
      $raw_query = isset($_POST['q']) ? $_POST['q'] : '';
    }
    else {
      $raw_query = isset($_GET['q']) ? $_GET['q'] : '';
    }
    // 1.2. Parse the command and rest of the query
    $query_terms = explode(' ', strtolower($query));
    $command = array_shift($query_terms);
    $query = implode(' ', $query_terms);

    // 2. Retrieve the fallback command
    $fallback_command = isset($_GET['fallback']) ? $_GET['fallback'] : null;
    if (!$fallback_command || !property_exists($command_map, $fallback_command)) {
      $fallback_command = Sitevars::FALLBACK_COMMAND;
    }
    $fallback_used = false;

    // 3. Check whether the command exists. If not, use fallback.
    if (!property_exists($command_map, $command)) {
      $command = $fallback_command;
      $query = $raw_query;
      $fallback_used = true;
    }

    // 4. Retrieve the command's executeQuery function
    $command_controller_filepath = $command_map->{$command};
    require_once($command_controller_filepath);
    $command_controller_name = basename($command_controller_filepath, '.command.php');
    $command_executor = [$command_controller_name, 'executeQuery'];

    // 5. Check for debug mode
    $debug = isset($_GET['debug']) ? ($_GET['debug'] == '1') : false;

    return [
      'debug' => $debug,
      'command' => $command,
      'command_executor' => $command_executor,
      'fallback_used' => $fallback_used,
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
    // TODO: Two out of these three are not required. :P
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