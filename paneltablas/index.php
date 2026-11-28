<?php
require_once dirname(__DIR__) . '/controladores/panel.php';

$secciones = [
    'users' => 'Mi perfil',
    'empresas' => $panelEsAdministrador ? 'Empresas' : 'Mis empresas',
    'usuario_empresa' => $panelEsAdministrador ? 'Accesos y roles' : 'Mi acceso y rol',
    'contact_requests' => 'Mis solicitudes de contacto',
];
$seccion = $_GET['seccion'] ?? 'dashboard';
$tablaSeleccionada = $_GET['tabla'] ?? '';
if (
    !is_string($seccion)
    || (!isset($secciones[$seccion]) && !in_array($seccion, ['dashboard', 'tabla'], true))
) {
    $seccion = 'dashboard';
}

$datos = [];
$columnas = [];
$titulo = 'Panel principal';
$tablasBdd = [];
$paginaActual = 1;
$totalPaginas = 1;
$totalRegistros = 0;
$tamanoPagina = 50;

try {
    $tablasConsulta = $conn->query(
        "SELECT TABLE_NAME
        FROM information_schema.TABLES
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_TYPE = 'BASE TABLE'
        ORDER BY TABLE_NAME"
    );
    $tablasBdd = $tablasConsulta->fetchAll(PDO::FETCH_COLUMN);

    if ($seccion === 'tabla') {
        if (!is_string($tablaSeleccionada) || !in_array($tablaSeleccionada, $tablasBdd, true)) {
            http_response_code(404);
            $errorPanel = 'La tabla solicitada no existe.';
        } else {
            $titulo = $tablaSeleccionada;
            $tablaSql = '`' . str_replace('`', '``', $tablaSeleccionada) . '`';
            $metadatosColumnas = $conn->query('SHOW COLUMNS FROM ' . $tablaSql)->fetchAll();
            $camposTabla = [];

            foreach ($metadatosColumnas as $metadato) {
                $campo = $metadato['Field'];
                if (in_array(strtolower($campo), ['password', 'token_hash'], true)) {
                    continue;
                }

                $camposTabla[] = $campo;
                $columnas[$campo] = $campo;
            }

            if (!$camposTabla) {
                $errorPanel = 'La tabla no contiene campos visibles.';
            } else {
                $paginaRecibida = filter_var(
                    $_GET['pagina'] ?? '1',
                    FILTER_VALIDATE_INT,
                    ['options' => ['min_range' => 1]]
                );

                if ($paginaRecibida === false) {
                    http_response_code(400);
                    $errorPanel = 'El número de página no es válido.';
                } else {
                    $where = '';
                    $parametros = [];

                    if ($panelEsAdministrador) {
                        if ($panelEmpresa) {
                            switch ($tablaSeleccionada) {
                                case 'empresas':
                                    $where = 'WHERE t.id = :empresa_id AND t.activa = TRUE';
                                    $parametros['empresa_id'] = $panelEmpresa['id'];
                                    break;
                                case 'usuario_empresa':
                                    $where = 'WHERE t.empresa_id = :empresa_id';
                                    $parametros['empresa_id'] = $panelEmpresa['id'];
                                    break;
                                case 'users':
                                    $where = 'WHERE EXISTS (
                                        SELECT 1 FROM usuario_empresa ue
                                        WHERE ue.user_id = t.id AND ue.empresa_id = :empresa_id
                                    )';
                                    $parametros['empresa_id'] = $panelEmpresa['id'];
                                    break;
                                case 'tasks':
                                    $where = 'WHERE EXISTS (
                                        SELECT 1 FROM usuario_empresa ue
                                        WHERE ue.user_id = t.user_id AND ue.empresa_id = :empresa_id
                                    )';
                                    $parametros['empresa_id'] = $panelEmpresa['id'];
                                    break;
                                case 'contact_requests':
                                    $where = 'WHERE EXISTS (
                                        SELECT 1 FROM usuario_empresa ue
                                        WHERE ue.user_id = t.users_id AND ue.empresa_id = :empresa_id
                                    )';
                                    $parametros['empresa_id'] = $panelEmpresa['id'];
                                    break;
                                case 'activaciones':
                                    $where = 'WHERE EXISTS (
                                        SELECT 1 FROM usuario_empresa ue
                                        WHERE ue.user_id = t.user_id AND ue.empresa_id = :empresa_id
                                    )';
                                    $parametros['empresa_id'] = $panelEmpresa['id'];
                                    break;
                            }
                        }
                    } else {
                        switch ($tablaSeleccionada) {
                            case 'role':
                                $where = 'WHERE EXISTS (
                                    SELECT 1 FROM users u
                                    WHERE u.id_role = t.id_role AND u.id = :user_id
                                )';
                                $parametros['user_id'] = $panelUser['id'];
                                break;
                            case 'users':
                                $where = 'WHERE t.id = :user_id';
                                $parametros['user_id'] = $panelUser['id'];
                                break;
                            case 'empresas':
                                $where = 'WHERE t.activa = TRUE AND EXISTS (
                                    SELECT 1 FROM usuario_empresa ue
                                    WHERE ue.empresa_id = t.id AND ue.user_id = :user_id
                                )';
                                $parametros['user_id'] = $panelUser['id'];
                                if ($panelEmpresa) {
                                    $where .= ' AND t.id = :empresa_id';
                                    $parametros['empresa_id'] = $panelEmpresa['id'];
                                }
                                break;
                            case 'usuario_empresa':
                                $where = 'WHERE t.user_id = :user_id AND EXISTS (
                                    SELECT 1 FROM empresas e
                                    WHERE e.id = t.empresa_id AND e.activa = TRUE
                                )';
                                $parametros['user_id'] = $panelUser['id'];
                                if ($panelEmpresa) {
                                    $where .= ' AND t.empresa_id = :empresa_id';
                                    $parametros['empresa_id'] = $panelEmpresa['id'];
                                }
                                break;
                            case 'tasks':
                                $where = 'WHERE t.user_id = :user_id';
                                $parametros['user_id'] = $panelUser['id'];
                                break;
                            case 'contact_requests':
                                $where = 'WHERE t.users_id = :user_id';
                                $parametros['user_id'] = $panelUser['id'];
                                break;
                            case 'activaciones':
                                $where = 'WHERE t.user_id = :user_id';
                                $parametros['user_id'] = $panelUser['id'];
                                break;
                            default:
                                $where = 'WHERE 1 = 0';
                                break;
                        }
                    }

                    $conteoConsulta = $conn->prepare(
                        'SELECT COUNT(*) FROM ' . $tablaSql . ' AS t ' . $where
                    );
                    $conteoConsulta->execute($parametros);
                    $totalRegistros = (int) $conteoConsulta->fetchColumn();
                    $totalPaginas = max(1, (int) ceil($totalRegistros / $tamanoPagina));
                    $paginaActual = min($paginaRecibida, $totalPaginas);
                    $offset = ($paginaActual - 1) * $tamanoPagina;

                    $clavesPrimarias = $conn->query(
                        "SHOW KEYS FROM " . $tablaSql . " WHERE Key_name = 'PRIMARY'"
                    )->fetchAll();
                    $ordenCampos = [];
                    foreach ($clavesPrimarias as $clavePrimaria) {
                        $campoClave = $clavePrimaria['Column_name'];
                        if (in_array($campoClave, $camposTabla, true)) {
                            $ordenCampos[] = 't.`' . str_replace('`', '``', $campoClave) . '`';
                        }
                    }
                    if (!$ordenCampos) {
                        $ordenCampos[] = 't.`' . str_replace('`', '``', $camposTabla[0]) . '`';
                    }

                    $camposSql = array_map(
                        static fn(string $campo): string => 't.`' . str_replace('`', '``', $campo) . '`',
                        $camposTabla
                    );
                    $consulta = $conn->prepare(
                        'SELECT ' . implode(', ', $camposSql)
                        . ' FROM ' . $tablaSql . ' AS t ' . $where
                        . ' ORDER BY ' . implode(', ', $ordenCampos)
                        . ' LIMIT :limite OFFSET :offset'
                    );
                    foreach ($parametros as $nombre => $valor) {
                        $consulta->bindValue(':' . $nombre, $valor, PDO::PARAM_INT);
                    }
                    $consulta->bindValue(':limite', $tamanoPagina, PDO::PARAM_INT);
                    $consulta->bindValue(':offset', $offset, PDO::PARAM_INT);
                    $consulta->execute();
                    $datos = $consulta->fetchAll();
                }
            }
        }
    } elseif ($seccion === 'users') {
        $titulo = $secciones[$seccion];
        $consulta = $conn->prepare(
            'SELECT u.name, u.surnames, u.email, u.created_at, r.`role` AS account_role, u.activo
            FROM users u
            INNER JOIN `role` r ON r.id_role = u.id_role
            WHERE u.id = :user_id'
        );
        $consulta->execute(['user_id' => $panelUser['id']]);
        $usuario = $consulta->fetch();
        $columnas = [
            'name' => 'Nombre',
            'surnames' => 'Apellidos',
            'email' => 'Correo electrónico',
            'created_at' => 'Fecha de creación',
            'account_role' => 'Rol de cuenta',
            'activo' => 'Cuenta activa',
        ];
        $datos = $usuario ? [$usuario] : [];
    } elseif ($seccion === 'empresas') {
        $titulo = $secciones[$seccion];
        if ($panelEsAdministrador && $panelEmpresa) {
            $consulta = $conn->prepare(
                'SELECT id, nombre, activa
                FROM empresas
                WHERE id = :empresa_id AND activa = TRUE'
            );
            $consulta->execute(['empresa_id' => $panelEmpresa['id']]);
        } elseif ($panelEsAdministrador) {
            $consulta = $conn->query(
                'SELECT id, nombre, activa
                FROM empresas
                WHERE activa = TRUE
                ORDER BY nombre, id'
            );
        } elseif ($panelEmpresa) {
            $consulta = $conn->prepare(
                'SELECT e.id, e.nombre, e.activa
                FROM empresas e
                INNER JOIN usuario_empresa ue ON ue.empresa_id = e.id
                WHERE ue.user_id = :user_id
                    AND e.id = :empresa_id
                    AND e.activa = TRUE'
            );
            $consulta->execute([
                'user_id' => $panelUser['id'],
                'empresa_id' => $panelEmpresa['id'],
            ]);
        } else {
            $consulta = $conn->prepare(
                'SELECT e.id, e.nombre, e.activa
                FROM empresas e
                INNER JOIN usuario_empresa ue ON ue.empresa_id = e.id
                WHERE ue.user_id = :user_id AND e.activa = TRUE
                ORDER BY e.nombre, e.id'
            );
            $consulta->execute(['user_id' => $panelUser['id']]);
        }
        $empresas = $consulta->fetchAll();
        $columnas = [
            'id' => 'ID',
            'nombre' => 'Empresa',
            'activa' => 'Empresa activa',
        ];
        $datos = $empresas;
    } elseif ($seccion === 'usuario_empresa') {
        $titulo = $secciones[$seccion];
        if ($panelEsAdministrador && $panelEmpresa) {
            $consulta = $conn->prepare(
                'SELECT ue.user_id, e.nombre AS empresa, ue.rol
                FROM usuario_empresa ue
                INNER JOIN empresas e ON e.id = ue.empresa_id
                WHERE ue.empresa_id = :empresa_id AND e.activa = TRUE
                ORDER BY ue.user_id'
            );
            $consulta->execute(['empresa_id' => $panelEmpresa['id']]);
        } elseif ($panelEsAdministrador) {
            $consulta = $conn->query(
                'SELECT ue.user_id, e.nombre AS empresa, ue.rol
                FROM usuario_empresa ue
                INNER JOIN empresas e ON e.id = ue.empresa_id
                WHERE e.activa = TRUE
                ORDER BY e.nombre, ue.user_id'
            );
        } elseif ($panelEmpresa) {
            $consulta = $conn->prepare(
                'SELECT ue.user_id, e.nombre AS empresa, ue.rol
                FROM usuario_empresa ue
                INNER JOIN empresas e ON e.id = ue.empresa_id
                WHERE ue.user_id = :user_id
                    AND ue.empresa_id = :empresa_id
                    AND e.activa = TRUE'
            );
            $consulta->execute([
                'user_id' => $panelUser['id'],
                'empresa_id' => $panelEmpresa['id'],
            ]);
        } else {
            $consulta = $conn->prepare(
                'SELECT ue.user_id, e.nombre AS empresa, ue.rol
                FROM usuario_empresa ue
                INNER JOIN empresas e ON e.id = ue.empresa_id
                WHERE ue.user_id = :user_id AND e.activa = TRUE
                ORDER BY e.nombre, e.id'
            );
            $consulta->execute(['user_id' => $panelUser['id']]);
        }
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../general/assets/css/estilos.css?v=<?= filemtime(dirname(__DIR__) . '/general/assets/css/estilos.css') ?>">
</head>

<body>
    <a class="skip" href="#contenido">Saltar al contenido</a>
    <header class="panel-cabecera">
        <div class="panel-contenedor panel-cabecera-interior">
            <a class="panel-logo" href="index.php" aria-label="Icare Coaching, dashboard">
                <span class="panel-logo-texto">icare coaching</span>
                <img class="panel-logo-icono panel-marca-icono" src="../general/assets/images/iconoicare.jpg" alt="">
            </a>
            <div class="panel-identidad">
                <span><?= htmlspecialchars($panelUser['nombre'] . ' ' . $panelUser['apellidos'], ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <form class="panel-selector-empresa" action="index.php" method="post">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="accion" value="cambiar_empresa">
                <label for="empresa_id">Empresa</label>
                <select id="empresa_id" name="empresa_id">
                    <option value="todas"<?= $panelEmpresa === null ? ' selected' : '' ?>>Todas las empresas</option>
                    <?php foreach ($panelEmpresas as $empresa): ?>
                        <option value="<?= (int) $empresa['id'] ?>"<?= $panelEmpresa && (int) $panelEmpresa['id'] === (int) $empresa['id'] ? ' selected' : '' ?>>
                            <?= htmlspecialchars($empresa['nombre'], ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
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
                    <img class="panel-marca-icono" src="../general/assets/images/iconoicare.jpg" alt="">
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
                <summary aria-label="Setting" title="Setting">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="m19.4 15 .1.1 1.4 1.1-1.4 2.4-1.7-.7a8 8 0 0 1-1.7 1l-.3 1.8h-2.8l-.3-1.8a8 8 0 0 1-1.7-1l-1.7.7-1.4-2.4 1.4-1.1a7 7 0 0 1 0-2l-1.4-1.1 1.4-2.4 1.7.7a8 8 0 0 1 1.7-1l.3-1.8h2.8l.3 1.8a8 8 0 0 1 1.7 1l1.7-.7 1.4 2.4-1.4 1.1a7 7 0 0 1 0 2Z" transform="translate(-1 -1)"></path>
                    </svg>
                    <span class="panel-menu-texto">Setting</span>
                    <span class="panel-menu-flecha" aria-hidden="true">⌄</span>
                </summary>
                <ul>
                    <?php foreach ($tablasBdd as $nombreTabla): ?>
                        <li>
                            <a class="panel-menu-enlace" href="?seccion=tabla&amp;tabla=<?= rawurlencode($nombreTabla) ?>" aria-label="<?= htmlspecialchars($nombreTabla, ENT_QUOTES, 'UTF-8') ?>" title="<?= htmlspecialchars($nombreTabla, ENT_QUOTES, 'UTF-8') ?>"<?= $seccion === 'tabla' && $tablaSeleccionada === $nombreTabla ? ' aria-current="page"' : '' ?>>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                    <ellipse cx="12" cy="5" rx="8" ry="3"></ellipse>
                                    <path d="M4 5v7c0 1.7 3.6 3 8 3s8-1.3 8-3V5M4 12v7c0 1.7 3.6 3 8 3s8-1.3 8-3v-7"></path>
                                </svg>
                                <span class="panel-menu-texto"><?= htmlspecialchars($nombreTabla, ENT_QUOTES, 'UTF-8') ?></span>
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
                
                <div class="panel-tarjetas-resumen">
                    <section class="panel-tarjeta">
                        <h2>Usuario</h2>
                        <p><?= htmlspecialchars($panelUser['nombre'] . ' ' . $panelUser['apellidos'], ENT_QUOTES, 'UTF-8') ?></p>
                        <a href="?seccion=users">Ver mi perfil</a>
                    </section>
                    <section class="panel-tarjeta">
                        <h2><?= count($panelEmpresas) === 1 ? 'Empresa' : 'Empresas' ?></h2>
                        <p><?= $panelEmpresa
                            ? htmlspecialchars($panelEmpresa['nombre'], ENT_QUOTES, 'UTF-8')
                            : (count($panelEmpresas) ? 'Todas las empresas' : 'Sin empresas asignadas') ?></p>
                        <?php if ($panelEmpresas): ?>
                            <a href="?seccion=empresas">Ver empresas</a>
                        <?php endif; ?>
                    </section>
                    <section class="panel-tarjeta">
                        <h2>Solicitudes de contacto</h2>
                        <p><?= $totalContactos ?></p>
                        <a href="?seccion=contact_requests">Ver mis solicitudes</a>
                    </section>
                </div>
                <section class="panel-bloque">
                    <h2>Acceso actual</h2>
                    <?php if ($panelEsAdministrador && $panelEmpresas): ?>
                        <p>El rol administrador permite consultar todas las empresas activas.</p>
                    <?php elseif ($panelEmpresas): ?>
                        <?php
                        $relacionesEmpresa = [];
                        foreach ($panelEmpresas as $empresa) {
                            if ($panelEmpresa === null || (int) $panelEmpresa['id'] === (int) $empresa['id']) {
                                $relacionesEmpresa[] = [
                                    'user_id' => $panelUser['id'],
                                    'empresa' => $empresa['nombre'],
                                    'rol' => $empresa['rol'],
                                ];
                            }
                        }
                        $mostrarTabla('Relación de usuario con la empresa', [
                            'user_id' => 'ID de usuario',
                            'empresa' => 'Empresa',
                            'rol' => 'Rol en la empresa',
                        ], $relacionesEmpresa);
                        ?>
                    <?php elseif ($panelEsAdministrador): ?>
                        <p>No hay empresas activas disponibles.</p>
                    <?php else: ?>
                        <p>No tienes empresas activas asignadas.</p>
                    <?php endif; ?>
                </section>
            <?php else: ?>
                
                
                <?php $mostrarTabla($titulo, $columnas, $datos); ?>
                <?php if ($seccion === 'tabla'): ?>
                    <nav class="tabla-paginacion" aria-label="Paginación de la tabla">
                        <?php if ($totalRegistros > 0): ?>
                            <?php
                            $inicioRango = ($paginaActual - 1) * $tamanoPagina + 1;
                            $finRango = min($paginaActual * $tamanoPagina, $totalRegistros);
                            ?>
                            <p>Mostrando <?= number_format($inicioRango, 0, ',', '.') ?>&ndash;<?= number_format($finRango, 0, ',', '.') ?> de <?= number_format($totalRegistros, 0, ',', '.') ?> registros</p>
                        <?php else: ?>
                            <p>No hay registros para mostrar.</p>
                        <?php endif; ?>
                        <?php if ($totalPaginas > 1): ?>
                            <div class="tabla-paginacion-controles">
                                <?php if ($paginaActual > 1): ?>
                                    <a href="?seccion=tabla&amp;tabla=<?= rawurlencode($tablaSeleccionada) ?>&amp;pagina=<?= $paginaActual - 1 ?>">‹ Anterior</a>
                                <?php endif; ?>

                                <?php
                                $numPaginasVisibles = 5;
                                $inicioPaginas = max(
                                    1,
                                    min(
                                        $paginaActual - (int) floor($numPaginasVisibles / 2),
                                        $totalPaginas - $numPaginasVisibles + 1
                                    )
                                );
                                $finPaginas = min($totalPaginas, $inicioPaginas + $numPaginasVisibles - 1);

                                if ($inicioPaginas > 1) {
                                    echo '<a href="?seccion=tabla&amp;tabla=' . rawurlencode($tablaSeleccionada) . '&amp;pagina=1">1</a>';
                                    if ($inicioPaginas > 2) {
                                        echo '<span aria-hidden="true">…</span>';
                                    }
                                }

                                for ($i = $inicioPaginas; $i <= $finPaginas; $i++): ?>
                                    <?php if ($i === $paginaActual): ?>
                                        <span class="current-page" aria-current="page"><?= $i ?></span>
                                    <?php else: ?>
                                        <a href="?seccion=tabla&amp;tabla=<?= rawurlencode($tablaSeleccionada) ?>&amp;pagina=<?= $i ?>"><?= $i ?></a>
                                    <?php endif; ?>
                                <?php endfor; ?>

                                <?php
                                if ($finPaginas < $totalPaginas) {
                                    if ($finPaginas < $totalPaginas - 1) {
                                        echo '<span aria-hidden="true">…</span>';
                                    }
                                    echo '<a href="?seccion=tabla&amp;tabla=' . rawurlencode($tablaSeleccionada) . '&amp;pagina=' . $totalPaginas . '">' . $totalPaginas . '</a>';
                                }
                                ?>

                                <?php if ($paginaActual < $totalPaginas): ?>
                                    <a href="?seccion=tabla&amp;tabla=<?= rawurlencode($tablaSeleccionada) ?>&amp;pagina=<?= $paginaActual + 1 ?>">Siguiente ›</a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </main>
    </div>
    <script>
        const panelMenuToggle = document.getElementById('panel-menu-toggle');
        const empresaSelect = document.getElementById('empresa_id');
        const panelCabecera = document.querySelector('.panel-cabecera');

        if (panelCabecera) {
            const actualizarAlturaCabecera = () => {
                document.documentElement.style.setProperty(
                    '--panel-cabecera-altura',
                    `${panelCabecera.getBoundingClientRect().height}px`
                );
            };

            actualizarAlturaCabecera();
            if ('ResizeObserver' in window) {
                new ResizeObserver(actualizarAlturaCabecera).observe(panelCabecera);
            } else {
                window.addEventListener('resize', actualizarAlturaCabecera);
            }
        }

        if (empresaSelect) {
            empresaSelect.addEventListener('change', () => empresaSelect.form.submit());
        }

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
