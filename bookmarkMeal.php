<?php
session_start();
require 'dbconfig.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['mealId']) || !isset($input['bookmarkDate']) || !isset($input['bookmarkTime'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit();
}

$mealId = $input['mealId'];
$bookmarkDate = $input['bookmarkDate'];
$bookmarkTime = $input['bookmarkTime'];
$mealLink = $input['mealLink'];

// Fetch meal details
$response = file_get_contents("https://www.themealdb.com/api/json/v1/1/lookup.php?i=$mealId");
$mealData = json_decode($response, true);

if (!$mealData || !isset($mealData['meals'][0])) {
    echo json_encode(['success' => false, 'message' => 'Meal not found.']);
    exit();
}

$meal = $mealData['meals'][0];
$mealName = $meal['strMeal'];
$mealInstructions = substr($meal['strInstructions'], 0, 200) . "...";

// Redirect to calendar.php
header('Location: calendar.php?mealId=' . $mealId . '&mealName=' . urlencode($mealName) . '&mealInstructions=' . urlencode($mealInstructions));

exit();
?>
