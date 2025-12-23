<?php
header("Content-Type: application/json");

require_once __DIR__ . "/../includes/includeDB.inc.php";

$sql = "
    SELECT 
        e.*,
        GROUP_CONCAT(em.maneuverID) AS manList
    FROM Experience e
    LEFT JOIN Experience_Maneuver em ON e.expID = em.expID
    GROUP BY e.expID
    ORDER BY e.expID ASC
";

// Prepare & execute (PDO)
$stmt = $pdo->prepare($sql);
$stmt->execute();

// Fetch all rows
$rows = $stmt->fetchAll();

$output = [];

foreach ($rows as $row) {

    $arr = [
        "expID"      => (int)$row["expID"],
        "date"       => $row["date"],
        "startTime"  => $row["startTime"],
        "endTime"    => $row["endTime"],
        "kilometers" => (float)$row["kilometers"],
        "weatherID"  => (int)$row["weatherID"],
        "surfaceID"  => (int)$row["surfaceID"],
        "trafficID"  => (int)$row["trafficID"],
        "maneuvers"  => []
    ];

    // Convert "1,3,4" → [1,3,4]
    if (!empty($row["manList"])) {
        $arr["maneuvers"] = array_map(
            "intval",
            explode(",", $row["manList"])
        );
    }

    $output[] = $arr;
}

echo json_encode($output);
