Para tu aplicación, la opción más sencilla y segura es **enviar al correo del usuario un enlace temporal y de un solo uso para crear una contraseña nueva**. No se recupera ni se envía la contraseña anterior. OWASP recomienda los enlaces con un token aleatorio como la implementación más sencilla de este proceso. [cheatsheetseries.owasp](https://cheatsheetseries.owasp.org/cheatsheets/Forgot_Password_Cheat_Sheet)

Es parecido al sistema de invitaciones que vimos, pero con una diferencia: aquí la cuenta ya existe y el enlace solo permite cambiar su contraseña.

## 1. Funcionamiento para el usuario

```text
Pantalla de login
    ↓
Pulsa «He olvidado mi contraseña»
    ↓
Introduce su correo
    ↓
Recibe un enlace temporal
    ↓
Abre el enlace y escribe una contraseña nueva
    ↓
La contraseña se actualiza y el enlace queda inutilizado
    ↓
Vuelve al login y accede normalmente
```

Después del cambio, es preferible que inicie sesión mediante el login habitual, en lugar de iniciar sesión automáticamente desde el enlace de recuperación. [cheatsheetseries.owasp](https://cheatsheetseries.owasp.org/cheatsheets/Forgot_Password_Cheat_Sheet)

## 2. Qué debe hacer el sistema

### A. Recibir el correo

En el login, añade un enlace:

```html
<a href="recuperar-password.php">
    He olvidado mi contraseña
</a>
```

El formulario de recuperación pide únicamente el correo. Tanto si existe como si no, muestra la misma respuesta:

> Si existe una cuenta asociada a ese correo, recibirás un enlace para restablecer tu contraseña.

La respuesta y su tiempo de procesamiento deben ser lo más uniformes posible para no revelar qué personas tienen una cuenta. Añade límites de solicitudes por cuenta y por dirección IP para evitar abusos. [cheatsheetseries.owasp](https://cheatsheetseries.owasp.org/cheatsheets/Forgot_Password_Cheat_Sheet)

### B. Generar un enlace seguro

Si la cuenta puede recuperar su acceso, el servidor genera un **token**: una cadena aleatoria secreta que autoriza exclusivamente el cambio de contraseña. Debe ser suficientemente largo, caducar y funcionar una sola vez. [cheatsheetseries.owasp](https://cheatsheetseries.owasp.org/cheatsheets/Forgot_Password_Cheat_Sheet)

En PHP, un ejemplo de generación sería:

```php
$token = bin2hex(random_bytes(32));

// Esto se guarda en la base de datos:
$tokenHash = hash('sha256', $token);
```

`random_bytes()` es una función apropiada para generar valores aleatorios de seguridad en PHP. Guarda el hash del token, no el token original. [cheatsheetseries.owasp](https://cheatsheetseries.owasp.org/cheatsheets/Forgot_Password_Cheat_Sheet)

El correo contiene un enlace como este:

```text
https://tu-dominio.es/restablecer-password.php?token=VALOR_ALEATORIO
```

La dirección del sitio debe salir de una configuración de confianza del servidor, no de un dominio indicado por quien hace la petición. El enlace debe utilizar HTTPS. [cheatsheetseries.owasp](https://cheatsheetseries.owasp.org/cheatsheets/Forgot_Password_Cheat_Sheet)

### C. Guardar la solicitud de recuperación

Para tu proyecto, propondría una tabla separada:

```text
recuperaciones_password
├── id
├── usuario_id
├── token_hash
├── expira_en
└── usado_en
```

Como decisión de diseño, puedes establecer una caducidad de **30 minutos**. Es un ejemplo razonable para empezar, no un plazo universal obligatorio: OWASP exige una caducidad adecuada, pero no fija ese valor concreto. [cheatsheetseries.owasp](https://cheatsheetseries.owasp.org/cheatsheets/Forgot_Password_Cheat_Sheet)

No cambies todavía la contraseña ni bloquees la cuenta cuando alguien solicite el correo. La solicitud por sí sola no demuestra que la haya hecho su propietario. [cheatsheetseries.owasp](https://cheatsheetseries.owasp.org/cheatsheets/Forgot_Password_Cheat_Sheet)

### D. Comprobar el enlace y guardar la contraseña nueva

Al abrir el enlace, muestra el formulario si el token es válido:

```text
Crear contraseña nueva

Nueva contraseña:
[________________________]

Repetir contraseña:
[________________________]

[Guardar contraseña]
```

Cuando el usuario lo envíe, el servidor debe volver a verificar que el token existe, no ha caducado y no se ha utilizado. Validarlo solo al mostrar la página no basta. Después actualiza la contraseña e inutiliza el token. [cheatsheetseries.owasp](https://cheatsheetseries.owasp.org/cheatsheets/Forgot_Password_Cheat_Sheet)

La contraseña nueva se almacena mediante un algoritmo de hash para contraseñas, nunca en texto plano ni con un cifrado reversible. No utilices para las contraseñas el mismo hash rápido del ejemplo del token. [cheatsheetseries.owasp](https://cheatsheetseries.owasp.org/cheatsheets/Password_Storage_Cheat_Sheet.html)

Para evitar que dos peticiones utilicen simultáneamente el mismo enlace, implementaría la actualización de contraseña y el consumo del token dentro de una transacción con bloqueo o una operación condicional equivalente.

## 3. Qué ocurre después del cambio

El proceso debe:

- Invalidar los enlaces de recuperación pendientes de esa cuenta.
- Invalidar sus sesiones anteriores, o dar al usuario la opción de hacerlo; para tu aplicación elegiría cerrarlas automáticamente.
- Enviar un aviso de que la contraseña se ha cambiado, sin incluir la contraseña.
- Mostrar un enlace para volver al login. [cheatsheetseries.owasp](https://cheatsheetseries.owasp.org/cheatsheets/Forgot_Password_Cheat_Sheet)

Cambiar la contraseña no debe cambiar la empresa, el rol ni los permisos: en tu diseño se actualiza la credencial del usuario, no su relación con las empresas.

## 4. Detalles que no debes omitir

| Medida | Para qué sirve |
|---|---|
| Enlace aleatorio, temporal y de un solo uso | Dificulta adivinarlo y evita reutilizarlo. |
| Límites de solicitudes e intentos | Reduce el envío abusivo de correos y los intentos de adivinar tokens. |
| Mensaje público genérico | Evita revelar si un correo tiene cuenta. |
| Página de recuperación sin filtraciones del enlace | Protege el token frente a terceros. |
| Hash seguro de la contraseña | Protege las credenciales almacenadas. |

Estas medidas forman parte de las recomendaciones de OWASP. En la página de restablecimiento, configura `Referrer-Policy: no-referrer` y evita que el token se registre en logs o se envíe a herramientas de analítica. [cheatsheetseries.owasp](https://cheatsheetseries.owasp.org/cheatsheets/Forgot_Password_Cheat_Sheet)

## 5. Organización sencilla para tu web PHP

Lo organizaría así:

```text
icare/
│
├── login.php
│
├── recuperar-password.php
│   └── Formulario de correo y gestión de la solicitud
│
├── restablecer-password.php
│   └── Validación del enlace y formulario de contraseña nueva
│
└── includes/
    ├── conexion.php
    ├── correo.php
    └── autenticacion.php
```

Es una propuesta de organización, no necesitas exactamente esos nombres.

En resumen: el usuario solicita el enlace, demuestra acceso a su correo al utilizarlo y establece una contraseña nueva. El administrador no necesita intervenir ni conocerla. Es el flujo que implementaría en tu aplicación para las cuentas con acceso a su correo registrado. [cheatsheetseries.owasp](https://cheatsheetseries.owasp.org/cheatsheets/Forgot_Password_Cheat_Sheet)