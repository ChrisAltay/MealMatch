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

    <div class="container mx-auto p-8">
        <h1 class="text-2xl mb-4">Delete Account</h1>
        <p>To delete your account, please enter your password:</p>
        <form method="POST">
            <div class="mb-4">
                <input type="password" name="password" id="password" required class="border px-4 py-2 w-full"/>
            </div>
            <button type="submit" class="bg-red-500 text-white px-4 py-2">Delete Account</button>
        </form>
    </div>
</body>
</html>
