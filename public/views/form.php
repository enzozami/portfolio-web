<?php
$action = $isEdit ? "index.php?route=update" : "index.php?route=store";

if (!$product) {
    require __DIR__ . '/not-found.php';
    return;
}

$invalid = fn(string $field): string => isset($errors[$field]) ? 'is-invalid' : '';
$ariaInvalid = fn(string $field): string => isset($errors[$field]) ? 'aria-invalid="true"' : '';
?>

<section class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h1 class="h4 mb-4"><?= $isEdit ? 'Editar produto' : 'Novo produto' ?></h1>

                <!-- novalidate desliga os balões nativos do navegador: quem valida é o form-validation.js -->
                <form action="<?= escape($action) ?>" method="post" class="row g-3" novalidate data-validate
                    data-is-edit="<?= $isEdit ? 'true' : 'false' ?>">
                    <?= csrfField() ?>
                    <?php if ($isEdit): ?>
                    <input type="hidden" name="id" value="<?= escape($product['id']) ?>">
                    <?php endif; ?>

                    <div class="col-md-6">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" id="nome" name="nome" class="form-control <?= $invalid('nome') ?>"
                            value="<?= escape($product['nome']) ?>" minlength="3" maxlength="100" required
                            aria-describedby="nome-feedback" <?= $ariaInvalid('nome') ?>>
                        <div class="invalid-feedback" id="nome-feedback"><?= escape($errors['nome'] ?? '') ?></div>
                    </div>

                    <div class="col-md-6">
                        <label for="marca" class="form-label">Marca</label>
                        <input type="text" id="marca" name="marca" class="form-control <?= $invalid('marca') ?>"
                            value="<?= escape($product['marca']) ?>" minlength="3" maxlength="100" required
                            aria-describedby="marca-feedback" <?= $ariaInvalid('marca') ?>>
                        <div class="invalid-feedback" id="marca-feedback"><?= escape($errors['marca'] ?? '') ?></div>
                    </div>

                    <div class="col-md-6">
                        <label for="senha" class="form-label">Senha</label>
                        <div class="input-group has-validation">
                            <input type="password" id="senha" name="senha" class="form-control <?= $invalid('senha') ?>"
                                minlength="8" autocomplete="new-password" <?= $isEdit ? '' : 'required' ?>
                                aria-describedby="senha-feedback<?= $isEdit ? ' senha-help' : '' ?>"
                                <?= $ariaInvalid('senha') ?>>
                            <!-- Mostrar/ocultar senha (password-toggle.js) -->
                            <button type="button" class="btn btn-outline-secondary" data-toggle-password="senha"
                                aria-label="Mostrar senha" aria-pressed="false">
                                <i class="bi bi-eye" aria-hidden="true"></i>
                            </button>
                            <div class="invalid-feedback" id="senha-feedback"><?= escape($errors['senha'] ?? '') ?>
                            </div>
                        </div>
                        <?php if ($isEdit): ?>
                        <div class="form-text" id="senha-help">Deixe em branco para manter a senha atual.</div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6">
                        <label for="cor" class="form-label">Cor</label>
                        <input type="text" id="cor" name="cor" class="form-control <?= $invalid('cor') ?>"
                            value="<?= escape($product['cor']) ?>" maxlength="50" required
                            aria-describedby="cor-feedback" <?= $ariaInvalid('cor') ?>>
                        <div class="invalid-feedback" id="cor-feedback"><?= escape($errors['cor'] ?? '') ?></div>
                    </div>

                    <div class="col-md-6">
                        <label for="valor" class="form-label">Valor</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text">R$</span>
                            <input type="number" id="valor" name="valor" class="form-control <?= $invalid('valor') ?>"
                                value="<?= escape($product['valor']) ?>" step="0.01" min="0.01" max="99999999.99"
                                inputmode="decimal" required aria-describedby="valor-feedback"
                                <?= $ariaInvalid('valor') ?>>
                            <div class="invalid-feedback" id="valor-feedback"><?= escape($errors['valor'] ?? '') ?>
                            </div>
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