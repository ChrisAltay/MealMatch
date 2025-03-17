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

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x/dist/cdn.min.js" defer></script>

    <script>

function toggleMenu() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    }

    // Attach event listener to the hamburger button
    document.querySelector(".md:hidden").addEventListener("click", toggleMenu);

    // Attach event listener to the close button inside the menu
    document.querySelector("#mobile-menu button").addEventListener("click", toggleMenu);


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
                    alert('Meal saved to favorites in profile.');
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
function rateMeal(mealId) {
            if (!isLoggedIn()) {
                alert('Please log in to rate meals.');
                return;
            }
            const ratingInput = document.getElementById(`rating-${mealId}`);
            const rating = ratingInput.value;
            if (rating && rating >= 1 && rating <= 5) {
                fetch('rateMeal.php', {
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
                alert('Please enter a valid rating, numbers(1-5).');
            }
        }

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
                                <h3 class="text-xl font-semibold mb-2">Missing ingredients? Locations to shop below:</h3>
                                <h6 class="text-xl font-semibold">Grocery Stores & Supermarkets:</h6>
                                Walmart | Costco | Whole Foods Market | Aldi | Local Farmers Market | Shoprite | Target | Kroger
                                <h6 class="text-xl font-semibold mt-2">Online Grocery Stores:</h6>
                                Amazon Fresh | FreshDirect | Walmart Grocery Delivery | Instacart | Misfits Market | Boxed | Weee!
                                
                                <h3 class="text-xl font-semibold mb-2 mt-4">Instructions:</h3>
                                <p class="mb-4">${meal.strInstructions}</p>
                                <button onclick="closeModal()" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Close</button>
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

        function toggleMenu() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        }
    </script>

</head>

<body class="font-sans">
    <div class="container mx-auto">
       <!-- Navigation -->
<nav x-data="{ menuOpen: false }" class="flex justify-between items-center py-4 px-6 border-b shadow-md relative">
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
        <a href="index.php" class="text-xl font-semibold text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-3 py-2 transition">Home</a>
        <a href="about.php" class="text-xl font-semibold text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-3 py-2 transition">About</a>

        <?php if ($isLoggedIn): ?>
            <a href="profile.php" class="text-xl font-semibold text-white bg-blue-500 hover:bg-blue-700 rounded-md px-3 py-2 transition">Profile</a>
            <a href="logout.php" class="text-xl font-semibold text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-3 py-2 transition">Logout</a>
        <?php else: ?>
            <a href="login.php" class="text-xl font-semibold text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-3 py-2 transition">Login</a>
            <a href="signup.php" class="text-xl font-semibold text-white bg-blue-500 hover:bg-blue-700 rounded-md px-3 py-2 transition">Sign up</a>
        <?php endif; ?>
    </div>

    <!-- Mobile Menu Overlay (Backdrop) -->
<div x-show="menuOpen" x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden" @click="menuOpen = false"></div>

<!-- Mobile Menu Overlay (Backdrop) -->
<div x-show="menuOpen" x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden" @click="menuOpen = false"></div>

<!-- Mobile Slide-in Menu  -->
<div x-show="menuOpen" x-cloak 
     x-transition:enter="transition transform duration-300 ease-in-out"
     x-transition:enter-start="translate-x-full opacity-0"
     x-transition:enter-end="translate-x-0 opacity-100"
     x-transition:leave="transition transform duration-300 ease-in-out"
     x-transition:leave-start="translate-x-0 opacity-100"
     x-transition:leave-end="translate-x-full opacity-0"
     class="fixed top-0 right-0 h-full w-64 bg-white shadow-lg z-50 p-6 md:hidden">

    <!-- Close Button -->
    <button class="absolute top-4 right-4 text-3xl text-blue-600 hover:text-blue-800" @click="menuOpen = false">✖</button>

    <!-- Menu Items -->
    <div class="mt-12 flex flex-col space-y-4 text-xl">
        <a href="index.php" class="text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-4 py-2 transition" @click="menuOpen = false">Home</a>
        <a href="about.php" class="text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-4 py-2 transition" @click="menuOpen = false">About</a>

        <?php if ($isLoggedIn): ?>
            <a href="profile.php" class="text-white bg-blue-500 hover:bg-blue-700 rounded-md px-4 py-2 transition" @click="menuOpen = false">Profile</a>
            <a href="logout.php" class="text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-4 py-2 transition" @click="menuOpen = false">Logout</a>
        <?php else: ?>
            <a href="login.php" class="text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-4 py-2 transition" @click="menuOpen = false">Login</a>
            <a href="signup.php" class="text-white bg-blue-500 hover:bg-blue-700 rounded-md px-4 py-2 transition" @click="menuOpen = false">Sign up</a>
        <?php endif; ?>
    </div>
</div>

</nav>

        <!-- Main Content -->
        <div class="flex flex-wrap md:flex-nowrap mb-20">     

            <div class="w-full md:w-2/3 p-4 mt-0 md:mt-[110px] ml-[4%] order-2 md:order-1">
                <h1 class="font-bold text-4xl md:text-6xl text bg-gradient-to-r from-blue-500 to-green-500 bg-clip-text text-transparent">Learn. Cook. Save.</h1>
                <h1 class="font-bold text-4xl md:text-6xl text bg-gradient-to-r from-blue-500 to-green-500 bg-clip-text text-transparent">Cooking Made Easy!</h1>
                <p class="max-w-md py-3 text-[20px] md:text-[27px]">Say good bye to long and frustrating food blogs and recipe videos. Access our recipe cards to prepare any dish in minutes.</p>
                <div class="flex">
                    <button class="border px-4 py-2 bg-blue-500 text-white text-2xl rounded-md px-3 md:ml-[20%] mx-auto hover:bg-blue-700" onclick="scrollToSearch()">Browse Dish</button>
                </div>
            </div>
            
            <div class="w-full md:w-2/3 rounded-full drop-shadow-2xl mt-10 order-1 md:order-2">
                <div>
                    <img id="dishImage" alt="Image of Dish" class="w-[80%] h-auto mx-auto md:mx-0 transition duration-500" src="SalmonDish1.png" />
                </div>
            </div>

        </div>
        
      <!-- Search Section -->
<div class="bg-blue-50 py-10" id="search-section">
    <h2 class="text-center text-2xl mb-4 font-bold">Search For A Dish</h2>

    <!-- White Box Around Search Bar and Results -->
    <div class="bg-white shadow-lg rounded-lg mx-auto max-w-screen-lg p-6">
        
        <!-- Search Form -->
        <form id="search-form" class="flex items-center space-x-2">
            <div class="relative flex-grow">
                <input id="search-input" 
                    class="border border-gray-300 p-2 w-full rounded-md focus:bg-white focus:ring-0 focus:border-gray-300" 
                    placeholder="Enter ingredients (e.g., chicken, broccoli)" type="text" />
                <ul id="suggestions-list" 
                    class="absolute z-10 bg-white border border-gray-300 w-full mt-1 hidden">
                </ul>
            </div>
            <button type="submit" 
                class="border p-2 rounded-md bg-blue-500 text-white hover:bg-blue-700 transition">
                <i class="fas fa-search"></i> Search
            </button>
        </form>

        <!-- Search Results -->
        <div id="search-results" class="flex flex-wrap justify-center gap-4 mt-6"></div>
    </div>
</div>


    <!-- Scripts -->
    <script src="search.js" defer></script>

    <script>
    // Function to trigger the spin animation on login or sign-up
    function spinDish() {
        const dishImage = document.getElementById("dishImage");
        if (dishImage) {
            dishImage.classList.add("animate-spin"); // Add spin effect
            setTimeout(() => {
                dishImage.classList.remove("animate-spin"); // Remove spin after 2 seconds
            }, 1000);
        }
    }

    // Run the spinDish function when the user logs in or signs up
    document.addEventListener("DOMContentLoaded", function () {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has("login") || urlParams.has("signup")) {
            spinDish();
        }
    });
</script>

</body>

</html>