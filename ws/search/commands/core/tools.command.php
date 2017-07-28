<?php

/* @command gradescope
 * @description Visit Gradescope to view or submit assignments
 * @usage gradescope
 *
 * @command mfile
 * @description Visit Mfile to manage your files in your AFS space
 * @usage mfile
 *
 * @command mcommunity
 * @description Search for people or groups in UM's directory
 * @usage mcommunity [query]
 *
 * @command mc
 * @aliases mcommunity
 *
 * @command ecoach
 * @description Visit Ecoach
 * @usage ecoach
 */


register_command('gradescope', 'gradescope_process_query');
function gradescope_process_query($query) {
  return 'https://gradescope.com';
}


register_command('mfile', 'mfile_process_query');
function mfile_process_query($query) {
  return 'https://mfile.umich.edu';
}


register_command('mcommunity', 'mcommunity_process_query');
register_command('mc', 'mcommunity_process_query');
function mcommunity_process_query($query) {
  $mc_main_url = 'https://mcommunity.umich.edu';
  $mc_search_url = $mc_main_url . '/#search:';
  if (!$query || !strlen($query)) {
    return $mc_main_url;
  }
  return $mc_search_url . $query;
}


register_command('ecoach', 'ecoach_process_query');
function ecoach_process_query($query) {
  return 'https://ecoach.ai.umich.edu';
}

?>
	