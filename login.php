<html>
<head>
    <title>Login Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
    <?php
    session_start();
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
                header('Location: index.php');
                exit();
            } else {
                $error = 'Invalid username or password';
            }
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
    ?>

    <header class="w-full bg-white shadow">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <div class="text-lg font-semibold">
                [Title Here]
            </div>
            <nav class="space-x-4">
                <a href="index.php" class="text-gray-600 hover:text-gray-900">Home</a>
                <a href="about.php" class="text-gray-600 hover:text-gray-900">About</a>
                <?php if (isset($_SESSION['username'])): ?>
                    <a href="profile.php" class="text-gray-600 hover:text-gray-900">Profile</a>
                <?php else: ?>
                    <a href="login.php" class="text-gray-600 hover:text-gray-900">Login</a>
                    <a href="signup.php" class="text-gray-600 hover:text-gray-900">Sign up</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="flex flex-col items-center justify-center flex-1 w-full">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md border">
            <h2 class="text-2xl font-semibold mb-6 text-center">Login</h2>
            <?php if ($error): ?>
                <div class="text-red-500 text-center mb-4"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST" action="" class="space-y-4">
                <div class="flex items-center">
                    <label for="username" class="w-1/3 text-right pr-4">Username:</label>
                    <input type="text" id="username" name="username" placeholder="[Insert Username Here]" class="w-2/3 p-2 border rounded" required>
                </div>
                <div class="flex items-center">
                    <label for="password" class="w-1/3 text-right pr-4">Password:</label>
                    <input type="password" id="password" name="password" placeholder="[Insert Password Here]" class="w-2/3 p-2 border rounded" required>
                </div>
                <div class="flex justify-center">
                    <button type="submit" class="px-4 py-2 border rounded">Login</button>
                </div>
            </form>
            <div class="text-center mt-4">
                <p>Don't Have an account: <a href="signup.php" class="text-blue-600 hover:underline">Sign Up</a></p>
            </div>
        </div>
    </main>
</body>
</html>
