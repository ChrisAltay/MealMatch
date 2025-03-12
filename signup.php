<html>
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
    <header class="w-full bg-white shadow">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <div class="text-lg font-semibold">
                NealMatch
            </div>
            <nav class="space-x-4">
                <a href="index.php" class="text-gray-600 hover:text-gray-900">Home</a>
                <a href="about.php" class="text-gray-600 hover:text-gray-900">About</a>
                <a href="login.php" class="text-gray-600 hover:text-gray-900">Login</a>
                <a href="signup.php" class="text-gray-600 hover:text-gray-900">Sign up</a>
            </nav>
        </div>
    </header>
    <main class="flex flex-col items-center justify-center flex-grow">
        <div class="w-full max-w-sm bg-white border border-gray-300 p-6 rounded-lg shadow-md">
            <h2 class="text-center text-xl font-semibold mb-6">Sign Up</h2>
            <?php
            include 'dbconfig.php'; // Ensure the database connection is included

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $username = $_POST['username'];
                $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $email = $_POST['email'];

                $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";

                try {
                    $conn->query($sql);
                    echo "<p class='text-green-500 text-center'>Sign up successful. Redirecting to login page...</p>";
                    header("refresh:2;url=login.php");
                } catch (PDOException $e) {
                    echo "<p class='text-red-500 text-center'>Error: " . $e->getMessage() . "</p>";
                }
                $conn = null; // Close the database connection
            }
            ?>
            <form class="space-y-4" method="POST" action="">
                <div>
                    <label class="block text-gray-700">Username:</label>
                    <input type="text" name="username" placeholder="[Insert Username Here]" class="w-full border border-gray-400 p-2 rounded" required>
                </div>
                <div>
                    <label class="block text-gray-700">Password:</label>
                    <input type="password" name="password" placeholder="[Insert Password Here]" class="w-full border border-gray-400 p-2 rounded" required>
                </div>
                <div>
                    <label class="block text-gray-700">E-mail:</label>
                    <input type="email" name="email" placeholder="[Insert E-mail Here]" class="w-full border border-gray-400 p-2 rounded" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="border border-gray-400 px-4 py-2 rounded">Sign Up</button>
                </div>
            </form>
            <div class="text-center mt-4">
                <span>Have an account: </span>
                <a href="login.php" class="text-blue-600 underline">Login</a>
            </div>
        </div>
    </main>
</body>
</html>
