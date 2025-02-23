<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<html>
<head>
    <title>Google Calendar</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-sans">
    <div class="container mx-auto p-4">
        <!-- Navigation -->

        <nav class="flex justify-between items-center py-4 border-b">
            <div class="text-lg font-bold">[Title Here]</div>
            <div class="space-x-4">
                <a href="index.php" class="text-gray-600 hover:text-gray-900">Home</a>
                <a href="about.php" class="text-gray-600 hover:text-gray-900">About</a>
                <?php
                session_start();
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
    <main class="flex flex-col items-center mt-8">
        <h1 class="text-2xl mb-4">Google Calendar</h1>
        <table class="border-collapse border border-black">
            <thead>
                <tr>
                    <th class="border border-black px-4 py-2">Sun</th>
                    <th class="border border-black px-4 py-2">Mon</th>
                    <th class="border border-black px-4 py-2">Tues</th>
                    <th class="border border-black px-4 py-2">Wed</th>
                    <th class="border border-black px-4 py-2">Thurs</th>
                    <th class="border border-black px-4 py-2">Fri</th>
                    <th class="border border-black px-4 py-2">Sat</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border border-black h-20"></td>
                    <td class="border border-black h-20"></td>
                    <td class="border border-black h-20"></td>
                    <td class="border border-black h-20"></td>
                    <td class="border border-black h-20"></td>
                    <td class="border border-black h-20"></td>
                    <td class="border border-black h-20"></td>
                </tr>
                <tr>
                    <td class="border border-black h-20"></td>
                    <td class="border border-black h-20"></td>
                    <td class="border border-black h-20"></td>
                    <td class="border border-black h-20"></td>
                    <td class="border border-black h-20"></td>
                    <td class="border border-black h-20"></td>
                    <td class="border border-black h-20"></td>
                </tr>
                <tr>
                    <td class="border border-black h-20"></td>
                    <td class="border border-black h-20"></td>
                    <td class="border border-black h-20"></td>
                    <td class="border border-black h-20"></td>
                    <td class="border border-black h-20"></td>
                    <td class="border border-black h-20"></td>
                    <td class="border border-black h-20"></td>
                </tr>
                <tr>
                    <td class="border border-black h-20"></td>
                    <td class="border border-black h-20"></td>
                    <td class="border border-black h-20"></td>
                    <td class="border border-black h-20"></td>
                    <td class="border border-black h-20"></td>
                    <td class="border border-black h-20"></td>
                    <td class="border border-black h-20"></td>
                </tr>
                <tr>
                    <td class="border border-black h-20"></td>
                    <td class="border border-black h-20"></td>
                    <td class="border border-black h-20"></td>
                    <td class="border border-black h-20"></td>
                    <td class="border border-black h-20"></td>
                    <td class="border border-black h-20"></td>
                    <td class="border border-black h-20"></td>
                </tr>
            </tbody>
        </table>
    </main>
</body>
</html>
