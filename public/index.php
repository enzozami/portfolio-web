<?php
session_start();

require_once __DIR__ . '/../src/controllers/ProductController.php';
require_once __DIR__ . '/../src/database/connection.php';
require_once __DIR__ . '/../src/helpers/helpers.php';

$routes = [
    'GET' => [
        'index' => 'index',
        'show' => 'show',
        'create' => 'create',
        'edit' => 'edit',
    ],
    'POST' => [
        'store'  => 'store',
        'update' => 'update',
        'delete' => 'delete',
    ],
];

$route = $_GET['route'] ?? 'index';
$method = $_SERVER['REQUEST_METHOD'];
$handler = $routes[$method][$route] ?? 'index';

$pdo = connection();
$controller = new ProductController(
    new ListProductAction($pdo),
    new CreateProductAction($pdo),
    new FindProductAction($pdo),
    new UpdateProductAction($pdo),
    new DeleteProductAction($pdo)
);

$data = match ($handler) {
    'index' => $controller->index($_GET),
    'show' => $controller->show((int) ($_GET['id'] ?? 0)),
    'create' => $controller->create(),
    'edit' => $controller->edit((int) ($_GET['id'] ?? 0)),
    'store' => $controller->store($_POST),
    'update' => $controller->update((int) ($_POST['id'] ?? 0), $_POST),
    'delete' => $controller->destroy((int) ($_POST['id'] ?? 0)),
    default => $controller->index($_GET),
};

extract($data);

require __DIR__ . '/views/layout.php';
