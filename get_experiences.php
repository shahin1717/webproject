<?php

require_once "cors.php";
header("Content-Type: application/json");
require_once "connection.inc.php";
$sql = "SELECT expID, date, startTime, endTime, kilometers,
               weatherID, surfaceID, trafficID, accidentID
        FROM Experience
        ORDER BY expID ASC";

$result = $mysqli->query($sql);

$data = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Cast numeric fields
        $row["expID"]      = (int)$row["expID"];
        $row["kilometers"] = (float)$row["kilometers"];
        $row["weatherID"]  = (int)$row["weatherID"];
        $row["surfaceID"]  = (int)$row["surfaceID"];
        $row["trafficID"]  = (int)$row["trafficID"];
        $row["accidentID"] = (int)$row["accidentID"];
        $data[] = $row;
    }
}

echo json_encode($data);

$mysqli->close();
?>
