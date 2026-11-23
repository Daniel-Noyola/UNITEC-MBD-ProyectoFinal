<?php
declare(strict_types=1);

// Ejemplo del patrón de 2 archivos: este archivo prepara los datos y delega el HTML a la vista.
$pageData = (object) [
    'title' => 'Iniciar sesión — Sistema de Gestión Bibliotecaria',
    'subtitle' => 'Acceso para personal bibliotecario',
    'error' => null,
    'email' => '',
    'actions' => (object) [
        'submitUrl' => 'login.php',
    ],
];

require __DIR__ . '/../views/login.view.php';
