<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Profile Page</title>
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
            <h1 class="text-center text-2xl mb-4">Profile</h1>
            <div class="flex justify-center space-x-8 mb-8">
                <div>
                    ID: <span class="text-gray-700"><?php echo $_SESSION['user_id'] ?? 'N/A'; ?></span>
                </div>
                <div>
                    Username: <span class="text-gray-700"><?php echo $_SESSION['username']; ?></span>
                </div>
                <div>
                    Email: <span class="text-gray-700"><?php echo $_SESSION['email'] ?? 'N/A'; ?></span>
                </div>
            </div>
            <div class="flex justify-center space-x-4">
            
                <a href="change_password.php" class="border px-4 py-2">Change Password</a>

                <a href="calendar.php" class="border px-4 py-2">Google Calendar</a>

                <form action="delete_account.php" method="GET">
                    <button type="submit" class="border px-4 py-2">Delete Account</button>
                </form>
            </div>
        </section>
    </div>
    <!-- Search Section -->
        <h2 class="text-center text-xl mb-4">Favoite Dishes</h2>
        <div class="flex justify-center mb-8">
        </div>

    <div class="flex flex-wrap justify-center space-x-4">
            <div class="border p-4 w-64 mb-4">
                <img alt="Image of Search Related Food Item Here" class="w-full h-auto mb-2" src="https://placehold.co/200x200"/>
                <p class="text-center">[Name Of Related Food Dish Here]</p>
                <div class="flex justify-between mt-2">
                    <button class="border p-2"><i class="fas fa-bookmark"></i></button>
                    <button class="border p-2"><i class="fas fa-star"></i></button>
                </div>
            </div>
        </div>
</body>
</html>
