<!-- reset_password.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x/dist/cdn.min.js" defer></script>
</head>

<?php
session_start();
$error = '';
$success = '';

if (!isset($_SESSION['reset_user_id'])) {
    header('Location: login.php'); // Redirect if session doesn't have user ID
    exit();
}

include 'dbconfig.php'; // Ensure the database connection is included

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password === $confirm_password) {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        try {
            $stmt = $conn->prepare("UPDATE users SET password = :password WHERE id = :id");
            $stmt->execute(['password' => $hashed_password, 'id' => $_SESSION['reset_user_id']]);

            $success = 'Your password has been reset successfully!';
            unset($_SESSION['reset_user_id']); // Clear the session ID
            header('Location: login.php'); // Redirect back to the login page
            exit();
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    } else {
        $error = 'Passwords do not match';
    }
}
?>

<body class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
    <!-- Reset Password Form -->
    <main class="flex flex-col items-center justify-center flex-grow">
        <div class="bg-white p-8 rounded-lg shadow-md border mb-[25%] lg:mb-[0%] w-[320px] md:w-[350px] lg:w-[400px]">
            <h2 class="text-2xl font-semibold mb-6 text-center">Reset Password</h2>
            <?php if ($error): ?>
                <div class="text-red-500 text-center mb-4"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="text-green-500 text-center mb-4"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST" action="" class="space-y-4">
                <div class="flex items-center">
                    <label for="new_password" class="w-1/3 text-right pr-4">New Password:</label>
                    <input type="password" id="new_password" name="new_password" placeholder="Enter New Password" class="w-2/3 p-2 border rounded hover:border-gray-400" required>
                </div>
                <div class="flex items-center">
                    <label for="confirm_password" class="w-1/3 text-right pr-4">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" class="w-2/3 p-2 border rounded hover:border-gray-400" required>
                </div>
                <div class="flex justify-center">
                    <button type="submit" class="px-4 py-2 border text-white border-blue-500 rounded shadow-lg bg-gradient-to-r from-blue-500 to-gray-300 transition-all duration-300 hover:from-blue-400 hover:to-gray-200">
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
