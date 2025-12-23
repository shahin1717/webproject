<?php
header("Content-Type: application/json");

require_once __DIR__ . "/../includes/includeDB.inc.php";

// Final response structure
$response = [
    "status"    => "success",
    "weather"   => [],
    "surface"   => [],
    "traffic"   => [],
    "maneuvers" => []
];

try {
    // ============================
    // WEATHER
    // ============================
    $stmt1 = $pdo->prepare(
        "SELECT weatherID, weatherDescription
         FROM Weather
         ORDER BY weatherID"
    );
    $stmt1->execute();

    foreach ($stmt1->fetchAll() as $row) {
        $row["weatherID"] = (int)$row["weatherID"];
        $response["weather"][] = $row;
    }

    // ============================
    // SURFACE
    // ============================
    $stmt2 = $pdo->prepare(
        "SELECT surfaceID, surfaceDescription
         FROM Surface
         ORDER BY surfaceID"
    );
    $stmt2->execute();

    foreach ($stmt2->fetchAll() as $row) {
        $row["surfaceID"] = (int)$row["surfaceID"];
        $response["surface"][] = $row;
    }

    // ============================
    // TRAFFIC
    // ============================
    $stmt3 = $pdo->prepare(
        "SELECT trafficID, trafficDescription
         FROM Traffic
         ORDER BY trafficID"
    );
    $stmt3->execute();

    foreach ($stmt3->fetchAll() as $row) {
        $row["trafficID"] = (int)$row["trafficID"];
        $response["traffic"][] = $row;
    }

    // ============================
    // MANEUVERS
    // ============================
    $stmt4 = $pdo->prepare(
        "SELECT maneuverID, maneuverDescription
         FROM Maneuvers
         ORDER BY maneuverID"
    );
    $stmt4->execute();

    foreach ($stmt4->fetchAll() as $row) {
        $row["maneuverID"] = (int)$row["maneuverID"];
        $response["maneuvers"][] = $row;
    }

    echo json_encode($response);
    exit;

} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "msg"    => "Database query failed",
        "error"  => $e->getMessage()
    ]);
    exit;
}
