<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

require_once __DIR__ . '/../controllers/ProductsController.php';  // Adjust path as needed

class ProductsControllerTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $dbMock;

    /**
     * @var ProductsController
     */
    private $productsController;

    protected function setUp(): void
    {
        // Create a mock for the Database class
        $this->dbMock = $this->createMock(Database::class);

        // Inject the mock into the ProductsController
        $this->productsController = new ProductsController($this->dbMock);
    }

    public function testCreateProductSuccess()
    {
        $this->dbMock->expects($this->once())
            ->method('execute')
            ->with(
                $this->equalTo('INSERT INTO products (name, price, quantity_available) VALUES (?, ?, ?)'),
                $this->equalTo(['Test Product', 9.99, 10])
            );

        $this->productsController->create('Test Product', 9.99, 10);
    }

    public function testCreateProductThrowsExceptionOnInvalidData()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid product data');

        // Call create with invalid data (negative quantity)
        $this->productsController->create('Test Product', 9.99, -1);
    }

    public function testGetProductByIdSuccess()
    {
        // Define return value for fetch method
        $expectedProduct = ['id' => 1, 'name' => 'Test Product', 'price' => 9.99, 'quantity_available' => 10];

        $this->dbMock->expects($this->once())
            ->method('fetch')
            ->with(
                $this->equalTo('SELECT * FROM products WHERE id = ?'),
                $this->equalTo([1])
            )
            ->willReturn($expectedProduct);

        // Call the getById method and assert result
        $product = $this->productsController->getById(1);
        $this->assertEquals($expectedProduct, $product);
    }

    public function testGetProductByIdNotFound()
    {
        $this->dbMock->expects($this->once())
            ->method('fetch')
            ->with(
                $this->equalTo('SELECT * FROM products WHERE id = ?'),
                $this->equalTo([99])
            )
            ->willReturn(false);  // Product not found

        // Call the getById method and assert result
        $product = $this->productsController->getById(99);
        $this->assertFalse($product);
    }

    public function testUpdateProductSuccess()
    {
        $this->dbMock->expects($this->once())
            ->method('execute')
            ->with(
                $this->equalTo('UPDATE products SET name = ?, price = ?, quantity_available = ? WHERE id = ?'),
                $this->equalTo(['Updated Product', 19.99, 5, 1])
            );

        // Call the update method
        $this->productsController->update(1, 'Updated Product', 19.99, 5);
    }

    public function testDeleteProductSuccess()
    {
        $this->dbMock->expects($this->once())
            ->method('execute')
            ->with(
                $this->equalTo('UPDATE products SET deleted_at = NOW() WHERE id = ?'),
                $this->equalTo([1])
            );

        // Call the delete method
        $this->productsController->delete(1);
    }

    public function testPurchaseProductSuccess()
    {
        // Mock the product returned by getById
        $this->dbMock->expects($this->once())
            ->method('fetch')
            ->with($this->equalTo('SELECT * FROM products WHERE id = ?'), $this->equalTo([1]))
            ->willReturn(['id' => 1, 'name' => 'Test Product', 'price' => 10, 'quantity_available' => 5]);

        // Set up a counter to track method calls
        $this->dbMock->expects($this->exactly(2))
            ->method('execute')
            ->willReturnCallback(function ($sql, $params) {
                static $callCount = 0;
                $callCount++;

                if ($callCount === 1) {
                    // First call: update product quantity
                    $this->assertEquals('UPDATE products SET quantity_available = ? WHERE id = ?', $sql);
                    $this->assertEquals([3, 1], $params);  // Quantity should be 3 after purchase
                }

                if ($callCount === 2) {
                    // Second call: insert into transactions
                    $this->assertEquals('INSERT INTO transactions (user_id, product_id, quantity, total_price) VALUES (?, ?, ?, ?)', $sql);
                    $this->assertEquals([1, 1, 2, 20], $params);  // User 1, product 1, quantity 2, total price 20
                }

                return true;  // Simulate successful execution
            });

        // Call the purchase method
        $result = $this->productsController->purchaseProduct(1, 1, 2);

        // Assert that the purchase was successful
        $this->assertTrue($result);
    }

    public function testPurchaseProductThrowsExceptionOnInsufficientQuantity()
    {
        $this->dbMock->expects($this->once())
            ->method('fetch')
            ->with($this->equalTo('SELECT * FROM products WHERE id = ?'), $this->equalTo([1]))
            ->willReturn(['id' => 1, 'name' => 'Test Product', 'price' => 10, 'quantity_available' => 1]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Not enough quantity available.");

        // Try to purchase more than available stock
        $this->productsController->purchaseProduct(1, 1, 2);
    }
}
