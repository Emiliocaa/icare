<?php
require_once __DIR__ . '/controladores/registro.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Crea tu cuenta en Icare Coaching.">
    <title>Crear cuenta | Icare Coaching</title>
    <link rel="stylesheet" href="assets/css/estilos.css?v=<?= filemtime(__DIR__ . '/assets/css/estilos.css') ?>">
</head>

<body>
    <a class="skip" href="#contenido">Saltar al contenido</a>
    <?php require __DIR__ . '/header.php'; ?>

    <main id="contenido">
        <section class="formulario-tarjeta tarjeta-registro" aria-labelledby="titulo-registro">
            <?php if ($registroCorrecto): ?>
                <p class="note" role="status">Tu contraseña se ha configurado correctamente. Ya puedes iniciar sesión.</p>
            <?php endif; ?>

            <h1 id="titulo-registro">Crear cuenta</h1>
            <p>Proceder a registrar tras recibir correo de activación, en respuesta a su solicitud de información.</p>

            <?php if ($errores): ?>
                <div role="alert" aria-labelledby="errores-registro">
                    <p id="errores-registro">Revisa los siguientes errores:</p>
                    <ul>
                        <?php foreach ($errores as $error): ?>
                            <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="registro.php" method="post">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

                <div class="campo">
                    <label for="nombre">Nombre *</label>
                    <input id="nombre" name="nombre" autocomplete="given-name" maxlength="20" required value="<?= htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="campo">
                    <label for="apellidos">Apellidos *</label>
                    <input id="apellidos" name="apellidos" autocomplete="family-name" maxlength="40" required value="<?= htmlspecialchars($apellidos, ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="campo campo-completo">
                    <label for="email">Correo electrónico *</label>
                    <input id="email" name="email" type="email" autocomplete="email" maxlength="40" required value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="campo">
                    <label for="password">Contraseña *</label>
                    <div class="campo-password">
                        <input id="password" name="password" type="password" autocomplete="new-password" minlength="8" required>
                        <button class="boton-visibilidad" type="button" aria-label="Mostrar contraseña" aria-controls="password" data-visible="false" hidden>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"></path>
                                <circle class="icono-ojo-centro" cx="12" cy="12" r="3"></circle>
                                <path class="icono-ojo-cerrado" d="m3 3 18 18"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="campo<?= $confirmacionNoCoincide ? ' campo-error' : '' ?>">
                    <label for="confirmacion">Confirma la contraseña *</label>
                    <div class="campo-password">
                        <input id="confirmacion" name="confirmacion" type="password" autocomplete="new-password" minlength="8" required<?= $confirmacionNoCoincide ? ' aria-invalid="true" aria-describedby="confirmacion-error"' : '' ?>>
                        <button class="boton-visibilidad" type="button" aria-label="Mostrar confirmación de contraseña" aria-controls="confirmacion" data-visible="false" hidden>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"></path>
                                <circle class="icono-ojo-centro" cx="12" cy="12" r="3"></circle>
                                <path class="icono-ojo-cerrado" d="m3 3 18 18"></path>
                            </svg>
                        </button>
                    </div>
                    <p class="error-campo" id="confirmacion-error"<?= $confirmacionNoCoincide ? '' : ' hidden' ?>>Confirmación de contraseña incorrecta.</p>
                </div>

                <button class="boton boton-principal" type="submit">Registrarse</button>
            </form>

            <p class="formulario-enlace">¿Ya tienes una cuenta? <a href="login.php">Inicia sesión</a>.</p>
        </section>
    </main>
    <script>
        document.querySelectorAll('.boton-visibilidad').forEach(boton => {
            const campo = document.getElementById(boton.getAttribute('aria-controls'));

            boton.addEventListener('click', () => {
                const mostrar = campo.type === 'password';
                campo.type = mostrar ? 'text' : 'password';
                boton.dataset.visible = String(mostrar);
                boton.setAttribute(
                    'aria-label',
                    `${mostrar ? 'Ocultar' : 'Mostrar'} ${campo.name === 'password' ? 'contraseña' : 'confirmación de contraseña'}`
                );
            });

            boton.hidden = false;
        });

        const registroForm = document.querySelector('.tarjeta-registro form');
        const password = document.getElementById('password');
        const confirmacion = document.getElementById('confirmacion');
        const errorConfirmacion = document.getElementById('confirmacion-error');
        let registroIntentado = confirmacion.getAttribute('aria-invalid') === 'true';
        const mensajeConfirmacion = 'Confirmación de contraseña incorrecta.';

        function actualizarErrorConfirmacion() {
            const mostrarError = registroIntentado && password.value !== confirmacion.value;
            confirmacion.setCustomValidity(mostrarError ? mensajeConfirmacion : '');
            errorConfirmacion.hidden = !mostrarError;

            if (mostrarError) {
                confirmacion.setAttribute('aria-invalid', 'true');
                confirmacion.setAttribute('aria-describedby', 'confirmacion-error');
            } else {
                confirmacion.removeAttribute('aria-invalid');
                confirmacion.removeAttribute('aria-describedby');
            }
        }

        password.addEventListener('input', actualizarErrorConfirmacion);
        confirmacion.addEventListener('input', actualizarErrorConfirmacion);
        registroForm.addEventListener('invalid', () => {
            if (password.value && confirmacion.value && password.value !== confirmacion.value) {
                registroIntentado = true;
                actualizarErrorConfirmacion();
            }
        }, true);
        registroForm.addEventListener('submit', event => {
            registroIntentado = true;
            actualizarErrorConfirmacion();

            if (password.value !== confirmacion.value) {
                event.preventDefault();
                confirmacion.focus();
                confirmacion.reportValidity();
            }
        });
    </script>
</body>

</html>
