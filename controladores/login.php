<?php

session_start();

if (!empty($_SESSION['user_id']) && !empty($_SESSION['empresa_id'])) {
    header('Location: paneltablas/index.php', true, 303);
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$flashLogin = $_SESSION['login_flash'] ?? [];
unset($_SESSION['login_flash']);

$errores = is_array($flashLogin['errores'] ?? null) ? $flashLogin['errores'] : [];
$email = is_string($flashLogin['email'] ?? null) ? $flashLogin['email'] : '';
$empresasDisponibles = [];
$mostrarSelectorEmpresa = !empty($flashLogin['mostrar_selector_empresa']);
$mensajeAccesoDenegado = 'No se pudo iniciar sesión. Comprueba el correo electrónico y la contraseña.';
$mensajeSinEmpresa = 'La contraseña es correcta, pero esta cuenta no tiene ninguna empresa activa asignada. Contacta con el administrador de Icare.';
$loginPendiente = $_SESSION['login_pendiente'] ?? null;

if (
    !is_array($loginPendiente)
    || !isset($loginPendiente['user_id'], $loginPendiente['expira_en'])
    || !is_int($loginPendiente['user_id'])
    || !is_int($loginPendiente['expira_en'])
    || $loginPendiente['expira_en'] < time()
) {
    unset($_SESSION['login_pendiente']);
    $loginPendiente = null;
}

$cargarEmpresas = static function (PDO $conn, int $userId): array {
    $consulta = $conn->prepare(
        'SELECT e.id, e.nombre
        FROM usuario_empresa ue
        INNER JOIN users u ON u.id = ue.user_id
        INNER JOIN empresas e ON e.id = ue.empresa_id
        WHERE ue.user_id = :user_id AND u.activo = TRUE AND e.activa = TRUE
        ORDER BY e.nombre'
    );
    $consulta->execute(['user_id' => $userId]);

    return $consulta->fetchAll();
};

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    $accion = $_POST['accion'] ?? '';

    if (
        !is_string($token)
        || !is_string($accion)
        || !hash_equals($_SESSION['csrf_token'], $token)
    ) {
        $errores[] = 'El formulario ha caducado. Recarga la página e inténtalo de nuevo.';
    } else {
        try {
            require_once dirname(__DIR__) . '/bdd/conexion.php';

            if ($accion === 'iniciar_sesion') {
                unset($_SESSION['login_pendiente']);
                $loginPendiente = null;

                $emailRecibido = $_POST['email'] ?? '';
                $password = $_POST['password'] ?? '';
                $email = is_string($emailRecibido) ? trim($emailRecibido) : '';
                $password = is_string($password) ? $password : '';

                if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email, 'UTF-8') > 40 || $password === '') {
                    $errores[] = 'Introduce un correo electrónico válido y tu contraseña.';
                } else {
                    $consulta = $conn->prepare(
                        'SELECT id, password, activo
                        FROM users
                        WHERE email = :email
                        LIMIT 1'
                    );
                    $consulta->execute(['email' => $email]);
                    $usuario = $consulta->fetch();
                    $usuarioActivo = $usuario && (int) $usuario['activo'] === 1;
                    $passwordCorrecta = $usuario && password_verify($password, $usuario['password']);

                    if (
                        !$usuario
                        || !$usuarioActivo
                        || !$passwordCorrecta
                    ) {
                        $errores[] = $mensajeAccesoDenegado;
                    } else {
                        $mostrarSelectorEmpresa = true;
                        $empresasDisponibles = $cargarEmpresas($conn, (int) $usuario['id']);

                        if (!$empresasDisponibles) {
                            $errores[] = $mensajeSinEmpresa;
                        } else {
                            session_regenerate_id(true);
                            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                            $_SESSION['login_pendiente'] = [
                                'user_id' => (int) $usuario['id'],
                                'expira_en' => time() + 300,
                            ];
                            $loginPendiente = $_SESSION['login_pendiente'];
                        }
                    }
                }
            } elseif ($accion === 'seleccionar_empresa') {
                if ($loginPendiente === null) {
                    $errores[] = $mensajeAccesoDenegado;
                } else {
                    $empresaId = filter_var(
                        $_POST['empresa_id'] ?? null,
                        FILTER_VALIDATE_INT,
                        ['options' => ['min_range' => 1]]
                    );

                    if ($empresaId === false) {
                        $errores[] = 'Selecciona una empresa válida.';
                        $empresasDisponibles = $cargarEmpresas($conn, $loginPendiente['user_id']);
                    } else {
                        $consulta = $conn->prepare(
                            'SELECT u.id, u.role, e.id AS empresa_id
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
                            'user_id' => $loginPendiente['user_id'],
                            'empresa_id' => $empresaId,
                        ]);
                        $acceso = $consulta->fetch();

                        if (!$acceso) {
                            unset($_SESSION['login_pendiente']);
                            $loginPendiente = null;
                            $errores[] = $mensajeAccesoDenegado;
                        } else {
                            session_regenerate_id(true);
                            $_SESSION['user_id'] = (int) $acceso['id'];
                            $_SESSION['empresa_id'] = (int) $acceso['empresa_id'];
                            $_SESSION['user_role'] = $acceso['role'];
                            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                            unset($_SESSION['login_pendiente']);
                            header('Location: paneltablas/index.php', true, 303);
                            exit;
                        }
                    }
                }
            } else {
                $errores[] = 'La solicitud no es válida. Recarga la página e inténtalo de nuevo.';
            }
        } catch (PDOException $error) {
            error_log('Error durante el inicio de sesión: ' . $error->getMessage());
            $errores[] = 'No se pudo completar el inicio de sesión. Inténtalo más tarde.';
        }
    }
}

if ($loginPendiente !== null && !$empresasDisponibles) {
    try {
        require_once dirname(__DIR__) . '/bdd/conexion.php';
        $empresasDisponibles = $cargarEmpresas($conn, $loginPendiente['user_id']);
    } catch (PDOException $error) {
        error_log('Error al cargar las empresas disponibles para el inicio de sesión: ' . $error->getMessage());
        unset($_SESSION['login_pendiente']);
        $loginPendiente = null;
        $errores[] = 'No se pudo completar el inicio de sesión. Inténtalo más tarde.';
    }
}

if ($loginPendiente !== null && !$empresasDisponibles) {
    unset($_SESSION['login_pendiente']);
    $loginPendiente = null;
    $errores[] = $mensajeAccesoDenegado;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['login_flash'] = [
        'errores' => $errores,
        'email' => $email,
        'mostrar_selector_empresa' => $mostrarSelectorEmpresa,
    ];
    header('Location: login.php', true, 303);
    exit;
}
