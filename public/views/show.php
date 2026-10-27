<?php
/**
 * Variáveis vindas do index.php:
 * @var PDO $pdo
 */
$product = (new FindProductAction($pdo))->handle((int) ($_GET['id'] ?? 0));

if (!$product) {
    require __DIR__ . '/not-found.php';
    return;
}
?>

<section class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <h1 class="h4 mb-0"><?= e($product['nome']) ?></h1>
                    <span class="badge <?= $product['ativo'] ? 'text-bg-success' : 'text-bg-secondary' ?>">
                        <?= $product['ativo'] ? 'Ativo' : 'Inativo' ?>
                    </span>
                </div>

                <dl class="row mb-4">
                    <dt class="col-sm-4">Código</dt>
                    <dd class="col-sm-8"><?= e($product['id']) ?></dd>

                    <dt class="col-sm-4">Marca</dt>
                    <dd class="col-sm-8"><?= e($product['marca']) ?></dd>

                    <dt class="col-sm-4">Cor</dt>
                    <dd class="col-sm-8"><?= e($product['cor']) ?></dd>

                    <dt class="col-sm-4">Valor</dt>
                    <dd class="col-sm-8">R$ <?= number_format((float) $product['valor'], 2, ',', '.') ?></dd>

                    <dt class="col-sm-4">Criado em</dt>
                    <dd class="col-sm-8"><?= e(date('d/m/Y H:i', strtotime($product['data_criacao']))) ?></dd>
                </dl>

                <div class="d-flex flex-column flex-sm-row gap-2">
                    <a href="index.php?route=edit&id=<?= e($product['id']) ?>" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                    <form action="index.php?route=delete" method="post"
                        onsubmit="return confirm('Excluir este produto?');">
                        <input type="hidden" name="id" value="<?= e($product['id']) ?>">
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-trash"></i> Excluir
                        </button>
                    </form>
                    <a href="index.php?route=index" class="btn btn-link ms-sm-auto">Voltar</a>
                </div>
            </div>
        </div>
    </div>
</section>