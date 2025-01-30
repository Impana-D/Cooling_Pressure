<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form inputs
    $highPressure = htmlspecialchars($_POST['highPressure']);
    $lowPressure = htmlspecialchars($_POST['lowPressure']);
    $unit = htmlspecialchars($_POST['unit']);
    $mass = htmlspecialchars($_POST['mass']);

    // Assign the actual unit based on the selected value
if ($unit == 1) {
    $input_unit = 'kPa';  // kPa selected
} elseif ($unit == 2) {
    $input_unit = 'MPa';  // MPa selected
} elseif ($unit == 3) {
    $input_unit = 'bar';  // bar selected
} else {
    echo json_encode(["success" => false, "message" => "Invalid unit selected."]);
    exit;
}

    // Validate that the pressure values match the selected unit
    if ($input_unit === 'kPa' && ($highPressure < 0 || $lowPressure < 0)) {
        echo json_encode(["success" => false, "message" => "Invalid input: Pressure values cannot be negative for kPa."]);
        exit;
    } elseif ($input_unit === 'MPa' && ($highPressure < 0 || $lowPressure < 0)) {
        echo json_encode(["success" => false, "message" => "Invalid input: Pressure values cannot be negative for MPa."]);
        exit;
    } elseif ($input_unit === 'bar' && ($highPressure < 0 || $lowPressure < 0)) {
        echo json_encode(["success" => false, "message" => "Invalid input: Pressure values cannot be negative for bar."]);
        exit;
    }

    // Check if the unit and pressure values match
    if (($input_unit === 'kPa' && ($highPressure > 1000 || $lowPressure > 1000)) ||
        ($input_unit === 'MPa' && ($highPressure > 1 || $lowPressure > 1)) || 
        ($input_unit === 'bar' && ($highPressure > 10 || $lowPressure > 10))) {
        echo json_encode(["success" => false, "message" => "Pressure values do not match the selected unit. Please check the unit and values."]);
        exit;
    }

    // Proceed with calculations (only if unit and pressure match)
    $command = escapeshellcmd('/home/priyanka-a/anaconda3/bin/python3.12 /opt/lampp/htdocs/cooling_pressure2/process.py ' . $highPressure . ' ' . $lowPressure . ' ' . $unit . ' ' . $mass);
    $output = shell_exec($command . " 2>&1");
    error_log("Raw Python output: " . $output);

    // Check if the raw output is a valid JSON string
    $data = json_decode($output, true);

    // Set content type for JSON response
    header('Content-Type: application/json');

    // Check if JSON decoding was successful
    if ($data === null) {
        // Handle JSON decoding errors
        error_log("JSON decode error: " . json_last_error_msg());
        $response = [
            "success" => false,
            "message" => "Error decoding JSON from Python script.",
            "raw_output" => $output
        ];
        echo json_encode($response);  // Send JSON response
    } else {
        // Success response with Python data
        $response = [
            "success" => true,
            "data" => $data
        ];
        echo json_encode($response);  // Send JSON response
    }
}
?>
