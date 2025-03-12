<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
require 'dbconfig.php'; // Include the database connection

// Fetch saved meals
$stmt = $conn->prepare('SELECT * FROM saved_meals WHERE user_id = ?');
$stmt->execute([$_SESSION['user_id']]);
$savedMeals = $stmt->fetchAll();

// Fetch meal details for saved meals
$favoriteMeals = [];
foreach ($savedMeals as $savedMeal) {
    $mealId = $savedMeal['meal_id'];
    $response = file_get_contents("https://www.themealdb.com/api/json/v1/1/lookup.php?i=$mealId");
    $data = json_decode($response, true);
    if ($data && isset($data['meals'][0])) {
        $favoriteMeals[] = $data['meals'][0];
    }
}

// require_once 'vendor/autoload.php';  // Include the Google client

// $client = new Google_Client();
// $client->setAuthConfig('client_secret_1045868393633-9moipf0u97pvqhs30nuqj4ga0fkjbhqv.apps.googleusercontent.com.json');
// $client->setRedirectUri('http://localhost/Meal-Match/oauth2callback.php');
// $client->addScope(Google_Service_Calendar::CALENDAR);
// $client->setAccessType('offline');
// $client->setPrompt('select_account consent');

// Check if user has a valid access token
// if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
//     $client->setAccessToken($_SESSION['access_token']);
//     $service = new Google_Service_Calendar($client);
// } else {
//     header('Location: oauth2callback.php');
//     exit();
// }
// ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Profile Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <script>

        function rateMeal(mealId) {
            const rating = prompt('Rate this meal (1-5):');
            if (rating && rating >= 1 && rating <= 5) {
                fetch('rateMeal.php', {  // Updated to point to the correct PHP file
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ mealId, rating }),
                })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.success ? 'Meal rated successfully!' : data.message);
                    })
                    .catch(error => {
                        console.error('Error rating meal:', error);
                        alert('An error occurred while rating the meal.');
                    });
            } else {
                alert('Please enter a valid rating (1-5).');
            }
        }

        function bookmarkMeal(mealId) {
            // Redirect to calendar.php
            window.location.href = `calendar.php?mealId=${mealId}`;
        }



        // Function to delete a saved meal
        function deleteMeal(mealId) {
            if (confirm("Are you sure you want to delete this meal?")) {
                fetch('saveMeal.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ mealId: mealId })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            location.reload(); // Reload the page to reflect changes
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting meal:', error);
                        alert('Failed to delete meal. Please try again.');
                    });
            }
        }



        // Function to show recipe details in a modal
        function showRecipeDetails(mealId) {
            fetch(`https://www.themealdb.com/api/json/v1/1/lookup.php?i=${mealId}`)
                .then(response => response.json())
                .then(data => {
                    const meal = data.meals[0];
                    const ingredients = [];
                    for (let i = 1; i <= 20; i++) {
                        if (meal[`strIngredient${i}`]) {
                            ingredients.push(`${meal[`strIngredient${i}`]} - ${meal[`strMeasure${i}`]}`);
                        }
                    }

                    const modalContent = `
                        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
                            <div class="bg-white p-6 rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                                <h2 class="text-2xl font-bold mb-4">${meal.strMeal}</h2>
                                <img src="${meal.strMealThumb}" alt="${meal.strMeal}" class="w-full h-64 object-cover mb-4">
                                <h3 class="text-xl font-semibold mb-2">Ingredients:</h3>
                                <ul class="mb-4">
                                    ${ingredients.map(ing => `<li>${ing}</li>`).join('')}
                                </ul>
                                <h3 class="text-xl font-semibold mb-2">Instructions:</h3>
                                <p class="mb-4">${meal.strInstructions}</p>
                                <button onclick="closeModal()" class="bg-red-500 text-white px-4 py-2 rounded">Close</button>
                            </div>
                        </div>
                    `;

                    document.body.insertAdjacentHTML('beforeend', modalContent);
                })
                .catch(error => {
                    console.error('Error fetching recipe details:', error);
                    alert('Failed to load recipe details. Please try again.');
                });
        }

        // Function to close the modal
        function closeModal() {
            const modal = document.querySelector('.fixed.inset-0');
            if (modal) modal.remove();
        }
    </script>
</head>

<body class="font-sans">
    <div class="container mx-auto p-4">
        <!-- Navigation -->
        <nav class="flex justify-between items-center py-4 border-b">
            <div class="text-lg font-bold">MealMatch</div>
            <div class="space-x-4">
                <a href="index.php" class="text-gray-600 hover:text-gray-900">Home</a>
                <a href="about.php" class="text-gray-600 hover:text-gray-900">About</a>
                <a href="profile.php" class="text-gray-600 hover:text-gray-900">Google Calendar</a>

                <a href="logout.php" class="text-gray-600 hover:text-gray-900">Logout</a>
            </div>
        </nav>

        <!-- Profile Section -->
        <section class="p-8 border-b">
            <h1 class="text-center text-2xl mb-4">Profile</h1>
            <div class="flex justify-center space-x-8 mb-8">
                <div>
                    Username: <span class="text-gray-700"><?php echo $_SESSION['username'] ?? 'N/A'; ?></span>
                </div>

                <div>
                    Email: <span class="text-gray-700"><?php echo $_SESSION['email'] ?? 'N/A'; ?></span>
                </div>

                <div>
                    <a href="oauth2callback.php" class="text-blue-500 hover:underline">Google Calendar</a>
                </div>
            </div>

            <div class="flex justify-center space-x-4">

                <a href="change_password.php" class="border px-4 py-2">Change Password</a>

                <a href="calendar.php" class="border px-4 py-2">Google Calendar</a>

                <form action="delete_account.php" method="GET">
                    <button type="submit" class="border px-4 py-2">Delete Account</button>
                </form>
            </div>
        </section>

        <!-- Saved Meals -->
        <section>
            <h2 class="text-xl font-semibold mb-4">Your Saved Meals</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                <?php foreach ($favoriteMeals as $meal): ?>
                    <div class="recipe-card border p-4 w-64 mb-4">
                        <img src="<?= $meal['strMealThumb'] ?>" alt="<?= $meal['strMeal'] ?>" class="w-full h-auto mb-2">
                        <p class="text-center"><?= $meal['strMeal'] ?></p>
                        <div class="flex justify-between mt-2">
                            <button class="border p-2" onclick="deleteMeal('<?= $meal['idMeal'] ?>')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                            <button class="border p-2" onclick="rateMeal('<?= $meal['idMeal'] ?>')">
                                <i class="fas fa-star"></i> Rate
                            </button>
                            <button class="border p-2" onclick="bookmarkMeal('<?= $meal['idMeal'] ?>')">
                                <i class="fas fa-calendar"></i> Bookmark
                            </button>
                        </div>
                        <button class="border p-2 w-full mt-2" onclick="showRecipeDetails('<?= $meal['idMeal'] ?>')">
                            View Recipe
                        </button>

                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</body>

</html>