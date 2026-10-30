<?php

/**
 * Estrutura comum a todas as páginas: cabeçalho, mensagens, conteúdo, rodapé
 * e o modal de confirmação de exclusão.
 *
 * Variáveis vindas do index.php:
 * @var string     $handler Ação executada (index, show, create, edit)
 * @var array|null $flash   Mensagem de retorno da ação anterior
 */

// Título da aba do navegador conforme a página
$pageTitle = match ($handler) {
    'create' => 'Novo produto',
    'edit' => !empty($product) ? 'Editar: ' . $product['nome'] : 'Produto não encontrado',
    'show' => !empty($product) ? $product['nome'] : 'Produto não encontrado',
    default => 'Produtos',
};
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= escape($pageTitle) ?> | Tech Store</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Estilos próprios: carregados depois do Bootstrap para poder sobrescrevê-lo -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <header class="site-header shadow-sm">
        <nav class="navbar" aria-label="Navegação principal">
            <div class="container">
                <a class="navbar-brand fw-bold" href="index.php?route=index">
                    <i class="bi bi-cpu" aria-hidden="true"></i> Tech Store
                </a>
                <span class="navbar-text d-none d-sm-inline">Cadastro de produtos</span>
            </div>
        </nav>
    </header>

    <main class="flex-grow-1">
        <div class="container py-4">
            <?php if ($flash): ?>
                <div class="alert alert-<?= escape($flash['type']) ?> alert-dismissible fade show" role="alert"
                    data-autodismiss>
                    <?= escape($flash['message']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                </div>
            <?php endif; ?>

            <?php if ($handler === 'index'): ?>
                <?php require __DIR__ . '/list.php'; ?>
            <?php elseif (in_array($handler, ['create', 'edit'])): ?>
                <?php require __DIR__ . '/form.php'; ?>
            <?php elseif ($handler === 'show'): ?>
                <?php require __DIR__ . '/show.php'; ?>
            <?php endif; ?>
        </div>
    </main>

    <?php if ($handler === 'index'): ?>
        <!-- Botão flutuante: fica fixo no canto da tela, só na listagem -->
        <a class="btn btn-primary fab" href="index.php?route=create" title="Novo produto" aria-label="Novo produto">
            <i class="bi bi-plus-lg" aria-hidden="true"></i>
        </a>
    <?php endif; ?>

    <footer class="site-footer py-3 text-center small">
        Tech Store &copy; <?= date('Y') ?>
    </footer>

    <!-- Modal de confirmação usado pelos botões de excluir (ver delete-confirm.js) -->
    <div class="modal fade" id="delete-modal" tabindex="-1" aria-labelledby="delete-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title h5" id="delete-modal-title">Excluir produto</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    Tem certeza que deseja excluir <strong data-delete-name>este produto</strong>?
                    Esta ação não pode ser desfeita.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" data-delete-confirm>
                        <i class="bi bi-trash" aria-hidden="true"></i> Excluir
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts no fim do body com defer: rodam depois que o HTML foi lido -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="assets/js/flash.js" defer></script>
    <script src="assets/js/delete-confirm.js" defer></script>
    <script src="assets/js/table-search.js" defer></script>
    <script src="assets/js/form-validation.js" defer></script>
    <script src="assets/js/password-toggle.js" defer></script>
</body>

</html>
