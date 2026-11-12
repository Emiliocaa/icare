<?php
/**
 * Hoja de estilos dinámica para los estados de los carruseles.
 * Genera CSS a partir de los artículos existentes en articulos.json.
 * No necesita JavaScript.
 */

header('Content-Type: text/css; charset=UTF-8');

$rutaArticulos = __DIR__ . '/data/articulos.json';
$articulos = [];

if (is_readable($rutaArticulos)) {
    $contenido = file_get_contents($rutaArticulos);

    try {
        $datos = json_decode($contenido, true, 512, JSON_THROW_ON_ERROR);
        if (is_array($datos)) {
            $articulos = $datos;
        }
    } catch (JsonException $e) {
        error_log('Error al generar estados de carrusel: ' . $e->getMessage());
    }
}

// Mantener exactamente el mismo orden por fecha que utiliza index.php.
usort($articulos, function ($a, $b) {
    return strcmp(
        $b['fecha'] ?? '',
        $a['fecha'] ?? ''
    );
});

// Estas claves deben coincidir con las temáticas de conocimiento/index.php.
$clavesTematicas = [
    'coaching',
    'liderazgo',
    'tecnologia',
    'estrategia',
    'mejora'
];

foreach ($clavesTematicas as $clave) {
    $listaArticulos = [];

    foreach ($articulos as $articulo) {
        if (($articulo['tematica'] ?? '') === $clave) {
            $listaArticulos[] = $articulo;
        }
    }

    foreach ($listaArticulos as $indice => $articulo) {
        $idTarjeta = 'articulo-' . $clave . '-' . $indice;
        $pagina = intdiv($indice, 5);

        echo ".seccion-carrusel:has(#{$idTarjeta}:target) ";
        echo ".estado-flechas[data-indice=\"{$indice}\"] { display: flex; }\n";

        echo ".seccion-carrusel:has(#{$idTarjeta}:target) ";
        echo ".punto-carrusel[data-pagina=\"{$pagina}\"] {\n";
        echo "    background: var(--verde-oscuro);\n";
        echo "    transform: scale(1.3);\n";
        echo "    box-shadow: 0 0 0 0.2rem rgba(23, 80, 69, 0.14);\n";
        echo "}\n";
    }

    // Estado inicial: primera flecha y primer indicador activos.
    echo ".seccion-carrusel:not(:has(.tarjeta-articulo:target)) ";
    echo ".estado-flechas[data-indice=\"0\"] { display: flex; }\n";

    echo ".seccion-carrusel:not(:has(.tarjeta-articulo:target)) ";
    echo ".punto-carrusel[data-pagina=\"0\"] {\n";
    echo "    background: var(--verde-oscuro);\n";
    echo "    transform: scale(1.3);\n";
    echo "    box-shadow: 0 0 0 0.2rem rgba(23, 80, 69, 0.14);\n";
    echo "}\n";
}
