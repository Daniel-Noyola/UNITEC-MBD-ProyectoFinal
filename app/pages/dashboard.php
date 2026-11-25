<?php
declare(strict_types=1);

require_once __DIR__ . '/../helpers/auth.php';

if (isset($_GET['logout']) && $_GET['logout'] === '1') {
    logoutLibrarian();
    header('Location: login.php');
    exit;
}

requireAuthentication('login.php');

$librarian = getAuthenticatedLibrarian();
$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Sistema de Gestión Bibliotecaria</title>
</head>
<body>
    <!-- Archivo placeholder para recibir la redirección del login; la vista completa se construirá en su propia rama. -->
    <main>
        <h1>Dashboard (En construcción)</h1>
        <p>Sesión iniciada como: <?= $escape((string) ($librarian['nombre_completo'] ?? '')) ?> (<?= $escape((string) ($librarian['num_empleado'] ?? '')) ?>)</p>
        <p><a href="dashboard.php?logout=1">Cerrar sesión</a></p>
    </main>
</body>
</html>
