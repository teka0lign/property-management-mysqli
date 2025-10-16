<?php
require_once __DIR__ . '/../src/Config.php';
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/Models/Property.php';
require_once __DIR__ . '/../src/Repositories/PropertyRepository.php';
require_once __DIR__ . '/../src/Controllers/PropertyController.php';
require_once __DIR__ . '/../src/Middleware/AuthMiddleware.php';

use App\Config;
use App\Database;
use App\Repositories\PropertyRepository;
use App\Controllers\PropertyController;
use App\Middleware\AuthMiddleware;

// Basic router
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = rtrim($path, '/');

$db = new Database();
$repo = new PropertyRepository($db->getConnection());
$controller = new PropertyController($repo);

// Simple routing (for demo)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
if ($method === 'OPTIONS') { http_response_code(204); exit; }

if ($path === '/api/properties' && $method === 'GET') {
    $controller->index();
    exit;
}

if (preg_match('#^/api/properties/(\d+)$#', $path, $m)) {
    $id = (int)$m[1];
    if ($method === 'GET') { $controller->show($id); exit; }
    if ($method === 'PUT') {
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $controller->update($id, $input); exit;
    }
    if ($method === 'DELETE') { 
        $auth = AuthMiddleware::authenticate(); if (!$auth) { http_response_code(401); echo json_encode(['error'=>'Unauthorized']); exit; }
        $controller->destroy($id); exit;
    }
}

if ($path === '/api/properties' && $method === 'POST') {
    $auth = AuthMiddleware::authenticate(); if (!$auth) { http_response_code(401); echo json_encode(['error'=>'Unauthorized']); exit; }
    $input = json_decode(file_get_contents('php://input'), true) ?? [];
    $controller->store($input, $auth);
    exit;
}

http_response_code(404);
echo json_encode(['error' => 'Not found']);
