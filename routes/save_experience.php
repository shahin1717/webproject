<?php
// save_experience.php
header("Content-Type: application/json");

require_once "cors.php";
require_once __DIR__ . "/../includes/includeDB.inc.php";

// Read JSON body
$raw  = file_get_contents("php://input");
$data = json_decode($raw, true);

if (!is_array($data)) {
    echo json_encode([
        "status" => "error",
        "msg"    => "Invalid JSON payload"
    ]);
    exit;
}

// Extract values
$expID      = isset($data["expID"]) ? (int)$data["expID"] : null;
$date       = $data["date"]       ?? null;
$startTime  = $data["startTime"]  ?? null;
$endTime    = $data["endTime"]    ?? null;
$kilometers = isset($data["kilometers"]) ? (float)$data["kilometers"] : null;

$weatherID  = isset($data["weatherID"]) ? (int)$data["weatherID"] : null;
$surfaceID  = isset($data["surfaceID"]) ? (int)$data["surfaceID"] : null;
$trafficID  = isset($data["trafficID"]) ? (int)$data["trafficID"] : null;

// Maneuvers array
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

try {
    // ============================
    // Start transaction
    // ============================
    $pdo->beginTransaction();

    /*
     * ============================
     * 1️⃣ Insert into Experience
     * ============================
     */
    $stmt = $pdo->prepare("
        INSERT INTO Experience
        (expID, date, startTime, endTime, kilometers, weatherID, surfaceID, trafficID)
        VALUES
        (:expID, :date, :startTime, :endTime, :kilometers, :weatherID, :surfaceID, :trafficID)
    ");

    $stmt->execute([
        ":expID"      => $expID,
        ":date"       => $date,
        ":startTime"  => $startTime,
        ":endTime"    => $endTime,
        ":kilometers" => $kilometers,
        ":weatherID"  => $weatherID,
        ":surfaceID"  => $surfaceID,
        ":trafficID"  => $trafficID
    ]);

    /*
     * ==========================================
     * 2️⃣ Insert maneuvers into bridge table
     * ==========================================
     */
    if (!empty($maneuvers)) {
        $stmt2 = $pdo->prepare("
            INSERT INTO Experience_Maneuver (expID, maneuverID)
            VALUES (:expID, :maneuverID)
        ");

        foreach ($maneuvers as $manID) {
            $stmt2->execute([
                ":expID"      => $expID,
                ":maneuverID" => (int)$manID
            ]);
        }
    }

    // ============================
    // Commit transaction
    // ============================
    $pdo->commit();

    echo json_encode([
        "status" => "success",
        "msg"    => "Experience saved",
        "expID"  => $expID
    ]);

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    // Duplicate key (expID already exists)
    if ($e->getCode() === "23000") {
        echo json_encode([
            "status" => "error",
            "msg"    => "Experience ID already exists"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "msg"    => "DB error: " . $e->getMessage()
        ]);
    }
}
