<?php

?>

<section class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h1 class="h4 mb-4"><?= $isEdit ? 'Editar produto' : 'Novo produto' ?></h1>

                <form action="<?= e($action) ?>" method="post" class="row g-3">
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="id" value="<?= e($product['id']) ?>">
                    <?php endif; ?>

                    <div class="col-md-6">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" id="nome" name="nome" class="form-control" value="<?= e($product['nome']) ?>"
                            minlength="3" maxlength="100" required>
                    </div>

                    <div class="col-md-6">
                        <label for="marca" class="form-label">Marca</label>
                        <input type="text" id="marca" name="marca" class="form-control"
                            value="<?= e($product['marca']) ?>" minlength="3" maxlength="100" required>
                    </div>

                    <div class="col-md-6">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" id="senha" name="senha" class="form-control" minlength="8"
                            autocomplete="new-password" <?= $isEdit ? '' : 'required' ?>>
                        <?php if ($isEdit): ?>
                            <div class="form-text">Deixe em branco para manter a senha atual.</div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6">
                        <label for="cor" class="form-label">Cor</label>
                        <input type="text" id="cor" name="cor" class="form-control" value="<?= e($product['cor']) ?>"
                            maxlength="50" required>
                    </div>

                    <div class="col-md-6">
                        <label for="valor" class="form-label">Valor</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" id="valor" name="valor" class="form-control"
                                value="<?= e($product['valor']) ?>" step="0.01" min="0.01" required>
                        </div>
                    </div>

                    <div class="col-md-6 d-flex align-items-end">
                        <div class="form-check form-switch mb-2">
                            <input type="checkbox" id="ativo" name="ativo" value="1" class="form-check-input"
                                <?= !empty($product['ativo']) ? 'checked' : '' ?>>
                            <label for="ativo" class="form-check-label">Produto ativo</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="d-flex flex-column flex-sm-row gap-3 mt-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <?= $isEdit ? 'Salvar alterações' : 'Cadastrar' ?>
                            </button>
                            <button type="reset" class="btn btn-light border">Limpar formulário</button>
                            <a href="index.php?route=index" class="btn btn-link ms-sm-auto">Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>