<?php
session_start();
session_destroy();
session_start();
require_once "cors.php";
header("Content-Type: application/json");
require_once __DIR__ . "/../includes/includeDB.inc.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["expCode"])) {
    echo json_encode(["status" => "error", "msg" => "expCode required"]);
    exit;
}

$code = $data["expCode"];

if (!isset($_SESSION["exp_map"][$code])) {
    echo json_encode(["status" => "error", "msg" => "Invalid or expired code"]);
    exit;
}

$expID = $_SESSION["exp_map"][$code];

try {
    $pdo->beginTransaction();

    $pdo->prepare(
        "DELETE FROM Experience_Maneuver WHERE expID = :id"
    )->execute([":id" => $expID]);

    $pdo->prepare(
        "DELETE FROM Experience WHERE expID = :id"
    )->execute([":id" => $expID]);

    $pdo->commit();

    unset($_SESSION["exp_map"][$code]); // prevent reuse

    echo json_encode(["status" => "success"]);

} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode([
        "status" => "error",
        "msg" => "Delete failed"
    ]);
}
