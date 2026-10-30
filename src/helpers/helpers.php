<?php
function redirect(string $location): never
{
    header("Location: {$location}");
    exit;
}


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


function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}


function csrfField(): string
{
    return '<input type="hidden" name="csrf_token" value="' . escape(csrfToken()) . '">';
}

function verifyCsrf(): void
{
    $sent = $_POST['csrf_token'] ?? '';


    if (!is_string($sent) || !hash_equals(csrfToken(), $sent)) {
        setFlash('danger', 'Sua sessão expirou ou a requisição é inválida. Tente novamente.');
        redirect('index.php');
    }
}


function escape(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}