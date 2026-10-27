<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../style.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <header class="hero-section shadow-sm">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand fw-bold" href="/?action=index">Tech Store</a>
                <a class="btn btn-warning btn-sm" href="/?action=form">Adicionar Produto</a>
            </div>
        </nav>
    </header>

    <main class="container py-5">
        <?php if ((($route ?? 'index') === 'index')): ?>
            <?php require __DIR__ . '/list.php'; ?>
        <?php else: ?>
            <?php require __DIR__ . '/form.php'; ?>
        <?php endif; ?>
    </main>
</body>

</html>