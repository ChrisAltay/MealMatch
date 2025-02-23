<html>
 <head>
  <title>
   Title Here
  </title>
  <script src="https://cdn.tailwindcss.com">
  </script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
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
   <main class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <!-- Left Column -->
    <div>
     <h2 class="text-2xl mb-2">
      Name of Food Dish Here
     </h2>
     <img alt="Image of Food Dish Here" class="border mb-2" src="https://placehold.co/300x200"/>
     <p class="mb-4">
      Description Here
     </p>
     <h3 class="text-xl mb-2">
      List of Ingredients Here
     </h3>
     <ul class="list-disc list-inside">
      <li>
       Filler
      </li>
      <li>
       Filler
      </li>
      <li>
       Filler
      </li>
      <li>
       Filler
      </li>
     </ul>
    </div>
    <!-- Right Column -->
    <div>
     <h3 class="text-xl mb-2">
      Step-By-Step Here
     </h3>
     <ol class="list-decimal list-inside mb-4">
      <li>
       1.
      </li>
      <li>
       2.
      </li>
      <li>
       3.
      </li>
      <li>
       4.
      </li>
      <li>
       5.
      </li>
      <li>
       6.
      </li>
      <li>
       7.
      </li>
     </ol>
     <h3 class="text-xl mb-2">
      Suggestions Here
     </h3>
     <h4 class="text-lg mb-2">
      Buy This [Insert Food Item] Here
     </h4>
     <table class="table-auto border-collapse border border-gray-400 w-full">
      <thead>
       <tr>
        <th class="border border-gray-300 px-4 py-2">
         *Item 1:
        </th>
        <th class="border border-gray-300 px-4 py-2">
         Walmart
        </th>
        <th class="border border-gray-300 px-4 py-2">
         Amazon
        </th>
        <th class="border border-gray-300 px-4 py-2">
         Whole Foods
        </th>
       </tr>
      </thead>
      <tbody>
       <tr>
        <td class="border border-gray-300 px-4 py-2">
         *Item 2:
        </td>
        <td class="border border-gray-300 px-4 py-2">
         Walmart
        </td>
        <td class="border border-gray-300 px-4 py-2">
         Amazon
        </td>
        <td class="border border-gray-300 px-4 py-2">
         Whole Foods
        </td>
       </tr>
       <tr>
        <td class="border border-gray-300 px-4 py-2">
         *Item 3:
        </td>
        <td class="border border-gray-300 px-4 py-2">
         Walmart
        </td>
        <td class="border border-gray-300 px-4 py-2">
         Amazon
        </td>
        <td class="border border-gray-300 px-4 py-2">
         Whole Foods
        </td>
       </tr>
       <tr>
        <td class="border border-gray-300 px-4 py-2">
         *Item 4:
        </td>
        <td class="border border-gray-300 px-4 py-2">
         Walmart
        </td>
        <td class="border border-gray-300 px-4 py-2">
         Amazon
        </td>
        <td class="border border-gray-300 px-4 py-2">
         Whole Foods
        </td>
       </tr>
       <tr>
        <td class="border border-gray-300 px-4 py-2">
         *Item 5:
        </td>
        <td class="border border-gray-300 px-4 py-2">
         Walmart
        </td>
        <td class="border border-gray-300 px-4 py-2">
         Amazon
        </td>
        <td class="border border-gray-300 px-4 py-2">
         Whole Foods
        </td>
       </tr>
      </tbody>
     </table>
    </div>
   </main>
  </div>
 </body>
</html>
