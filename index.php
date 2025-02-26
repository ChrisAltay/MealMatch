<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>MealMatch</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <script>
        function scrollToSearch() {
            document.getElementById('search-section').scrollIntoView({ behavior: 'smooth' });
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

                <?php
                session_start();
                $isLoggedIn = isset($_SESSION['username']); // Check if the user is logged in
                if ($isLoggedIn): ?>
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
                <div class="border p-4 mb-4">
                    <p>Filler Filler Filler Filler</p>
                    <p>Filler Filler Filler Filler</p>
                    <p>Filler Filler Filler Filler</p>
                </div>
                <button class="border px-4 py-2" onclick="scrollToSearch()">Browse Dish</button>
            </div>
            <div class="w-full md:w-2/3 p-4">
                <div class="border p-4">
                    <img alt="Image of Food Here" class="w-full h-auto" src="https://placehold.co/600x400"/>
                </div>
            </div>
        </div>

        <!-- Search Section -->
        <div class="border-t pt-4" id="search-section">
            <h2 class="text-center text-xl mb-4">Search For A Dish</h2>
            <form id="search-form" class="flex flex-col items-center mb-8">
                <div class="relative w-1/2">
                    <input id="search-input" class="border p-2 w-full" placeholder="Enter ingredients (e.g., chicken, broccoli)" type="text"/>
                    <ul id="suggestions-list" class="absolute z-10 bg-white border border-gray-300 w-full mt-1 hidden"></ul>
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