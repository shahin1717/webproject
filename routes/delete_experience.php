<?php
// delete_experience.php

require_once "cors.php";
header("Content-Type: application/json");
require_once __DIR__ . "/../includes/includeDB.inc.php";

$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

if (!is_array($data) || !isset($data["expID"])) {
    echo json_encode([
        "status" => "error",
        "msg"    => "expID is required"
    ]);
    exit;
}

$expID = (int)$data["expID"];

if ($expID <= 0) {
    echo json_encode([
        "status" => "error",
        "msg"    => "Invalid expID"
    ]);
    exit;
}

try {
    // Use transaction for safety
    $mysqli->begin_transaction();

    // 1) Delete related maneuvers
    $stmt1 = $mysqli->prepare("DELETE FROM Experience_Maneuver WHERE expID = ?");
    if (!$stmt1) {
        throw new Exception("Prepare failed (Experience_Maneuver): " . $mysqli->error);
    }
    $stmt1->bind_param("i", $expID);
    if (!$stmt1->execute()) {
        throw new Exception("Execute failed (Experience_Maneuver): " . $stmt1->error);
    }
    $stmt1->close();

    // 2) Delete experience itself
    $stmt2 = $mysqli->prepare("DELETE FROM Experience WHERE expID = ?");
    if (!$stmt2) {
        throw new Exception("Prepare failed (Experience): " . $mysqli->error);
    }
    $stmt2->bind_param("i", $expID);
    if (!$stmt2->execute()) {
        throw new Exception("Execute failed (Experience): " . $stmt2->error);
    }

    $affected = $stmt2->affected_rows;
    $stmt2->close();

    $mysqli->commit();

    if ($affected === 0) {
        echo json_encode([
            "status" => "error",
            "msg"    => "No experience found with that ID"
        ]);
    } else {
        echo json_encode([
            "status" => "success",
            "msg"    => "Experience deleted successfully"
        ]);
    }

} catch (Exception $e) {
    $mysqli->rollback();
    echo json_encode([
        "status" => "error",
        "msg"    => "DB error during delete: " . $e->getMessage()
    ]);
}

$mysqli->close();
?>
