<?php
declare(strict_types=1);

require_once __DIR__ . '/auth.php';

function getCsrfToken(): string
{
    startAppSession();

    if (!isset($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verifyCsrfToken(?string $token): bool
{
    startAppSession();

    if (!is_string($token) || !isset($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
        return false;
    }

    return hash_equals($_SESSION['csrf_token'], $token);
}

function setFlashMessage(string $type, string $message): void
{
    startAppSession();

    $_SESSION['flash_message'] = [
        'type' => $type,
        'message' => $message,
    ];
}

/**
 * @return ?array{type: string, message: string}
 */
function consumeFlashMessage(): ?array
{
    startAppSession();

    if (!isset($_SESSION['flash_message']) || !is_array($_SESSION['flash_message'])) {
        return null;
    }

    $flash = [
        'type' => (string) ($_SESSION['flash_message']['type'] ?? 'info'),
        'message' => (string) ($_SESSION['flash_message']['message'] ?? ''),
    ];

    unset($_SESSION['flash_message']);

    return $flash['message'] !== '' ? $flash : null;
}
