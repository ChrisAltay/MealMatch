<?php
session_start(); // Start the session

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'dbconfig.php'; // Include the database configuration file

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputPassword = $_POST['password'];
    $userId = $_SESSION['user_id'];
    
    // Prepare to fetch the user's password from the database
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $hashedPassword = $stmt->fetchColumn();

    // Verify the password
    if (password_verify($inputPassword, $hashedPassword)) {
        // Delete the account
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$userId]);

        // Destroy the session
        session_destroy();
        header("Location: index.php");
        exit();
    } else {
        $error = "Incorrect password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Account</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x/dist/cdn.min.js" defer></script>
</head>

<body class="font-sans">
    <div class="container mx-auto">

        <!-- Navigation -->
        <nav x-data="{ menuOpen: false }" class="flex justify-between items-center py-4 px-6 border-b shadow-md relative">
            <!-- MealMatch Title -->
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

                <?php if (isset($_SESSION['username'])): ?>
                    <a href="profile.php" class="text-xl font-semibold text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-3 py-2 transition">Profile</a>
                    <a href="logout.php" class="text-xl font-semibold text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-3 py-2 transition">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="text-xl font-semibold text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-3 py-2 transition">Login</a>
                    <a href="signup.php" class="text-xl font-semibold text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-3 py-2 transition">Sign up</a>
                <?php endif; ?>
            </div>

            <!-- Mobile Menu Overlay -->
            <div x-show="menuOpen" x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden" @click="menuOpen = false"></div>

            <!-- Mobile Slide-in Menu -->
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

                    <?php if (isset($_SESSION['username'])): ?>
                        <a href="profile.php" class="text-white bg-blue-500 hover:bg-blue-700 rounded-md px-4 py-2 transition" @click="menuOpen = false">Profile</a>
                        <a href="logout.php" class="text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-4 py-2 transition" @click="menuOpen = false">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-4 py-2 transition" @click="menuOpen = false">Login</a>
                        <a href="signup.php" class="text-white bg-blue-500 hover:bg-blue-700 rounded-md px-4 py-2 transition" @click="menuOpen = false">Sign up</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>

        <!-- Delete Account Section -->
        <div class="container mx-auto p-8">
            <h1 class="text-2xl mb-4 font-bold">Delete Account</h1>
            <p class="font-semibold">To delete your account, please enter your password:</p>

            <?php if (isset($error)): ?>
                <p class="text-red-500"><?php echo $error; ?></p>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-4">
                    <input type="password" name="password" id="password" placeholder="Enter existing password here" required class="border px-4 py-2 w-full rounded hover:border-gray-400">
                </div>
                <button type="submit" class="bg-red-500 text-white px-4 py-2 hover:bg-red-700 rounded-md">Delete Account</button>
            </form>

            <div class="text-center mt-4">
                <a href="profile.php" class="text-blue-500 hover:underline">Back to Profile</a>
            </div>
        </div>
    </div>
</body>
</html>
