<?php

session_start();

if (empty($_SESSION['user_id']) || empty($_SESSION['empresa_id'])) {
    header('Location: ../login.php', true, 303);
    exit;
}

try {
    require_once dirname(__DIR__) . '/bdd/conexion.php';

    $consulta = $conn->prepare(
        'SELECT
            u.id,
            u.name,
            u.surnames,
            u.email,
            u.role,
            e.id AS empresa_id,
            e.nombre AS empresa_nombre,
            ue.rol AS empresa_rol
        FROM users u
        INNER JOIN usuario_empresa ue ON ue.user_id = u.id
        INNER JOIN empresas e ON e.id = ue.empresa_id
        WHERE u.id = :user_id
            AND u.activo = TRUE
            AND e.id = :empresa_id
            AND e.activa = TRUE
        LIMIT 1'
    );
    $consulta->execute([
        'user_id' => $_SESSION['user_id'],
        'empresa_id' => $_SESSION['empresa_id'],
    ]);
    $acceso = $consulta->fetch();
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
    'rol' => $acceso['role'],
];
$panelEmpresa = [
    'id' => (int) $acceso['empresa_id'],
    'nombre' => $acceso['empresa_nombre'],
    'rol' => $acceso['empresa_rol'],
];

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
