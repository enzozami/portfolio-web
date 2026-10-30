<?php

/**
 * Funções utilitárias usadas pelo controller e pelas views.
 *
 * Os dados que precisam sobreviver a um redirect (erros, valores digitados,
 * mensagens de sucesso) ficam na sessão e são apagados assim que lidos,
 * para aparecerem uma única vez ("flash").
 */

/**
 * Redireciona o navegador e encerra o script.
 */
function redirect(string $location): never
{
    header("Location: {$location}");
    exit;
}

/**
 * Guarda os erros de validação para exibir no formulário após o redirect.
 */
function setFormErrors(array $errors)
{
    $_SESSION['errors'] = $errors;
}

function getFormErrors(): array
{
    $errors = $_SESSION['errors'] ?? [];
    unset($_SESSION['errors']);

    return is_array($errors) ? $errors : [];
}

/**
 * Guarda o que o usuário digitou, para o formulário voltar preenchido após um erro.
 */
function setOldInput(array $input)
{
    // A senha nunca volta para o formulário
    unset($input['senha'], $input['csrf_token']);
    $_SESSION['old'] = $input;
}

function getOldInput(): array
{
    $old = $_SESSION['old'] ?? [];
    unset($_SESSION['old']);

    return is_array($old) ? $old : [];
}

/**
 * Mensagem de retorno exibida no topo da próxima página (ex.: "Produto cadastrado").
 *
 * @param string $type Cor do alerta do Bootstrap: success, danger, warning...
 */
function setFlash(string $type, string $message)
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);

    return is_array($flash) ? $flash : null;
}

/**
 * Token CSRF da sessão. Todo formulário POST envia esse valor, e o index.php
 * recusa a requisição se ele não bater. Assim, outro site não consegue
 * enviar formulários em nome do usuário.
 */
function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

/**
 * Campo oculto com o token, para colocar dentro de cada <form method="post">.
 */
function csrfField(): string
{
    return '<input type="hidden" name="csrf_token" value="' . escape(csrfToken()) . '">';
}

function verifyCsrf(): void
{
    $sent = $_POST['csrf_token'] ?? '';

    // hash_equals compara em tempo constante, sem vazar pistas pelo tempo de resposta
    if (!is_string($sent) || !hash_equals(csrfToken(), $sent)) {
        setFlash('danger', 'Sua sessão expirou ou a requisição é inválida. Tente novamente.');
        redirect('index.php');
    }
}

/**
 * Escapa um valor para exibição segura no HTML (proteção contra XSS).
 */
function escape(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
