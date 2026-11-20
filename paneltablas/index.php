<?php
require_once dirname(__DIR__) . '/controladores/panel.php';

$secciones = [
    'users' => 'Mi perfil',
    'empresas' => 'Mi empresa',
    'usuario_empresa' => 'Mi acceso y rol',
    'contact_requests' => 'Mis solicitudes de contacto',
];
$seccion = $_GET['seccion'] ?? 'dashboard';
if (!is_string($seccion) || (!isset($secciones[$seccion]) && $seccion !== 'dashboard')) {
    $seccion = 'dashboard';
}

$datos = [];
$columnas = [];
$titulo = 'Panel principal';

try {
    if ($seccion === 'users') {
        $titulo = $secciones[$seccion];
        $consulta = $conn->prepare(
            'SELECT name, surnames, email, created_at, role, activo
            FROM users
            WHERE id = :user_id'
        );
        $consulta->execute(['user_id' => $panelUser['id']]);
        $usuario = $consulta->fetch();
        $columnas = [
            'name' => 'Nombre',
            'surnames' => 'Apellidos',
            'email' => 'Correo electrónico',
            'created_at' => 'Fecha de creación',
            'role' => 'Rol de cuenta',
            'activo' => 'Cuenta activa',
        ];
        $datos = $usuario ? [$usuario] : [];
    } elseif ($seccion === 'empresas') {
        $titulo = $secciones[$seccion];
        $consulta = $conn->prepare(
            'SELECT id, nombre, activa
            FROM empresas
            WHERE id = :empresa_id'
        );
        $consulta->execute(['empresa_id' => $panelEmpresa['id']]);
        $empresa = $consulta->fetch();
        $columnas = [
            'id' => 'ID',
            'nombre' => 'Empresa',
            'activa' => 'Empresa activa',
        ];
        $datos = $empresa ? [$empresa] : [];
    } elseif ($seccion === 'usuario_empresa') {
        $titulo = $secciones[$seccion];
        $consulta = $conn->prepare(
            'SELECT ue.user_id, e.nombre AS empresa, ue.rol
            FROM usuario_empresa ue
            INNER JOIN empresas e ON e.id = ue.empresa_id
            WHERE ue.user_id = :user_id AND ue.empresa_id = :empresa_id'
        );
        $consulta->execute([
            'user_id' => $panelUser['id'],
            'empresa_id' => $panelEmpresa['id'],
        ]);
        $datos = $consulta->fetchAll();
        $columnas = [
            'user_id' => 'ID de usuario',
            'empresa' => 'Empresa',
            'rol' => 'Rol en la empresa',
        ];
    } elseif ($seccion === 'contact_requests') {
        $titulo = $secciones[$seccion];
        $consulta = $conn->prepare(
            'SELECT nombre, email, perfil, empresa, cargo, servicio, telefono, mensaje, created_at
            FROM contact_requests
            WHERE users_id = :user_id
            ORDER BY created_at DESC'
        );
        $consulta->execute(['user_id' => $panelUser['id']]);
        $datos = $consulta->fetchAll();
        $columnas = [
            'nombre' => 'Nombre',
            'email' => 'Correo electrónico',
            'perfil' => 'Perfil',
            'empresa' => 'Empresa',
            'cargo' => 'Cargo',
            'servicio' => 'Servicio',
            'telefono' => 'Teléfono',
            'mensaje' => 'Mensaje',
            'created_at' => 'Fecha',
        ];
    }

    if ($seccion === 'dashboard') {
        $consulta = $conn->prepare(
            'SELECT COUNT(*) FROM contact_requests WHERE users_id = :user_id'
        );
        $consulta->execute(['user_id' => $panelUser['id']]);
        $totalContactos = (int) $consulta->fetchColumn();
    }
} catch (PDOException $error) {
    error_log('Error al cargar los datos del panel: ' . $error->getMessage());
    http_response_code(500);
    $errorPanel = 'No se pudieron cargar los datos. Inténtalo más tarde.';
}

$escapar = static function ($valor): string {
    return htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
};

$mostrarTabla = static function (string $caption, array $columnas, array $filas) use ($escapar): void {
    if (!$filas) {
        echo '<p>No hay información disponible en esta sección.</p>';
        return;
    }
    ?>
    <div class="tabla-panel-contenedor">
        <table class="tabla-panel">
            <caption><?= $escapar($caption) ?></caption>
            <thead>
                <tr>
                    <?php foreach ($columnas as $etiqueta): ?>
                        <th scope="col"><?= $escapar($etiqueta) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($filas as $fila): ?>
                    <tr>
                        <?php foreach ($columnas as $campo => $etiqueta): ?>
                            <?php
                            $valor = $fila[$campo] ?? '';
                            if (in_array($campo, ['activo', 'activa'], true)) {
                                $valor = (int) $valor === 1 ? 'Sí' : 'No';
                            }
                            ?>
                            <td><?= $escapar($valor) ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
};
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?> | Icare Coaching</title>
    <link rel="stylesheet" href="../assets/css/estilos.css?v=<?= filemtime(dirname(__DIR__) . '/assets/css/estilos.css') ?>">
</head>

<body>
    <a class="skip" href="#contenido">Saltar al contenido</a>
    <header class="panel-cabecera">
        <div class="panel-contenedor panel-cabecera-interior">
            <a class="panel-logo" href="index.php">icare coaching</a>
            <div class="panel-identidad">
                <span><?= htmlspecialchars($panelUser['nombre'] . ' ' . $panelUser['apellidos'], ENT_QUOTES, 'UTF-8') ?></span>
                <span><?= htmlspecialchars($panelEmpresa['nombre'], ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <form action="salir.php" method="post">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                <button class="boton boton-secundario" type="submit">Cerrar sesión</button>
            </form>
        </div>
    </header>

    <div class="panel-estructura">
        <nav class="panel-menu" id="panel-menu" aria-label="Menú del área privada">
            <div class="panel-menu-cabecera">
                <a class="panel-marca" href="index.php" aria-label="Icare Coaching, dashboard">
                    <span class="panel-marca-icono" aria-hidden="true">i</span>
                    <span class="panel-menu-texto">icare coaching</span>
                </a>
                <button class="panel-menu-toggle" id="panel-menu-toggle" type="button" aria-label="Contraer menú" aria-expanded="true" aria-controls="panel-menu" hidden>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" focusable="false">
                        <path d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            <a class="panel-menu-enlace" href="index.php" aria-label="Dashboard" title="Dashboard"<?= $seccion === 'dashboard' ? ' aria-current="page"' : '' ?>>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" aria-hidden="true" focusable="false">
                    <rect x="3" y="3" width="8" height="8" rx="1"></rect>
                    <rect x="13" y="3" width="8" height="5" rx="1"></rect>
                    <rect x="13" y="10" width="8" height="11" rx="1"></rect>
                    <rect x="3" y="13" width="8" height="8" rx="1"></rect>
                </svg>
                <span class="panel-menu-texto">Dashboard</span>
            </a>
            <details class="panel-menu-configuracion"<?= $seccion !== 'dashboard' ? ' open' : '' ?>>
                <summary aria-label="Configuración" title="Configuración">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="m19.4 15 .1.1 1.4 1.1-1.4 2.4-1.7-.7a8 8 0 0 1-1.7 1l-.3 1.8h-2.8l-.3-1.8a8 8 0 0 1-1.7-1l-1.7.7-1.4-2.4 1.4-1.1a7 7 0 0 1 0-2l-1.4-1.1 1.4-2.4 1.7.7a8 8 0 0 1 1.7-1l.3-1.8h2.8l.3 1.8a8 8 0 0 1 1.7 1l1.7-.7 1.4 2.4-1.4 1.1a7 7 0 0 1 0 2Z" transform="translate(-1 -1)"></path>
                    </svg>
                    <span class="panel-menu-texto">Configuración</span>
                    <span class="panel-menu-flecha" aria-hidden="true">⌄</span>
                </summary>
                <ul>
                    <?php foreach ($secciones as $clave => $etiqueta): ?>
                        <?php
                        $iconos = [
                            'users' => '<circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path>',
                            'empresas' => '<rect x="4" y="3" width="16" height="18" rx="2"></rect><path d="M9 21v-4h6v4M8 7h2m4 0h2M8 11h2m4 0h2"></path>',
                            'usuario_empresa' => '<circle cx="9" cy="8" r="3"></circle><path d="M3 20a6 6 0 0 1 12 0m3-12h3m-1.5-1.5v3"></path>',
                            'contact_requests' => '<rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="m3 7 9 6 9-6"></path>',
                        ];
                        ?>
                        <li>
                            <a class="panel-menu-enlace" href="?seccion=<?= rawurlencode($clave) ?>" aria-label="<?= htmlspecialchars($etiqueta, ENT_QUOTES, 'UTF-8') ?>" title="<?= htmlspecialchars($etiqueta, ENT_QUOTES, 'UTF-8') ?>"<?= $seccion === $clave ? ' aria-current="page"' : '' ?>>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><?= $iconos[$clave] ?></svg>
                                <span class="panel-menu-texto"><?= htmlspecialchars($etiqueta, ENT_QUOTES, 'UTF-8') ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </details>
        </nav>

        <main id="contenido" class="panel-contenido">
            <?php if (isset($errorPanel)): ?>
                <p class="panel-mensaje-error" role="alert"><?= htmlspecialchars($errorPanel, ENT_QUOTES, 'UTF-8') ?></p>
            <?php elseif ($seccion === 'dashboard'): ?>
                <p class="panel-eyebrow">Área privada</p>
                <h1>Dashboard</h1>
                <p>Resumen de la cuenta y la empresa seleccionada.</p>
                <div class="panel-tarjetas-resumen">
                    <section class="panel-tarjeta">
                        <h2>Usuario</h2>
                        <p><?= htmlspecialchars($panelUser['nombre'] . ' ' . $panelUser['apellidos'], ENT_QUOTES, 'UTF-8') ?></p>
                        <a href="?seccion=users">Ver mi perfil</a>
                    </section>
                    <section class="panel-tarjeta">
                        <h2>Empresa</h2>
                        <p><?= htmlspecialchars($panelEmpresa['nombre'], ENT_QUOTES, 'UTF-8') ?></p>
                        <a href="?seccion=empresas">Ver empresa</a>
                    </section>
                    <section class="panel-tarjeta">
                        <h2>Solicitudes de contacto</h2>
                        <p><?= $totalContactos ?></p>
                        <a href="?seccion=contact_requests">Ver mis solicitudes</a>
                    </section>
                </div>
                <section class="panel-bloque">
                    <h2>Acceso actual</h2>
                    <?php
                    $mostrarTabla('Relación de usuario con la empresa', [
                        'user_id' => 'ID de usuario',
                        'empresa' => 'Empresa',
                        'rol' => 'Rol en la empresa',
                    ], [[
                        'user_id' => $panelUser['id'],
                        'empresa' => $panelEmpresa['nombre'],
                        'rol' => $panelEmpresa['rol'],
                    ]]);
                    ?>
                </section>
            <?php else: ?>
                <p class="panel-eyebrow">Configuración</p>
                <h1><?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?></h1>
                <?php $mostrarTabla($titulo, $columnas, $datos); ?>
            <?php endif; ?>
        </main>
    </div>
    <script>
        const panelMenuToggle = document.getElementById('panel-menu-toggle');

        if (panelMenuToggle) {
            const menuMovil = window.matchMedia('(max-width: 700px)');
            const sincronizarBotonMenu = () => {
                const contraido = document.body.classList.contains('panel-menu-colapsado');
                panelMenuToggle.setAttribute('aria-expanded', String(!contraido));
                panelMenuToggle.setAttribute(
                    'aria-label',
                    contraido ? 'Expandir menú' : 'Contraer menú'
                );
            };

            panelMenuToggle.hidden = false;
            if (menuMovil.matches) {
                document.body.classList.add('panel-menu-colapsado');
            }
            sincronizarBotonMenu();
            panelMenuToggle.addEventListener('click', () => {
                document.body.classList.toggle('panel-menu-colapsado');
                sincronizarBotonMenu();
            });
        }
    </script>
</body>
</html>
