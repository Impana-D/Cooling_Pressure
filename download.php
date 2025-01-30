<?php
if (isset($_GET['file'])) {
    $file = basename($_GET['file']); // Sanitize the file name
    $filePath = '/opt/lampp/htdocs/cooling_pressure/uploads/Pressure/' . $file;

    if (file_exists($filePath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    } else {
        echo "File not found.";
    }
}
?>

