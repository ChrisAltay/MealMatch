<!-- index.php -->

<?php
session_start(); // Start session to handle user login
$isLoggedIn = isset($_SESSION['username']); // Check if the user is logged in
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>MealMatch</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <script>
        function scrollToSearch() {
            document.getElementById('search-section').scrollIntoView({ behavior: 'smooth' });
        }

        // Check if the user is logged in
        function isLoggedIn() {
            return <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
        }

        // Function to save a meal
        function saveMeal(mealId) {
            fetch('saveMeal.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ mealId: mealId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Meal saved successfully.');
                } else {
                    alert('Error occurred: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error saving meal:', error);
                alert('Error occurred while saving the meal.');
            });
        }

        // Function to rate a meal
        async function rateMeal(mealId) {
            if (!isLoggedIn()) {
                alert('Please log in to rate meals.');
                return;
            }
            const rating = prompt('Rate this meal (1-5):');
            if (rating && rating >= 1 && rating <= 5) {
                try {
                    const response = await fetch('rateMeal.php', {  // Updated to point to the correct PHP file
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ mealId, rating }),
                    });
                    const data = await response.json();
                    alert(data.success ? 'Meal rated successfully!' : data.message);
                } catch (error) {
                    console.error('Error rating meal:', error);
                    alert('An error occurred while rating the meal.');
                }
            } else {
                alert('Please enter a valid rating (1-5).');
            }
        }

        // Function to bookmark a meal
      // Function to bookmark a meal
async function bookmarkMeal(mealId) {
    if (!isLoggedIn()) {
        alert('Please log in to bookmark meals.');
        return;
    }
    // Redirect to calendar.php
    window.location.href = `calendar.php?mealId=${mealId}`;
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

                <?php if ($isLoggedIn): ?>
                    <a href="profile.php" class="text-gray-600 hover:text-gray-900">Profile</a>
                    <a href="logout.php" class="text-gray-600 hover:text-gray-900">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="text-gray-600 hover:text-gray-900">Login</a>
                    <a href="signup.php" class="text-gray-600 hover:text-gray-900">Sign up</a>
                <?php endif; ?>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="flex flex-wrap md:flex-nowrap mb-8">
            <div class="w-full md:w-1/3 p-4">
                <button class="border px-4 py-2" onclick="scrollToSearch()">Browse Dish</button>
            </div>
            <div class="w-full md:w-2/3 p-4">
                <div class="border p-4">
                    <img alt="Image of Food Here" class="w-full h-auto" src="https://placehold.co/600x400" />
                </div>
            </div>
        </div>

        <!-- Search Section -->
        <div class="border-t pt-4" id="search-section">
            <h2 class="text-center text-xl mb-4">Search For A Dish</h2>
            <form id="search-form" class="flex flex-col items-center mb-8">
                <div class="relative w-1/2">
                    <input id="search-input" class="border p-2 w-full"
                        placeholder="Enter ingredients (e.g., chicken, broccoli)" type="text" />
                    <ul id="suggestions-list" class="absolute z-10 bg-white border border-gray-300 w-full mt-1 hidden">
                    </ul>
                </div>
                <button type="submit" class="border p-2 mt-2">
                    <i class="fas fa-search"></i> Search
                </button>
            </form>

            <!-- Search Results -->
            <div id="search-results" class="flex flex-wrap justify-center gap-4"></div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="search.js" defer></script>
</body>

</html>