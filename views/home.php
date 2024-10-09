<?php
// Define the title for the page
$title = "Home";

// Define the content for the page
ob_start();
?>

<div class="text-center">
    <h1 class="text-4xl font-bold text-gray-800">Welcome to the Vending Machine System</h1>
    <div class="mt-8">
        <a href="/vending-machine/login" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
            Login
        </a>
        <a href="/vending-machine/register" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 ml-4">
            Register
        </a>
    </div>
</div>

<?php
// Store the content into a variable
$content = ob_get_clean();

// Include the master layout
require 'layouts/master.php';
