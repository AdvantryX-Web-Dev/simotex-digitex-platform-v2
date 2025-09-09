<?php

// Database configuration
$dbHost = '127.0.0.1';
// $dbHost = 'db';
$dbUsername = 'root';
//$dbPassword = '#R3DR&uE3k0RuMk38';
$dbPassword = 'Testing321';

$dbName = 'db_simotex';

try {
    // Connect to the database
    $con = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

    // Check connection
    if ($con->connect_errno) {
        throw new Exception("Database connection error: " . $con->connect_error);
    }

    // Set charset to utf8
    $con->set_charset('utf8');
} catch (Exception $e) {
    // Log the error message
    error_log($e->getMessage());
    // Show a user-friendly message
    echo "Unable to connect to the database. Please try again later.";
    exit();
}
