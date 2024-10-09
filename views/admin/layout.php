<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/vending-machine/public/css/styles.css" rel="stylesheet"> <!-- Tailwind CSS -->
    <title>Admin Dashboard</title>
</head>

<body class="bg-gray-100 flex">

    <!-- Sidebar -->
    <div class="w-1/4 bg-gray-800 h-screen p-5">
        <h2 class="text-white text-2xl mb-5">Admin Dashboard</h2>
        <ul>
            <li><a href="/vending-machine/admin/dashboard" class="text-gray-300 hover:text-white block py-2">Overview</a></li>
            <li><a href="/vending-machine/admin/products" class="text-gray-300 hover:text-white block py-2">Manage Products</a></li>
            <li><a href="/vending-machine/admin/reports/sales" class="text-gray-300 hover:text-white block py-2">Sales Reports</a></li>
            <li><a href="/vending-machine/admin/reports/inventory" class="text-gray-300 hover:text-white block py-2">Inventory Reports</a></li>
            <li><a href="/vending-machine/admin/users" class="text-gray-300 hover:text-white block py-2">User Management</a></li>
            <li><a href="/vending-machine/controllers/logout.php" class="text-red-500 hover:text-red-600 block py-2">Logout</a></li>
        </ul>
    </div>

    <!-- Main content -->
    <div class="w-3/4 p-5">
        <header class="mb-5">
            <h1 class="text-3xl font-bold">Welcome, Admin</h1>
        </header>

        <!-- Dynamic Content -->
        <div class="content">
            <?php echo $content; ?> <!-- The dynamic content will be injected here -->
        </div>
    </div>

</body>

</html>
