<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>About Page</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x/dist/cdn.min.js" defer></script>
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
                <a href="about.php" class="text-xl font-semibold text-white bg-blue-500 hover:bg-blue-700 rounded-md px-3 py-2 transition">About</a>

                <?php
                session_start();
                $isLoggedIn = isset($_SESSION['username']); // Check if the user is logged in
                if ($isLoggedIn): ?>
                    <a href="profile.php" class="text-xl font-semibold text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-3 py-2 transition">Profile</a>
                    <a href="logout.php" class="text-xl font-semibold text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-3 py-2 transition">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="text-xl font-semibold text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-3 py-2 transition">Login</a>
                    <a href="signup.php" class="text-xl font-semibold text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-3 py-2 transition">Sign up</a>
                <?php endif; ?>
            </div>

            <!-- Mobile Menu Overlay (Backdrop) -->
            <div x-show="menuOpen" x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden" @click="menuOpen = false"></div>

            <!-- Mobile Slide-in Menu (Fully Working) -->
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
                    <a href="about.php" class="text-white bg-blue-500 hover:bg-blue-700 rounded-md px-4 py-2 transition" @click="menuOpen = false">About</a>

                    <?php if ($isLoggedIn): ?>
                        <a href="profile.php" class="text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-4 py-2 transition" @click="menuOpen = false">Profile</a>
                        <a href="logout.php" class="text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-4 py-2 transition" @click="menuOpen = false">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-4 py-2 transition" @click="menuOpen = false">Login</a>
                        <a href="signup.php" class="text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-4 py-2 transition" @click="menuOpen = false">Sign up</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="text-center mt-1">
            <div class="bg-blue-50">
                <h1 class="text-2xl font-bold">About</h1>
                <p class="px-5 py-4 border-b shadow-sm">
                    Tired of searching through long recipe blogs just to find a meal you can cook with what you already have at home?
                    MealMatch makes it simple! Just enter your ingredients, and we’ll match you with delicious recipes in seconds. 
                    Save your favorite meals, rate recipes, and even bookmark dishes for later. Whether you're an experienced cook or 
                    just starting out, MealMatch helps you discover and prepare meals effortlessly. Start exploring today and make cooking easier than ever!
                </p>
            </div>
            <h6 class="mt-3 text-2xl font-semibold">Meet the Team!</h6>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mt-5">
                <!-- Profile 1 -->
                <div class="flex flex-col items-center">
                    <div class="w-32 h-32 border rounded-full flex items-center justify-center">
                        <img src="Chrisp.png" alt="chris P" class="rounded-md">
                    </div>
                    <div class="mt-4 text-lg font-semibold">Christopher Paredes</div>
                    <div class="text-gray-600">Project Manager, UX-UI, FrontEnd Dev</div>
                </div>
                <!-- Profile 2 -->
                <div class="flex flex-col items-center">
                    <div class="w-32 h-32 border rounded-full flex items-center justify-center">
                        <img src="ChrisE.png" alt="chris eng" class="rounded-md">
                    </div>
                    <div class="mt-4 text-lg font-semibold">Christopher Eng</div>
                    <div class="text-gray-600">FrontEnd Dev, System Tester</div>
                </div>
                <!-- Profile 3 -->
                <div class="flex flex-col items-center">
                    <div class="w-32 h-32 border rounded-full flex items-center justify-center">
                        <img src="Solomon.jpeg" alt="Solomon" class="rounded-md">
                    </div>
                    <div class="mt-4 text-lg font-semibold">Solomon Thomas</div>
                    <div class="text-gray-600">BackEnd Dev, System Tester</div>
                </div>

                <div class="flex flex-col items-center">
                    <div class="w-32 h-32 border rounded-full flex items-center justify-center">
                        <img src="Adam.png" alt="Adam" class="rounded-md">
                    </div>
                    <div class="mt-4 text-lg font-semibold">Adam Spencer</div>
                    <div class="text-gray-600">Project Manager, System Analyst</div>
                </div>

                <div class="flex flex-col items-center">
                    <div class="w-32 h-32 border rounded-full flex items-center justify-center">
                        <img src="Max.png" alt="Max" class="rounded-md">
                    </div>
                    <div class="mt-4 text-lg font-semibold">Max Kaiser</div>
                    <div class="text-gray-600 mb-8">BackEnd Dev, System Tester</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
