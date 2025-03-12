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

if (!$mealId) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit();
}

$userId = $_SESSION['user_id'];

// Check the request method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the user has already saved the meal
    $stmt = $conn->prepare('SELECT id FROM saved_meals WHERE user_id = ? AND meal_id = ?');
    $stmt->execute([$userId, $mealId]);
    $existingMeal = $stmt->fetch();

    if ($existingMeal) {
        echo json_encode(['success' => false, 'message' => 'Meal already saved']);
        exit();
    }

    // Save the meal
    $stmt = $conn->prepare('INSERT INTO saved_meals (user_id, meal_id) VALUES (?, ?)');
    $stmt->execute([$userId, $mealId]);

    echo json_encode(['success' => true, 'message' => 'Meal saved successfully']);
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Delete the meal
    $stmt = $conn->prepare('DELETE FROM saved_meals WHERE user_id = ? AND meal_id = ?');
    $stmt->execute([$userId, $mealId]);

    echo json_encode(['success' => true, 'message' => 'Meal deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
