<?php
declare(strict_types=1);

require_once __DIR__ . '/../connection.php';

/**
 * Busca un bibliotecario por correo institucional o número de empleado.
 *
 * @return ?array{
 *     id: int,
 *     num_empleado: string,
 *     nombre_completo: string,
 *     correo: string,
 *     password_hash: string,
 *     estado: string
 * }
 */
function findLibrarianByIdentifier(string $identifier): ?array
{
    $pdo = getDbConnection();

    $sql = 'SELECT id, num_empleado, nombre_completo, correo, password_hash, estado
            FROM bibliotecarios
            WHERE correo = :email OR num_empleado = :employee_number
            LIMIT 1';

    $statement = $pdo->prepare($sql);
    $statement->execute([
        'email' => $identifier,
        'employee_number' => $identifier,
    ]);

    $row = $statement->fetch();

    return is_array($row) ? $row : null;
}

/**
 * Obtiene conteos básicos del acervo físico para el resumen lateral del login.
 *
 * @return array{total: int, available: int, loaned: int}
 */
function getCollectionSummary(): array
{
    $pdo = getDbConnection();

    $sql = "SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN estado_disponibilidad = 'Disponible' THEN 1 ELSE 0 END) AS available,
                SUM(CASE WHEN estado_disponibilidad = 'Prestado' THEN 1 ELSE 0 END) AS loaned
            FROM ejemplares";

    $statement = $pdo->query($sql);
    $row = $statement->fetch();

    return [
        'total' => (int) ($row['total'] ?? 0),
        'available' => (int) ($row['available'] ?? 0),
        'loaned' => (int) ($row['loaned'] ?? 0),
    ];
}
