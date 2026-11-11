
<?php

$titulo = 'Conocimiento | Icare Coaching';

$rutaArticulos = __DIR__ . '/data/articulos.json';

$articulos = [];

// ==========================================
// 1. LEER LOS ARTÍCULOS DEL ARCHIVO JSON
// ==========================================

if (is_readable($rutaArticulos)) {

    $contenido = file_get_contents($rutaArticulos);

    try {

        $datos = json_decode(
            $contenido,
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        if (is_array($datos)) {
            $articulos = $datos;
        }

    } catch (JsonException $e) {

        error_log('Error al leer articulos.json: ' . $e->getMessage());

    }
}

// ==========================================
// 2. ORDENAR LOS ARTÍCULOS POR FECHA
// ==========================================

usort($articulos, function ($a, $b) {

    return strcmp(
        $b['fecha'] ?? '',
        $a['fecha'] ?? ''
    );

});

// ==========================================
// 3. DEFINIR LOS CARRUSELES
// ==========================================

$tematicas = [

    'coaching' => [
        'titulo' => 'Coaching y desarrollo personal',
        'descripcion' => 'Herramientas para crecer, aprender y desarrollar todo tu potencial.'
    ],

    'liderazgo' => [
        'titulo' => 'Liderazgo y gestión de equipos',
        'descripcion' => 'Nuevas perspectivas para liderar personas y organizaciones.'
    ],

    'tecnologia' => [
        'titulo' => 'Tecnología e inteligencia artificial',
        'descripcion' => 'Innovación, digitalización y nuevas oportunidades para las empresas.'
    ],

    'estrategia' => [
        'titulo' => 'Estrategia y dirección empresarial',
        'descripcion' => 'Ideas para tomar mejores decisiones y construir empresas competitivas.'
    ],

    'mejora' => [
        'titulo' => 'Mejora continua y optimización',
        'descripcion' => 'Procesos más eficientes, organizaciones más ágiles y mejores resultados.'
    ]

];

// ==========================================
// 4. AGRUPAR LOS ARTÍCULOS POR TEMÁTICA
// ==========================================

$articulosPorTematica = [];

foreach ($tematicas as $clave => $tematica) {

    $articulosPorTematica[$clave] = [];

}

foreach ($articulos as $articulo) {

    $clave = $articulo['tematica'] ?? '';

    if (isset($articulosPorTematica[$clave])) {

        $articulosPorTematica[$clave][] = $articulo;

    }

}

// ==========================================
// 5. CONFIGURACIÓN DE LA PAGINACIÓN
// ==========================================

// Cada punto representa un grupo de cinco artículos.

$articulosPorPagina = 5;

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta
        name="description"
        content="Artículos y reflexiones sobre liderazgo, coaching, inteligencia artificial, tecnología y estrategia empresarial."
    >

    <title><?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?></title>

    <!-- CSS GENERAL -->

    <link rel="stylesheet" href="../assets/css/estilos.css">

    <!-- CSS ESPECÍFICO DE CONOCIMIENTO -->

    <link rel="stylesheet" href="assets/css/conocimiento.css">

</head>

<body>

    <!-- ======================================
         CABECERA Y NAVEGACIÓN
    ======================================= -->

    <?php require __DIR__ . '/header.php'; ?>

    <main id="contenido">
        <section class="hero">

            <div class="container ">

                <!-- INTRODUCCIÓN -->

                <div class="introduccion-articulos">

                    <p class="eyebrow">
                        REVISTA ICARE
                    </p>

                    <h1>
                        Ideas que impulsan el cambio.
                    </h1>

                    <p class="lead">
                        
                    </p>

                </div>
          </div>
        </section>
    </main>

    <main>
        

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

                    // Dividir los artículos en grupos de cinco.

                    $paginasArticulos = array_chunk(
                        $listaArticulos,
                        $articulosPorPagina
                    );

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


                            <!-- ======================================
                                 INDICADORES DE NAVEGACIÓN
                            ======================================= -->

                            <?php if (count($paginasArticulos) > 1): ?>

                                <nav
                                    class="indicadores-carrusel"
                                    aria-label="Páginas de artículos de <?= htmlspecialchars($tematica['titulo'], ENT_QUOTES, 'UTF-8') ?>"
                                >

                                    <?php foreach ($paginasArticulos as $numeroPagina => $grupoArticulos): ?>

                                        <?php

                                        // El primer artículo de cada grupo
                                        // será el destino del indicador.

                                        $primerArticulo = $numeroPagina * $articulosPorPagina;

                                        $idDestino = 'articulo-' . $clave . '-' . $primerArticulo;

                                        ?>

                                        <a
                                            class="punto-carrusel"
                                            href="#<?= htmlspecialchars($idDestino, ENT_QUOTES, 'UTF-8') ?>"
                                            aria-label="Ir al grupo <?= $numeroPagina + 1 ?> de artículos"
                                            title="Grupo <?= $numeroPagina + 1 ?>"
                                        ></a>

                                    <?php endforeach; ?>

                                </nav>

                            <?php endif; ?>

                        </section>

                    <?php endif; ?>

                <?php endforeach; ?>

            </div>

        </section>

    </main>

</body>

</html>
