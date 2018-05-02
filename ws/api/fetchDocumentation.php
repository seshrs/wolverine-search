<?php

header('Content-type: application/json');
$documentation_location = __DIR__ . '/../search_refactor/__build__/documentation_content.json';
echo file_get_contents($documentation_location);

?>