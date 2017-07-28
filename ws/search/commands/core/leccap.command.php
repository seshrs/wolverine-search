<?php

global $LECCAP_URLS;
$LECCAP_URLS = [
  'eecs280' => 'https://leccap.engin.umich.edu/leccap/site/qhchm4ppg4ujwbovts8',
];

register_command('leccap', 'leccap_process_query');
function leccap_process_query($query) {
  global $LECCAP_URLS;
  $leccap_url = "https://leccap.engin.umich.edu/leccap/";
  
  $query = trim($query);
  if ( !$query || !strlen($query) ) {
    return $leccap_url;
  }
  
  if ( array_key_exists($query, $LECCAP_URLS) ) {
    return $LECCAP_URLS[$query];
  }
  return $leccap_url;
}

?>