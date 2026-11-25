# Icare Coaching

Sitio web de Icare Coaching, S. L., centrado en coaching, liderazgo, desarrollo de equipos, consultoría de datos y transformación tecnológica.

## Objetivo del sitio

Presentar los servicios y la trayectoria de Icare Coaching, facilitar el contacto y publicar artículos sobre coaching, liderazgo, estrategia, mejora continua e inteligencia artificial.

El formulario de contacto guarda sus datos en MySQL y abre Gmail con un borrador para que el visitante lo revise y lo envíe desde allí. El sitio no envía el correo automáticamente.

## Estado actual

- La página principal y el área de Conocimiento están implementadas.
- `registro.php` crea una cuenta con la contraseña hasheada; queda inactiva hasta que la active un administrador.
- `login.php` autentica cuentas activas y abre el dashboard aunque no tengan empresas asignadas. En el selector, los usuarios solo ven sus empresas activas asignadas; los administradores pueden ver todas las empresas activas.
- El menú Setting enumera las tablas de la base de datos. Los administradores pueden consultar sus registros; los usuarios ven solo las filas autorizadas para su cuenta. Las columnas `password` y `token_hash` se ocultan y las tablas se muestran con paginación.
- Los artículos se leen desde `conocimiento/data/articulos.json`.
- `conocimiento/categoria.php` se mantiene como espacio reservado para trabajo futuro.
- `bdd/bdd.sql` define el esquema inicial; `bdd/conexion.php` configura la conexión local ignorada por Git.
- No hay una integración de IA ejecutándose en el sitio. Copilot se utiliza como apoyo de desarrollo, no como dependencia de la aplicación.
- El repositorio no incluye todavía una suite de pruebas automatizadas ni un gestor de dependencias.

## Tecnologías y herramientas

### Aplicación web

- **Frontend:** HTML5, CSS3 y JavaScript nativo, sin framework frontend.
- **Backend:** PHP, con plantillas y lógica del servidor.
- **Contenido editorial:** JSON.
- **Base de datos prevista:** MySQL o MariaDB, mediante PDO.
- **Carruseles:** enlaces y CSS; `conocimiento/estados-carrusel.php` genera estilos dinámicos a partir de los artículos.

### Entorno de desarrollo

- **Sistema habitual:** Windows.
- **Servidor local:** XAMPP (Apache y PHP); MySQL/MariaDB se necesita al trabajar con la base de datos.
- **Editor:** Visual Studio Code.
- **Control de versiones:** Git.
- **Asistente de IA:** GitHub Copilot en VS Code como apoyo durante el desarrollo. No se requiere para ejecutar el sitio.

El código usa `JSON_THROW_ON_ERROR`, disponible desde PHP 7.3. Se recomienda utilizar una versión de PHP 8.x mantenida que esté disponible para la instalación de XAMPP.

## Ejecución local

1. Coloca el proyecto en `C:\xampp\htdocs\proyectos\icare` (o ajusta la ruta según tu instalación).
2. Inicia Apache desde el panel de XAMPP.
3. Abre:
   - Página principal: <http://localhost/proyectos/icare/>
   - Conocimiento: <http://localhost/proyectos/icare/conocimiento/>

La página principal y Conocimiento funcionan con archivos PHP y JSON; no necesitan la base de datos para mostrar el contenido actual. El registro, el inicio de sesión y el panel requieren MySQL/MariaDB activo y las tablas definidas en `bdd/bdd.sql`.

### Base de datos

La base de datos es opcional para el sitio actual. Si se retoma esa funcionalidad:

1. Inicia MySQL/MariaDB desde XAMPP.
2. Importa `bdd/bdd.sql`.
3. Revisa la configuración local de `bdd/conexion.php`.
4. Si la tabla `users` ya existía, comprueba que no haya correos duplicados y ejecuta una sola vez `bdd/migrar-email-unico.sql` para añadir la restricción única.
5. Si `users` ya tenía la columna `role`, haz una copia de seguridad y ejecuta una sola vez `bdd/migrar-roles.sql` para crear el catálogo y conservar los roles actuales en `id_role`.

No despliegues credenciales locales ni la configuración de desarrollo en un servidor público. El acceso al panel requiere una cuenta activa en `users`. Los usuarios solo acceden a empresas vinculadas en `usuario_empresa`; los administradores pueden acceder a todas las empresas activas. En Setting todos pueden ver los nombres de las tablas, pero las filas se limitan según el rol y las relaciones de la cuenta. Las tablas sin una regla de acceso para usuarios no administradores no muestran filas. En el visor se omiten siempre las columnas `password` y `token_hash`.

`.gitignore` contiene reglas para `bdd/conexion.php` y `bdd/copia.mkd`.

## Estructura del proyecto

```text
icare/
├── .gitignore
├── README.md
├── index.php
├── header.php
├── login.php                         # Inicio de sesión y acceso directo al dashboard
├── registro.php                      # Registro de cuentas pendientes de activación
├── paneltablas/
│   ├── index.php                     # Dashboard y datos autorizados
│   └── salir.php                     # Cierre de sesión
├── adocumentos/
│   └── texto.md                      # Contenido y textos de referencia
├── assets/
│   └── css/
│       └── estilos.css               # Estilos compartidos
├── bdd/
│   ├── bdd.sql                       # Esquema inicial de la base de datos
│   ├── migrar-email-unico.sql        # Migración para bases ya existentes
│   ├── conexion.php                  # Conexión local mediante PDO
│   └── copia.mkd
├── js/
│   └── menu.js                       # Menú de navegación móvil
└── conocimiento/
    ├── articulo.php                 # Vista de un artículo
    ├── categoria.php                # Reservado para desarrollo futuro
    ├── cargador-articulos.php       # Carga y ordenación de artículos JSON
    ├── configuracion-articulos.php  # Temáticas y tamaño de página
    ├── estados-carrusel.php         # CSS dinámico de los carruseles
    ├── header.php                   # Configura rutas y reutiliza la cabecera común
    ├── index.php                    # Listado de artículos
    ├── assets/
    │   ├── css/
    │   │   ├── articulo.css
    │   │   └── conocimiento.css
    │   └── img/
    │       └── articulos/
    │           ├── estrategia.jpg
    │           ├── ia-equipos.jpg
    │           └── liderezgo-consciente.jpg
    └── data/
        └── articulos.json           # Datos y contenido de los artículos
```

## Edición de artículos

Añade o modifica artículos en `conocimiento/data/articulos.json`. Cada artículo puede incluir:

- `id`: identificador único usado en la URL.
- `tematica`: una de las claves de `tematicas` definidas en `conocimiento/configuracion-articulos.php` (`coaching`, `liderazgo`, `tecnologia`, `estrategia` o `mejora`).
- `titulo`, `resumen`, `categoria`, `fecha`, `tiempo_lectura`, `imagen` y `alt`.
- `contenido`: bloques con tipo `parrafo`, `titulo`, `cita`, `lista` o `imagen`.

Las imágenes deben guardarse en `conocimiento/assets/img/articulos/` y sus rutas deben ser relativas a `conocimiento/`, por ejemplo `assets/img/articulos/estrategia.jpg`.

El número de artículos por página se configura en `conocimiento/configuracion-articulos.php`. Las temáticas y ese valor son compartidos por el índice y los estilos dinámicos del carrusel.

## Construcción SEO

El SEO se trabaja desde la estructura y el contenido de cada página, sin repetir palabras clave artificialmente:

- Cada página pública debe tener un título y una descripción propios; los artículos toman ambos datos de `titulo` y `resumen` en `conocimiento/data/articulos.json`.
- Se utiliza `lang="es"`, un encabezado principal por página y una jerarquía ordenada de encabezados.
- Los enlaces a artículos son HTML navegable, sus identificadores se mantienen estables y las imágenes editoriales deben llevar textos `alt` descriptivos.
- La fecha de cada artículo se marca con `<time datetime="YYYY-MM-DD">`. Una URL de artículo inexistente devuelve HTTP 404 y `noindex`.
- El índice de Conocimiento debe ofrecer una introducción visible y descriptiva, además de enlazar a los artículos por temática.

Antes de publicar, hay que configurar el dominio HTTPS definitivo. Con ese dato se podrán añadir etiquetas `canonical` y metadatos de redes sociales con URLs absolutas, y generar `sitemap.xml` y `robots.txt` coherentes con las páginas públicas. Después conviene verificar las URLs y el sitemap en Google Search Console y Bing Webmaster Tools. No se deben generar URLs canónicas con `localhost` ni indexar las páginas de desarrollo.

Al crear artículos nuevos, redacta títulos y resúmenes únicos y claros, conserva el `id` textual usado en la URL, usa fechas ISO (`YYYY-MM-DD`) y describe la función de cada imagen en su texto `alt`.

## Accesibilidad

La implementación se revisa con el objetivo de acercarse a WCAG 2.2 nivel AA; estas comprobaciones no constituyen una certificación de conformidad.

- Las páginas públicas declaran el idioma, usan regiones semánticas y una jerarquía de encabezados; las páginas principales incluyen un enlace para saltar al contenido.
- Los campos del formulario tienen etiquetas asociadas, validación nativa, una explicación de los campos obligatorios y una región de estado.
- El menú móvil y los controles del carrusel tienen nombres accesibles; los indicadores del carrusel anuncian la página actual y tienen un área táctil de al menos 24 × 24 píxeles.
- Los elementos interactivos muestran foco de teclado. Los enlaces que abren una pestaña nueva lo indican en su nombre accesible.
- La paleta de enlaces, metadatos y foco se ha ajustado para mejorar el contraste. Las animaciones respetan `prefers-reduced-motion`.

La revisión actual incluyó comprobaciones DOM en la portada, el índice de Conocimiento y un artículo: un `h1` por página, imágenes con atributo `alt`, controles etiquetados, botones con nombre y ausencia de IDs duplicados. También se midieron combinaciones de color usadas en texto y foco. No hay un escáner automatizado como axe-core integrado en el proyecto.

Se comprobó por teclado el menú móvil: apertura, recorrido circular con Tab y Mayús+Tab, cierre con Escape y restauración del foco. Antes de publicar, completar las pruebas de teclado del carrusel, del formulario de registro y de las nuevas pantallas de inicio de sesión y panel; probar también con lector de pantalla, zoom/reflujo y dispositivos táctiles reales.

## Validación básica

No hay un runner de pruebas configurado. Tras modificar PHP, se puede comprobar su sintaxis desde PowerShell con:

```powershell
C:\xampp\php\php.exe -l index.php
C:\xampp\php\php.exe -l conocimiento\index.php
C:\xampp\php\php.exe -l conocimiento\articulo.php
```

También conviene abrir las páginas principal y de Conocimiento en el navegador y comprobar la navegación móvil después de cambiar cabeceras, rutas o estilos.

### Comprobación funcional importante

**Las pruebas de registro e inicio de sesión deben recorrer el flujo real de la aplicación servido por Apache, no sustituirse por cambios directos en la base de datos, generación manual de hashes ni pruebas aisladas de `password_hash`/`password_verify`.** Esas comprobaciones indirectas no detectan errores de integración, como variables del formulario sobrescritas al incluir la configuración de conexión.

Para validar el registro y el inicio de sesión:

1. Abre `http://localhost/proyectos/icare/registro.php` y registra un correo que no exista todavía.
2. Confirma el mensaje de éxito. La cuenta se crea inactiva y no puede iniciar sesión hasta que un administrador la active.
3. La activación todavía no tiene interfaz en la aplicación y se realiza manualmente por administración.
4. Cuando la cuenta esté activa, inicia sesión en `http://localhost/proyectos/icare/login.php` con el mismo correo y contraseña.
5. Confirma que el acceso abre directamente el dashboard. Puede entrar aunque todavía no tenga empresas asignadas; el selector Empresa aparece en la cabecera, empieza por la primera empresa activa y aplica el cambio al seleccionarlo.

La verificación directa del hash en la base de datos puede complementar la prueba, pero no reemplaza el recorrido completo por las páginas reales. No se deben imprimir ni registrar contraseñas o hashes durante la depuración.

## Mantenimiento de este README

Actualiza este documento cuando cambien el stack, los requisitos, los pasos para ejecutar el sitio, el esquema de los artículos o la estructura principal de carpetas. Mantén el estado de las funciones y las páginas reservadas acorde con lo que realmente esté implementado.

## ERRORES VS FUNCIONALIDADES PENDIENTES POR CORREGIR

7.MONTAR SISTEMA DE SEGURIDAD HAKERS
8.AÑADIR COOKIS , REQUERIMIENTO LEGAL, VER DE IMPLEMENTAR LOCALHOST SI FUESE NECESARIO, O AUNQUE SIRVA PARA PROBAR
11.CREAR UN GMAU , FICHADAS PRESENCIA RRHH, TAREAS , PROYECTOS, PANTALLA INICIAL GENERAL(CALENDARIO, LISTA WASAP O MENSAJERIA INTERNA,..)
13.EDITAR DESDE EL DASHBOARD LOS REGISTROS.
14.BOTON RESTAURAR CONTRASEÑA Y ENVIr EMAIL. EN EL LOGIN.PROBLEMA DE GESTION DE CORREO, ADJUNTO MD. LOGINOLVIDADO PARA RESTAURAR CONTRASEÑA
