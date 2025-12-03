<?php
// save_experience.php
header("Content-Type: application/json");

require_once "cors.php";
header("Content-Type: application/json");
require_once "connection.inc.php";

// Read JSON body from fetch()
$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

if (!is_array($data)) {
    echo json_encode([
        "status" => "error",
        "msg"    => "Invalid JSON payload"
    ]);
    exit;
}

// Extract & basic validation
$expID       = isset($data["expID"]) ? (int)$data["expID"] : null;
$date        = $data["date"]        ?? null;
$startTime   = $data["startTime"]   ?? null;
$endTime     = $data["endTime"]     ?? null;
$kilometers  = isset($data["kilometers"]) ? (float)$data["kilometers"] : null;
$weatherID   = isset($data["weatherID"])  ? (int)$data["weatherID"]    : null;
$surfaceID   = isset($data["surfaceID"])  ? (int)$data["surfaceID"]    : null;
$trafficID   = isset($data["trafficID"])  ? (int)$data["trafficID"]    : null;
$accidentID  = isset($data["accidentID"]) ? (int)$data["accidentID"]   : null;

// Very simple "required" check – you already validate in JS too
if (
    !$expID || !$date || !$startTime || !$endTime ||
    $kilometers === null || !$weatherID || !$surfaceID ||
    !$trafficID || !$accidentID
) {
    echo json_encode([
        "status" => "error",
        "msg"    => "Missing required fields"
    ]);
    exit;
}

// Insert into Experience
$stmt = $mysqli->prepare("
    INSERT INTO Experience
    (expID, date, startTime, endTime, kilometers, weatherID, surfaceID, trafficID, accidentID)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
");

if (!$stmt) {
    echo json_encode([
        "status" => "error",
        "msg"    => "Prepare failed: " . $mysqli->error
    ]);
    exit;
}

$stmt->bind_param(
    "isssdiiii",
    $expID,
    $date,
    $startTime,
    $endTime,
    $kilometers,
    $weatherID,
    $surfaceID,
    $trafficID,
    $accidentID
);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "msg"    => "Experience saved",
        "expID"  => $expID
    ]);
} else {
    if ($mysqli->errno == 1062) { // duplicate PK
        echo json_encode([
            "status" => "error",
            "msg"    => "This Experience ID already exists in DB"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "msg"    => "DB error: " . $mysqli->error
        ]);
    }
}

$stmt->close();
$mysqli->close();
?>
