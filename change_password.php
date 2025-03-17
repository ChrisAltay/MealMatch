<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

require 'dbconfig.php';

$message = ''; // Variable to store the success or error message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Fetch current password from the database
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    if ($user && password_verify($currentPassword, $user['password'])) {
        // Check if the new password and confirm password match
        if ($newPassword === $confirmPassword) {
            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

            // Update the password in the database
            $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $updateStmt->execute([$hashedPassword, $_SESSION['user_id']]);

            $message = "Password changed successfully!";
            // Redirect to profile.php after 2 seconds
            header("refresh:2;url=profile.php");
        } else {
            $message = "New passwords do not match.";
        }
    } else {
        $message = "Current password is incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Change Password</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x/dist/cdn.min.js" defer></script>
</head>

<body class="font-sans">
    <div class="container mx-auto">

        <!-- Navigation -->
        <nav x-data="{ menuOpen: false }" class="flex justify-between items-center py-4 px-6 border-b shadow-md relative">
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
                <a href="profile.php" class="text-xl font-semibold text-white bg-blue-500 hover:bg-blue-700 rounded-md px-3 py-2 transition">Profile</a>
                <a href="logout.php" class="text-xl font-semibold text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-3 py-2 transition">Logout</a>
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
                    <a href="profile.php" class="text-white bg-blue-500 hover:bg-blue-700 rounded-md px-4 py-2 transition" @click="menuOpen = false">Profile</a>
                    <a href="logout.php" class="text-blue-500 hover:bg-blue-500 hover:text-white rounded-md px-4 py-2 transition" @click="menuOpen = false">Logout</a>
                </div>
            </div>
        </nav>

        <!-- Change Password Section -->
        <section class="p-8 border-b">
            <h1 class="text-center text-2xl mb-4 font-bold">Change Password</h1>

            <!-- Success or Error Message -->
            <?php if ($message): ?>
                <div class="text-center text-lg <?php echo $message === 'Password changed successfully!' ? 'text-green-500' : 'text-red-500'; ?> mb-4">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <!-- Change Password Form -->
            <form action="change_password.php" method="POST" class="w-1/2 mx-auto">
                <div class="mb-4">
                    <label for="current_password" class="block text-sm font-semibold">Current Password</label>
                    <input type="password" id="current_password" placeholder="Enter current password" name="current_password" class="border p-2 w-full rounded hover:border-gray-400" required />
                </div>

                <div class="mb-4">
                    <label for="new_password" class="block text-sm font-semibold">New Password</label>
                    <input type="password" id="new_password" placeholder="Enter new password" name="new_password" class="border p-2 w-full rounded hover:border-gray-400" required />
                </div>

                <div class="mb-4">
                    <label for="confirm_password" class="block text-sm font-semibold">Confirm New Password</label>
                    <input type="password" id="confirm_password" placeholder="Enter new password again" name="confirm_password" class="border p-2 w-full border rounded hover:border-gray-400" required />
                </div>

                <button type="submit" class="bg-blue-500 text-white px-4 rounded-md py-2 hover:bg-blue-700">Change Password</button>
            </form>

            <div class="text-center mt-4">
                <a href="profile.php" class="text-blue-500 hover:underline">Back to Profile</a>
            </div>
        </section>
    </div>
</body>
</html>
