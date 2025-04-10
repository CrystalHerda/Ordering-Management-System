<?php
// filepath: c:\GitHub\Ordering-Management-System\SOURCE CODE\Employee\public\api\products.php

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Handle OPTIONS preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Include product model
require_once '../../src/models/ProductModel.php';

// Initialize product object
$product = new ProductModel();

// Get request method
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Check for method override (for PUT/DELETE)
if ($requestMethod === 'POST' && isset($_GET['_method'])) {
    $requestMethod = $_GET['_method'];
}

// Handle request based on HTTP method
switch ($requestMethod) {
    case 'GET':
        // Get all products or single product
        if (isset($_GET['id'])) {
            // Get single product
            $result = $product->getById($_GET['id']);
            echo json_encode($result);
        } else {
            // Get all products
            $products = $product->getAll();
            echo json_encode([
                'status' => 'success',
                'data' => $products
            ]);
        }
        break;
        
    case 'POST':
        // Create new product
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $product->createProduct($data);
        echo json_encode($result);
        break;
        
    case 'PUT':
        // Update product
        if (!isset($_GET['id'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Product ID is required'
            ]);
            break;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $product->updateProduct($_GET['id'], $data);
        echo json_encode($result);
        break;
        
    case 'DELETE':
        // Delete product
        if (!isset($_GET['id'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Product ID is required'
            ]);
            break;
        }
        
        $result = $product->deleteProduct($_GET['id']);
        echo json_encode($result);
        break;
        
    default:
        // Method not allowed
        http_response_code(405);
        echo json_encode([
            'status' => 'error',
            'message' => 'Method not allowed'
        ]);
        break;
}
?>