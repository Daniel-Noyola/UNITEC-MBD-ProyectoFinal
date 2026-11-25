<?php
declare(strict_types=1);

/**
 * @var object{
 *     title: string,
 *     identifier: string,
 *     error: ?string,
 *     dbStatus: object{
 *         connected: bool,
 *         variant: string,
 *         label: string
 *     },
 *     collection: object{
 *         total: string,
 *         available: string,
 *         loaned: string
 *     },
 *     policies: object{
 *         dailyFine: string
 *     },
 *     actions: object{
 *         submitUrl: string
 *     }
 * } $pageData
 */

$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
$badgeVariantClass = $pageData->dbStatus->connected ? 'status-badge--success' : 'status-badge--error';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $escape($pageData->title) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Newsreader:ital,opsz,wght@0,6..72,500;0,6..72,600;1,6..72,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block" rel="stylesheet">
    <link rel="stylesheet" href="../styles/tokens.css">
    <link rel="stylesheet" href="../styles/login.css">
</head>
<body class="login-body">
    <div class="login-shell">
        <header class="login-container login-header">
            <div class="brand-group">
                <div class="brand-icon" aria-hidden="true">
                    <span class="material-symbols-outlined">local_library</span>
                </div>
                <div>
                    <p class="brand-title">Sistema de Gestión Bibliotecaria</p>
                    <p class="brand-subtitle">Universidad Nacional · Dirección General de Bibliotecas</p>
                </div>
            </div>

            <span
                class="status-badge <?= $escape($badgeVariantClass) ?>"
                role="status"
                aria-live="polite"
            >
                <span class="status-badge__dot" aria-hidden="true"></span>
                <span><?= $escape($pageData->dbStatus->label) ?></span>
            </span>
        </header>

        <main class="login-container login-main">
            <aside class="login-aside" aria-label="Estado del acervo">
                <article class="info-card">
                    <header class="info-card__header">
                        <span class="material-symbols-outlined info-card__icon--secondary" aria-hidden="true">inventory_2</span>
                        <h2 class="info-card__title">Estado del Acervo</h2>
                    </header>

                    <ul class="metric-list">
                        <li class="metric-list__item">
                            <span class="metric-list__label">Total catalogado</span>
                            <span class="metric-list__value tabular-nums"><?= $escape($pageData->collection->total) ?></span>
                        </li>
                        <li class="metric-list__item">
                            <span class="metric-list__label">Disponibles</span>
                            <span class="metric-list__value metric-list__value--success tabular-nums"><?= $escape($pageData->collection->available) ?></span>
                        </li>
                        <li class="metric-list__item">
                            <span class="metric-list__label">En préstamo</span>
                            <span class="metric-list__value metric-list__value--primary tabular-nums"><?= $escape($pageData->collection->loaned) ?></span>
                        </li>
                    </ul>
                </article>

                <article class="compact-card">
                    <div class="compact-card__icon compact-card__icon--secondary" aria-hidden="true">
                        <span class="material-symbols-outlined">apartment</span>
                    </div>
                    <div>
                        <p class="compact-card__title">Biblioteca Central</p>
                        <p class="compact-card__subtitle">Sala General · Hemeroteca</p>
                    </div>
                </article>
            </aside>

            <section class="login-center" aria-label="Formulario de acceso">
                <article class="login-card">
                    <header class="login-card__header">
                        <h1 class="login-card__title">Acceso al Mostrador</h1>
                        <p class="login-card__subtitle">
                            Ingrese sus credenciales institucionales para iniciar turno de atención.
                        </p>
                    </header>

                    <?php if (!$pageData->dbStatus->connected && $pageData->error === null): ?>
                        <div class="alert-box alert-box--error" role="alert">
                            <span class="material-symbols-outlined" aria-hidden="true">database_off</span>
                            <span>No se pudo establecer conexión con la base de datos. Verifique que MySQL esté activo.</span>
                        </div>
                    <?php endif; ?>

                    <?php if ($pageData->error !== null): ?>
                        <div class="alert-box alert-box--error" role="alert">
                            <span class="material-symbols-outlined" aria-hidden="true">error</span>
                            <span><?= $escape($pageData->error) ?></span>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?= $escape($pageData->actions->submitUrl) ?>" class="login-form" novalidate>
                        <div class="form-field">
                            <label for="identifier" class="form-field__label">Correo institucional o No. Empleado</label>
                            <div class="input-wrapper">
                                <span class="material-symbols-outlined input-wrapper__icon" aria-hidden="true">badge</span>
                                <input
                                    id="identifier"
                                    name="identifier"
                                    type="text"
                                    value="<?= $escape($pageData->identifier) ?>"
                                    placeholder="admin@biblioteca.local o EMP-001"
                                    autocomplete="username"
                                    required
                                    class="form-input"
                                >
                            </div>
                        </div>

                        <div class="form-field">
                            <div class="form-field__label-row">
                                <label for="password-input" class="form-field__label">Contraseña</label>
                                <button id="recovery-toggle" type="button" class="form-field__help-link">
                                    ¿Olvidó su contraseña?
                                </button>
                            </div>
                            <div class="input-wrapper">
                                <span class="material-symbols-outlined input-wrapper__icon" aria-hidden="true">lock</span>
                                <input
                                    id="password-input"
                                    name="password"
                                    type="password"
                                    placeholder="Ingrese su contraseña"
                                    autocomplete="current-password"
                                    required
                                    class="form-input form-input--with-toggle"
                                >
                                <button
                                    id="toggle-password"
                                    type="button"
                                    class="password-toggle"
                                    aria-label="Mostrar u ocultar contraseña"
                                    aria-pressed="false"
                                >
                                    <span id="toggle-password-icon" class="material-symbols-outlined" aria-hidden="true">visibility</span>
                                </button>
                            </div>
                        </div>

                        <div id="recovery-hint" class="alert-box alert-box--info recovery-hint" role="note">
                            <span class="material-symbols-outlined" aria-hidden="true">info</span>
                            <span>Para restablecer su acceso, solicite apoyo al administrador de la Dirección General de Bibliotecas (Ext. 4120).</span>
                        </div>

                        <button
                            type="submit"
                            class="btn-primary"
                            <?= !$pageData->dbStatus->connected ? 'disabled' : '' ?>
                        >
                            <span>Iniciar Sesión</span>
                            <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span>
                        </button>
                    </form>

                    <footer class="login-card__footer">
                        <span class="material-symbols-outlined" aria-hidden="true">verified_user</span>
                        <p class="login-card__footer-text">
                            Acceso exclusivo para personal bibliotecario activo. Toda operación queda registrada bajo su número de empleado.
                        </p>
                    </footer>
                </article>
            </section>

            <aside class="login-aside" aria-label="Políticas de préstamo y soporte">
                <article class="info-card">
                    <header class="info-card__header">
                        <span class="material-symbols-outlined info-card__icon--primary" aria-hidden="true">menu_book</span>
                        <h2 class="info-card__title">Políticas de Préstamo</h2>
                    </header>

                    <ul class="metric-list">
                        <li class="metric-list__item">
                            <span class="metric-list__label">Alumno</span>
                            <span class="metric-list__value tabular-nums">3 libros · 7 días</span>
                        </li>
                        <li class="metric-list__item">
                            <span class="metric-list__label">Docente</span>
                            <span class="metric-list__value tabular-nums">5 libros · 14 días</span>
                        </li>
                        <li class="metric-list__item">
                            <span class="metric-list__label">Mora diaria</span>
                            <span class="metric-list__value metric-list__value--error tabular-nums"><?= $escape($pageData->policies->dailyFine) ?></span>
                        </li>
                    </ul>
                </article>

                <article class="compact-card">
                    <div class="compact-card__icon compact-card__icon--primary" aria-hidden="true">
                        <span class="material-symbols-outlined">support_agent</span>
                    </div>
                    <div>
                        <p class="compact-card__title">Soporte de Mostrador</p>
                        <p class="compact-card__subtitle tabular-nums">Ext. 4120 · Turno 07:00–21:00</p>
                    </div>
                </article>
            </aside>
        </main>

        <footer class="login-container login-footer">
            <p>Sistema de Gestión Bibliotecaria · Ciclo 2026</p>
            <p>Estación de Préstamos y Devoluciones</p>
        </footer>
    </div>

    <script>
        (function () {
            var toggleBtn = document.getElementById('toggle-password');
            var passwordInput = document.getElementById('password-input');
            var toggleIcon = document.getElementById('toggle-password-icon');

            if (toggleBtn && passwordInput && toggleIcon) {
                toggleBtn.addEventListener('click', function () {
                    var isPassword = passwordInput.type === 'password';
                    passwordInput.type = isPassword ? 'text' : 'password';
                    toggleIcon.textContent = isPassword ? 'visibility_off' : 'visibility';
                    toggleBtn.setAttribute('aria-pressed', isPassword ? 'true' : 'false');
                });
            }

            var recoveryBtn = document.getElementById('recovery-toggle');
            var recoveryHint = document.getElementById('recovery-hint');

            if (recoveryBtn && recoveryHint) {
                recoveryBtn.addEventListener('click', function () {
                    recoveryHint.classList.toggle('is-visible');
                });
            }
        })();
    </script>
</body>
</html>
