<?php
header('Content-Type: application/json');

// Ensure the email parameter is provided
if (!isset($_GET['email']) || empty($_GET['email'])) {
    echo json_encode(["error" => "No email provided"]);
    exit;
}

$email = $_GET['email'];

// Basic email format validation (very basic check)
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["error" => "Invalid email format"]);
    exit;
}

// Include the database connection
require 'dbconfig.php';

try {
    // Check if the email already exists in the database
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $emailExists = $stmt->fetchColumn() > 0;
    
    // Return the response
    echo json_encode([
        "exists" => $emailExists,  // Check if email exists in DB
        "valid" => true            // Since it's a basic email format check, assume it's valid
    ]);

} catch (Exception $e) {
    // If there's an error, catch it and log it
    error_log("Error: " . $e->getMessage());
    echo json_encode(["error" => "An error occurred: " . $e->getMessage()]);
    exit;
}
