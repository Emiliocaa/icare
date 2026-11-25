<?php

session_start();

if (!empty($_SESSION['user_id'])) {
    header('Location: paneltablas/index.php', true, 303);
    exit;
}

unset($_SESSION['login_pendiente']);

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$flashLogin = $_SESSION['login_flash'] ?? [];
unset($_SESSION['login_flash']);

$errores = is_array($flashLogin['errores'] ?? null) ? $flashLogin['errores'] : [];
$email = is_string($flashLogin['email'] ?? null) ? $flashLogin['email'] : '';
$mensajeAccesoDenegado = 'No se pudo iniciar sesión. Comprueba el correo electrónico y la contraseña.';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    $accion = $_POST['accion'] ?? '';

    if (
        !is_string($token)
        || !is_string($accion)
        || !hash_equals($_SESSION['csrf_token'], $token)
    ) {
        $errores[] = 'El formulario ha caducado. Recarga la página e inténtalo de nuevo.';
    } elseif ($accion !== 'iniciar_sesion') {
        $errores[] = 'La solicitud no es válida. Recarga la página e inténtalo de nuevo.';
    } else {
        $emailRecibido = $_POST['email'] ?? '';
        $passwordIntroducida = $_POST['password'] ?? '';
        $email = is_string($emailRecibido) ? trim($emailRecibido) : '';
        $passwordIntroducida = is_string($passwordIntroducida) ? $passwordIntroducida : '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email, 'UTF-8') > 40 || $passwordIntroducida === '') {
            $errores[] = 'Introduce un correo electrónico válido y tu contraseña.';
        } else {
            try {
                require_once dirname(__DIR__) . '/bdd/conexion.php';

                $consulta = $conn->prepare(
                    'SELECT id, password, activo, id_role
                    FROM users
                    WHERE email = :email
                    LIMIT 1'
                );
                $consulta->execute(['email' => $email]);
                $usuario = $consulta->fetch();
                $passwordCorrecta = $usuario
                    && password_verify($passwordIntroducida, $usuario['password']);

                if (!$usuario || !$passwordCorrecta) {
                    $errores[] = $mensajeAccesoDenegado;
                } elseif ((int) $usuario['activo'] !== 1) {
                    $errores[] = 'Tu cuenta está pendiente de activación.';
                } else {
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = (int) $usuario['id'];
                    unset($_SESSION['empresa_id']);
                    $_SESSION['user_role_id'] = (int) $usuario['id_role'];
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                    header('Location: paneltablas/index.php', true, 303);
                    exit;
                }
            } catch (PDOException $error) {
                error_log('Error durante el inicio de sesión: ' . $error->getMessage());
                $errores[] = 'No se pudo completar el inicio de sesión. Inténtalo más tarde.';
            }
        }
    }

    $_SESSION['login_flash'] = [
        'errores' => $errores,
        'email' => $email,
    ];
    header('Location: login.php', true, 303);
    exit;
}
