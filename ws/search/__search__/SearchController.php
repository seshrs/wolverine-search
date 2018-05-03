<?php
/**
 * SearchController.php
 * @author Sesh Sadasivam (sesh.sadasivam@gmail.com)
 * Last Modified: 2018-03-25
 */

require_once(__DIR__ . '/../../__util__/Sitevars.php');
require_once(__DIR__ . '/../__definitions__/ICommandController.php');
require_once(__DIR__ . '/../../scripts/Analytics.php');

class SearchController {
  /**
   * This is the entry point for the search controller. It parses the request
   * from GET/POST parameters (or from the custom query), sends the redirection
   * header and logs the request.
   * 
   * @param {string} [$custom_request = null] - An optional custom delegate query
   * @return {void}
   */
  public static function search($custom_request = null, $request_trace = []) {
    $command_map = self::fetchCommandMap();
    $request = self::parseRequest($command_map, $custom_request, $request_trace);
    // Check if the command is ws (to minimize request cycles)
    if ($request['command'] === 'ws') {
      array_push($request_trace, $request);
      return self::search($request['query'], $request_trace);
    }
    // Otherwise, call the executor
    $result = $request['command_executor']($request['query']);
    if ($result->isResultUnresolved()) {
      // Redirect to main site
      self::redirectUser(Sitevars::DOMAIN_NAME, $request, $request_trace);
    }
    else if ($result->isURL()) {
      self::redirectUser($result->getURL(), $request, $request_trace);
    }
    else {
      array_push($request_trace, $request);
      return self::search(
        implode(' ', [$result->getCommand(), $result->getQuery()]),
        $request_trace
      );
    }
    if (!$request['debug']) {
      self::endConnectionAndLogRequest($request, $request_trace);
    }
  }

  /**
   * Fetches the pre-built command-metadata map.
   * 
   * @return {dict<string, string>}
   */
  private static function fetchCommandMap() {
    $command_map_json_filepath = __DIR__ . '/../__build__/commandMap.json';
    $command_map_JSON = file_get_contents($command_map_json_filepath);
    return json_decode($command_map_JSON);
  }

  private static function parseRequest($command_map, $custom_request = null, &$request_trace = []) {
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
    $query_terms = explode(' ', strtolower($raw_query));
    $command = array_shift($query_terms);
    $query = implode(' ', $query_terms);

    // 2.1. Check if custom query requesting original query
    if ($query === Result::ORIGINAL_QUERY) {
      // Find request 0 and use its raw query
      for ($i = 0; $i < count($request_trace); $i++) {
        if ($request_trace[$i]['request_trace_id'] === 0) {
          $raw_query = $query = $request_trace[$i]['raw_query'];
          break;
        }
      }
    }

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

    // 4.1. Also check if custom request requires fallback command.
    if ($command === Result::FALLBACK_COMMAND) {
      $command = $fallback_command;
      $fallback_used = true;
    }

    // 5. Retrieve the command's executeQuery function
    $command_controller_filepath = $command_map->{$command};
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
      'raw_query' => trim($raw_query),
      'request_trace_id' => count($request_trace),
      'query' => trim($query),
    ];
  }

  private static function redirectUser($redirect_url, $request, &$request_trace = []) {
    if ($request['debug']) {
      return self::printDebugOutput($redirect_url, $request, $request_trace);
    }
    header('Location: ' . $redirect_url);
  }

  private static function printDebugOutput($redirect_url, $request, &$request_trace = []) {
    array_push($request_trace, $request);
    $debug_info = [
      'request_trace' => $request_trace,
      'result' => $redirect_url,
    ];
    header('Content-type: application/json');
    echo json_encode($debug_info, JSON_PRETTY_PRINT);
  }

  private static function endConnectionAndLogRequest($request, $request_trace) {
    $init_request = null;
    $resolved_request = $request;
    if (count($request_trace) < 1) {
      $init_request = $resolved_request;
      $resolved_request = null;
    }
    else {
      // Find the init request
      for ($i = 0; $i < count($request_trace); ++$i) {
        if ($request_trace[$i]['request_trace_id'] === 0) {
          $init_request = $request_trace[$i];
          break;
        }
      }
    }
    Analytics::endConnectionAndLogSearchRequest($init_request, $resolved_request);
  }
}

Analytics::createDeviceIDIfNeeded();
SearchController::search();

?>