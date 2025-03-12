<?php
session_start();
require 'dbconfig.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$mealId = $input['mealId'] ?? null;
$rating = $input['rating'] ?? null;

if (!$mealId || !in_array($rating, [1, 2, 3, 4, 5])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit();
}

$userId = $_SESSION['user_id'];

// Check if the user has already rated the meal
$stmt = $conn->prepare('SELECT id FROM meal_ratings WHERE user_id = ? AND meal_id = ?');
$stmt->execute([$userId, $mealId]);
$existingRating = $stmt->fetch();

if ($existingRating) {
    // Update existing rating
    $stmt = $conn->prepare('UPDATE meal_ratings SET rating = ? WHERE id = ?');
    $stmt->execute([$rating, $existingRating['id']]);
} else {
    // Insert new rating
    $stmt = $conn->prepare('INSERT INTO meal_ratings (user_id, meal_id, rating) VALUES (?, ?, ?)');
    $stmt->execute([$userId, $mealId, $rating]);
}

echo json_encode(['success' => true]);
exit();
?>