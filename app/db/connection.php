<?php
declare(strict_types=1);

if (!defined('FINE_RATE_PER_DAY')) {
    define('FINE_RATE_PER_DAY', 15.00);
}

// Devuelve una única conexión PDO reutilizable durante toda la petición.
function getDbConnection(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = '127.0.0.1';
    $port = 3306;
    $dbname = 'biblioteca_db';
    $charset = 'utf8mb4';
    $username = 'root';
    $password = 'root';

    $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $host, $port, $dbname, $charset);

    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $pdo;
}
