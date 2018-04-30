<?php

/**
 * Determines if a given string has the test substring at its end.
 * 
 * Retrieved from: https://stackoverflow.com/questions/619610/whats-the-most-efficient-test-of-whether-a-php-string-ends-with-another-string
 * 
 * @param {string} The given string
 * @param {string} The substring to check if at the end of the given string
 * @return {bool} Whether the test substring occurs at the end of the given string.
 */
function endsWith($string, $test) {
  $strlen = strlen($string);
  $testlen = strlen($test);
  if ($testlen > $strlen) return false;
  return substr_compare($string, $test, $strlen - $testlen, $testlen) === 0;
}

/**
 * Converts string from snake case to title case.
 * From: https://stackoverflow.com/a/29405882/5868796
 */
function snakeCaseToTitleCase($str) {
  return ucwords(str_replace("_", " ", $str));
}

/**
 * Array<shape(
 *   dir_name => string,
 *   meta_md => ?Markdown,
 *   markdown_content => Array<Markdown>,
 * )>
 */
$documentationContent = [];

$documentationDirectories = [];
$blacklisted_directories = [
  __DIR__ . "/../search_refactor/__build__",
  __DIR__ . "/../search_refactor/__definitions__",
  __DIR__ . "/../search_refactor/__search__",
];

$output_path = __DIR__ . '/../search_refactor/__build__/documentation_content.json';

// Find all documentation files
$itemsQueue = new SplQueue();
$itemsQueue->enqueue(__DIR__ . "/../search_refactor");
while (!$itemsQueue->isEmpty()) {
	$item = $itemsQueue->dequeue();
	if (is_dir($item)) {
		if (in_array($item, $blacklisted_directories)) {
			// Skip the directory
			continue;
		}
		
		if (basename($item) === '__documentation__') {
			// It's a directory named '__documentation__'
			array_push($documentationDirectories, $item);
		}
		else {
			// Check inside the directory
			$scanned_directory = array_diff(scandir($item), array('..', '.'));
			foreach ( $scanned_directory as $content ) {
				$itemsQueue->enqueue($item . '/' . $content);
			}
		}
	}
}

// Fetch all documentation from their directories
foreach ($documentationDirectories as $directory_path) {
	$parentDirectoryPath = dirname($directory_path);
  $nameOfParentDirectory = substr($parentDirectoryPath, strrpos($parentDirectoryPath, '/') + 1);
  $dir_name = snakeCaseToTitleCase($nameOfParentDirectory);
	
  $documentationContentInThisDirectory = [];
  $meta_documentation = null;

	$scanned_directory = array_diff(scandir($directory_path), array('..', '.'));
	foreach ( $scanned_directory as $file ) {
    $file_path = $directory_path . '/' . $file;
    if (is_dir($file_path)) {
      die("Something terrible happened. Debug the buildDocumentation script at the point of this error message.");
    }
		if (endsWith($file_path, '.command.md')) {
			$documentation = file_get_contents($file_path);
			array_push($documentationContentInThisDirectory, $documentation);
    }
    else if (endsWith($file_path, '__meta__.md')) {
      $meta_documentation = file_get_contents($file_path);
    }
	}
	$documentationContent[] = [
    'dir_name' => $dir_name,
    'meta_md' => $meta_documentation,
    'markdown_content' => $documentationContentInThisDirectory,
  ];
}

file_put_contents($output_path, json_encode($documentationContent));

?>
