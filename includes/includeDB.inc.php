<?php

/***********************************************
 * includeDB.inc.php
 * 
 * - Starts session
 * - Loads all class definitions
 * - Connects to database (mysqli)
 * - Unified include for all backend files
 ***********************************************/

ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
session_destroy();
session_start();

$classesPath = __DIR__ . '/../classes/';

require_once $classesPath . 'DrivingExperience.php';
require_once $classesPath . 'Weather.php';
require_once $classesPath . 'Surface.php';
require_once $classesPath . 'Traffic.php';
require_once $classesPath . 'Maneuver.php';


// -------------------------------
// 3. Database credentials
// -------------------------------

$host = "mysql-shahin.alwaysdata.net";
$user = "shahin_hwtester";
$password = "tester.123";
$db   = "shahin_hw_project";

// -------------------------------
// 4. Create mysqli connection
// -------------------------------

$mysqli = new mysqli(hostname: $host, username: $user, password: $password, database: $db);

if ($mysqli->connect_errno) {
    die("<b>Database connection failed:</b> " . $mysqli->connect_error);
}

// Set charset
$mysqli->set_charset("utf8");
