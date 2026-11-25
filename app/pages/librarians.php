<?php
declare(strict_types=1);

require_once __DIR__ . '/../db/connection.php';
require_once __DIR__ . '/../db/repositories/librarians_repo.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/flash.php';

requireAuthentication('login.php');

$currentLibrarian = getAuthenticatedLibrarian();
if ($currentLibrarian === null) {
    header('Location: login.php');
    exit;
}

$searchQuery = trim((string) ($_GET['q'] ?? ''));
$rawStatus = (string) ($_GET['status'] ?? 'all');
$statusFilter = in_array($rawStatus, ['all', 'Activo', 'Inactivo'], true) ? $rawStatus : 'all';

$redirectQuery = static function (string $search, string $status): string {
    $params = [];
    if ($search !== '') {
        $params['q'] = $search;
    }
    if ($status !== 'all') {
        $params['status'] = $status;
    }

    return $params === [] ? 'librarians.php' : 'librarians.php?' . http_build_query($params);
};

/**
 * Valida los datos comunes de un bibliotecario contra las restricciones de `bibliotecarios`.
 */
$validateLibrarianFields = static function (
    string $employeeNumber,
    string $fullName,
    string $email,
    string $password,
    string $passwordConfirm,
    bool $requirePassword,
    ?int $excludeId = null
): ?string {
    if ($employeeNumber === '') {
        return 'El número de empleado es obligatorio.';
    }
    if (mb_strlen($employeeNumber) > 20) {
        return 'El número de empleado no puede superar 20 caracteres.';
    }
    if ($fullName === '') {
        return 'El nombre completo es obligatorio.';
    }
    if (mb_strlen($fullName) > 120) {
        return 'El nombre completo no puede superar 120 caracteres.';
    }
    if ($email === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        return 'Ingrese un correo institucional válido.';
    }
    if (mb_strlen($email) > 100) {
        return 'El correo institucional no puede superar 100 caracteres.';
    }

    if ($requirePassword || $password !== '' || $passwordConfirm !== '') {
        if (strlen($password) < 8) {
            return 'La contraseña debe tener al menos 8 caracteres.';
        }
        if ($password !== $passwordConfirm) {
            return 'Las contraseñas no coinciden.';
        }
    }

    if (existsLibrarianByEmployeeNumber($employeeNumber, $excludeId)) {
        return 'Ya existe un bibliotecario registrado con ese número de empleado.';
    }
    if (existsLibrarianByEmail($email, $excludeId)) {
        return 'Ya existe un bibliotecario registrado con ese correo institucional.';
    }

    return null;
};

$flash = consumeFlashMessage();
$formError = null;
$openModal = null;

$createForm = [
    'num_empleado' => '',
    'nombre_completo' => '',
    'correo' => '',
];

$editForm = [
    'id' => 0,
    'num_empleado' => '',
    'nombre_completo' => '',
    'correo' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = isset($_POST['csrf_token']) ? (string) $_POST['csrf_token'] : null;
    $action = (string) ($_POST['action'] ?? '');

    if (!verifyCsrfToken($csrfToken)) {
        setFlashMessage('error', 'La solicitud expiró o no es válida. Intente nuevamente.');
        header('Location: ' . $redirectQuery($searchQuery, $statusFilter));
        exit;
    }

    try {
        if ($action === 'create') {
            $employeeNumber = trim((string) ($_POST['num_empleado'] ?? ''));
            $fullName = trim((string) ($_POST['nombre_completo'] ?? ''));
            $email = strtolower(trim((string) ($_POST['correo'] ?? '')));
            $password = (string) ($_POST['password'] ?? '');
            $passwordConfirm = (string) ($_POST['password_confirm'] ?? '');

            $createForm = [
                'num_empleado' => $employeeNumber,
                'nombre_completo' => $fullName,
                'correo' => $email,
            ];

            $validationError = $validateLibrarianFields(
                $employeeNumber,
                $fullName,
                $email,
                $password,
                $passwordConfirm,
                true
            );

            if ($validationError !== null) {
                $formError = $validationError;
                $openModal = 'create';
            } else {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                createLibrarian($employeeNumber, $fullName, $email, $passwordHash);
                setFlashMessage('success', 'Bibliotecario registrado correctamente con estado Activo.');
                header('Location: ' . $redirectQuery($searchQuery, $statusFilter));
                exit;
            }
        } elseif ($action === 'update') {
            $id = (int) ($_POST['id'] ?? 0);
            $employeeNumber = trim((string) ($_POST['num_empleado'] ?? ''));
            $fullName = trim((string) ($_POST['nombre_completo'] ?? ''));
            $email = strtolower(trim((string) ($_POST['correo'] ?? '')));
            $password = (string) ($_POST['password'] ?? '');
            $passwordConfirm = (string) ($_POST['password_confirm'] ?? '');

            $editForm = [
                'id' => $id,
                'num_empleado' => $employeeNumber,
                'nombre_completo' => $fullName,
                'correo' => $email,
            ];

            $existing = $id > 0 ? findLibrarianById($id) : null;
            if ($existing === null) {
                setFlashMessage('error', 'El bibliotecario seleccionado no existe.');
                header('Location: ' . $redirectQuery($searchQuery, $statusFilter));
                exit;
            }

            $validationError = $validateLibrarianFields(
                $employeeNumber,
                $fullName,
                $email,
                $password,
                $passwordConfirm,
                false,
                $id
            );

            if ($validationError !== null) {
                $formError = $validationError;
                $openModal = 'edit';
            } else {
                $passwordHash = $password !== '' ? password_hash($password, PASSWORD_DEFAULT) : null;
                updateLibrarian($id, $employeeNumber, $fullName, $email, $passwordHash);

                // Sincroniza los datos visibles de la sesión si el operador editó su propia cuenta.
                if ($id === $currentLibrarian['id']) {
                    $_SESSION['librarian']['num_empleado'] = $employeeNumber;
                    $_SESSION['librarian']['nombre_completo'] = $fullName;
                    $_SESSION['librarian']['correo'] = $email;
                }

                setFlashMessage('success', 'Datos del bibliotecario actualizados correctamente.');
                header('Location: ' . $redirectQuery($searchQuery, $statusFilter));
                exit;
            }
        } elseif ($action === 'toggle_status') {
            $id = (int) ($_POST['id'] ?? 0);
            $targetStatus = (string) ($_POST['target_status'] ?? '');

            if ($id === $currentLibrarian['id'] && $targetStatus === 'Inactivo') {
                setFlashMessage('error', 'No puedes desactivar tu propia cuenta mientras tienes la sesión activa.');
                header('Location: ' . $redirectQuery($searchQuery, $statusFilter));
                exit;
            }

            $existing = $id > 0 ? findLibrarianById($id) : null;
            if ($existing === null || !in_array($targetStatus, ['Activo', 'Inactivo'], true)) {
                setFlashMessage('error', 'No se pudo actualizar el estado del bibliotecario.');
                header('Location: ' . $redirectQuery($searchQuery, $statusFilter));
                exit;
            }

            updateLibrarianStatus($id, $targetStatus);
            $label = $targetStatus === 'Activo' ? 'activada' : 'desactivada';
            setFlashMessage('success', 'Cuenta de ' . $existing['nombre_completo'] . ' ' . $label . ' correctamente.');
            header('Location: ' . $redirectQuery($searchQuery, $statusFilter));
            exit;
        }
    } catch (Throwable $exception) {
        $formError = 'Ocurrió un error al guardar los cambios en la base de datos.';
        $openModal = $action === 'update' ? 'edit' : ($action === 'create' ? 'create' : null);
    }
}

$dbError = null;
$librarians = [];
$counts = [
    'total' => 0,
    'active' => 0,
    'inactive' => 0,
];

try {
    $counts = getLibrarianStatusCounts();
    $librarians = getAllLibrarians(
        $searchQuery !== '' ? $searchQuery : null,
        $statusFilter !== 'all' ? $statusFilter : null
    );
} catch (Throwable $exception) {
    $dbError = 'No fue posible consultar el listado de bibliotecarios. Verifique la conexión con MySQL.';
}

$pageData = (object) [
    'title' => 'Administración de Bibliotecarios — Sistema de Gestión Bibliotecaria',
    'activeNav' => 'librarians',
    'currentLibrarian' => $currentLibrarian,
    'csrfToken' => getCsrfToken(),
    'flash' => $flash,
    'dbError' => $dbError,
    'formError' => $formError,
    'openModal' => $openModal,
    'filters' => (object) [
        'search' => $searchQuery,
        'status' => $statusFilter,
    ],
    'counts' => (object) $counts,
    'librarians' => $librarians,
    'createForm' => (object) $createForm,
    'editForm' => (object) $editForm,
    'actions' => (object) [
        'selfUrl' => $redirectQuery($searchQuery, $statusFilter),
        'filterAllUrl' => $redirectQuery($searchQuery, 'all'),
        'filterActiveUrl' => $redirectQuery($searchQuery, 'Activo'),
        'filterInactiveUrl' => $redirectQuery($searchQuery, 'Inactivo'),
    ],
];

require __DIR__ . '/../views/librarians.view.php';
