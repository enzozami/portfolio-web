<?php

$columns = [
    'id' => 'ID',
    'nome' => 'Nome',
    'marca' => 'Marca',
    'cor' => 'Cor',
    'valor' => 'Valor',
];

// Colunas escondidas em telas pequenas, para as ações caberem no celular
$responsive = [
    'id' => 'd-none d-sm-table-cell',
    'marca' => 'd-none d-md-table-cell',
    'cor' => 'd-none d-md-table-cell',
];

$sortUrl = function (string $column) use ($sortBy, $descending, $active): string {
    return 'index.php?' . http_build_query([
        'route' => 'index',
        'sortBy' => $column,
        'descending' => $column === $sortBy && !$descending ? 1 : 0,
        'ativo' => (int) $active,
    ]);
};
?>

<section aria-labelledby="page-title">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
        <h1 class="h3 mb-0" id="page-title">Produtos</h1>

        <div class="btn-group" role="group" aria-label="Filtrar por status">
            <a href="index.php?route=index&ativo=1" class="btn btn-sm <?= $active ? 'btn-dark' : 'btn-outline-dark' ?>"
                <?= $active ? 'aria-current="page"' : '' ?>>Ativos</a>
            <a href="index.php?route=index&ativo=0" class="btn btn-sm <?= !$active ? 'btn-dark' : 'btn-outline-dark' ?>"
                <?= !$active ? 'aria-current="page"' : '' ?>>Inativos</a>
        </div>
    </div>

    <?php if (!$products): ?>
    <div class="empty-state text-center text-muted py-5 rounded shadow-sm">
        <i class="bi bi-box-seam fs-1 d-block mb-2" aria-hidden="true"></i>
        Nenhum produto <?= $active ? 'ativo' : 'inativo' ?> encontrado.
        <div class="mt-3">
            <a href="index.php?route=create" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg" aria-hidden="true"></i> Cadastrar produto
            </a>
        </div>
    </div>
    <?php else: ?>
    <!-- Busca instantânea: filtra as linhas da tabela sem recarregar (table-search.js) -->
    <div class="mb-3">
        <label for="table-search" class="visually-hidden">Buscar produtos</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-search" aria-hidden="true"></i></span>
            <input type="search" id="table-search" class="form-control" placeholder="Buscar por nome, marca ou cor..."
                aria-controls="products-table" aria-describedby="search-count" autocomplete="off">
        </div>
        <div id="search-count" class="form-text" aria-live="polite">
            <?= count($products) ?> produto(s)
        </div>
    </div>

    <div class="table-responsive table-card rounded shadow-sm">
        <table class="table table-hover align-middle mb-0" id="products-table">
            <caption class="visually-hidden">Lista de produtos <?= $active ? 'ativos' : 'inativos' ?></caption>
            <thead class="table-light">
                <tr>
                    <?php foreach ($columns as $column => $label): ?>
                    <th scope="col" class="<?= $responsive[$column] ?? '' ?>"
                        <?= $column === $sortBy ? 'aria-sort="' . ($descending ? 'descending' : 'ascending') . '"' : '' ?>>
                        <a href="<?= escape($sortUrl($column)) ?>" class="sort-link">
                            <?= escape($label) ?>
                            <?php if ($column === $sortBy): ?>
                            <i class="bi bi-caret-<?= $descending ? 'down' : 'up' ?>-fill" aria-hidden="true"></i>
                            <?php endif; ?>
                        </a>
                    </th>
                    <?php endforeach; ?>
                    <th scope="col" class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr data-search="<?= escape($product['nome'] . ' ' . $product['marca'] . ' ' . $product['cor']) ?>">
                    <td class="<?= $responsive['id'] ?>"><?= escape($product['id']) ?></td>
                    <td class="fw-medium">
                        <?= escape($product['nome']) ?>
                        <div class="small text-muted fw-normal d-md-none"><?= escape($product['marca']) ?></div>
                    </td>
                    <td class="<?= $responsive['marca'] ?>"><?= escape($product['marca']) ?></td>
                    <td class="<?= $responsive['cor'] ?>"><?= escape($product['cor']) ?></td>
                    <td class="text-nowrap">R$ <?= number_format((float) $product['valor'], 2, ',', '.') ?></td>
                    <td class="text-end text-nowrap">
                        <a href="index.php?route=show&id=<?= escape($product['id']) ?>"
                            class="btn btn-sm btn-outline-secondary" title="Ver"
                            aria-label="Ver <?= escape($product['nome']) ?>">
                            <i class="bi bi-eye" aria-hidden="true"></i>
                        </a>
                        <a href="index.php?route=edit&id=<?= escape($product['id']) ?>"
                            class="btn btn-sm btn-outline-primary" title="Editar"
                            aria-label="Editar <?= escape($product['nome']) ?>">
                            <i class="bi bi-pencil" aria-hidden="true"></i>
                        </a>
                        <form action="index.php?route=delete" method="post" class="d-inline" data-delete-form
                            data-product-name="<?= escape($product['nome']) ?>">
                            <?= csrfField() ?>
                            <input type="hidden" name="id" value="<?= escape($product['id']) ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir"
                                aria-label="Excluir <?= escape($product['nome']) ?>">
                                <i class="bi bi-trash" aria-hidden="true"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p id="search-empty" class="text-center text-muted py-4 mb-0" hidden>
            Nenhum produto corresponde à busca.
        </p>
    </div>
    <?php endif; ?>
</section>