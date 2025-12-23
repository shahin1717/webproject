<?php
header("Content-Type: application/json");

// CORS (AlwaysData requires this for JSON POST)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");

require_once "cors.php";
require_once __DIR__ . "/../includes/includeDB.inc.php";

// Read incoming JSON
$raw  = file_get_contents("php://input");
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
$maneuvers  = isset($data["maneuvers"]) && is_array($data["maneuvers"])
    ? $data["maneuvers"]
    : [];

// Validate
if (!$expID || !$date || !$startTime || !$endTime || $kilometers === null) {
    echo json_encode([
        "status" => "error",
        "msg"    => "Missing required fields"
    ]);
    exit;
}

try {
    // ============================
    // Start transaction
    // ============================
    $pdo->beginTransaction();

    // ============================
    // 1️⃣ UPDATE Experience
    // ============================
    $updateSQL = "
        UPDATE Experience
        SET date = :date,
            startTime = :startTime,
            endTime = :endTime,
            kilometers = :kilometers,
            weatherID = :weatherID,
            surfaceID = :surfaceID,
            trafficID = :trafficID
        WHERE expID = :expID
    ";

    $stmt = $pdo->prepare($updateSQL);
    $stmt->execute([
        ":date"       => $date,
        ":startTime"  => $startTime,
        ":endTime"    => $endTime,
        ":kilometers" => $kilometers,
        ":weatherID"  => $weatherID,
        ":surfaceID"  => $surfaceID,
        ":trafficID"  => $trafficID,
        ":expID"      => $expID
    ]);

    // ============================
    // 2️⃣ REMOVE old maneuvers
    // ============================
    $del = $pdo->prepare(
        "DELETE FROM Experience_Maneuver WHERE expID = :expID"
    );
    $del->execute([
        ":expID" => $expID
    ]);

    // ============================
    // 3️⃣ ADD new maneuvers
    // ============================
    if (!empty($maneuvers)) {
        $ins = $pdo->prepare(
            "INSERT INTO Experience_Maneuver (expID, maneuverID)
             VALUES (:expID, :maneuverID)"
        );

        foreach ($maneuvers as $manID) {
            $ins->execute([
                ":expID"      => $expID,
                ":maneuverID" => (int)$manID
            ]);
        }
    }

    // ============================
    // Commit
    // ============================
    $pdo->commit();

    echo json_encode([
        "status" => "success",
        "msg"    => "Experience updated successfully",
        "expID"  => $expID
    ]);

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    echo json_encode([
        "status" => "error",
        "msg"    => "DB error during update: " . $e->getMessage()
    ]);
}
