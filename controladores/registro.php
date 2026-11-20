<?php

session_start();

$errores = [];
$registroCorrecto = !empty($_SESSION['registro_correcto']);
unset($_SESSION['registro_correcto']);
$nombre = '';
$apellidos = '';
$email = '';
$confirmacionNoCoincide = false;

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $campoTexto = static function (string $campo): string {
        $valor = $_POST[$campo] ?? '';
        return is_string($valor) ? trim($valor) : '';
    };

    $nombre = $campoTexto('nombre');
    $apellidos = $campoTexto('apellidos');
    $email = $campoTexto('email');
    $password = $_POST['password'] ?? '';
    $confirmacion = $_POST['confirmacion'] ?? '';
    $token = $_POST['csrf_token'] ?? '';

    if (!is_string($password)) {
        $password = '';
    }
    if (!is_string($confirmacion)) {
        $confirmacion = '';
    }
    if (!is_string($token)) {
        $token = '';
    }

    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        $errores[] = 'El formulario no es válido. Recarga la página.';
    }
    if ($nombre === '') {
        $errores[] = 'El nombre es obligatorio.';
    } elseif (mb_strlen($nombre, 'UTF-8') > 20) {
        $errores[] = 'El nombre no puede superar los 20 caracteres.';
    }
    if ($apellidos === '') {
        $errores[] = 'Los apellidos son obligatorios.';
    } elseif (mb_strlen($apellidos, 'UTF-8') > 40) {
        $errores[] = 'Los apellidos no pueden superar los 40 caracteres.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email, 'UTF-8') > 40) {
        $errores[] = 'Introduce un correo electrónico válido de hasta 40 caracteres.';
    }
    if (mb_strlen($password, 'UTF-8') < 8) {
        $errores[] = 'La contraseña debe tener al menos 8 caracteres.';
    }
    if ($password !== $confirmacion) {
        $confirmacionNoCoincide = true;
        $errores[] = 'Confirmación de contraseña incorrecta.';
    }

    if (!$errores) {
        try {
            $passwordIntroducida = $password;
            require_once dirname(__DIR__) . '/bdd/conexion.php';

            $passwordHash = password_hash($passwordIntroducida, PASSWORD_DEFAULT);
            $consulta = $conn->prepare(
                'UPDATE users
                SET password = :password
                WHERE email = :email AND activo = TRUE'
            );
            $consulta->execute([
                'password' => $passwordHash,
                'email' => $email,
            ]);

            $verificarConsulta = $conn->prepare(
                'SELECT password
                FROM users
                WHERE email = :email AND activo = TRUE
                LIMIT 1'
            );
            $verificarConsulta->execute(['email' => $email]);
            $hashGuardado = $verificarConsulta->fetchColumn();
            $passwordPersistidaCorrecta = is_string($hashGuardado)
                && password_verify($passwordIntroducida, $hashGuardado);

            if (
                $consulta->rowCount() !== 1
                || !$passwordPersistidaCorrecta
            ) {
                $errores[] = 'No se pudo completar el registro. Contacta con el administrador de Icare en ecarolas@gmail.com.';
            } else {
                $_SESSION['registro_correcto'] = true;
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                header('Location: registro.php', true, 303);
                exit;
            }
        } catch (PDOException $error) {
            error_log('Error durante el registro: ' . $error->getMessage());
            $errores[] = 'No se pudo completar el registro. Inténtalo más tarde.';
        }
    }
}
