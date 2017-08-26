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
  public static $USER_ACTION = [
    'SEARCH' => 'SearchHits',
    'LANDING' => 'LandingPageHits',
    'ABOUT' => 'AboutPageHits',
    'LIST' => 'ListPageHits',
  ];

  public static function runAnalytics($userAction) {
    $deviceID = isset($_COOKIE['DeviceID']) ? $_COOKIE['DeviceID'] : null;
    if ( !in_array($userAction, self::$USER_ACTION) ) {
      return;
    }

    if ($deviceID) {
      // The user possibly has visited the site before, so update the count
      // There is no need to check if the deviceID is valid. The SQL statement
      // wouldn't execute. The device wouldn't be counted into the analytics,
      // but the conservative approach should be okay. (Not a lot of users
      // sabotage cookies.)
      self::logUserActivity($deviceID, $userAction);
    }
    else {
      self::createDeviceID();
    }
  }

  /*
   * PRIVATE MEMBERS
   */
  private static function createDeviceID() {
    global $DBCONFIG;

    $numRowsInserted = 1;
    $timeoutCounter = 5;

    $connection = mysqli_connect($DBCONFIG['URL'], $DBCONFIG['USERNAME'], $DBCONFIG['PASSWORD'], $DBCONFIG['NAME']);
    if (!$connection) {
      echo('<!-- Analytics: SQL Error: Could not connect to SQL database -->');
      return;
    }
    
    do {
      if (!$timeoutCounter) {
        return;
      }
      $timeoutCounter--;

      $randomDeviceID = uniqid();

      $query = "INSERT INTO UniqueVisitorDevices(`DeviceID`) VALUES(?)";
      $stmt = mysqli_prepare($connection, $query);
      mysqli_stmt_bind_param($stmt, "s", $randomDeviceID);
      
      $numRowsInserted = mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
      // Check if executed successfully. If not, repeat at most 5 times.
    } while (!$numRowsInserted);

    $expiryTime = time() + 60*60*24*365*2; // 2 years
    setcookie('DeviceID', $randomDeviceID, $expiryTime, '/');
  }


  private static function logUserActivity($deviceID, $userAction) {
    global $DBCONFIG;

    $connection = mysqli_connect($DBCONFIG['URL'], $DBCONFIG['USERNAME'], $DBCONFIG['PASSWORD'], $DBCONFIG['NAME']);
    if (!$connection) {
      echo('<!-- Analytics: SQL Error: Could not connect to SQL database -->');
      return;
    }

    $query = "UPDATE UniqueVisitorDevices SET `" . $userAction . "` = `" . $userAction . "` + 1 WHERE DeviceID = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "s", $deviceID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }
}

?>