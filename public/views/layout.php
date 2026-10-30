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
            </div>
        </nav>
    </header>

    <main class="flex-grow-1">
        <div class="container py-4">
            <?php if ($handler === 'index'): ?>
                <?php require __DIR__ . '/list.php'; ?>
            <?php elseif (in_array($handler, ['create', 'edit'])): ?>
                <?php require __DIR__ . '/form.php'; ?>
            <?php elseif ($handler === 'show'): ?>
                <?php require __DIR__ . '/show.php'; ?>
            <?php endif; ?>
        </div>
    </main>

    <div class="container">
        <a class="btn btn-success btn-sm" href="/?route=create"> <i class="bi bi-plus-lg"></i> Adicionar Produto</a>
    </div>

    <footer class="py-3 text-center text-muted small border-top bg-white">
        Tech Store &copy; <?= date('Y') ?>
    </footer>
</body>

</html>