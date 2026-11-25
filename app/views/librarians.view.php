<?php
declare(strict_types=1);

/**
 * @var object{
 *     title: string,
 *     activeNav: string,
 *     currentLibrarian: array{id: int, num_empleado: string, nombre_completo: string, correo: string},
 *     csrfToken: string,
 *     flash: ?array{type: string, message: string},
 *     dbError: ?string,
 *     formError: ?string,
 *     openModal: ?string,
 *     filters: object{search: string, status: string},
 *     counts: object{total: int, active: int, inactive: int},
 *     librarians: list<array{id: int, num_empleado: string, nombre_completo: string, correo: string, estado: string}>,
 *     createForm: object{num_empleado: string, nombre_completo: string, correo: string},
 *     editForm: object{id: int, num_empleado: string, nombre_completo: string, correo: string},
 *     actions: object{selfUrl: string, filterAllUrl: string, filterActiveUrl: string, filterInactiveUrl: string}
 * } $pageData
 */
$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
$activeNav = $pageData->activeNav;
$currentLibrarian = $pageData->currentLibrarian;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $escape($pageData->title) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,500;0,6..72,600;1,6..72,400&amp;family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=block" rel="stylesheet">
  <link rel="stylesheet" href="../styles/tokens.css">
  <link rel="stylesheet" href="../styles/styles.css">
  <link rel="stylesheet" href="../styles/librarians.css">
</head>
<body>
  <div class="app-shell">
    <?php require __DIR__ . '/partials/topbar.php'; ?>

    <main class="app-main">
      <div class="app-container page-stack">
        <div class="page-header">
          <div class="page-header-copy">
            <h1 class="page-title">Administración de Bibliotecarios</h1>
            <p class="page-subtitle">
              Alta, consulta y control de cuentas institucionales autorizadas para operar el mostrador.
            </p>
          </div>
          <button id="open-create-modal" type="button" class="btn btn-primary">
            <span class="material-symbols-outlined btn-icon">person_add</span>
            <span>Nuevo Bibliotecario</span>
          </button>
        </div>

        <?php if ($pageData->flash !== null): ?>
          <div class="alert <?= $pageData->flash['type'] === 'success' ? 'alert-success' : 'alert-error' ?>" role="status">
            <span class="material-symbols-outlined alert-icon">
              <?= $pageData->flash['type'] === 'success' ? 'check_circle' : 'error' ?>
            </span>
            <span><?= $escape($pageData->flash['message']) ?></span>
          </div>
        <?php endif; ?>

        <?php if ($pageData->dbError !== null): ?>
          <div class="alert alert-error" role="alert">
            <span class="material-symbols-outlined alert-icon">database_off</span>
            <span><?= $escape($pageData->dbError) ?></span>
          </div>
        <?php endif; ?>

        <section class="card filter-toolbar">
          <form method="get" action="librarians.php" class="filter-search-form">
            <span class="material-symbols-outlined filter-search-icon">search</span>
            <?php if ($pageData->filters->status !== 'all'): ?>
              <input type="hidden" name="status" value="<?= $escape($pageData->filters->status) ?>">
            <?php endif; ?>
            <input
              id="staff-search"
              type="search"
              name="q"
              value="<?= $escape($pageData->filters->search) ?>"
              class="filter-search-input"
              placeholder="Buscar por No. Empleado, nombre o correo..."
            >
          </form>

          <div class="status-pills" role="group">
            <a
              href="<?= $escape($pageData->actions->filterAllUrl) ?>"
              class="status-pill<?= $pageData->filters->status === 'all' ? ' is-active' : '' ?>"
            >
              Todos (<?= $pageData->counts->total ?>)
            </a>
            <a
              href="<?= $escape($pageData->actions->filterActiveUrl) ?>"
              class="status-pill<?= $pageData->filters->status === 'Activo' ? ' is-active' : '' ?>"
            >
              Activos (<?= $pageData->counts->active ?>)
            </a>
            <a
              href="<?= $escape($pageData->actions->filterInactiveUrl) ?>"
              class="status-pill<?= $pageData->filters->status === 'Inactivo' ? ' is-active' : '' ?>"
            >
              Inactivos (<?= $pageData->counts->inactive ?>)
            </a>
          </div>
        </section>

        <section class="card">
          <div class="table-wrap">
            <table class="data-table">
              <thead>
                <tr>
                  <th scope="col">No. Empleado</th>
                  <th scope="col">Nombre Completo</th>
                  <th scope="col">Correo Institucional</th>
                  <th scope="col">Estado</th>
                  <th scope="col" class="col-right">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($pageData->librarians === []): ?>
                  <tr>
                    <td colspan="5" class="table-empty">
                      No se encontraron bibliotecarios que coincidan con los criterios actuales.
                    </td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($pageData->librarians as $librarian): ?>
                    <?php
                    $isActive = $librarian['estado'] === 'Activo';
                    $isSelf = $librarian['id'] === $currentLibrarian['id'];
                    $nextStatus = $isActive ? 'Inactivo' : 'Activo';
                    ?>
                    <tr class="<?= $isActive ? '' : 'staff-row-inactive' ?>">
                      <td>
                        <span class="staff-code tabular-nums"><?= $escape($librarian['num_empleado']) ?></span>
                      </td>
                      <td>
                        <span class="staff-name"><?= $escape($librarian['nombre_completo']) ?></span>
                      </td>
                      <td>
                        <span class="staff-email"><?= $escape($librarian['correo']) ?></span>
                      </td>
                      <td>
                        <span class="badge <?= $isActive ? 'badge-success' : 'badge-neutral' ?>">
                          <span class="badge-dot"></span>
                          <span><?= $escape($librarian['estado']) ?></span>
                        </span>
                      </td>
                      <td class="col-right">
                        <div class="row-actions">
                          <button
                            type="button"
                            class="btn btn-outline btn-sm js-edit-librarian"
                            data-id="<?= $librarian['id'] ?>"
                            data-emp="<?= $escape($librarian['num_empleado']) ?>"
                            data-name="<?= $escape($librarian['nombre_completo']) ?>"
                            data-email="<?= $escape($librarian['correo']) ?>"
                          >
                            <span class="material-symbols-outlined btn-icon">edit</span>
                            <span>Editar</span>
                          </button>

                          <form method="post" action="<?= $escape($pageData->actions->selfUrl) ?>">
                            <input type="hidden" name="csrf_token" value="<?= $escape($pageData->csrfToken) ?>">
                            <input type="hidden" name="action" value="toggle_status">
                            <input type="hidden" name="id" value="<?= $librarian['id'] ?>">
                            <input type="hidden" name="target_status" value="<?= $escape($nextStatus) ?>">
                            <button
                              type="submit"
                              class="btn btn-sm <?= $isActive ? 'btn-danger-ghost' : 'btn-success-ghost' ?>"
                              <?= $isSelf && $isActive ? 'disabled title="No puedes desactivar tu propia sesión activa"' : '' ?>
                            >
                              <span class="material-symbols-outlined btn-icon">
                                <?= $isActive ? 'person_off' : 'person_check' ?>
                              </span>
                              <span><?= $isActive ? 'Desactivar' : 'Activar' ?></span>
                            </button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <div class="table-footer">
            <span>
              Mostrando <strong class="tabular-nums"><?= count($pageData->librarians) ?></strong>
              de <strong class="tabular-nums"><?= $pageData->counts->total ?></strong> bibliotecarios registrados
            </span>
            <span>
              Solo cuentas en estado <span class="rn-highlight">Activo</span> pueden iniciar sesión
            </span>
          </div>
        </section>
      </div>
    </main>
  </div>

  <!-- Modal: Nuevo Bibliotecario (estructura `bibliotecarios` omitiendo valores automáticos `id` y `estado`) -->
  <div
    id="create-librarian-modal"
    class="modal-backdrop"
    role="dialog"
    <?= $pageData->openModal === 'create' ? '' : 'hidden' ?>
  >
    <article class="modal-dialog">
      <header class="modal-header">
        <div>
          <h2 id="create-modal-title" class="modal-title">Nuevo Bibliotecario</h2>
          <p class="modal-subtitle">Registra una cuenta institucional autorizada para operar el mostrador.</p>
        </div>
        <button type="button" class="modal-close" data-close-modal="create-librarian-modal">
          <span class="material-symbols-outlined">close</span>
        </button>
      </header>

      <?php if ($pageData->openModal === 'create' && $pageData->formError !== null): ?>
        <div class="alert alert-error" role="alert">
          <span class="material-symbols-outlined alert-icon">error</span>
          <span><?= $escape($pageData->formError) ?></span>
        </div>
      <?php endif; ?>

      <form method="post" action="<?= $escape($pageData->actions->selfUrl) ?>" class="form-stack">
        <input type="hidden" name="csrf_token" value="<?= $escape($pageData->csrfToken) ?>">
        <input type="hidden" name="action" value="create">

        <div class="form-grid form-grid-1-2">
          <label class="field">
            <span class="field-label">No. Empleado <span class="field-required">*</span></span>
            <input
              type="text"
              name="num_empleado"
              value="<?= $escape($pageData->createForm->num_empleado) ?>"
              class="field-input tabular-nums"
              required
              maxlength="20"
              placeholder="Ej. EMP-005"
            >
            <span class="field-help">Único · máx. 20 car.</span>
          </label>

          <label class="field">
            <span class="field-label">Nombre Completo <span class="field-required">*</span></span>
            <input
              type="text"
              name="nombre_completo"
              value="<?= $escape($pageData->createForm->nombre_completo) ?>"
              class="field-input"
              required
              maxlength="120"
              placeholder="Nombre y apellidos"
            >
            <span class="field-help">Máx. 120 caracteres</span>
          </label>
        </div>

        <label class="field">
          <span class="field-label">Correo Institucional <span class="field-required">*</span></span>
          <input
            type="email"
            name="correo"
            value="<?= $escape($pageData->createForm->correo) ?>"
            class="field-input"
            required
            maxlength="100"
            placeholder="nombre.apellido@universidad.edu.mx"
          >
          <span class="field-help">Se usará como usuario de acceso (único, formato válido con @)</span>
        </label>

        <div class="form-grid form-grid-2">
          <label class="field">
            <span class="field-label">Contraseña <span class="field-required">*</span></span>
            <div class="field-password-wrap">
              <input
                id="create-password"
                type="password"
                name="password"
                class="field-input"
                required
                minlength="8"
                autocomplete="new-password"
                placeholder="Mínimo 8 caracteres"
              >
              <button type="button" class="password-toggle" data-toggle-password="create-password">
                <span class="material-symbols-outlined">visibility</span>
              </button>
            </div>
          </label>

          <label class="field">
            <span class="field-label">Confirmar Contraseña <span class="field-required">*</span></span>
            <div class="field-password-wrap">
              <input
                id="create-password-confirm"
                type="password"
                name="password_confirm"
                class="field-input"
                required
                minlength="8"
                autocomplete="new-password"
                placeholder="Repite la contraseña"
              >
              <button type="button" class="password-toggle" data-toggle-password="create-password-confirm">
                <span class="material-symbols-outlined">visibility</span>
              </button>
            </div>
          </label>
        </div>

        <div class="alert alert-info">
          <span class="material-symbols-outlined alert-icon">info</span>
          <span>
            La contraseña se almacena cifrada y la cuenta se crea automáticamente con estado
            <strong class="rn-highlight">Activo</strong>.
          </span>
        </div>

        <footer class="modal-footer">
          <button type="button" class="btn btn-outline" data-close-modal="create-librarian-modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">
            <span class="material-symbols-outlined btn-icon">check</span>
            <span>Registrar Bibliotecario</span>
          </button>
        </footer>
      </form>
    </article>
  </div>

  <!-- Modal: Editar Bibliotecario -->
  <div
    id="edit-librarian-modal"
    class="modal-backdrop"
    role="dialog"
    <?= $pageData->openModal === 'edit' ? '' : 'hidden' ?>
  >
    <article class="modal-dialog">
      <header class="modal-header">
        <div>
          <h2 id="edit-modal-title" class="modal-title">Editar Bibliotecario</h2>
          <p class="modal-subtitle">Actualiza los datos de la cuenta institucional seleccionada.</p>
        </div>
        <button type="button" class="modal-close" data-close-modal="edit-librarian-modal">
          <span class="material-symbols-outlined">close</span>
        </button>
      </header>

      <?php if ($pageData->openModal === 'edit' && $pageData->formError !== null): ?>
        <div class="alert alert-error" role="alert">
          <span class="material-symbols-outlined alert-icon">error</span>
          <span><?= $escape($pageData->formError) ?></span>
        </div>
      <?php endif; ?>

      <form method="post" action="<?= $escape($pageData->actions->selfUrl) ?>" class="form-stack">
        <input type="hidden" name="csrf_token" value="<?= $escape($pageData->csrfToken) ?>">
        <input type="hidden" name="action" value="update">
        <input id="edit-id" type="hidden" name="id" value="<?= $pageData->editForm->id ?>">

        <div class="form-grid form-grid-1-2">
          <label class="field">
            <span class="field-label">No. Empleado <span class="field-required">*</span></span>
            <input
              id="edit-emp"
              type="text"
              name="num_empleado"
              value="<?= $escape($pageData->editForm->num_empleado) ?>"
              class="field-input tabular-nums"
              required
              maxlength="20"
            >
          </label>

          <label class="field">
            <span class="field-label">Nombre Completo <span class="field-required">*</span></span>
            <input
              id="edit-name"
              type="text"
              name="nombre_completo"
              value="<?= $escape($pageData->editForm->nombre_completo) ?>"
              class="field-input"
              required
              maxlength="120"
            >
          </label>
        </div>

        <label class="field">
          <span class="field-label">Correo Institucional <span class="field-required">*</span></span>
          <input
            id="edit-email"
            type="email"
            name="correo"
            value="<?= $escape($pageData->editForm->correo) ?>"
            class="field-input"
            required
            maxlength="100"
          >
        </label>

        <div class="form-grid form-grid-2">
          <label class="field">
            <span class="field-label">Nueva Contraseña (opcional)</span>
            <div class="field-password-wrap">
              <input
                id="edit-password"
                type="password"
                name="password"
                class="field-input"
                minlength="8"
                autocomplete="new-password"
                placeholder="Dejar vacío para conservar"
              >
              <button type="button" class="password-toggle" data-toggle-password="edit-password">
                <span class="material-symbols-outlined">visibility</span>
              </button>
            </div>
          </label>

          <label class="field">
            <span class="field-label">Confirmar Contraseña</span>
            <div class="field-password-wrap">
              <input
                id="edit-password-confirm"
                type="password"
                name="password_confirm"
                class="field-input"
                minlength="8"
                autocomplete="new-password"
                placeholder="Repite si deseas cambiarla"
              >
              <button type="button" class="password-toggle" data-toggle-password="edit-password-confirm">
                <span class="material-symbols-outlined">visibility</span>
              </button>
            </div>
          </label>
        </div>

        <footer class="modal-footer">
          <button type="button" class="btn btn-outline" data-close-modal="edit-librarian-modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">
            <span class="material-symbols-outlined btn-icon">save</span>
            <span>Guardar Cambios</span>
          </button>
        </footer>
      </form>
    </article>
  </div>

  <script src="../js/librarians.js" defer></script>
</body>
</html>
