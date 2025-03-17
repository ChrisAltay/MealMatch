<!-- search.php -->

<?php
header('Content-Type: application/json');

// Get the raw POST data
$data = json_decode(file_get_contents('php://input'), true);
$ingredients = $data['ingredients'] ?? [];

// Validate input
if (empty($ingredients)) {
    http_response_code(400);
    echo json_encode(['error' => 'No ingredients provided']);
    exit;
}

// Function to search meals by ingredient
function searchMealsByIngredients($ingredients) {
    $apiBaseUrl = 'https://www.themealdb.com/api/json/v1/1/';
    $allMeals = [];

    foreach ($ingredients as $ingredient) {
        $url = $apiBaseUrl . 'filter.php?i=' . urlencode($ingredient);
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if (isset($data['meals'])) {
            $allMeals = array_merge($allMeals, $data['meals']);
        }
    }

    // Remove duplicate meals
    $uniqueMeals = [];
    $mealIds = [];
    foreach ($allMeals as $meal) {
        if (!in_array($meal['idMeal'], $mealIds)) {
            $uniqueMeals[] = $meal;
            $mealIds[] = $meal['idMeal'];
        }
    }

    return $uniqueMeals;
}

try {
    $meals = searchMealsByIngredients($ingredients);
    echo json_encode($meals);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred while searching']);
}
