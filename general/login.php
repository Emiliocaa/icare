<?php
require_once __DIR__ . '/../controladores/login.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Inicia sesión en el área privada de Icare Coaching.">
    <title>Iniciar sesión | Icare Coaching</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,400,0,0&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/estilos.css?v=<?= filemtime(__DIR__ . '/assets/css/estilos.css') ?>">
</head>

<body>
    <a class="skip" href="#contenido">Saltar al contenido</a>
    <?php require __DIR__ . '/header.php'; ?>

    <main id="contenido">
        <section class="formulario-tarjeta tarjeta-login" aria-labelledby="titulo-login">
            <h1 id="titulo-login">Iniciar sesión</h1>
            <p>Accede con tu cuenta activa. El panel comienza mostrando todas las empresas a las que tienes acceso.</p>

            <?php if ($errores): ?>
                <div role="alert" aria-labelledby="errores-login">
                    <p id="errores-login">No se pudo completar el acceso:</p>
                    <ul>
                        <?php foreach ($errores as $error): ?>
                            <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="login.php" method="post">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="accion" value="iniciar_sesion">

                <div class="campo campo-completo">
                    <label for="email">Correo electrónico *</label>
                    <input id="email" name="email" type="email" autocomplete="username" maxlength="40" required value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="campo campo-completo">
                    <label for="password">Contraseña *</label>
                    <div class="campo-password">
                        <input id="password" name="password" type="password" autocomplete="current-password" required>
                        <button class="boton-visibilidad" type="button" aria-label="Mostrar contraseña" aria-controls="password" data-visible="false" hidden>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"></path>
                                <circle class="icono-ojo-centro" cx="12" cy="12" r="3"></circle>
                                <path class="icono-ojo-cerrado" d="m3 3 18 18"></path>
                            </svg>
                        </button>
                    </div>
                    <small>Por seguridad, tendrás que volver a escribirla si el acceso falla.</small>
                </div>

                <button class="boton boton-principal" type="submit">Iniciar sesión</button>
            </form>

            <p class="formulario-enlace">¿Todavía no tienes una cuenta? <a href="registro.php">Regístrate</a>.</p>
        </section>
    </main>
    <script>
        const password = document.getElementById('password');
        const botonVisibilidad = document.querySelector('.tarjeta-login .boton-visibilidad');

        if (password && botonVisibilidad) {
            botonVisibilidad.addEventListener('click', () => {
                const mostrar = password.type === 'password';
                password.type = mostrar ? 'text' : 'password';
                botonVisibilidad.dataset.visible = String(mostrar);
                botonVisibilidad.setAttribute(
                    'aria-label',
                    `${mostrar ? 'Ocultar' : 'Mostrar'} contraseña`
                );
            });

            botonVisibilidad.hidden = false;
        }
    </script>
</body>
</html>
