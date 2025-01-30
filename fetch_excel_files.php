<?php
$directory = '/opt/lampp/htdocs/cooling_pressure2/uploads/null';
$files = array_diff(scandir($directory), array('..', '.')); // Exclude '.' and '..'

$excelFiles = array_filter($files, function($file) {
    return pathinfo($file, PATHINFO_EXTENSION) === 'xlsx'; // Filter for Excel files only
});

echo json_encode(array_values($excelFiles)); // Send files as JSON response
?>

