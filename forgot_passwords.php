<!-- forgot_password.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x/dist/cdn.min.js" defer></script>
</head>

<?php
session_start();
$error = '';
$success = '';
include 'dbconfig.php'; // Ensure the database connection is included

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Database connection
    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username AND email = :email");
        $stmt->execute(['username' => $username, 'email' => $email]);
        $user = $stmt->fetch();

        if ($user) {
            $_SESSION['reset_user_id'] = $user['id']; // Store user ID in session
            header('Location: reset_password.php'); // Redirect to reset password page
            exit();
        } else {
            $error = 'Invalid username or email';
        }
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
?>

<body class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
    <!-- Forgot Password Form -->
    <main class="flex flex-col items-center justify-center flex-grow">
        <div class="bg-white p-8 rounded-lg shadow-md border mb-[25%] lg:mb-[0%] w-[320px] md:w-[350px] lg:w-[400px]">
            <h2 class="text-2xl font-semibold mb-6 text-center">Forgot Password</h2>
            <?php if ($error): ?>
                <div class="text-red-500 text-center mb-4"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="" class="space-y-4">
                <div class="flex items-center">
                    <label for="username" class="w-1/3 text-right pr-4">Username:</label>
                    <input type="text" id="username" name="username" placeholder="Enter Username" class="w-2/3 p-2 border rounded hover:border-gray-400" required>
                </div>
                <div class="flex items-center">
                    <label for="email" class="w-1/3 text-right pr-4">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Enter Email" class="w-2/3 p-2 border rounded hover:border-gray-400" required>
                </div>
                <div class="flex justify-center">
                    <button type="submit" class="px-4 py-2 border text-white border-blue-500 rounded shadow-lg bg-gradient-to-r from-blue-500 to-gray-300 transition-all duration-300 hover:from-blue-400 hover:to-gray-200">
                        Verify
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
