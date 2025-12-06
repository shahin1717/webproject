<?php
// save_experience.php
header("Content-Type: application/json");

require_once "cors.php";
require_once __DIR__ . "/includes/includeDB.inc.php";

// Read JSON body from fetch()
$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

if (!is_array($data)) {
    echo json_encode(["status" => "error", "msg" => "Invalid JSON payload"]);
    exit;
}

// Extract values
$expID      = isset($data["expID"]) ? (int)$data["expID"] : null;
$date       = $data["date"]        ?? null;
$startTime  = $data["startTime"]   ?? null;
$endTime    = $data["endTime"]     ?? null;
$kilometers = isset($data["kilometers"]) ? (float)$data["kilometers"] : null;

$weatherID  = isset($data["weatherID"])  ? (int)$data["weatherID"]  : null;
$surfaceID  = isset($data["surfaceID"])  ? (int)$data["surfaceID"]  : null;
$trafficID  = isset($data["trafficID"])  ? (int)$data["trafficID"]  : null;

// NEW — maneuvers array
$maneuvers = isset($data["maneuvers"]) && is_array($data["maneuvers"])
             ? $data["maneuvers"]
             : [];

// Validate required fields
if (
    !$expID || !$date || !$startTime || !$endTime ||
    $kilometers === null ||
    !$weatherID || !$surfaceID || !$trafficID
) {
    echo json_encode([
        "status" => "error",
        "msg"    => "Missing required fields"
    ]);
    exit;
}

// Validate maneuvers
foreach ($maneuvers as $m) {
    if (!is_numeric($m)) {
        echo json_encode([
            "status" => "error",
            "msg"    => "Invalid maneuver ID in array"
        ]);
        exit;
    }
}

/*
 * ============================
 *   1. Insert into Experience
 * ============================
 */
$stmt = $mysqli->prepare("
    INSERT INTO Experience
    (expID, date, startTime, endTime, kilometers, weatherID, surfaceID, trafficID)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");

if (!$stmt) {
    echo json_encode(["status" => "error", "msg" => "Prepare failed: " . $mysqli->error]);
    exit;
}

$stmt->bind_param(
    "isssdiii",
    $expID,
    $date,
    $startTime,
    $endTime,
    $kilometers,
    $weatherID,
    $surfaceID,
    $trafficID
);

if (!$stmt->execute()) {
    if ($mysqli->errno == 1062) {
        echo json_encode(["status" => "error", "msg" => "Experience ID already exists"]);
    } else {
        echo json_encode(["status" => "error", "msg" => "DB error: " . $mysqli->error]);
    }
    exit;
}
$stmt->close();

/*
 * ==========================================
 *   2. Insert maneuvers into bridge table
 * ==========================================
 */
$stmt2 = $mysqli->prepare("
    INSERT INTO Experience_Maneuver (expID, maneuverID)
    VALUES (?, ?)
");

if (!$stmt2) {
    echo json_encode(["status" => "error", "msg" => "Prepare failed: " . $mysqli->error]);
    exit;
}

foreach ($maneuvers as $manID) {
    $manID = (int)$manID;

    $stmt2->bind_param("ii", $expID, $manID);
    $stmt2->execute();
}

$stmt2->close();

echo json_encode([
    "status" => "success",
    "msg"    => "Experience saved",
    "expID"  => $expID
]);

$mysqli->close();
?>
