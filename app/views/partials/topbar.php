<?php
declare(strict_types=1);

/**
 * Barra superior y navegación horizontal compartida para vistas autenticadas.
 *
 * Variables esperadas en el ámbito que la incluye:
 * @var string $activeNav Ruta activa ('dashboard'|'circulation'|'catalog'|'users'|'fines'|'librarians')
 * @var array{id: int, num_empleado: string, nombre_completo: string, correo: string} $currentLibrarian
 */
$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

$navItems = [
    ['key' => 'dashboard', 'label' => 'Dashboard', 'href' => 'dashboard.php', 'icon' => 'dashboard'],
    ['key' => 'circulation', 'label' => 'Circulación', 'href' => 'circulation.php', 'icon' => 'swap_horiz'],
    ['key' => 'catalog', 'label' => 'Catálogo y Ejemplares', 'href' => 'catalog.php', 'icon' => 'menu_book'],
    ['key' => 'users', 'label' => 'Padrón de Usuarios', 'href' => 'users.php', 'icon' => 'group'],
    ['key' => 'fines', 'label' => 'Sanciones y Multas', 'href' => 'fines.php', 'icon' => 'receipt_long'],
    ['key' => 'librarians', 'label' => 'Bibliotecarios', 'href' => 'librarians.php', 'icon' => 'admin_panel_settings'],
];
?>
<header class="app-header">
  <div class="topbar">
    <div class="app-container topbar-inner">
      <a href="dashboard.php" class="brand-link">
        <span class="brand-mark">
          <span class="material-symbols-outlined">local_library</span>
        </span>
        <span class="brand-text">
          <span class="brand-title">Biblioteca</span>
          <span class="brand-subtitle">Gestión SGB</span>
        </span>
      </a>

      <form action="catalog.php" method="get" class="global-search" role="search">
        <span class="material-symbols-outlined global-search-icon">search</span>
        <input
          type="search"
          name="q"
          class="global-search-input"
          placeholder="Buscar libro, ISBN, matrícula o código de barras..."
        >
      </form>

      <div class="topbar-actions">
        <div class="operator-pill" title="Operador en turno">
          <span class="operator-dot"></span>
          <span class="operator-name"><?= $escape($currentLibrarian['nombre_completo']) ?></span>
          <span class="operator-code tabular-nums">· <?= $escape($currentLibrarian['num_empleado']) ?></span>
        </div>

        <a href="circulation.php" class="btn btn-secondary">
          <span class="material-symbols-outlined btn-icon">add</span>
          <span>Nuevo Préstamo</span>
        </a>

        <a href="dashboard.php?logout=1" class="btn-icon-only topbar-logout" title="Cerrar Turno">
          <span class="material-symbols-outlined">logout</span>
        </a>
      </div>
    </div>
  </div>

  <nav class="subnav">
    <div class="app-container subnav-inner">
      <?php foreach ($navItems as $item): ?>
        <?php $isActive = ($activeNav ?? '') === $item['key']; ?>
        <a
          href="<?= $escape($item['href']) ?>"
          class="subnav-link<?= $isActive ? ' is-active' : '' ?>"
        >
          <span class="material-symbols-outlined subnav-icon"><?= $escape($item['icon']) ?></span>
          <span><?= $escape($item['label']) ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </nav>
</header>
