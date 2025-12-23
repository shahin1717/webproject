<?php
session_start();

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

$stmt = $pdo->prepare($sql);
$stmt->execute();
$rows = $stmt->fetchAll();


if (!isset($_SESSION["exp_map"])) {
    $_SESSION["exp_map"] = [];
}

$output = [];

foreach ($rows as $row) {

    // 🔑 FIX: define expID first
    $expID = (int)$row["expID"];

    // Try to reuse an existing anonymous code
    $expCode = array_search($expID, $_SESSION["exp_map"], true);

    if ($expCode === false) {
        $expCode = bin2hex(random_bytes(6));
        $_SESSION["exp_map"][$expCode] = $expID;
    }

    $arr = [
        "expID"      => $expID,        // keep for edit/export
        "expCode"    => $expCode,      // anonymized (delete only)
        "date"       => $row["date"],
        "startTime"  => $row["startTime"],
        "endTime"    => $row["endTime"],
        "kilometers" => (float)$row["kilometers"],
        "weatherID"  => (int)$row["weatherID"],
        "surfaceID"  => (int)$row["surfaceID"],
        "trafficID"  => (int)$row["trafficID"],
        "maneuvers"  => []
    ];

    if (!empty($row["manList"])) {
        $arr["maneuvers"] = array_map(
            "intval",
            explode(",", $row["manList"])
        );
    }

    $output[] = $arr;
}

echo json_encode($output);