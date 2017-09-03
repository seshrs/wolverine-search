<?php

/* @command piazza
 * @description Visit your class' Piazza forum
 * @usage piazza [class]
 *
 * @command p
 * @aliases piazza
 */


register_command('piazza', 'piazza_process_query');
register_command('p', 'piazza_process_query');

global $PIAZZA_CLASS_URLS;
$PIAZZA_CLASS_URLS = [
  "eecs" => [
    "280" => "j6xqzm6faq53n7", // FA 17
    "485" => "iwqks3pw4ek1is", // WN 17
    "442" => "ixiaqagoao37oi", // WN 17
    "486" => "ixja7lvhrv57eo", // WN 17
  ],
  "engr" => [
    
  ],
];


function piazza_process_query($query) {
  $piazza_main_url = 'http://piazza.com';
  $piazza_class_url = 'http://piazza.com/class/';
  
  if (!$query || !strlen($query)) {
    return $piazza_main_url;
  }
  
  global $PIAZZA_CLASS_URLS;
  $query_terms = explode(' ', strtolower($query));
  
  if ( count($query_terms) === 2 ) {
    $department = $query_terms[0];
    $class = $query_terms[1];
  }
  else if ( count($query_terms) === 1 ) {
    $department = preg_replace('/[0-9]+/', '', $query_terms[0]);
    $class = preg_replace('/[a-z]+/', '', $query_terms[0]);
    
  }
  else {
    return $piazza_main_url;
  }
  
  if ( !array_key_exists($department, $PIAZZA_CLASS_URLS) ) {
    return $piazza_main_url;
  }
  if ( !array_key_exists($class, $PIAZZA_CLASS_URLS[$department]) ) {
    return $piazza_main_url;
  }
  return $piazza_class_url . $PIAZZA_CLASS_URLS[$department][$class];
}

?>