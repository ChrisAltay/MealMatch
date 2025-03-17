<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Sign Up Page</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x/dist/cdn.min.js" defer></script>

    <script>
        function validateForm() {
            let password = document.getElementById("password").value;
            let confirmPassword = document.getElementById("confirm_password").value;
            let email = document.getElementById("email").value;
            let confirmEmail = document.getElementById("confirm_email").value;

            if (password !== confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }

            if (email !== confirmEmail) {
                alert("Emails do not match.");
                return false;
            }

            return true;
        }
    </script>
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
                <a href="index.php" class="text-xl font-semibold text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-3 py-2 transition">Home</a>
                <a href="about.php" class="text-xl font-semibold text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-3 py-2 transition">About</a>
                <a href="login.php" class="text-xl font-semibold text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-3 py-2 transition">Login</a>
                <a href="signup.php" class="text-xl font-semibold text-white bg-blue-500 hover:bg-blue-700 rounded-md px-3 py-2 transition">Sign up</a>
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
                    <a href="login.php" class="text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-4 py-2 transition" @click="menuOpen = false">Login</a>
                    <a href="signup.php" class="text-white bg-blue-500 hover:bg-blue-700 rounded-md px-4 py-2 transition" @click="menuOpen = false">Sign up</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex flex-col items-center justify-center flex-grow">
        <div class="bg-white border border-gray-300 p-6 rounded-lg shadow-md w-[300px] md:w-[350px] lg:w-[400px]">
            <h2 class="text-center text-xl font-semibold mb-6">Sign Up</h2>
            
            <?php
            include 'dbconfig.php'; // Ensure the database connection is included

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $username = $_POST['username'];
                $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $email = $_POST['email'];

                // Check if the username already exists
                $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
                $stmt->execute([$username]);
                $usernameExists = $stmt->fetchColumn();

                // Check if the email already exists
                $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $emailExists = $stmt->fetchColumn();

                if ($usernameExists > 0) {
                    echo "<p class='text-red-500 text-center'>Error: Username already taken. Please choose another username.</p>";
                } elseif ($emailExists > 0) {
                    echo "<p class='text-red-500 text-center'>Error: Email is already used. Please use another email!</p>";
                } else {
                    // Insert the new user into the database if username and email are unique
                    $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";

                    try {
                        $conn->query($sql);
                        echo "<p class='text-green-500 text-center'>Sign up successful. Redirecting to login page...</p>";
                        header("refresh:2;url=login.php");
                    } catch (PDOException $e) {
                        echo "<p class='text-red-500 text-center'>Error: " . $e->getMessage() . "</p>";
                    }
                }
                $conn = null; // Close the database connection
            }
            ?>

       <form class="space-y-4" method="POST" action="" onsubmit="return validateForm()">
                <div>
                    <label class="block text-gray-700">Username:</label>
                    <input type="text" name="username" placeholder="Enter Username" class="w-full border p-2 rounded hover:border-gray-500" required>
                </div>
                <div>
                    <label class="block text-gray-700">Password:</label>
                    <input type="password" name="password" id="password" placeholder="Enter Password" class="w-full border p-2 rounded hover:border-gray-500" required>
                </div>
                <div>
                    <label class="block text-gray-700">Confirm Password:</label>
                    <input type="password" id="confirm_password" placeholder="Confirm Password" class="w-full border p-2 rounded hover:border-gray-500" required>
                </div>
                <div>
                    <label class="block text-gray-700">E-mail:</label>
                    <input type="email" name="email" id="email" placeholder="Enter Email" class="w-full border p-2 rounded hover:border-gray-500" required>
                </div>
                <div>
                    <label class="block text-gray-700">Confirm E-mail:</label>
                    <input type="email" id="confirm_email" placeholder="Confirm E-mail" class="w-full border p-2 rounded hover:border-gray-500" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="px-4 py-2 border text-white border-blue-500 rounded shadow-lg bg-gradient-to-r from-blue-500 to-gray-300 transition-all duration-300 hover:from-blue-400 hover:to-gray-200">Sign Up</button>
                </div>
            </form>
            <div class="text-center mt-4">
                <span>Have an account? </span>
                <a href="login.php" class="text-blue-600 hover:underline">Login</a>
            </div>
        </div>
    </main>
</body>
</html>


