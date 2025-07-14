<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Allow CORS
header('Access-Control-Allow-Methods: GET, POST, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require 'vendor/autoload.php'; // For Stripe
use Stripe\Stripe;
use Stripe\Checkout\Session;

$dsn = 'mysql:host=localhost;dbname=seedlings;charset=utf8';
$username = 'your_username'; // Replace with your MySQL username
$password = 'your_password'; // Replace with your MySQL password

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$segments = explode('/', trim($path, '/'));

if ($method === 'GET' && $segments[0] === 'products') {
    $stmt = $pdo->query('SELECT * FROM products');
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($products);
    exit;
}

if ($method === 'POST' && $segments[0] === 'cart') {
    $data = json_decode(file_get_contents('php://input'), true);
    $user_id = $data['user_id'] ?? null;
    if (!$user_id) {
        http_response_code(400);
        echo json_encode(['error' => 'User ID required']);
        exit;
    }
    $stmt = $pdo->prepare('SELECT id FROM carts WHERE user_id = ?');
    $stmt->execute([$user_id]);
    $cart = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$cart) {
        $stmt = $pdo->prepare('INSERT INTO carts (user_id) VALUES (?)');
        $stmt->execute([$user_id]);
        $cart_id = $pdo->lastInsertId();
    } else {
        $cart_id = $cart['id'];
    }
    echo json_encode(['cart_id' => $cart_id]);
    exit;
}

if ($method === 'POST' && $segments[0] === 'cart' && $segments[1] === 'add') {
    $data = json_decode(file_get_contents('php://input'), true);
    $user_id = $data['user_id'] ?? null;
    $product_id = $data['product_id'] ?? null;
    $quantity = $data['quantity'] ?? 1;
    if (!$user_id || !$product_id) {
        http_response_code(400);
        echo json_encode(['error' => 'User ID and product ID required']);
        exit;
    }
    $stmt = $pdo->prepare('SELECT id FROM carts WHERE user_id = ?');
    $stmt->execute([$user_id]);
    $cart = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$cart) {
        $stmt = $pdo->prepare('INSERT INTO carts (user_id) VALUES (?)');
        $stmt->execute([$user_id]);
        $cart_id = $pdo->lastInsertId();
    } else {
        $cart_id = $cart['id'];
    }
    $stmt = $pdo->prepare('SELECT id, quantity FROM cart_items WHERE cart_id = ? AND product_id = ?');
    $stmt->execute([$cart_id, $product_id]);
    $existingItem = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($existingItem) {
        $stmt = $pdo->prepare('UPDATE cart_items SET quantity = quantity + ? WHERE id = ?');
        $stmt->execute([$quantity, $existingItem['id']]);
    } else {
        $stmt = $pdo->prepare('INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)');
        $stmt->execute([$cart_id, $product_id, $quantity]);
    }
    echo json_encode(['success' => true]);
    exit;
}

if ($method === 'GET' && $segments[0] === 'cart' && isset($segments[1])) {
    $user_id = $segments[1];
    $stmt = $pdo->prepare('SELECT id FROM carts WHERE user_id = ?');
    $stmt->execute([$user_id]);
    $cart = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$cart) {
        echo json_encode([]);
        exit;
    }
    $stmt = $pdo->prepare(
        'SELECT ci.*, p.name, p.price
         FROM cart_items ci
         JOIN products p ON ci.product_id = p.id
         WHERE ci.cart_id = ?'
    );
    $stmt->execute([$cart['id']]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($items);
    exit;
}

if ($method === 'DELETE' && $segments[0] === 'cart' && isset($segments[1]) && isset($segments[2])) {
    $user_id = $segments[1];
    $product_id = $segments[2];
    $stmt = $pdo->prepare('SELECT id FROM carts WHERE user_id = ?');
    $stmt->execute([$user_id]);
    $cart = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$cart) {
        http_response_code(404);
        echo json_encode(['error' => 'Cart not found']);
        exit;
    }
    $stmt = $pdo->prepare('DELETE FROM cart_items WHERE cart_id = ? AND product_id = ?');
    $stmt->execute([$cart['id'], $product_id]);
    echo json_encode(['success' => true]);
    exit;
}

if ($method === 'POST' && $segments[0] === 'create-checkout-session') {
    $data = json_decode(file_get_contents('php://input'), true);
    $items = $data['items'] ?? [];
    $user_id = $data['user_id'] ?? null;
    if (!$items || !$user_id) {
        http_response_code(400);
        echo json_encode(['error' => 'Items and user ID required']);
        exit;
    }
    Stripe::setApiKey('sk_test_51J9Z8oK7x8y9z0w1'); // Replace with your Stripe secret key
    try {
        $line_items = array_map(function ($item) {
            return [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => ['name' => $item['name']],
                    'unit_amount' => (int)($item['price'] * 100),
                ],
                'quantity' => $item['quantity'],
            ];
        }, $items);
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => 'http://localhost/success.html',
            'cancel_url' => 'http://localhost/cart.html',
        ]);

        $total = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $items));
        $stmt = $pdo->prepare('INSERT INTO orders (user_id, total, status) VALUES (?, ?, ?)');
        $stmt->execute([$user_id, $total, 'pending']);
        $order_id = $pdo->lastInsertId();
        foreach ($items as $item) {
            $stmt = $pdo->prepare(
                'INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) VALUES (?, ?, ?, ?)'
            );
            $stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
        }

        $stmt = $pdo->prepare('SELECT id FROM carts WHERE user_id = ?');
        $stmt->execute([$user_id]);
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($cart) {
            $stmt = $pdo->prepare('DELETE FROM cart_items WHERE cart_id = ?');
            $stmt->execute([$cart['id']]);
        }

        echo json_encode(['id' => $session->id]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create checkout session']);
    }
    exit;
}

http_response_code(404);
echo json_encode(['error' => 'Endpoint not found']);
?>