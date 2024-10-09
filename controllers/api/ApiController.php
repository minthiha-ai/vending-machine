<?

require_once 'ProductsController.php';
require_once 'vendor/autoload.php';

class ApiController
{
    private $productsController;
    private $secretKey = 'your_secret_key';

    public function __construct()
    {
        $this->productsController = new ProductsController();
    }

    public function verifyToken()
    {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Authorization header not found']);
            exit();
        }

        $token = str_replace('Bearer ', '', $headers['Authorization']);

        try {
            $decoded = JWT::decode($token, $this->secretKey, ['HS256']);
            return $decoded;  // Return the user data from the token
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid token']);
            exit();
        }
    }

    // Get all products
    public function getProducts()
    {
        $products = $this->productsController->getAll();
        echo json_encode($products);
    }

    // Purchase a product
    public function purchaseProduct()
    {
        // Get POST data
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['user_id'], $data['product_id'], $data['quantity'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
            return;
        }

        try {
            $this->productsController->purchaseProduct($data['user_id'], $data['product_id'], $data['quantity']);
            echo json_encode(['message' => 'Purchase successful']);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
