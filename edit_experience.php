<?php
header("Content-Type: application/json");

// CORS (AlwaysData requires this for JSON POST)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");

require_once "cors.php";
require_once __DIR__ . "/includes/includeDB.inc.php";

// Read incoming JSON
$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

if (!is_array($data)) {
    echo json_encode([
        "status" => "error",
        "msg"    => "Invalid JSON payload"
    ]);
    exit;
}

// Extract fields
$expID      = isset($data["expID"]) ? (int)$data["expID"] : 0;
$date       = $data["date"] ?? null;
$startTime  = $data["startTime"] ?? null;
$endTime    = $data["endTime"] ?? null;
$kilometers = isset($data["kilometers"]) ? (float)$data["kilometers"] : null;
$weatherID  = isset($data["weatherID"]) ? (int)$data["weatherID"] : 0;
$surfaceID  = isset($data["surfaceID"]) ? (int)$data["surfaceID"] : 0;
$trafficID  = isset($data["trafficID"]) ? (int)$data["trafficID"] : 0;
$maneuvers  = isset($data["maneuvers"]) && is_array($data["maneuvers"]) ? $data["maneuvers"] : [];

// Validate
if (!$expID || !$date || !$startTime || !$endTime || $kilometers === null) {
    echo json_encode([
        "status" => "error",
        "msg"    => "Missing required fields"
    ]);
    exit;
}

// 1️⃣ UPDATE the Experience table
$updateSQL = "
    UPDATE Experience
    SET date = ?, startTime = ?, endTime = ?, kilometers = ?, 
        weatherID = ?, surfaceID = ?, trafficID = ?
    WHERE expID = ?
";

$stmt = $mysqli->prepare($updateSQL);
if (!$stmt) {
    echo json_encode([
        "status" => "error",
        "msg" => "Prepare failed: " . $mysqli->error
    ]);
    exit;
}

$stmt->bind_param(
    "sssdiisi",
    $date,
    $startTime,
    $endTime,
    $kilometers,
    $weatherID,
    $surfaceID,
    $trafficID,
    $expID
);

if (!$stmt->execute()) {
    echo json_encode([
        "status" => "error",
        "msg" => "Update failed: " . $stmt->error
    ]);
    exit;
}
$stmt->close();

// 2️⃣ REMOVE old maneuvers
$mysqli->query("DELETE FROM Experience_Maneuver WHERE expID = $expID");

// 3️⃣ ADD new maneuvers
if (!empty($maneuvers)) {
    $ins = $mysqli->prepare("INSERT INTO Experience_Maneuver (expID, maneuverID) VALUES (?, ?)");
    foreach ($maneuvers as $manID) {
        $mid = (int)$manID;
        $ins->bind_param("ii", $expID, $mid);
        $ins->execute();
    }
    $ins->close();
}

echo json_encode([
    "status" => "success",
    "msg"    => "Experience updated successfully",
    "expID"  => $expID
]);

$mysqli->close();
?>
