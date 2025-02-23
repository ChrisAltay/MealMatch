<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
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
    <title>Delete Account Process</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans">
    <div class="container mx-auto p-8">
        <h1 class="text-2xl mb-4">Delete Account</h1>
        <?php if (isset($error)): ?>
            <p class="text-red-500"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
