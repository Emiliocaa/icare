<?php

function cargarArticulos(): array
{
    $rutaArticulos = __DIR__ . '/data/articulos.json';

    if (!is_readable($rutaArticulos)) {
        error_log('No se puede leer el archivo de artículos: ' . $rutaArticulos);
        return [];
    }

    $contenido = file_get_contents($rutaArticulos);

    if ($contenido === false) {
        error_log('No se pudo leer el archivo de artículos: ' . $rutaArticulos);
        return [];
    }

    try {
        $articulos = json_decode(
            $contenido,
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    } catch (JsonException $e) {
        error_log('Error al decodificar articulos.json: ' . $e->getMessage());
        return [];
    }

    if (!is_array($articulos)) {
        error_log('El archivo de artículos no contiene una lista válida: ' . $rutaArticulos);
        return [];
    }

    return $articulos;
}

function ordenarArticulosPorFecha(array $articulos): array
{
    usort($articulos, function ($a, $b) {
        return strcmp(
            $b['fecha'] ?? '',
            $a['fecha'] ?? ''
        );
    });

    return $articulos;
}
