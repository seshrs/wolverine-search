<?php

/* @command ag
 * @parent eecs280
 * @description Submit your projects and view your scores
 * @usage ag
 *
 * @command oh
 * @parent eecs280
 * @aliases oh eecs280
 * @description Add yourself to the EECS 280 Office Hours queue
 * @usage oh
 * 
 * @command piazza
 * @parent eecs280
 * @aliases p eecs280
 * @description Visit the EECS 280 forum to ask questions and get answers
 * @usage piazza
 * 
 * @command canvas
 * @parent eecs280
 * @aliases canvas eecs280
 * @description View the course site on Canvas
 * @usage canvas
 * 
 * @command leccap
 * @parent eecs280
 * @aliases leccap eecs280
 * @description View the lecture recordings
 * @usage leccap
 * 
 * @command calendar
 * @parent eecs280
 * @description View the course calendar
 * @usage calendar
 * 
 * @command schedule
 * @parent eecs280
 * @description View the course schedule
 * @usage schedule
 *
 */

global $EECS280_COMMANDS;

$EECS280_COMMANDS['ag'] = 'ag_eecs280_process_query';
function ag_eecs280_process_query($query, $fallback) {
  return "https://autograder.io/web/course/2";
}

$EECS280_COMMANDS['oh'] = 'oh_eecs280_process_query';
function oh_eecs280_process_query($query, $fallback) {
  global $Helpers;
  $query = trim($query);
  if ( $query && strlen($query) ) {
    return $Helpers-> executeCommandWithQuery('oh', $query, $fallback);
  }
  return $Helpers-> executeCommandWithQuery('oh', 'eecs280');
}

$EECS280_COMMANDS['piazza'] = 'piazza_eecs280_process_query';
function piazza_eecs280_process_query($query, $fallback) {
  global $Helpers;
  $query = trim($query);
  if ( $query && strlen($query) ) {
    return $Helpers-> executeCommandWithQuery('p', $query, $fallback);
  }
  return $Helpers-> executeCommandWithQuery('p', 'eecs280');
}

$EECS280_COMMANDS['canvas'] = 'canvas_eecs280_process_query';
function canvas_eecs280_process_query($query, $fallback) {
  global $Helpers;
  $query = trim($query);
  if ( $query && strlen($query) ) {
    return $Helpers-> executeCommandWithQuery('canvas', $query, $fallback);
  }
  return $Helpers-> executeCommandWithQuery('canvas', 'eecs280');
}

$EECS280_COMMANDS['leccap'] = 'leccap_eecs280_process_query';
function leccap_eecs280_process_query($query, $fallback) {
  global $Helpers;
  $query = trim($query);
  if ( $query && strlen($query) ) {
    return $Helpers-> executeCommandWithQuery('leccap', $query, $fallback);
  }
  return $Helpers-> executeCommandWithQuery('leccap', 'eecs280');
}


$EECS280_COMMANDS['calendar'] = 'calendar_eecs280_process_query';
function calendar_eecs280_process_query($query, $fallback) {
  return "https://calendar.google.com/calendar/embed?src=umich.edu_umcjit31hdi8tqtc6ger1i4e88%40group.calendar.google.com&ctz=America/New_York";
}

$EECS280_COMMANDS['schedule'] = 'schedule_eecs280_process_query';
function schedule_eecs280_process_query($query, $fallback) {
  return "https://docs.google.com/spreadsheets/d/1FIKh4Fqm4nSFY264H7R8nrfL9TdT5qWOOier04q0j_w/edit#gid=0";
}
