<?php
header("Content-Type: application/json");

require_once __DIR__ . "/includes/includeDB.inc.php";

// Final response structure
$response = [
    "status"   => "success",
    "weather"  => [],
    "surface"  => [],
    "traffic"  => [],
    "maneuvers" => []
];

try {
    // WEATHER
    $q1 = $mysqli->query("SELECT weatherID, weatherDescription FROM Weather ORDER BY weatherID");
    while ($row = $q1->fetch_assoc()) {
        $row["weatherID"] = (int)$row["weatherID"];
        $response["weather"][] = $row;
    }

    // SURFACE
    $q2 = $mysqli->query("SELECT surfaceID, surfaceDescription FROM Surface ORDER BY surfaceID");
    while ($row = $q2->fetch_assoc()) {
        $row["surfaceID"] = (int)$row["surfaceID"];
        $response["surface"][] = $row;
    }

    // TRAFFIC
    $q3 = $mysqli->query("SELECT trafficID, trafficDescription FROM Traffic ORDER BY trafficID");
    while ($row = $q3->fetch_assoc()) {
        $row["trafficID"] = (int)$row["trafficID"];
        $response["traffic"][] = $row;
    }

    // MANEUVERS (NEW)
    $q4 = $mysqli->query("SELECT maneuverID, maneuverDescription FROM Maneuvers ORDER BY maneuverID");
    while ($row = $q4->fetch_assoc()) {
        $row["maneuverID"] = (int)$row["maneuverID"];
        $response["maneuvers"][] = $row;
    }

    echo json_encode($response);
    exit;

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "msg"    => "Database query failed",
        "error"  => $e->getMessage()
    ]);
    exit;
}
?>
