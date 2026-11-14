<?php
/**
 * Hoja de estilos dinámica para los estados de los carruseles.
 * Genera CSS a partir de los artículos existentes en articulos.json.
 * No necesita JavaScript.
 */

header('Content-Type: text/css; charset=UTF-8');
header('Cache-Control: no-cache, no-store, must-revalidate');

require_once __DIR__ . '/cargador-articulos.php';

$configuracionArticulos = require __DIR__ . '/configuracion-articulos.php';
$tematicas = $configuracionArticulos['tematicas'];
$articulosPorPagina = $configuracionArticulos['articulosPorPagina'];
$articulos = ordenarArticulosPorFecha(cargarArticulos());

foreach ($tematicas as $clave => $tematica) {
    $listaArticulos = [];

    foreach ($articulos as $articulo) {
        if (($articulo['tematica'] ?? '') === $clave) {
            $listaArticulos[] = $articulo;
        }
    }

    foreach ($listaArticulos as $indice => $articulo) {
        $idTarjeta = 'articulo-' . $clave . '-' . $indice;
        $pagina = intdiv($indice, $articulosPorPagina);

        echo ".seccion-carrusel:has(#{$idTarjeta}:target) ";
        echo ".estado-flechas[data-indice=\"{$indice}\"] { display: flex !important; visibility: visible !important; pointer-events: auto !important; }\n";

        echo ".seccion-carrusel:has(#{$idTarjeta}:target) ";
        echo ".punto-carrusel[data-pagina=\"{$pagina}\"] {\n";
        echo "    background: var(--verde-oscuro);\n";
        echo "    transform: scale(1.3);\n";
        echo "    box-shadow: 0 0 0 0.2rem rgba(23, 80, 69, 0.14);\n";
        echo "}\n";
    }

    // Estado inicial: primera flecha y primer indicador activos.
    echo ".seccion-carrusel:not(:has(.tarjeta-articulo:target)) ";
    echo ".estado-flechas[data-indice=\"0\"] { display: flex !important; visibility: visible !important; pointer-events: auto !important; }\n";

    echo ".seccion-carrusel:not(:has(.tarjeta-articulo:target)) ";
    echo ".punto-carrusel[data-pagina=\"0\"] {\n";
    echo "    background: var(--verde-oscuro);\n";
    echo "    transform: scale(1.3);\n";
    echo "    box-shadow: 0 0 0 0.2rem rgba(23, 80, 69, 0.14);\n";
    echo "}\n";
}
