<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="/vending-machine/public/css/styles.css" rel="stylesheet"> <!-- Link to Tailwind CSS or your custom CSS -->
</head>

<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-1/4 bg-gray-800 p-5">
            <h2 class="text-white text-2xl mb-5">User Dashboard</h2>
            <ul>
                <li><a href="/vending-machine/user/dashboard" class="text-gray-300 hover:text-white block py-2">Dashboard</a></li>
                <li><a href="/vending-machine/user/purchase" class="text-gray-300 hover:text-white block py-2">Purchase Products</a></li>
                <li><a href="/vending-machine/user/account" class="text-gray-300 hover:text-white block py-2">Account Information</a></li>
                <li><a href="/vending-machine/user/purchase-history" class="text-gray-300 hover:text-white block py-2">Purchase History</a></li>
                <li><a href="/vending-machine/logout" class="text-red-500 hover:text-red-600 block py-2">Logout</a></li>
            </ul>
        </div>

        <!-- Main Content Area -->
        <div class="w-3/4 p-5">
            <?php echo $content; ?> <!-- Dynamic content from other views -->
        </div>
    </div>
</body>

</html>
