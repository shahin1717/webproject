<?php
header("Content-Type: application/json");

require_once "connection.inc.php";

// Final response structure
$response = [
    "status" => "success",
    "weather" => [],
    "surface" => [],
    "traffic" => [],
    "accidents" => []
];

try {
    // WEATHER
    $q1 = $mysqliObject->query("SELECT weatherID, weatherDescription FROM Weather ORDER BY weatherID");
    while ($row = $q1->fetch_assoc()) {
        $response["weather"][] = $row;
    }

    // SURFACE
    $q2 = $mysqliObject->query("SELECT surfaceID, surfaceDescription FROM Surface ORDER BY surfaceID");
    while ($row = $q2->fetch_assoc()) {
        $response["surface"][] = $row;
    }

    // TRAFFIC
    $q3 = $mysqliObject->query("SELECT trafficID, trafficDesctiption FROM Traffic ORDER BY trafficID");
    while ($row = $q3->fetch_assoc()) {
        $response["traffic"][] = $row;
    }

    // ACCIDENTS
    $q4 = $mysqliObject->query("SELECT accidentID, accidentDescription FROM Accidents ORDER BY accidentID");
    while ($row = $q4->fetch_assoc()) {
        $response["accidents"][] = $row;
    }

    echo json_encode($response);
    exit;

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "msg" => "Database query failed",
        "error" => $e->getMessage()
    ]);
    exit;
}
?>
