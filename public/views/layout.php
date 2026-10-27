<?php

/**
 * Variáveis vindas do index.php:
 * @var string $route
 */
if (!function_exists('e')) {
    function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

$route = $route ?? 'index';
$errors = getFormErrors();

$view = match ($route) {
    'create', 'edit' => 'form.php',
    'show' => 'show.php',
    default => 'list.php',
};
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #1e293b, #334155);
        }

        .hero-section .navbar-brand {
            color: #fff;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100 bg-light">
    <header class="hero-section shadow-sm">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand fw-bold" href="index.php?route=index">
                    <i class="bi bi-cpu"></i> Tech Store
                </a>
                <a class="btn btn-warning btn-sm" href="index.php?route=create">
                    <i class="bi bi-plus-lg"></i> Adicionar Produto
                </a>
            </div>
        </nav>
    </header>

    <main class="container py-5 flex-grow-1">
        <?php if ($errors): ?>
            <div class="alert alert-danger">
                <strong>Verifique os dados informados:</strong>
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= e($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php require __DIR__ . '/' . $view; ?>
    </main>

    <footer class="py-3 text-center text-muted small border-top bg-white">
        Tech Store &copy; <?= date('Y') ?>
    </footer>
</body>

</html>