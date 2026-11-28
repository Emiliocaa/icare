
<?php

require_once __DIR__ . '/cargador-articulos.php';

$configuracionArticulos = require __DIR__ . '/configuracion-articulos.php';

// ==========================================
// CONFIGURACIÓN
// ==========================================

$titulo = 'Artículos sobre liderazgo, coaching e innovación | Icare Coaching';
$tematicas = $configuracionArticulos['tematicas'];
$articulosPorPagina = $configuracionArticulos['articulosPorPagina'];
$pasosCarrusel = [$articulosPorPagina, 3, 2, 1];
$articulos = ordenarArticulosPorFecha(cargarArticulos());

// ==========================================
// 1. AGRUPAR LOS ARTÍCULOS POR TEMÁTICA
// ==========================================

$articulosPorTematica = [];

foreach ($tematicas as $clave => $tematica) {

    $articulosPorTematica[$clave] = [];
}

foreach ($articulos as $articulo) {

    $clave = $articulo['tematica'] ?? '';
    //Comprueba si hay un elemento con esa clave y si su valor es distinto de null.
    if (isset($articulosPorTematica[$clave])) {

        $articulosPorTematica[$clave][] = $articulo;
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta
        name="description"
        content="Artículos sobre coaching, liderazgo, estrategia, tecnología e inteligencia artificial para mejorar equipos y organizaciones."
    >
    <title><?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,400,0,0&display=swap" rel="stylesheet">

    <!-- CSS GENERAL -->

    <link rel="stylesheet"
      href="../general/assets/css/estilos.css?v=<?= filemtime(dirname(__DIR__) . '/general/assets/css/estilos.css') ?>">

    <!-- CSS ESPECÍFICO DE CONOCIMIENTO -->

    <link
        rel="stylesheet"
        href="assets/css/conocimiento.css?v=<?= filemtime(__DIR__ . '/assets/css/conocimiento.css') ?>"
    >

    <!-- ESTADOS DINÁMICOS DE FLECHAS E INDICADORES -->

    <link rel="stylesheet" href="estados-carrusel.php">

</head>

<body>

    <a class="skip" href="#contenido">Saltar al contenido</a>

    <!-- ======================================
         CABECERA Y NAVEGACIÓN
    ======================================= -->

    <?php require __DIR__ . '/header.php'; ?>

    <main id="contenido">
        <section class="hero">
            <div class="container">

                <!-- INTRODUCCIÓN -->
                <div class="introduccion-articulos">

                    <p class="eyebrow">Conocimiento</p>
                    <h1>Ideas que impulsan el cambio.</h1>
                    <p class="lead">Artículos sobre coaching, liderazgo, estrategia, mejora continua, tecnología e inteligencia artificial para impulsar equipos y organizaciones.</p>
                </div>
            </div>
        </section>

        <!-- ======================================
             CONTENIDO EDITORIAL
        ======================================= -->

        <section class="hero seccion-articulos">
            <div class="container contenedor-articulos">

                <!-- ======================================
                     GENERAR LOS CINCO CARRUSELES
                ======================================= -->

                <?php foreach ($tematicas as $clave => $tematica): ?>

                    <?php
                    $listaArticulos = $articulosPorTematica[$clave];
                    ?>

                    <?php if (!empty($listaArticulos)): ?>
                        <section
                            class="seccion-carrusel"
                            aria-labelledby="titulo-<?= htmlspecialchars($clave, ENT_QUOTES, 'UTF-8') ?>"
                        >

                            <!-- TÍTULO DE LA TEMÁTICA -->

                            <div class="cabecera-carrusel">
                                <div class="titulo-carrusel">
                                    <h2 id="titulo-<?= htmlspecialchars($clave, ENT_QUOTES, 'UTF-8') ?>">

                                        <?= htmlspecialchars($tematica['titulo'], ENT_QUOTES, 'UTF-8') ?>
                                    </h2>
                                    <p>

                                        <?= htmlspecialchars($tematica['descripcion'], ENT_QUOTES, 'UTF-8') ?>
                                    </p>
                                </div>
                            </div>

                            <!-- ======================================
                                 CONTENEDOR DESPLAZABLE
                            ======================================= -->

                            <div class="marco-carrusel">

                                <!-- FLECHAS IZQUIERDAS -->

                                <nav
                                    class="control-flecha control-flecha-izquierda"
                                    aria-label="Retroceder artículos"
                                >
                                    <?php foreach ($listaArticulos as $indiceFlecha => $articuloFlecha): ?>
                                        <div
                                            class="estado-flechas"
                                            data-indice="<?= $indiceFlecha ?>"
                                        >
                                            <?php if ($indiceFlecha > 0): ?>
                                                <?php foreach ($pasosCarrusel as $pasoCarrusel): ?>
                                                    <?php $indiceDestino = max(0, $indiceFlecha - $pasoCarrusel); ?>
                                                    <a
                                                        class="flecha-carrusel flecha-paso-<?= $pasoCarrusel ?>"
                                                        href="#articulo-<?= htmlspecialchars($clave, ENT_QUOTES, 'UTF-8') ?>-<?= $indiceDestino ?>"
                                                        aria-label="Artículos anteriores"
                                                        title="Artículos anteriores"
                                                    >
                                                        <span class="material-symbols-outlined" aria-hidden="true">chevron_left</span>
                                                    </a>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </nav>

                                <!-- CARRUSEL -->

                                <div
                                    class="carrusel-articulos"
                                    id="carrusel-<?= htmlspecialchars($clave, ENT_QUOTES, 'UTF-8') ?>"
                                    tabindex="0"
                                    aria-label="Artículos de <?= htmlspecialchars($tematica['titulo'], ENT_QUOTES, 'UTF-8') ?>"
                                >
                                    <div class="pista-carrusel">
                                        <?php foreach ($listaArticulos as $indice => $articulo): ?>
                                            <?php

                                            $id = $articulo['id'] ?? '';
                                            $tituloArticulo = $articulo['titulo'] ?? '';
                                            $resumen = $articulo['resumen'] ?? '';
                                            $categoria = $articulo['categoria'] ?? '';
                                            $imagen = $articulo['imagen'] ?? '';
                                            $alt = $articulo['alt'] ?? '';
                                            $fecha = $articulo['fecha'] ?? '';
                                            $tiempoLectura = $articulo['tiempo_lectura'] ?? '';

                                            // Identificador único para cada tarjeta.

                                            $idTarjeta = 'articulo-' . $clave . '-' . $indice;

                                            ?>
                                            <article
                                                class="tarjeta-articulo"
                                                id="<?= htmlspecialchars($idTarjeta, ENT_QUOTES, 'UTF-8') ?>"
                                            >
                                                <!-- IMAGEN -->

                                                <a
                                                    href="articulo.php?id=<?= rawurlencode((string) $id) ?>"
                                                    class="enlace-imagen"
                                                    aria-label="Leer <?= htmlspecialchars($tituloArticulo, ENT_QUOTES, 'UTF-8') ?>"
                                                >
                                                    <img
                                                        class="tarjeta-imagen"
                                                        src="<?= htmlspecialchars($imagen, ENT_QUOTES, 'UTF-8') ?>"
                                                        alt="<?= htmlspecialchars($alt, ENT_QUOTES, 'UTF-8') ?>"
                                                        loading="lazy"
                                                    >
                                                </a>

                                                <!-- CONTENIDO -->

                                                <div class="tarjeta-contenido">
                                                    <span class="categoria-articulo">

                                                        <?= htmlspecialchars($categoria, ENT_QUOTES, 'UTF-8') ?>
                                                    </span>

                                                    <h3>

                                                        <a href="articulo.php?id=<?= rawurlencode((string) $id) ?>">

                                                            <?= htmlspecialchars($tituloArticulo, ENT_QUOTES, 'UTF-8') ?>
                                                        </a>
                                                    </h3>

                                                    <p class="resumen-articulo">

                                                        <?= htmlspecialchars($resumen, ENT_QUOTES, 'UTF-8') ?>
                                                    </p>

                                                    <!-- FECHA Y TIEMPO DE LECTURA -->

                                                    <div class="informacion-articulo">
                                                        <span>

                                                            <?= htmlspecialchars($fecha, ENT_QUOTES, 'UTF-8') ?>
                                                        </span>

                                                        <span>

                                                            <?= htmlspecialchars((string) $tiempoLectura, ENT_QUOTES, 'UTF-8') ?> min lectura
                                                        </span>
                                                    </div>

                                                    <!-- ENLACE AL ARTÍCULO -->

                                                    <a
                                                        href="articulo.php?id=<?= rawurlencode((string) $id) ?>"
                                                        class="leer-articulo"
                                                    >
                                                        Leer artículo

                                                        <span aria-hidden="true">
                                                            &rarr;
                                                        </span>
                                                    </a>
                                                </div>
                                            </article>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <!-- FLECHAS DERECHAS -->

                                <nav
                                    class="control-flecha control-flecha-derecha"
                                    aria-label="Avanzar artículos"
                                >
                                    <?php foreach ($listaArticulos as $indiceFlecha => $articuloFlecha): ?>
                                        <div
                                            class="estado-flechas"
                                            data-indice="<?= $indiceFlecha ?>"
                                        >
                                            <?php foreach ($pasosCarrusel as $pasoCarrusel): ?>
                                                <?php $indiceDestino = $indiceFlecha + $pasoCarrusel; ?>
                                                <?php if ($indiceDestino < count($listaArticulos)): ?>
                                                    <a
                                                        class="flecha-carrusel flecha-paso-<?= $pasoCarrusel ?>"
                                                        href="#articulo-<?= htmlspecialchars($clave, ENT_QUOTES, 'UTF-8') ?>-<?= $indiceDestino ?>"
                                                        aria-label="Artículos siguientes"
                                                        title="Artículos siguientes"
                                                    >
                                                        <span class="material-symbols-outlined" aria-hidden="true">chevron_right</span>
                                                    </a>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </nav>
                            </div>

                            <!-- ======================================
                                 INDICADORES DE NAVEGACIÓN
                            ======================================= -->

                            <?php if (count($listaArticulos) > 1): ?>
                                <nav
                                    class="indicadores-carrusel"
                                    aria-label="Páginas de artículos de <?= htmlspecialchars($tematica['titulo'], ENT_QUOTES, 'UTF-8') ?>"
                                >
                                    <?php foreach ($pasosCarrusel as $pasoCarrusel): ?>
                                        <?php $numeroPaginas = (int) ceil(count($listaArticulos) / $pasoCarrusel); ?>
                                        <?php for ($numeroPagina = 0; $numeroPagina < $numeroPaginas; $numeroPagina++): ?>
                                            <?php $indiceDestino = $numeroPagina * $pasoCarrusel; ?>
                                            <?php $idDestino = 'articulo-' . $clave . '-' . $indiceDestino; ?>
                                            <a
                                                class="punto-carrusel punto-paso-<?= $pasoCarrusel ?>"
                                                data-paso="<?= $pasoCarrusel ?>"
                                                data-pagina="<?= $numeroPagina ?>"
                                                href="#<?= htmlspecialchars($idDestino, ENT_QUOTES, 'UTF-8') ?>"
                                                aria-label="Ir al grupo <?= $numeroPagina + 1 ?> de artículos"
                                                title="Grupo <?= $numeroPagina + 1 ?>"
                                                <?= $numeroPagina === 0 ? 'aria-current="page"' : '' ?>
                                            ></a>
                                        <?php endfor; ?>
                                    <?php endforeach; ?>
                                </nav>
                            <?php endif; ?>
                        </section>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
    <footer>
        <div class="container footer-inner">
            <small>&copy; <?= date('Y') ?> icare coaching, s.l.</small>
            <div>
                <a href="#contenido">Volver al inicio</a>
                <a href="../general/login.php">Área privada</a>
            </div>
        </div>
    </footer>
    <script>
        const seccionesCarrusel = document.querySelectorAll('.seccion-carrusel');

        function actualizarIndicadoresCarrusel() {
            seccionesCarrusel.forEach(seccion => {
                const articuloActual = seccion.querySelector('.tarjeta-articulo:target');
                const indiceActual = articuloActual
                    ? Number(articuloActual.id.split('-').pop())
                    : 0;
                const puntos = Array.from(seccion.querySelectorAll('.punto-carrusel'));
                const puntoVisible = puntos.find(punto => window.getComputedStyle(punto).display !== 'none');
                const pasoActual = puntoVisible
                    ? Number(puntoVisible.dataset.paso)
                    : <?= (int) $articulosPorPagina ?>;
                const paginaActual = Math.floor(indiceActual / pasoActual);

                puntos.forEach(punto => {
                    if (
                        Number(punto.dataset.paso) === pasoActual
                        && Number(punto.dataset.pagina) === paginaActual
                    ) {
                        punto.setAttribute('aria-current', 'page');
                    } else {
                        punto.removeAttribute('aria-current');
                    }
                });
            });
        }

        function alinearArticuloObjetivo() {
            const articuloActual = document.querySelector('.tarjeta-articulo:target');
            if (!articuloActual) {
                return;
            }

            const carrusel = articuloActual.closest('.carrusel-articulos');
            if (!carrusel) {
                return;
            }

            const desplazamiento =
                articuloActual.getBoundingClientRect().left
                - carrusel.getBoundingClientRect().left
                + carrusel.scrollLeft;

            carrusel.scrollTo({
                left: desplazamiento,
                behavior: 'smooth'
            });
        }

        document.querySelectorAll('.flecha-carrusel, .punto-carrusel').forEach(control => {
            control.addEventListener('click', () => {
                window.requestAnimationFrame(alinearArticuloObjetivo);
            });
        });

        window.addEventListener('hashchange', () => {
            actualizarIndicadoresCarrusel();
            window.requestAnimationFrame(alinearArticuloObjetivo);
        });

        window.addEventListener('resize', actualizarIndicadoresCarrusel);
        actualizarIndicadoresCarrusel();
        window.requestAnimationFrame(alinearArticuloObjetivo);
    </script>
</body>
</html>
