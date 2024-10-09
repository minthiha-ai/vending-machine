<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/vending-machine/public/css/styles.css" rel="stylesheet"> <!-- Include Tailwind CSS -->
    <title><?php echo $title ?? 'Vending Machine'; ?></title>
</head>

<body class="bg-gray-100">

    <!-- Header or navigation could go here -->

    <!-- Content section -->
    <div class="container mx-auto p-4">
        <?php echo $content; ?> <!-- This will inject view-specific content -->
    </div>

    <!-- Footer (if needed) -->

</body>

</html>
