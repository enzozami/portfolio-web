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