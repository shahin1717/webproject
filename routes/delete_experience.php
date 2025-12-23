<?php
// delete_experience.php

require_once "cors.php";
header("Content-Type: application/json");
require_once __DIR__ . "/../includes/includeDB.inc.php";

// Read JSON body
$raw  = file_get_contents("php://input");
$data = json_decode($raw, true);

// Validate input
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
    // -----------------------------
    // 1. Start transaction (PDO)
    // -----------------------------
    $pdo->beginTransaction();

    // -----------------------------
    // 2. Delete from Experience_Maneuver
    // -----------------------------
    $stmt1 = $pdo->prepare(
        "DELETE FROM Experience_Maneuver WHERE expID = :expID"
    );
    $stmt1->execute([
        ":expID" => $expID
    ]);

    // -----------------------------
    // 3. Delete from Experience
    // -----------------------------
    $stmt2 = $pdo->prepare(
        "DELETE FROM Experience WHERE expID = :expID"
    );
    $stmt2->execute([
        ":expID" => $expID
    ]);

    // Row count check
    $affected = $stmt2->rowCount();

    // -----------------------------
    // 4. Commit transaction
    // -----------------------------
    $pdo->commit();

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

} catch (PDOException $e) {
    // Rollback on error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    echo json_encode([
        "status" => "error",
        "msg"    => "DB error during delete: " . $e->getMessage()
    ]);
}
