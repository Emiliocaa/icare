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
    if (mb_strlen($apellidos, 'UTF-8') > 40) {
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
                'INSERT INTO users (name, surnames, email, password, activo)
                VALUES (:name, :surnames, :email, :password, FALSE)'
            );
            $consulta->execute([
                'name' => $nombre,
                'surnames' => $apellidos,
                'email' => $email,
                'password' => $passwordHash,
            ]);

            $_SESSION['registro_correcto'] = true;
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            header('Location: registro.php', true, 303);
            exit;
        } catch (PDOException $error) {
            error_log('Error durante el registro: ' . $error->getMessage());
            $codigoMysql = $error->errorInfo[1] ?? null;
            $errores[] = (int) $codigoMysql === 1062
                ? 'Ya existe una cuenta con ese correo electrónico.'
                : 'No se pudo completar el registro. Inténtalo más tarde.';
        }
    }
}
