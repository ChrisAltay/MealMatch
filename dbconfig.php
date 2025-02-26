<?php
// dbconfig.php

$host = 'localhost';    // Database host
$dbname = 'testbase';   // Database name (change as necessary)
$user = 'tester';       // Database username (change as necessary)
$pass = 'comptest';     // Database password (change as necessary)
$chrs = 'utf8mb4';      // Character set

// Create a PDO instance
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=$chrs", $user, $pass);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
