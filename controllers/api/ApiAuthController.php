<?

require_once 'UserController.php';
require_once 'vendor/autoload.php'; // For JWT

use \Firebase\JWT\JWT;

class ApiAuthController
{
    private $userController;
    private $secretKey = 'your_secret_key'; // Use a secure key

    public function __construct()
    {
        $this->userController = new UserController();
    }

    public function login()
    {
        // Get POST data
        $data = json_decode(file_get_contents('php://input'), true);
        $username = $data['username'] ?? null;
        $password = $data['password'] ?? null;

        // Check if username and password are provided
        if (!$username || !$password) {
            http_response_code(400);
            echo json_encode(['error' => 'Username and password are required']);
            return;
        }

        // Authenticate user
        $user = $this->userController->authenticate($username, $password);

        if ($user) {
            $payload = [
                'iss' => 'yourdomain.com', // Issuer
                'iat' => time(), // Issued at
                'exp' => time() + 3600, // Expiration (1 hour)
                'user_id' => $user['id'],
                'role' => $user['role'],
            ];

            // Encode payload with secret key
            $jwt = JWT::encode($payload, $this->secretKey, 'HS256');
            echo json_encode(['token' => $jwt]);
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid credentials']);
        }
    }

    // Register new users (optional)
    public function register()
    {
        // Implement registration logic similar to login...
    }
}
