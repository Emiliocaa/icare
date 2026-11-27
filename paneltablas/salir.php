<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método no permitido.');
}

$token = $_POST['csrf_token'] ?? '';
if (
    !is_string($token)
    || empty($_SESSION['csrf_token'])
    || !hash_equals($_SESSION['csrf_token'], $token)
) {
    http_response_code(403);
    exit('La solicitud ha caducado. Recarga la página e inténtalo de nuevo.');
}

$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $parametrosCookie = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $parametrosCookie['path'],
        $parametrosCookie['domain'],
        $parametrosCookie['secure'],
        $parametrosCookie['httponly']
    );
}

session_destroy();
header('Location: ../general/login.php', true, 303);
exit;
