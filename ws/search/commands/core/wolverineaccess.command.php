<?php

/* @command wa
 * @description Visit Wolverine Access
 * @usage wa [[student/sb][employee/eb][faculty]]
 *
 * @command student
 * @aliases wa student
 *
 * @command sb
 * @aliases wa student
 *
 * @command employee
 * @aliases wa employee
 *
 * @command eb
 * @aliases wa employee
 *
 * @command faculty
 * @aliases wa faculty
 */

global $URLs, $aliases;

$URLs = [
	'main' => 'https://wolverineaccess.umich.edu',
	'student' => 'https://csprod.dsc.umich.edu/services/student',
	'employee' => 'https://hcmprod.dsc.umich.edu/services/employee/',
	'faculty' => 'https://csprod.dsc.umich.edu/services/faculty/',
];

$aliases = [
	'sb' => 'student',
	'student' => 'student',
	'eb' => 'employee',
	'employee' => 'employee',
	'fb' => 'faculty',
	'faculty' => 'faculty',
];

register_command('wa', 'wa_process_query');
register_command('wolverineaccess', 'wa_process_query');
function wa_process_query($query) {
	global $URLs, $aliases;
	if (!$query || !strlen($query)) {
		return $URLs['main'];
	}
	
	$query = strtolower($query);
	foreach($aliases as $alias => $category) {
		if (stripos($query, $alias) !== false) {
			return $URLs[$category];
		}
	}
	return $URLs['main'];
}

register_command('student', 'student_process_query');
register_command('sb', 'student_process_query');
function student_process_query($query) {
	global $URLs;
	return $URLs['student'];
}

register_command('employee', 'employee_process_query');
register_command('eb', 'employee_process_query');
function employee_process_query($query) {
	global $URLs;
	return $URLs['employee'];
}

register_command('faculty', 'faculty_process_query');
function faculty_process_query($query) {
	global $URLs;
	return $URLs['faculty'];
}
