<?php
declare(strict_types=1);

require_once __DIR__ . '/../helpers/auth.php';

requireAuthentication('login.php');

$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Circulación — Sistema de Gestión Bibliotecaria</title>
</head>
<body>
    <main>
        <h1>Circulación (En construcción)</h1>
        <p><a href="librarians.php">Ir a Administración de Bibliotecarios</a> · <a href="dashboard.php">Volver al Dashboard</a></p>
    </main>
</body>
</html>
