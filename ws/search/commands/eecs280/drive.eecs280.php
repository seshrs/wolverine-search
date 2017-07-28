<?php

/* @command files
 * @parent eecs280
 * @description View the EECS 280 course files
 * @usage files
 *
 * @command projects
 * @parent eecs280
 * @description View the EECS 280 projects folder
 * @usage projects
 *
 * @command exams
 * @parent eecs280
 * @description View the EECS 280 exams folder
 * @usage exams
 *
 * @command lectures
 * @parent eecs280
 * @description View the EECS 280 lectures folder
 * @usage lectures
 *
 * @command labs
 * @parent eecs280
 * @description View the EECS 280 labs folder
 * @usage labs
 *
 */

global $EECS280_COMMANDS;

$EECS280_COMMANDS['files'] = 'files_eecs280_process_query';
function files_eecs280_process_query($query, $fallback) {
  return "https://drive.google.com/drive/folders/0B49xEM_rtERAVTQ2YktuX0FsWEk";
}

$EECS280_COMMANDS['projects'] = 'projects_eecs280_process_query';
function projects_eecs280_process_query($query, $fallback) {
  return "https://drive.google.com/drive/folders/0B49xEM_rtERAYlpJU2RweVlWREU";
}

$EECS280_COMMANDS['exams'] = 'exams_eecs280_process_query';
function exams_eecs280_process_query($query, $fallback) {
  return "https://drive.google.com/drive/folders/0B49xEM_rtERAYXVid3dPTUM3TjA";
}

$EECS280_COMMANDS['lectures'] = 'lectures_eecs280_process_query';
function lectures_eecs280_process_query($query, $fallback) {
  return "https://drive.google.com/drive/folders/0B49xEM_rtERAOWN1bTVreml1X28";
}

$EECS280_COMMANDS['labs'] = 'labs_eecs280_process_query';
function labs_eecs280_process_query($query, $fallback) {
  return "https://drive.google.com/drive/folders/0B49xEM_rtERAX2tIU0hOV1BJdkE";
}
