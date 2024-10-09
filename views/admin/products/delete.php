<?php
require __DIR__ . '/../../../controllers/ProductsController.php';
$controller = new ProductsController();

$id = $_GET['id'];
$controller->delete($id);

header('Location: /vending-machine/admin/products');
exit();
