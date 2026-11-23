<?php
declare(strict_types=1);

/**
 * @var object{
 *     title: string,
 *     subtitle: string,
 *     error: ?string,
 *     email: string,
 *     actions: object{
 *         submitUrl: string
 *     }
 * } $pageData
 */

$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $escape($pageData->title)?></title>
</head>
<body>
    <main>
        <h1><?= $escape($pageData->title) ?></h1>
        <p><?= $escape($pageData->subtitle) ?></p>

        <?php if (!empty($pageData->error)): ?>
            <p role="alert"><?= $escape((string) $pageData->error) ?></p>
        <?php endif; ?>

        <form method="POST" action="<?= $escape($pageData->actions->submitUrl) ?>">
            <div>
                <label for="email">Correo institucional</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="<?= $escape($pageData->email) ?>"
                    placeholder="admin@biblioteca.local"
                    required
                >
            </div>
            <div>
                <label for="password">Contraseña</label>
                <input id="password" name="password" type="password" required>
            </div>
            <button type="submit">Entrar</button>
        </form>
    </main>
</body>
</html>
