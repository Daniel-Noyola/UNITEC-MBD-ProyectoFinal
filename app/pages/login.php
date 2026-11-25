<?php
declare(strict_types=1);

require_once __DIR__ . '/../db/connection.php';
require_once __DIR__ . '/../db/repositories/librarians_repo.php';
require_once __DIR__ . '/../helpers/auth.php';

startAppSession();

if (isAuthenticated()) {
    header('Location: dashboard.php');
    exit;
}

$dbConnected = isDbConnected();

$collectionSummary = (object) [
    'total' => '—',
    'available' => '—',
    'loaned' => '—',
];

if ($dbConnected) {
    try {
        $counts = getCollectionSummary();
        $collectionSummary = (object) [
            'total' => number_format($counts['total']),
            'available' => number_format($counts['available']),
            'loaned' => number_format($counts['loaned']) . ' activos',
        ];
    } catch (Throwable $exception) {
        // Conserva guiones si la tabla aún no existe o no se pudo consultar.
    }
}

$error = null;
$identifier = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = trim((string) ($_POST['identifier'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($identifier === '' || $password === '') {
        $error = 'Ingrese su correo institucional o número de empleado y su contraseña.';
    } elseif (!$dbConnected) {
        $error = 'No hay conexión con la base de datos. Verifique el servidor MySQL e intente nuevamente.';
    } else {
        try {
            $librarian = findLibrarianByIdentifier($identifier);
            $isActive = $librarian !== null && $librarian['estado'] === 'Activo';
            $isValidPassword = $isActive && password_verify($password, $librarian['password_hash']);

            if ($isValidPassword) {
                loginLibrarian($librarian);
                header('Location: dashboard.php');
                exit;
            }

            // Mensaje genérico según HU-11 (sin revelar si el correo existe o si la cuenta está inactiva).
            $error = 'Credenciales inválidas o cuenta inactiva. Verifique sus datos e intente nuevamente.';
        } catch (Throwable $exception) {
            $error = 'Ocurrió un error al validar sus credenciales. Intente nuevamente.';
        }
    }
}

$pageData = (object) [
    'title' => 'Inicio de Sesión — Sistema de Gestión Bibliotecaria',
    'identifier' => $identifier,
    'error' => $error,
    'dbStatus' => (object) [
        'connected' => $dbConnected,
        'variant' => $dbConnected ? 'success' : 'error',
        'label' => $dbConnected ? 'Mostrador Central Activo' : 'Sin conexión a la base de datos',
    ],
    'collection' => $collectionSummary,
    'policies' => (object) [
        'dailyFine' => '$' . number_format((float) FINE_RATE_PER_DAY, 2) . ' MXN',
    ],
    'actions' => (object) [
        'submitUrl' => 'login.php',
    ],
];

require __DIR__ . '/../views/login.view.php';
