<?php
session_start();
require 'dbconfig.php';

header('Content-Type: application/json'); // Set content type to JSON for API response

$input = json_decode(file_get_contents('php://input'), true); // Decode JSON input

if (!isset($input['mealId']) || !isset($input['bookmarkDate']) || !isset($input['bookmarkTime'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit();
}

$mealId = $input['mealId'];
$bookmarkDate = $input['bookmarkDate'];
$bookmarkTime = $input['bookmarkTime'];


// Fetch meal details
$response = file_get_contents("https://www.themealdb.com/api/json/v1/1/lookup.php?i=$mealId"); // Fetch meal details from MealDB API
$mealData = json_decode($response, true);

if (!file_exists('meal_log.txt')) { // Check if log file exists
    file_put_contents('meal_log.txt', ""); // Create log file if it doesn't exist
}
file_put_contents('meal_log.txt', print_r($mealData, true), FILE_APPEND); // Log the API response for debugging

if (!$mealData || !isset($mealData['meals'][0])) { // Check if meal data is valid
    echo json_encode(['success' => false, 'message' => 'Meal not found.']);
    exit();
}

$meal = $mealData['meals'][0];
$mealName = $meal['strMeal'];
$mealInstructions = substr($meal['strInstructions'], 0, 200) . "..."; // Limit instructions to 200 characters

// Redirect to calendar.php
header('Location: calendar.php?mealId=' . $mealId . '&mealName=' . urlencode($mealName) . '&mealInstructions=' . urlencode($mealInstructions));

exit();
?>
