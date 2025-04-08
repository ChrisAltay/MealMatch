<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['username'])) {
    // If logged in, redirect to the home page
    header('Location: index.php');
    exit();
}

$error = '';

include 'dbconfig.php'; // Ensure the database connection is included

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Database connection
    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user['id']; // Store user ID in session
            $_SESSION['email'] = $user['email']; // Store email in session
            header('Location: index.php?login');
            exit();
        } else {
            $error = 'Invalid username or password';
        }
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Login Page</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x/dist/cdn.min.js" defer></script>
</head>

<body class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
    <!-- Navigation -->
    <nav x-data="{ menuOpen: false }" class="w-full bg-white shadow-md py-4 px-6 border-b relative">
        <div class="container mx-auto flex justify-between items-center">
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
                <a href="index.php"
                    class="text-xl font-semibold text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-3 py-2 transition">Home</a>
                <a href="about.php"
                    class="text-xl font-semibold text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-3 py-2 transition">About</a>

                <?php if (isset($_SESSION['username'])): ?>
                    <a href="profile.php"
                        class="text-xl font-semibold text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-3 py-2 transition">Profile</a>
                <?php else: ?>
                    <a href="login.php"
                        class="text-xl font-semibold text-white bg-blue-500 hover:bg-blue-700 rounded-md px-3 py-2 transition">Login</a>
                    <a href="signup.php"
                        class="text-xl font-semibold text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-3 py-2 transition">Sign
                        up</a>
                <?php endif; ?>
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

                    <?php if (isset($_SESSION['username'])): ?>
                        <a href="profile.php"
                            class="text-white bg-blue-500 hover:bg-blue-700 rounded-md px-4 py-2 transition"
                            @click="menuOpen = false">Profile</a>
                    <?php else: ?>
                        <a href="login.php"
                            class="text-blue-500 text-white bg-blue-500 hover:bg-blue-700 rounded-md px-4 py-2 transition"
                            @click="menuOpen = false">Login</a>
                        <a href="signup.php"
                            class="text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-4 py-2 transition"
                            @click="menuOpen = false">Sign up</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Login Form -->
    <main class="flex flex-col items-center justify-center flex-grow">
        <div class="bg-white p-8 rounded-lg shadow-md border mb-[25%] lg:mb-[0%] w-[320px] md:w-[350px] lg:w-[400px]">
            <h2 class="text-2xl font-semibold mb-6 text-center">Login</h2>
            <?php if ($error): ?>
                <div class="text-red-500 text-center mb-4"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="" class="space-y-4">
                <div class="flex items-center">
                    <label for="username" class="w-1/3 text-right pr-4">Username:</label>
                    <input type="text" id="username" name="username" placeholder="Enter Username"
                        class="w-2/3 p-2 border rounded hover:border-gray-400" required>
                </div>
                <div class="flex items-center">
                    <label for="password" class="w-1/3 text-right pr-4">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Enter Password"
                        class="w-2/3 p-2 border rounded hover:border-gray-400" required>
                </div>
                <div class="flex justify-center">
                    <button type="submit"
                        class="px-4 py-2 border text-white border-blue-500 rounded shadow-lg bg-gradient-to-r from-blue-500 to-gray-300 transition-all duration-300 hover:from-blue-400 hover:to-gray-200">
                        Login
                    </button>
                </div>
            </form>
            <div class="text-center mt-4">
                <p>Forgot your password? <a href="forgot_password.php" class="text-blue-600 hover:underline">Reset
                        Password</a></p>
                <p>Don't Have an account? <a href="signup.php" class="text-blue-600 hover:underline">Sign Up</a></p>
            </div>
        </div>
    </main>
</body>

</html>
