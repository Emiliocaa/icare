# Icare Coaching

Sitio web de Icare Coaching, S. L., centrado en coaching, liderazgo, desarrollo de equipos, consultoría de datos y transformación tecnológica.

## Objetivo del sitio

Presentar los servicios y la trayectoria de Icare Coaching, facilitar el contacto y publicar artículos sobre coaching, liderazgo, estrategia, mejora continua e inteligencia artificial.

La página de contacto abre Gmail con un borrador para que el visitante lo revise y lo envíe desde allí. Es necesario iniciar sesión en Google; el sitio no envía los datos a un servidor.

## Estado actual

- La página principal y el área de Conocimiento están implementadas.
- Los artículos se leen desde `conocimiento/data/articulos.json`.
- Las páginas `login.php`, `registro.php` y `conocimiento/categoria.php` se mantienen como espacios reservados para trabajo futuro.
- `bdd/bdd.sql` y `bdd/conexion.php` son la base de una futura integración con base de datos; actualmente no están conectados a los formularios de acceso.
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

La página principal y Conocimiento funcionan con archivos PHP y JSON; no necesitan la base de datos para mostrar el contenido actual.

### Base de datos

La base de datos es opcional para el sitio actual. Si se retoma esa funcionalidad:

1. Inicia MySQL/MariaDB desde XAMPP.
2. Importa `bdd/bdd.sql`.
3. Revisa la configuración local de `bdd/conexion.php`.

No despliegues credenciales locales ni la configuración de desarrollo en un servidor público. La autenticación y las páginas de usuario aún no están implementadas.

`.gitignore` contiene reglas para `bdd/conexion.php` y `bdd/copia.mkd`.

## Estructura del proyecto

```text
icare/
├── .gitignore
├── README.md
├── index.php
├── header.php
├── login.php                         # Reservado para desarrollo futuro
├── registro.php                      # Reservado para desarrollo futuro
├── adocumentos/
│   └── texto.md                      # Contenido y textos de referencia
├── assets/
│   └── css/
│       └── estilos.css               # Estilos compartidos
├── bdd/
│   ├── bdd.sql                       # Esquema inicial de la base de datos
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

Se comprobó por teclado el menú móvil: apertura, recorrido circular con Tab y Mayús+Tab, cierre con Escape y restauración del foco. Antes de publicar, completar las pruebas de teclado del carrusel y de los errores del formulario, y probar con lector de pantalla, zoom/reflujo y dispositivos táctiles reales. Las páginas reservadas de inicio de sesión y registro no están implementadas; deben diseñarse y probarse accesiblemente antes de habilitarlas.

## Validación básica

No hay un runner de pruebas configurado. Tras modificar PHP, se puede comprobar su sintaxis desde PowerShell con:

```powershell
C:\xampp\php\php.exe -l index.php
C:\xampp\php\php.exe -l conocimiento\index.php
C:\xampp\php\php.exe -l conocimiento\articulo.php
```

También conviene abrir las páginas principal y de Conocimiento en el navegador y comprobar la navegación móvil después de cambiar cabeceras, rutas o estilos.

## Mantenimiento de este README

Actualiza este documento cuando cambien el stack, los requisitos, los pasos para ejecutar el sitio, el esquema de los artículos o la estructura principal de carpetas. Mantén el estado de las funciones y las páginas reservadas acorde con lo que realmente esté implementado.

## ERRORES VS FUNCIONALIDADES PENDIENTES POR CORREGIR

1.SUBMIT FUNCIONA PERO HAY QUE VERIFICAR EL CODIGO YA  QUE REDIRIGE A GMAIL???? VER SI ES ADECUADO
2.ENLACE EN FORM , MISMA PROBLEMATICA QUE PUNTO 1
3.EN SQL VER EL CAMPO CONTENIDO COMO SE ESTRUCTURA ANTES DE CARGAR EN MYSQL
4.PENDIENTE DE DAR CONTENIDO A LOGIN
5.PENDIENTE DE DAR CONTENIDO A REGISTRARSE
6.EL JSON DE ARTICULOS , PODRIA SERVIR COMO FICHERO ENLACE CON LA BASE DE DATOS,...TIPO API????
7.MONTAR SISTEMA DE SEGURIDAD HAKERS
8.AÑADIR COOKIS , REQUERIMIENTO LEGAL, VER DE IMPLEMENTAR LOCALHOST SI FUESE NECESARIO, O AUNQUE SIRVA PARA PROBAR

