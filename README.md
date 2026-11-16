# Icare Coaching

Sitio web de Icare Coaching, S. L., centrado en coaching, liderazgo, desarrollo de equipos, consultoría de datos y transformación tecnológica.

## Objetivo del sitio

Presentar los servicios y la trayectoria de Icare Coaching, facilitar el contacto y publicar artículos sobre coaching, liderazgo, estrategia, mejora continua e inteligencia artificial.

La página de contacto prepara un correo mediante `mailto:` para que el visitante lo revise y lo envíe desde su propia aplicación. No envía los datos a un servidor.

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
