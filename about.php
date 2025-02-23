<html>
<head>
    <title>About Page</title>
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

        <!-- Main Content -->
        <div class="text-center mt-8">
            <h1 class="text-2xl font-bold mb-8">About</h1>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Profile 1 -->
                <div class="flex flex-col items-center">
                    <div class="w-32 h-32 border rounded-full flex items-center justify-center">
                        <span>[Image Here]</span>
                    </div>
                    <div class="mt-4 text-lg font-semibold">[Name Here]</div>
                    <div class="text-gray-600">[Description Here]</div>
                </div>
                <!-- Profile 2 -->
                <div class="flex flex-col items-center">
                    <div class="w-32 h-32 border rounded-full flex items-center justify-center">
                        <span>[Image Here]</span>
                    </div>
                    <div class="mt-4 text-lg font-semibold">[Name Here]</div>
                    <div class="text-gray-600">[Description Here]</div>
                </div>
                <!-- Profile 3 -->
                <div class="flex flex-col items-center">
                    <div class="w-32 h-32 border rounded-full flex items-center justify-center">
                        <span>[Image Here]</span>
                    </div>
                    <div class="mt-4 text-lg font-semibold">[Name Here]</div>
                    <div class="text-gray-600">[Description Here]</div>
                </div>
                <!-- Profile 4 -->
                <div class="flex flex-col items-center">
                    <div class="w-32 h-32 border rounded-full flex items-center justify-center">
                        <span>[Image Here]</span>
                    </div>
                    <div class="mt-4 text-lg font-semibold">[Name Here]</div>
                    <div class="text-gray-600">[Description Here]</div>
                </div>
                <!-- Profile 5 -->
                <div class="flex flex-col items-center">
                    <div class="w-32 h-32 border rounded-full flex items-center justify-center">
                        <span>[Image Here]</span>
                    </div>
                    <div class="mt-4 text-lg font-semibold">[Name Here]</div>
                    <div class="text-gray-600">[Description Here]</div>
                </div>
                <!-- Profile 6 -->
                <div class="flex flex-col items-center">
                    <div class="w-32 h-32 border rounded-full flex items-center justify-center">
                        <span>[Image Here]</span>
                    </div>
                    <div class="mt-4 text-lg font-semibold">[Name Here]</div>
                    <div class="text-gray-600">[Description Here]</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
