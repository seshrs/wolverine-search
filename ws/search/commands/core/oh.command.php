<?php

global $OH_URLS;
$OH_URLS = [
  'eecs280' => 'https://lobster.eecs.umich.edu/queue',
  'eecs485' => 'https://lobster.eecs.umich.edu/queue',
  'engr101' => 'https://lobster.eecs.umich.edu/queue',
  'engr151' => 'https://lobster.eecs.umich.edu/queue',
  'eecs281' => 'https://oh.eecs.umich.edu/courses/eecs281',
  'eecs398' => 'https://oh.eecs.umich.edu/courses/eecs398',
  'eecs482' => 'https://oh.eecs.umich.edu/courses/eecs482',
  'eecs484' => 'https://oh.eecs.umich.edu/courses/eecs484',
  'eecs489' => 'https://oh.eecs.umich.edu/courses/eecs486',
  'eecs498' => 'https://oh.eecs.umich.edu/courses/eecs498',
];

register_command('oh', 'oh_process_query');
function oh_process_query($query) {
  global $OH_URLS;
  $main_oh_url = "https://oh.eecs.umich.edu/";
  
  $query = trim($query);
  if ( !$query || !strlen($query) ) {
    return $main_oh_url;
  }
  
  if ( array_key_exists($query, $OH_URLS) ) {
    return $OH_URLS[$query];
  }
  return $main_oh_url;
}

?>