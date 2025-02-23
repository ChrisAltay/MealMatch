<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Change Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
    <?php
    session_start();
    if (!isset($_SESSION['username'])) {
        header("Location: index.php");
        exit();
    }
    ?>
    <div class="container mx-auto p-4">
        <!-- Navigation -->
        <nav class="flex justify-between items-center py-4 border-b">
            <div class="text-lg font-bold">[Title Here]</div>
            <div class="space-x-4">
                <a href="index.php" class="text-gray-600 hover:text-gray-900">Home</a>
                <a href="about.php" class="text-gray-600 hover:text-gray-900">About</a>
                <a href="profile.php" class="text-gray-600 hover:text-gray-900">Profile</a>
                <a href="logout.php" class="text-gray-600 hover:text-gray-900">Logout</a>
            </div>
        </nav>

        <!-- Profile Section -->
        <section class="p-8 border-b">
            <h1 class="text-center text-2xl mb-4">Change Password</h1>
            <div class="flex justify-center space-x-8 mb-8">
            <p class="text-center">This page requires work, please help me with it.</p>
        </section>
    </div>
</body>
</html>
