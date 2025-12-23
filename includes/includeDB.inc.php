<?php
/***********************************************
 * includeDB.inc.php
 * 
 * - Starts session
 * - Loads all class definitions
 * - Connects to database (PDO)
 * - Unified include for all backend files
 ***********************************************/

ini_set('display_errors', 1);
error_reporting(E_ALL);

// ---------------------------------
// 1. Load classes
// ---------------------------------
$classesPath = __DIR__ . '/../classes/';

require_once $classesPath . 'DrivingExperience.php';
require_once $classesPath . 'Weather.php';
require_once $classesPath . 'Surface.php';
require_once $classesPath . 'Traffic.php';
require_once $classesPath . 'Maneuver.php';

// ---------------------------------
// 2. Database credentials
// ---------------------------------
$host = "mysql-shahin.alwaysdata.net";
$db   = "shahin_hw_project";
$user = "shahin_hwtester";
$pass = "tester.123";
$charset = "utf8";

// ---------------------------------
// 3. Create PDO connection
// ---------------------------------
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // throw exceptions
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // fetch_assoc equivalent
    PDO::ATTR_EMULATE_PREPARES   => false,                  // real prepared statements
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("<b>Database connection failed:</b> " . $e->getMessage());
}
