<?php

require('../sitevars.php');

$documentationContent = []; // Array<Array<markdown_content>>
$documentationDirectories = [];
//$coreDocumentationDirectory = "../search/commands/core/documentation";

// Find all documentation files
// Start with core directory
//array_push($documentationDirectories, $coreDocumentationDirectory);
// Now search for all other documentation directories
$itemsQueue = new SplQueue();
$itemsQueue->enqueue("../search/commands");
while ( !$itemsQueue->isEmpty() ) {
	$item = $itemsQueue->dequeue();
	if ( is_dir($item) ) {
		//if ( !strcmp($item, $coreDocumentationDirectory) ) {
		//	// Skip the core documentation directory
		//	continue;
		//}
		
		if ( endsWith($item, 'documentation') ) {
			// It's a directory named 'documentation'
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

// Fetch all documentation
foreach ($documentationDirectories as $directory_path) {
	$parentDirectoryPath = dirname($directory_path);
	$nameOfParentDirectory = substr($parentDirectoryPath, strrpos($parentDirectoryPath, '/') + 1);
	
	$documentationInThisDirectory = [];
	$scanned_directory = array_diff(scandir($directory_path), array('..', '.'));
	foreach ( $scanned_directory as $file ) {
		$file_path = $directory_path . '/' . $file;
		if ( !is_dir($file_path) && endsWith($file_path, '.md') ) {
			$documentation = file_get_contents($file_path);
			array_push($documentationInThisDirectory, $documentation);
		}
	}
	$documentationContent[$nameOfParentDirectory] = $documentationInThisDirectory;
}

echo json_encode($documentationContent);

// Retrieved from: https://stackoverflow.com/questions/619610/whats-the-most-efficient-test-of-whether-a-php-string-ends-with-another-string
function endsWith($string, $test) {
    $strlen = strlen($string);
    $testlen = strlen($test);
    if ($testlen > $strlen) return false;
    return substr_compare($string, $test, $strlen - $testlen, $testlen) === 0;
}

?>