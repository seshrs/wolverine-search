<?php

/* @command canvas
 * @description Visit your class' Canvas site
 * @usage canvas [class]
 *
 * @command c
 * @aliases canvas
 */


register_command('canvas', 'canvas_process_query');
register_command('c', 'canvas_process_query');

global $CANVAS_CLASS_URLS;
$CANVAS_CLASS_URLS = [
  "eecs" => [
    "280" => "126498",
    "485" => "117266",
  ],
  "engr" => [
    
  ],
];


function canvas_process_query($query) {
  $canvas_main_url = 'https://umich.instructure.com/';
  $canvas_class_url = 'https://umich.instructure.com/courses/';
  
  if (!$query || !strlen($query)) {
    return $canvas_main_url;
  }
  
  global $CANVAS_CLASS_URLS;
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
    return $canvas_main_url;
  }
  
  if ( !array_key_exists($department, $CANVAS_CLASS_URLS) ) {
    return $canvas_main_url;
  }
  if ( !array_key_exists($class, $CANVAS_CLASS_URLS[$department]) ) {
    return $canvas_main_url;
  }
  return $canvas_class_url . $CANVAS_CLASS_URLS[$department][$class];
}

?>
