<?php
if (!$product) {
    require __DIR__ . '/not-found.php';
    return;
}
?>

<section class="row justify-content-center">
    <div class="col-lg-8">
        <article class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                    <h1 class="h4 mb-0"><?= escape($product['nome']) ?></h1>
                    <span class="badge <?= $product['ativo'] ? 'text-bg-success' : 'text-bg-secondary' ?>">
                        <?= $product['ativo'] ? 'Ativo' : 'Inativo' ?>
                    </span>
                </div>

                <dl class="row mb-4">
                    <dt class="col-sm-4">Código</dt>
                    <dd class="col-sm-8"><?= escape($product['id']) ?></dd>

                    <dt class="col-sm-4">Marca</dt>
                    <dd class="col-sm-8"><?= escape($product['marca']) ?></dd>

                    <dt class="col-sm-4">Cor</dt>
                    <dd class="col-sm-8"><?= escape($product['cor']) ?></dd>

                    <dt class="col-sm-4">Valor</dt>
                    <dd class="col-sm-8">R$ <?= number_format((float) $product['valor'], 2, ',', '.') ?></dd>

                    <dt class="col-sm-4">Criado em</dt>
                    <dd class="col-sm-8">
                        <time datetime="<?= escape(date('c', strtotime($product['data_criacao']))) ?>">
                            <?= escape(date('d/m/Y H:i', strtotime($product['data_criacao']))) ?>
                        </time>
                    </dd>
                </dl>

                <div class="d-flex flex-column flex-sm-row gap-2">
                    <a href="index.php?route=edit&id=<?= escape($product['id']) ?>" class="btn btn-primary">
                        <i class="bi bi-pencil" aria-hidden="true"></i> Editar
                    </a>
                    <form action="index.php?route=delete" method="post" data-delete-form
                        data-product-name="<?= escape($product['nome']) ?>">
                        <?= csrfField() ?>
                        <input type="hidden" name="id" value="<?= escape($product['id']) ?>">
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-trash" aria-hidden="true"></i> Excluir
                        </button>
                    </form>
                    <a href="index.php?route=index" class="btn btn-link ms-sm-auto">Voltar</a>
                </div>
            </div>
        </article>
    </div>
</section>