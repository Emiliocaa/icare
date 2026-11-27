<?php

session_start();

header('Content-Type: application/json; charset=utf-8');

$responder = static function (int $estado, array $respuesta): void {
    http_response_code($estado);
    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
    exit;
};

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $responder(405, [
        'success' => false,
        'message' => 'Método no permitido.'
    ]);
}

$token = $_POST['csrf_token'] ?? '';
if (!is_string($token) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
    $responder(403, [
        'success' => false,
        'message' => 'El formulario ha caducado. Recarga la página e inténtalo de nuevo.'
    ]);
}

$camposTexto = ['nombre', 'email', 'perfil', 'empresa', 'cargo', 'servicio', 'telefono', 'mensaje'];
$datos = [];
foreach ($camposTexto as $campo) {
    $valor = $_POST[$campo] ?? '';
    if (!is_string($valor)) {
        $responder(400, [
            'success' => false,
            'message' => 'Revisa los datos introducidos en el formulario.'
        ]);
    }
    $datos[$campo] = trim($valor);
}

$longitudesMaximas = [
    'nombre' => 150,
    'email' => 200,
    'empresa' => 200,
    'cargo' => 150,
    'telefono' => 40,
    'mensaje' => 4000,
];

foreach ($longitudesMaximas as $campo => $maximo) {
    if (mb_strlen($datos[$campo], 'UTF-8') > $maximo) {
        $responder(400, [
            'success' => false,
            'message' => 'Uno o más campos superan la longitud permitida.'
        ]);
    }
}

if (
    $datos['nombre'] === ''
    || !filter_var($datos['email'], FILTER_VALIDATE_EMAIL)
    || $datos['perfil'] === ''
    || $datos['servicio'] === ''
    || $datos['mensaje'] === ''
    || !in_array($datos['perfil'], ['Empresa', 'Particular'], true)
    || !in_array($datos['servicio'], ['Coaching', 'Formación', 'Datos', 'IA'], true)
) {
    $responder(400, [
        'success' => false,
        'message' => 'Revisa los campos obligatorios del formulario.'
    ]);
}

try {
    require_once dirname(__DIR__) . '/general/bdd/conexion.php';

    $usersId = $_SESSION['user_id'] ?? null;
    if (is_string($usersId) && ctype_digit($usersId)) {
        $usersId = (int) $usersId;
    }
    if (!is_int($usersId) || $usersId <= 0) {
        $usersId = null;
    }

    $consulta = $conn->prepare(
        'INSERT INTO contact_requests
            (nombre, email, perfil, empresa, cargo, servicio, telefono, mensaje, users_id)
        VALUES
            (:nombre, :email, :perfil, :empresa, :cargo, :servicio, :telefono, :mensaje, :users_id)'
    );
    $consulta->execute([
        'nombre' => $datos['nombre'],
        'email' => $datos['email'],
        'perfil' => $datos['perfil'],
        'empresa' => $datos['empresa'] !== '' ? $datos['empresa'] : null,
        'cargo' => $datos['cargo'] !== '' ? $datos['cargo'] : null,
        'servicio' => $datos['servicio'],
        'telefono' => $datos['telefono'] !== '' ? $datos['telefono'] : null,
        'mensaje' => $datos['mensaje'],
        'users_id' => $usersId,
    ]);
} catch (PDOException $error) {
    error_log('Error al guardar el formulario de contacto: ' . $error->getMessage());
    $responder(500, [
        'success' => false,
        'message' => 'No se pudieron guardar los datos del contacto. Inténtalo de nuevo más tarde.'
    ]);
}

$responder(200, ['success' => true]);
