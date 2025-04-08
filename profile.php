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

$isLoggedIn = isset($_SESSION['username']); // Check if the user is logged in
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Profile Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x/dist/cdn.min.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <script>

        // Check if the user is logged in
        function isLoggedIn() {
            return <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
        }

        async function rateMeal(mealId) {
    const ratingInput = document.getElementById(`rating-${mealId}`);
    const rating = parseFloat(ratingInput.value);

    if (!rating || rating < 1 || rating > 5) {
        alert("Please enter a valid rating (1-5).");
        return;
    }

    let response = await fetch("rateMeal.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ mealId, rating }),
    });

    let result = await response.json();

    if (!result.success && result.message.includes("Overwrite")) {
        if (confirm(result.message)) {
            response = await fetch("rateMeal.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ mealId, rating, overwrite: true }),
            });

            result = await response.json();
        } else {
            return;
        }
    }

    if (result.success) {
        alert(result.message);
        updateRatingDisplay(mealId, result.averageRating, result.userCount);
    } else {
        alert(result.message);
    }
}


        function generateStars(rating) {
    const fullStars = Math.floor(rating);
    const halfStar = rating % 1 >= 0.5 ? 1 : 0;
    const emptyStars = 5 - fullStars - halfStar;

    return "★".repeat(fullStars) + (halfStar ? "✩" : "") + "☆".repeat(emptyStars);
}


       function updateRatingDisplay(mealId, averageRating = 0, userCount = 0) {
    const ratingInfo = document.getElementById(`rating-info-${mealId}`);
    ratingInfo.innerHTML = `
        <span class="text-yellow-500 text-lg">${generateStars(averageRating)}</span>
        <span class="ml-2">${averageRating.toFixed(1)} - (${userCount} users)</span>
    `;
}


        async function loadRatings(mealId) {
    const response = await fetch("rateMeal.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ mealId }),
    });

    const result = await response.json();

    updateRatingDisplay(mealId, result.averageRating, result.userCount);
}


        async function bookmarkMeal(mealId) {
            // Check if the user is logged in using PHP session data
            const userLoggedIn = <?php echo isset($_SESSION['username']) ? 'true' : 'false'; ?>;
            if (!userLoggedIn) {
                alert('Please log in to bookmark meals.');
                return;
            }

            try {
                // Fetch meal details
                const response = await fetch(`https://www.themealdb.com/api/json/v1/1/lookup.php?i=${mealId}`);
                const data = await response.json();

                if (!data.meals || data.meals.length === 0) {
                    alert('Meal details not found.');
                    return;
                }

                const mealName = data.meals[0].strMeal; // Extract meal name

                // Create Google Calendar link
                createGoogleCalendarLink(mealName);

            } catch (error) {
                console.error('Error fetching meal details:', error);
                alert('Failed to retrieve meal details.');
            }
        }



        function createGoogleCalendarLink(mealName) {
            // Set event start and end times
            const startDate = new Date();
            startDate.setHours(12, 0, 0);
            const endDate = new Date(startDate);
            endDate.setHours(13, 0, 0);

            // Generate Google Calendar link
            const googleCalendarLink = `https://www.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(mealName)}&dates=${startDate.toISOString().replace(/-|:|\.\d+/g, '')}/${endDate.toISOString().replace(/-|:|\.\d+/g, '')}`;

            // Redirect user to Google Calendar
            window.location.href = googleCalendarLink;
        }


        function deleteMeal(mealId) {
            if (confirm("Are you sure you want to delete this meal?")) {
                fetch('saveMeal.php', {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ mealId: mealId })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            location.reload();
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
                                <ul class="mb-4">${ingredients.map(ing => `<li>${ing}</li>`).join('')}</ul>
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


        function closeModal() {
            const modal = document.querySelector('.fixed.inset-0');
            if (modal) modal.remove();
        }

        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll(".recipe-card").forEach((card) => {
                const mealId = card.getAttribute("data-mealid");
                loadRatings(mealId);
            });
        });
    </script>
</head>


<body class="font-sans">
    <div class="container mx-auto">
        <!-- Navigation -->
        <nav x-data="{ menuOpen: false }"
            class="flex justify-between items-center py-4 px-6 border-b shadow-md relative">
            <!-- MealMatch Title (Smaller on Mobile) -->
            <div class="text-4xl font-extrabold ml-[1] md:ml-[2] lg:ml-20">
                <span class="text-black">Meal</span><span class="text-blue-500">Match</span>
            </div>

            <!-- Hamburger Menu Button (Mobile Only) -->
            <button class="md:hidden text-3xl z-50 transition-all duration-300" @click="menuOpen = !menuOpen">
                <span x-show="!menuOpen">☰</span>
                <span x-show="menuOpen">✖</span>
            </button>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex space-x-3 mr-[10%]">
                <a href="index.php"
                    class="text-xl font-semibold text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-3 py-2 transition">Home</a>
                <a href="about.php"
                    class="text-xl font-semibold text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-3 py-2 transition">About</a>
                <a href="profile.php"
                    class="text-xl font-semibold text-white bg-blue-500 hover:bg-blue-700 rounded-md px-3 py-2 transition">Profile</a>
                <a href="logout.php"
                    class="text-xl font-semibold text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-3 py-2 transition">Logout</a>
            </div>

            <!-- Mobile Menu Overlay -->
            <div x-show="menuOpen" x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden"
                @click="menuOpen = false"></div>

            <!-- Mobile Slide-in Menu -->
            <div x-show="menuOpen" x-cloak x-transition:enter="transition transform duration-300 ease-in-out"
                x-transition:enter-start="translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
                x-transition:leave="transition transform duration-300 ease-in-out"
                x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="translate-x-full opacity-0"
                class="fixed top-0 right-0 h-full w-64 bg-white shadow-lg z-50 p-6 md:hidden">

                <!-- Close Button -->
                <button class="absolute top-4 right-4 text-3xl text-blue-600 hover:text-blue-800"
                    @click="menuOpen = false">✖</button>

                <!-- Menu Items -->
                <div class="mt-12 flex flex-col space-y-4 text-xl">
                    <a href="index.php"
                        class="text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-4 py-2 transition"
                        @click="menuOpen = false">Home</a>
                    <a href="about.php"
                        class="text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-4 py-2 transition"
                        @click="menuOpen = false">About</a>
                    <a href="profile.php"
                        class="text-white bg-blue-500 hover:bg-blue-700 rounded-md px-4 py-2 transition"
                        @click="menuOpen = false">Profile</a>
                    <a href="logout.php"
                        class="text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-4 py-2 transition"
                        @click="menuOpen = false">Logout</a>
                </div>
            </div>

        </nav>

        <!-- Profile Section -->
        <section class="p-8 border-b">
            <h1 class="text-center text-2xl mb-4 font-bold">Profile</h1>
            <div class="flex justify-center space-x-8 mb-8">
                <div>
                    Username: <span class="text-gray-700"><?php echo $_SESSION['username'] ?? 'N/A'; ?></span>
                </div>

                <div>
                    Email: <span class="text-gray-700"><?php echo $_SESSION['email'] ?? 'N/A'; ?></span>
                </div>

            </div>

            <div class="flex justify-center space-x-4">

                <a href="change_password.php"
                    class="bg-gray-500 text-white px-4 py-2 hover:bg-gray-400 hover:text-white-600 rounded-md">Change
                    Password</a>

                <form action="delete_account.php" method="GET">
                    <button type="submit"
                        class="bg-red-500 text-white px-4 py-2 hover:bg-red-700 hover:text-white-600 rounded-md">Delete
                        Account</button>
                </form>
            </div>
        </section>

        <!-- Saved Meals Section -->
        <section>
            <h2 class="text-xl font-bold mb-4 ml-6 mt-2">Your Saved Meals</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <?php foreach ($favoriteMeals as $meal): ?>
                    <div class="recipe-card border p-4 w-64 mb-4 shadow-2xl ml-20 md:ml-30 rounded-lg overflow-hidden bg-white"
                        data-mealid="<?= $meal['idMeal'] ?>">
                        <!-- Image Section -->
                        <div class="w-full h-40 overflow-hidden">
                            <img src="<?= $meal['strMealThumb'] ?>" alt="<?= $meal['strMeal'] ?>"
                                class="w-full h-full object-cover rounded-t-lg">
                        </div>

                        <!-- Meal Name -->
                        <p class="text-center font-bold text-lg"><?= $meal['strMeal'] ?></p>

                        <!-- Ratings Section -->
                        <div id="rating-info-<?= $meal['idMeal'] ?>" class="text-center mt-2 text-sm text-gray-600">Loading
                            rating...</div>
                        <div class="flex flex-col mt-2">
                            <input type="number" min="1" max="5" step="0.1" id="rating-<?= $meal['idMeal'] ?>"
                                placeholder="Rate (1-5)" class="border p-2 mb-2 rounded-md w-full" />
                            <button onclick="rateMeal('<?= $meal['idMeal'] ?>')"
                                class="border p-2 bg-yellow-400 hover:bg-yellow-300 rounded-md w-full">
                                <i class="fas fa-star"></i> Submit Rating
                            </button>
                        </div>

                        <!-- Buttons Section -->
                        <div class="flex justify-between mt-2">
                            <button onclick="deleteMeal('<?= $meal['idMeal'] ?>')"
                                class="border p-2 bg-red-400 hover:bg-red-300 rounded-md flex-1 mr-1">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                            <button onclick="bookmarkMeal('<?= $meal['idMeal'] ?>')"
                                class="border p-2 bg-blue-400 hover:bg-blue-300 rounded-md flex-1 ml-1">
                                <i class="fas fa-calendar"></i> Bookmark
                            </button>

                        </div>

                        <!-- View Recipe Button -->
                        <button onclick="showRecipeDetails('<?= $meal['idMeal'] ?>')"
                            class="border p-2 w-full mt-2 bg-gray-200 hover:bg-gray-100 rounded-md">
                            View Recipe
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

    </div>
</body>


</html>