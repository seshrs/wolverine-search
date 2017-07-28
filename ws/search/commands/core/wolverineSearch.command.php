<?php

/* @command help
 * @description Learn more about using and installing Wolverine Search
 * @usage help
 *
 * @command list
 * @description View a list of all commands supported by Wolverine Search
 * @usage list [query]
 *
 */

register_command('help', 'help_process_query');
function help_process_query($query) {
  global $_SITE;
  return $_SITE['URL'] . '/about';
}


register_command('list', 'list_process_query');
function list_process_query($query) {
  global $_SITE;
  $filterParam = '';
  if ( $query && strlen($query) ) {
    $filterParam = '?q=' + $query;
  }
  return $_SITE['URL'] . '/list' . $filterParam;
}

?>
