<?php
session_start();
require 'dbconfig.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$mealId = $data['mealId'] ?? null;
$rating = isset($data['rating']) ? floatval($data['rating']) : null;
$overwrite = $data['overwrite'] ?? false;
$userId = $_SESSION['user_id'] ?? null;

// Fetch total rating and user count (Always show ratings)
$totalRatingQuery = $conn->prepare("SELECT AVG(rating) AS averageRating, COUNT(user_id) AS userCount FROM meal_ratings WHERE meal_id = ?");
$totalRatingQuery->execute([$mealId]);
$ratingData = $totalRatingQuery->fetch(PDO::FETCH_ASSOC);
$averageRating = $ratingData['averageRating'] !== null ? round($ratingData['averageRating'], 1) : 0;
$userCount = $ratingData['userCount'] ?? 0;

// If it's just a request to fetch ratings, return them
if (!$rating) {
    echo json_encode([
        'success' => true,
        'averageRating' => $averageRating,
        'userCount' => $userCount
    ]);
    exit();
}

// Ensure user is logged in for rating submission
if (!$userId) {
    echo json_encode([
        'success' => false,
        'averageRating' => $averageRating,
        'userCount' => $userCount,
        'message' => 'You must be logged in to rate meals.'
    ]);
    exit();
}

// Validate rating input
if ($rating < 1 || $rating > 5) {
    echo json_encode(['success' => false, 'message' => 'Invalid rating.']);
    exit();
}

// Check if the user already rated the meal
$stmt = $conn->prepare("SELECT rating FROM meal_ratings WHERE user_id = ? AND meal_id = ?");
$stmt->execute([$userId, $mealId]);
$existingRating = $stmt->fetchColumn();

if ($existingRating !== false && !$overwrite) {
    echo json_encode([
        'success' => false,
        'message' => 'You have already rated this meal. Overwrite your previous rating?',
        'averageRating' => $averageRating,
        'userCount' => $userCount
    ]);
    exit();
}

// Insert or update rating
$stmt = $conn->prepare("INSERT INTO meal_ratings (user_id, meal_id, rating) 
                        VALUES (?, ?, ?) 
                        ON DUPLICATE KEY UPDATE rating = VALUES(rating)");
$success = $stmt->execute([$userId, $mealId, $rating]);

// Fetch updated total rating and user count
$totalRatingQuery->execute([$mealId]);
$ratingData = $totalRatingQuery->fetch(PDO::FETCH_ASSOC);
$averageRating = $ratingData['averageRating'] !== null ? round($ratingData['averageRating'], 1) : 0;
$userCount = $ratingData['userCount'] ?? 0;

if ($success) {
    echo json_encode([
        'success' => true,
        'message' => 'Rating updated successfully.',
        'averageRating' => $averageRating,
        'userCount' => $userCount
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update rating.']);
}

$conn = null;
?>
