<?php
require_once "cors.php";
header("Content-Type: application/json");
require_once __DIR__ . "/includes/includeDB.inc.php";

/*
 * 1. Fetch all experiences
 */
$sql = "
    SELECT 
        expID, date, startTime, endTime, kilometers,
        weatherID, surfaceID, trafficID
    FROM Experience
    ORDER BY expID ASC
";

$result = $mysqli->query($sql);

$experiences = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {

        $expID = (int)$row["expID"];

        // Base experience info
        $experiences[$expID] = [
            "expID"      => $expID,
            "date"       => $row["date"],
            "startTime"  => $row["startTime"],
            "endTime"    => $row["endTime"],
            "kilometers" => (float)$row["kilometers"],
            "weatherID"  => (int)$row["weatherID"],
            "surfaceID"  => (int)$row["surfaceID"],
            "trafficID"  => (int)$row["trafficID"],
            "maneuvers"  => []   // will be filled below
        ];
    }
}

/*
 * 2. Fetch maneuvers for each experience
 */
$joinSQL = "
    SELECT EM.expID, EM.maneuverID, M.maneuverDescription
    FROM Experience_Maneuver EM
    JOIN Maneuvers M ON M.maneuverID = EM.maneuverID
    ORDER BY EM.expID, EM.maneuverID
";

$joinResult = $mysqli->query($joinSQL);

if ($joinResult) {
    while ($row = $joinResult->fetch_assoc()) {

        $expID = (int)$row["expID"];
        $manID = (int)$row["maneuverID"];

        if (isset($experiences[$expID])) {
            $experiences[$expID]["maneuvers"][] = [
                "maneuverID"   => $manID,
                "description"  => $row["maneuverDescription"]
            ];
        }
    }
}

// Reindex array (remove expID keys)
$output = array_values($experiences);

// Send JSON
echo json_encode($output);

$mysqli->close();
?>
