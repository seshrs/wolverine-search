<?php

// analytics.php
// Exposes

require_once(__DIR__ . '/dbconfig.php');

//
// If no cookie has been set, then one of these may be true:
//   1. User is visiting for first time
//        In this case, set a cookie. The count is off-by-one, but that's okay.
//   2. User has disabled cookies.
//        This means that all setcookie requests go unheeded. It's difficult to
//        count this as a unique device, hence conservatively not counting it
//        at all.

class Analytics {
  const SEARCH_HIT = 'SearchHits';
  const LANDING_PAGE_HIT = 'LandingPageHits';
  const ABOUT_PAGE_HIT = 'AboutPageHits';
  const LIST_PAGE_HIT = 'ListPageHits';

  private static $DEVICE_ID = null;
  private static $CONNECTION = null;

  /**
   * This function must be called before HTTP headers are sent (before any
   * script output).
   */
  public static function createDeviceIDIfNeeded() {
    // The following Shutdown Functionality is from: https://stackoverflow.com/a/141026/5868796
    ob_end_clean();
    header("Connection: close");
    ignore_user_abort(true); // just to be safe
    ob_start();

    self::$DEVICE_ID = isset($_COOKIE['DeviceID']) ? $_COOKIE['DeviceID'] : null;
    if (!self::$DEVICE_ID || !self::checkDeviceIDIntegrity()) {
      self::createDeviceID();
    }
  }

  /**
   * This function must be called after all script output is complete since
   * this will terminate the connection with the client.
   */
  public static function endConnectionAndLogUserActivity($user_action) {
    self::endConnection();
    if (!self::$DEVICE_ID) {
      return;
    }
    
    switch ($user_action) {
      case self::SEARCH_HIT:
      case self::LANDING_PAGE_HIT:
      case self::ABOUT_PAGE_HIT:
      case self::LIST_PAGE_HIT:
        self::logUserActivity($user_action);
        break;
    }
  }

  /**
   * @param $init_request     => The request from the user.
   *                             If $resolved_request uses fallback, this param will be ignored.
   * @param $resolved_request => The final resolved request that generated a URL.
   *                             If $init_request was the resolved_request, this param must be null.
   */
  public static function endConnectionAndLogSearchRequest($init_request, $resolved_request) {
    self::endConnection();

    if ($resolved_request === null) {
      // Only the initial request needs to be logged.
      if ($init_request['fallback_used']) {
        self::logFallback($init_request, 'InitHits');
        self::logFallback($init_request, 'ResolvedHits');
      }
      else {
        self::logCommand($init_request, 'InitHits');
        self::logCommand($init_request, 'ResolvedHits');
      }
      self::logQuery($init_request, 'InitHits');
      self::logQuery($init_request, 'ResolvedHits');
    }
    else if ($resolved_request['fallback_used']) {
      // Ignore the initial request
      self::logFallback($resolved_request, 'ResolvedHits');
      self::logQuery($resolved_request, 'ResolvedHits');
    }
    else {
      // Log both initial and final requests.
      if ($init_request['fallback_used']) {
        self::logFallback($init_request, 'InitHits');
      }
      else {
        self::logCommand($init_request, 'InitHits');
      }
      self::logQuery($init_request, 'InitHits');

      self::logCommand($resolved_request, 'ResolvedHits');
      self::logQuery($resolved_request, 'ResolvedHits');
    }
    // Finally, log information about the device ID.
    self::logUserActivity(self::SEARCH_HIT);
  }

  /*
   * PRIVATE MEMBERS
   */
  private static function endConnection() {
    // fastcgi_finish_request();
    // The following Shutdown Functionality is from: https://stackoverflow.com/a/141026/5868796
    $size = ob_get_length();
    header("Content-Length: $size");
    ob_end_flush(); // Strange behaviour, will not work
    flush(); // Unless both are called!
  }

  private static function connectToDB() {
    self::$CONNECTION = mysqli_connect(DBConfig::URL, DBConfig::USERNAME, DBConfig::PASSWORD, DBConfig::NAME);
  }

  private static function checkDeviceIDIntegrity() {
    if (!self::$CONNECTION) {
      self::connectToDB();
      if (!self::$CONNECTION) {
        self::logError('SQL Error: Could not connect to SQL database while checking device ID integrity');
        return;
      }
    }

    $integrity_ok = true;

    $stmt = mysqli_prepare(self::$CONNECTION, "SELECT DeviceID FROM UniqueVisitorDevices WHERE DeviceID=?");
    mysqli_stmt_bind_param($stmt, 's', self::$DEVICE_ID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $integrity_ok = (mysqli_stmt_num_rows($stmt) !== 0);
    mysqli_stmt_close($stmt);
    
    return $integrity_ok;
  }

  private static function createDeviceID() {
    if (!self::$CONNECTION) {
      self::connectToDB();
      if (!self::$CONNECTION) {
        self::logError('SQL Error: Could not connect to SQL database while creating device ID');
        return;
      }
    }

    $num_rows_inserted = 1;
    $timeout_counter = 5;
    
    do {
      if (!$timeout_counter) {
        return;
      }
      $timeout_counter--;

      $random_device_id = uniqid();

      $query = "INSERT INTO UniqueVisitorDevices(`DeviceID`) VALUES(?)";
      $stmt = mysqli_prepare(self::$CONNECTION, $query);
      mysqli_stmt_bind_param($stmt, "s", $random_device_id);
      
      $num_rows_inserted = mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
      // Check if executed successfully. If not, repeat at most 5 times.
      // (It inserts 0 rows if the DeviceID was successfully created or if
      // random device ID was assigned to some other device.)
      if ($num_rows_inserted === 0 && $timeout_counter === 4) {
        // Random device ID collission
        $num_rows_inserted = 1;
      }
    } while (!$num_rows_inserted);

    $expiry_time = time() + 60*60*24*365*2; // 2 years
    setcookie('DeviceID', $random_device_id, $expiry_time, '/');
  }


  private static function logUserActivity($user_action) {
    if (!self::$CONNECTION) {
      self::connectToDB();
      if (!self::$CONNECTION) {
        self::logError('SQL Error: Could not connect to SQL database while logging user activity: ' . $user_action);
        return;
      }
    }

    $query = "UPDATE UniqueVisitorDevices SET `" . $user_action . "` = `" . $user_action . "` + 1 WHERE DeviceID = ?";
    $stmt = mysqli_prepare(self::$CONNECTION, $query);
    mysqli_stmt_bind_param($stmt, "s", self::$DEVICE_ID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }

  /**
   * Future refactor task
   * TODO: The following three log functions are very similar.
   *       Need to move common logic to a common function.
   */

  private static function logCommand($request, $hits_column) {
    if (!self::$CONNECTION) {
      self::connectToDB();
      if (!self::$CONNECTION) {
        self::logError('SQL Error: Could not connect to SQL database while logging serach command');
        return;
      }
    }

    $init_hits = $hits_column === 'InitHits' ? 1 : 0;
    $resolved_hits = 1 - $init_hits;
    $query = "INSERT INTO CommandLog(Command, InitHits, ResolvedHits) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE `" . $hits_column . "` = `" . $hits_column . "` + 1";
    $stmt = mysqli_prepare(self::$CONNECTION, $query) or die("Could not prepare");
    mysqli_stmt_bind_param($stmt, "sdd", $request['command'], $init_hits, $resolved_hits) or die("Could bind params");
    mysqli_stmt_execute($stmt) or die("Could not execute");
    mysqli_stmt_close($stmt);
  }

  private static function logQuery($request, $hits_column) {
    if (!self::$CONNECTION) {
      self::connectToDB();
      if (!self::$CONNECTION) {
        self::logError('SQL Error: Could not connect to SQL database while logging raw serach query');
        return;
      }
    }

    $init_hits = $hits_column === 'InitHits' ? 1 : 0;
    $resolved_hits = 1 - $init_hits;
    $query = "INSERT INTO QueryLog(Query, InitHits, ResolvedHits) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE `" . $hits_column . "` = `" . $hits_column . "` + 1";
    $stmt = mysqli_prepare(self::$CONNECTION, $query);
    mysqli_stmt_bind_param($stmt, "sdd", $request['raw_query'], $init_hits, $resolved_hits);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }

  private static function logFallback($request, $hits_column) {
    if (!self::$CONNECTION) {
      self::connectToDB();
      if (!self::$CONNECTION) {
        self::logError('SQL Error: Could not connect to SQL database while logging fallback command');
        return;
      }
    }

    $init_hits = $hits_column === 'InitHits' ? 1 : 0;
    $resolved_hits = 1 - $init_hits;
    $query = "INSERT INTO FallbackLog(Command, InitHits, ResolvedHits) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE `" . $hits_column . "` = `" . $hits_column . "` + 1";
    $stmt = mysqli_prepare(self::$CONNECTION, $query);
    mysqli_stmt_bind_param($stmt, "sdd", $request['command'], $init_hits, $resolved_hits);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }

  private static function logError($message) {
    $error_log_path = __DIR__ . '/logs/analyticsErrorLog.log';
    file_put_contents(
      $error_log_path,
      date("Y-m-j G:i:s") . '  :  ' . $message,
      FILE_APPEND
    );
  }
}

?>