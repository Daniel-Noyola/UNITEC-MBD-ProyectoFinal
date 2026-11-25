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

/**
 * Lista bibliotecarios aplicando filtros opcionales de búsqueda y estado.
 *
 * @return list<array{
 *     id: int,
 *     num_empleado: string,
 *     nombre_completo: string,
 *     correo: string,
 *     estado: string
 * }>
 */
function getAllLibrarians(?string $search = null, ?string $status = null): array
{
    $pdo = getDbConnection();

    $conditions = [];
    $params = [];

    $trimmedSearch = trim((string) $search);
    if ($trimmedSearch !== '') {
        $likePattern = '%' . $trimmedSearch . '%';
        $conditions[] = '(num_empleado LIKE :search_emp OR nombre_completo LIKE :search_name OR correo LIKE :search_email)';
        $params['search_emp'] = $likePattern;
        $params['search_name'] = $likePattern;
        $params['search_email'] = $likePattern;
    }

    if ($status === 'Activo' || $status === 'Inactivo') {
        $conditions[] = 'estado = :status';
        $params['status'] = $status;
    }

    $sql = 'SELECT id, num_empleado, nombre_completo, correo, estado
            FROM bibliotecarios';

    if ($conditions !== []) {
        $sql .= ' WHERE ' . implode(' AND ', $conditions);
    }

    $sql .= ' ORDER BY estado ASC, id ASC';

    $statement = $pdo->prepare($sql);
    $statement->execute($params);

    $rows = $statement->fetchAll();

    return array_map(
        static fn (array $row): array => [
            'id' => (int) $row['id'],
            'num_empleado' => (string) $row['num_empleado'],
            'nombre_completo' => (string) $row['nombre_completo'],
            'correo' => (string) $row['correo'],
            'estado' => (string) $row['estado'],
        ],
        $rows
    );
}

/**
 * Devuelve conteos globales de bibliotecarios por estado operativo.
 *
 * @return array{total: int, active: int, inactive: int}
 */
function getLibrarianStatusCounts(): array
{
    $pdo = getDbConnection();

    $sql = "SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN estado = 'Activo' THEN 1 ELSE 0 END) AS active_count,
                SUM(CASE WHEN estado = 'Inactivo' THEN 1 ELSE 0 END) AS inactive_count
            FROM bibliotecarios";

    $statement = $pdo->query($sql);
    $row = $statement->fetch();

    return [
        'total' => (int) ($row['total'] ?? 0),
        'active' => (int) ($row['active_count'] ?? 0),
        'inactive' => (int) ($row['inactive_count'] ?? 0),
    ];
}

/**
 * Busca un bibliotecario por su identificador primario.
 *
 * @return ?array{
 *     id: int,
 *     num_empleado: string,
 *     nombre_completo: string,
 *     correo: string,
 *     estado: string
 * }
 */
function findLibrarianById(int $id): ?array
{
    $pdo = getDbConnection();

    $sql = 'SELECT id, num_empleado, nombre_completo, correo, estado
            FROM bibliotecarios
            WHERE id = :id
            LIMIT 1';

    $statement = $pdo->prepare($sql);
    $statement->execute(['id' => $id]);

    $row = $statement->fetch();
    if (!is_array($row)) {
        return null;
    }

    return [
        'id' => (int) $row['id'],
        'num_empleado' => (string) $row['num_empleado'],
        'nombre_completo' => (string) $row['nombre_completo'],
        'correo' => (string) $row['correo'],
        'estado' => (string) $row['estado'],
    ];
}

function existsLibrarianByEmployeeNumber(string $employeeNumber, ?int $excludeId = null): bool
{
    $pdo = getDbConnection();

    $sql = 'SELECT 1 FROM bibliotecarios WHERE num_empleado = :employee_number';
    $params = ['employee_number' => $employeeNumber];

    if ($excludeId !== null) {
        $sql .= ' AND id <> :exclude_id';
        $params['exclude_id'] = $excludeId;
    }

    $sql .= ' LIMIT 1';

    $statement = $pdo->prepare($sql);
    $statement->execute($params);

    return $statement->fetchColumn() !== false;
}

function existsLibrarianByEmail(string $email, ?int $excludeId = null): bool
{
    $pdo = getDbConnection();

    $sql = 'SELECT 1 FROM bibliotecarios WHERE correo = :email';
    $params = ['email' => $email];

    if ($excludeId !== null) {
        $sql .= ' AND id <> :exclude_id';
        $params['exclude_id'] = $excludeId;
    }

    $sql .= ' LIMIT 1';

    $statement = $pdo->prepare($sql);
    $statement->execute($params);

    return $statement->fetchColumn() !== false;
}

/**
 * Registra un nuevo bibliotecario (id automático y estado por defecto 'Activo').
 */
function createLibrarian(
    string $employeeNumber,
    string $fullName,
    string $email,
    string $passwordHash
): int {
    $pdo = getDbConnection();

    $sql = 'INSERT INTO bibliotecarios (num_empleado, nombre_completo, correo, password_hash)
            VALUES (:employee_number, :full_name, :email, :password_hash)';

    $statement = $pdo->prepare($sql);
    $statement->execute([
        'employee_number' => $employeeNumber,
        'full_name' => $fullName,
        'email' => $email,
        'password_hash' => $passwordHash,
    ]);

    return (int) $pdo->lastInsertId();
}

/**
 * Actualiza datos generales de un bibliotecario y, opcionalmente, su contraseña.
 */
function updateLibrarian(
    int $id,
    string $employeeNumber,
    string $fullName,
    string $email,
    ?string $passwordHash = null
): bool {
    $pdo = getDbConnection();

    if ($passwordHash !== null) {
        $sql = 'UPDATE bibliotecarios
                SET num_empleado = :employee_number,
                    nombre_completo = :full_name,
                    correo = :email,
                    password_hash = :password_hash
                WHERE id = :id';

        $params = [
            'employee_number' => $employeeNumber,
            'full_name' => $fullName,
            'email' => $email,
            'password_hash' => $passwordHash,
            'id' => $id,
        ];
    } else {
        $sql = 'UPDATE bibliotecarios
                SET num_empleado = :employee_number,
                    nombre_completo = :full_name,
                    correo = :email
                WHERE id = :id';

        $params = [
            'employee_number' => $employeeNumber,
            'full_name' => $fullName,
            'email' => $email,
            'id' => $id,
        ];
    }

    $statement = $pdo->prepare($sql);

    return $statement->execute($params);
}

/**
 * Cambia el estado operativo ('Activo' o 'Inactivo') de un bibliotecario.
 */
function updateLibrarianStatus(int $id, string $status): bool
{
    if ($status !== 'Activo' && $status !== 'Inactivo') {
        throw new InvalidArgumentException('Estado de bibliotecario no válido.');
    }

    $pdo = getDbConnection();

    $sql = 'UPDATE bibliotecarios
            SET estado = :status
            WHERE id = :id';

    $statement = $pdo->prepare($sql);

    return $statement->execute([
        'status' => $status,
        'id' => $id,
    ]);
}
