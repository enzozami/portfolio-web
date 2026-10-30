<?php
$columns = [
    'id' => 'ID',
    'nome' => 'Nome',
    'marca' => 'Marca',
    'cor' => 'Cor',
    'valor' => 'Valor',
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

<section>
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
        <h1 class="h3 mb-0">Produtos</h1>

        <div class="btn-group" role="group" aria-label="Filtrar por status">
            <a href="index.php?route=index&ativo=1"
                class="btn btn-sm <?= $active ? 'btn-dark' : 'btn-outline-dark' ?>">Ativos</a>
            <a href="index.php?route=index&ativo=0"
                class="btn btn-sm <?= !$active ? 'btn-dark' : 'btn-outline-dark' ?>">Inativos</a>
        </div>
    </div>

    <?php if (!$products): ?>
        <div class="text-center text-muted py-5 bg-white rounded shadow-sm">
            <i class="bi bi-box-seam fs-1 d-block mb-2"></i>
            Nenhum produto <?= $active ? 'ativo' : 'inativo' ?> encontrado.
        </div>
    <?php else: ?>
        <div class="table-responsive bg-white rounded shadow-sm">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <?php foreach ($columns as $column => $label): ?>
                            <th scope="col">
                                <a href="<?= escape($sortUrl($column)) ?>" class="text-decoration-none text-dark">
                                    <?= escape($label) ?>
                                    <?php if ($column === $sortBy): ?>
                                        <i class="bi bi-caret-<?= $descending ? 'down' : 'up' ?>-fill"></i>
                                    <?php endif; ?>
                                </a>
                            </th>
                        <?php endforeach; ?>
                        <th scope="col" class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= escape($product['id']) ?></td>
                            <td><?= escape($product['nome']) ?></td>
                            <td><?= escape($product['marca']) ?></td>
                            <td><?= escape($product['cor']) ?></td>
                            <td>R$ <?= number_format((float) $product['valor'], 2, ',', '.') ?></td>
                            <td class="text-end text-nowrap">
                                <a href="index.php?route=show&id=<?= escape($product['id']) ?>"
                                    class="btn btn-sm btn-outline-secondary" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="index.php?route=edit&id=<?= escape($product['id']) ?>"
                                    class="btn btn-sm btn-outline-primary" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="index.php?route=delete" method="post" class="d-inline"
                                    onsubmit="return confirm('Excluir este produto?');">
                                    <input type="hidden" name="id" value="<?= escape($product['id']) ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>