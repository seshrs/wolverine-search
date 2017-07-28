<?php

/* @command g
 * @description Search Google
 * @usage g [query]
 *
 * @command bing
 * @description Search Bing
 * @usage bing [query]
 *
 * @command yn
 * @description Execute a Yubnub command
 * @usage yn [query]
 *
 * @command yubnub
 * @aliases yn
 */


register_command('g', 'google_process_query');
function google_process_query($query) {
  if (!$query || !strlen($query)) {
    return "https://www.google.com";
  }
  return "https://www.google.com/search?q=" . $query;
}


register_command('bing', 'bing_process_query');
function bing_process_query($query) {
  if (!$query || !strlen($query)) {
    return "https://www.bing.com";
  }
  return "http://www.bing.com/search?q=" . $query;
}


register_command('yn', 'yubnub_process_query');
register_command('yubnub', 'yubnub_process_query');
function yubnub_process_query($query) {
  if (!$query || !strlen($query)) {
    return "http://yubnub.org";
  }
  return "http://yubnub.org/parser/parse?command=" . $query;
}
