<?php
require 'vendor/autoload.php'; // PhpSpreadsheet library

use PhpOffice\PhpSpreadsheet\IOFactory;

$file = $_GET['file'];
$filePath = '/opt/lampp/htdocs/cooling_pressure2/uploads/null/' . $file;

if (file_exists($filePath)) {
    $spreadsheet = IOFactory::load($filePath);
    $sheet = $spreadsheet->getActiveSheet();
    $data = [];
    
    foreach ($sheet->getRowIterator() as $row) {
        $rowData = [];
        foreach ($row->getCellIterator() as $cell) {
            $rowData[] = $cell->getValue();
        }
        $data[] = $rowData;
    }
    
    echo json_encode($data); // Send sheet data as JSON response
} else {
    echo json_encode([]); // File not found
}
?>

