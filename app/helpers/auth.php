<?php
declare(strict_types=1);

function startAppSession(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    session_set_cookie_params([
        'httponly' => true,
    ]);

    session_start();
}

function isAuthenticated(): bool
{
    startAppSession();

    return isset($_SESSION['librarian']['id']) && is_int($_SESSION['librarian']['id']);
}

/**
 * @return ?array{id: int, num_empleado: string, nombre_completo: string, correo: string}
 */
function getAuthenticatedLibrarian(): ?array
{
    if (!isAuthenticated()) {
        return null;
    }

    return $_SESSION['librarian'];
}

/**
 * @param array{id: int|string, num_empleado: string, nombre_completo: string, correo: string} $librarian
 */
function loginLibrarian(array $librarian): void
{
    startAppSession();
    // Regenera el ID de sesión tras autenticar para prevenir fijación de sesión.
    session_regenerate_id(true);

    $_SESSION['librarian'] = [
        'id' => (int) $librarian['id'],
        'num_empleado' => (string) $librarian['num_empleado'],
        'nombre_completo' => (string) $librarian['nombre_completo'],
        'correo' => (string) $librarian['correo'],
    ];
}

function logoutLibrarian(): void
{
    startAppSession();
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            (bool) $params['secure'],
            (bool) $params['httponly']
        );
    }

    session_destroy();
}

function requireAuthentication(string $redirectTo = 'login.php'): void
{
    if (!isAuthenticated()) {
        header('Location: ' . $redirectTo);
        exit;
    }
}
