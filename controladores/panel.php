<?php

session_start();

if (empty($_SESSION['user_id'])) {
    header('Location: ../login.php', true, 303);
    exit;
}

try {
    require_once dirname(__DIR__) . '/bdd/conexion.php';

    $panelEmpresas = [];
    $panelEsAdministrador = false;
    $usuarioConsulta = $conn->prepare(
        'SELECT
            u.id,
            u.name,
            u.surnames,
            u.email,
            u.id_role,
            r.`role` AS account_role
        FROM users u
        INNER JOIN `role` r ON r.id_role = u.id_role
        WHERE u.id = :user_id
            AND u.activo = TRUE
        LIMIT 1'
    );
    $usuarioConsulta->execute(['user_id' => $_SESSION['user_id']]);
    $acceso = $usuarioConsulta->fetch();

    if ($acceso) {
        $panelEsAdministrador = $acceso['account_role'] === 'administrador';

        if ($panelEsAdministrador) {
            $empresasConsulta = $conn->query(
                'SELECT id, nombre
                FROM empresas
                WHERE activa = TRUE
                ORDER BY nombre, id'
            );
        } else {
            $empresasConsulta = $conn->prepare(
                'SELECT e.id, e.nombre, ue.rol
                FROM usuario_empresa ue
                INNER JOIN empresas e ON e.id = ue.empresa_id
                WHERE ue.user_id = :user_id AND e.activa = TRUE
                ORDER BY e.nombre, e.id'
            );
            $empresasConsulta->execute(['user_id' => $_SESSION['user_id']]);
        }

        $panelEmpresas = $empresasConsulta->fetchAll();
    }
} catch (PDOException $error) {
    error_log('Error al validar la sesión del panel: ' . $error->getMessage());
    http_response_code(500);
    echo 'No se pudo cargar el panel. Inténtalo más tarde.';
    exit;
}

if (!$acceso) {
    $_SESSION = [];
    session_destroy();
    header('Location: ../login.php', true, 303);
    exit;
}

$panelUser = [
    'id' => (int) $acceso['id'],
    'nombre' => $acceso['name'],
    'apellidos' => $acceso['surnames'],
    'email' => $acceso['email'],
    'id_role' => (int) $acceso['id_role'],
    'role' => $acceso['account_role'],
];

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    $accion = $_POST['accion'] ?? '';
    $empresaRecibida = $_POST['empresa_id'] ?? '';

    if (
        !is_string($token)
        || !hash_equals($_SESSION['csrf_token'], $token)
    ) {
        http_response_code(403);
        exit('La solicitud ha caducado. Recarga la página e inténtalo de nuevo.');
    }

    if ($accion !== 'cambiar_empresa' || !is_string($empresaRecibida)) {
        http_response_code(400);
        exit('La solicitud no es válida.');
    }

    if ($empresaRecibida === 'todas') {
        $_SESSION['empresa_id'] = 'todas';
    } else {
        $empresaId = filter_var(
            $empresaRecibida,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );
        $empresaAsignada = false;

        foreach ($panelEmpresas as $empresa) {
            if ($empresaId !== false && (int) $empresa['id'] === $empresaId) {
                $empresaAsignada = true;
                break;
            }
        }

        if (!$empresaAsignada) {
            http_response_code(403);
            exit('La empresa seleccionada no está asignada a esta cuenta.');
        }

        $_SESSION['empresa_id'] = $empresaId;
    }

    header('Location: index.php', true, 303);
    exit;
}

$empresaSeleccionadaId = $_SESSION['empresa_id'] ?? null;
$panelEmpresa = null;

if ($empresaSeleccionadaId === null) {
    $empresaSeleccionadaId = $panelEmpresas
        ? (int) $panelEmpresas[0]['id']
        : 'todas';
    $_SESSION['empresa_id'] = $empresaSeleccionadaId;
}

if ($empresaSeleccionadaId !== 'todas') {
    foreach ($panelEmpresas as $empresa) {
        if ((int) $empresa['id'] === (int) $empresaSeleccionadaId) {
            $panelEmpresa = [
                'id' => (int) $empresa['id'],
                'nombre' => $empresa['nombre'],
            ];
            break;
        }
    }
}

if ($empresaSeleccionadaId !== 'todas' && $panelEmpresa === null) {
    $panelEmpresa = $panelEmpresas
        ? [
            'id' => (int) $panelEmpresas[0]['id'],
            'nombre' => $panelEmpresas[0]['nombre'],
        ]
        : null;
    $_SESSION['empresa_id'] = $panelEmpresa['id'] ?? 'todas';
}
