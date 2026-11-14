
<?php

require_once __DIR__ . '/cargador-articulos.php';

// ==========================================
// CONFIGURACIÓN
// ==========================================

$tituloWeb = 'Articulo | Icare Coaching ';
$articulos = cargarArticulos();
$articulo = null;
$articulosRelacionados = [];
$idSolicitado = $_GET['id'] ?? '';


// ==========================================
// FUNCIÓN DE SEGURIDAD
// ==========================================
//$esc es un atajo reutilizable para mostrar los datos de tus 
//artículos como texto HTML, sin repetir htmlspecialchars() en cada línea.
$esc = function ($valor) {
    return htmlspecialchars(
        (string) $valor,
        ENT_QUOTES,
        'UTF-8'
    );
};

// ==========================================
// BUSCAR ARTÍCULO SOLICITADO
// ==========================================

foreach ($articulos as $item) {
    if (
        isset($item['id']) &&
        $item['id'] === $idSolicitado
    ) {

        $articulo = $item;
        break;
    }
}

// ==========================================
// ARTÍCULO NO ENCONTRADO
// ==========================================

if (!$articulo) {

    http_response_code(404);
}

// ==========================================
// ARTÍCULOS RELACIONADOS
// ==========================================

if ($articulo) {
    foreach ($articulos as $item) {

        if (
            isset($item['tematica']) &&
            $item['tematica'] === ($articulo['tematica'] ?? '') &&
            $item['id'] !== $articulo['id']
        ) {

            $articulosRelacionados[] = $item;

        }
    }

    $articulosRelacionados = array_slice(
        $articulosRelacionados,
        0,
        3
    );
}

// ==========================================
// PREPARAR DATOS DEL ARTÍCULO
// ==========================================

$tituloArticulo = $articulo['titulo'] ?? 'Artículo no encontrado';
$resumen = $articulo['resumen'] ?? '';
$categoria = $articulo['categoria'] ?? '';
$fecha = $articulo['fecha'] ?? '';
$tiempoLectura = $articulo['tiempo_lectura'] ?? '';
$imagen = $articulo['imagen'] ?? '';
$alt = $articulo['alt'] ?? $tituloArticulo;
$bloquesContenido = $articulo['contenido'] ?? [];
$tituloWeb = $tituloArticulo . ' | Icare Coaching';

?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $esc($tituloWeb) ?></title>

    <meta
        name="description"
        content="<?= $esc($resumen) ?>"
    >
    <link
        rel="stylesheet"
        href="../assets/css/estilos.css"
    >
    <link
        rel="stylesheet"
        href="assets/css/conocimiento.css"
    >
    <link
        rel="stylesheet"
        href="assets/css/articulo.css?v=<?= filemtime(__DIR__ . '/assets/css/articulo.css') ?>"
    >
</head>

<body>
    <?php include __DIR__ . '/header.php'; ?>

    <main class="pagina-articulo">
        <div class="contenedor-articulo">
            <?php if ($articulo): ?>
                <div class="tarjeta-lectura">
                    <header class="cabecera-articulo">
                        <span class="categoria-destacada">

                            <?= $esc($categoria) ?>
                        </span>

                        <h1 class="titulo-articulo">

                            <?= $esc($tituloArticulo) ?>
                        </h1>

                        <p class="resumen-destacado">

                            <?= $esc($resumen) ?>
                        </p>

                        <div class="datos-articulo">
                            <?php if ($fecha !== ''): ?>
                                <span>

                                    <?= $esc($fecha) ?>

                                </span>
                            <?php endif; ?>

                            <?php if ($fecha !== '' && $tiempoLectura !== ''): ?>

                                <span class="separador-datos">·</span>
                            <?php endif; ?>

                            <?php if ($tiempoLectura !== ''): ?>
                                <span>

                                    <?= $esc($tiempoLectura) ?> min de lectura
                                </span>
                            <?php endif; ?>
                        </div>
                    </header>

                    <article class="contenido-articulo">
                        <!-- IMAGEN LATERAL -->

                        <?php if ($imagen !== ''): ?>
                            <figure class="imagen-lateral-articulo">

                                <img
                                    src="<?= $esc($imagen) ?>"
                                    alt="<?= $esc($alt) ?>"
                                >

                            </figure>
                        <?php endif; ?>

                        <!-- CONTENIDO DINÁMICO DESDE JSON -->

                        <?php if (!empty($bloquesContenido)): ?>
                            <?php foreach ($bloquesContenido as $bloque): ?>
                                <?php

                                $tipo = $bloque['tipo'] ?? '';
                                $texto = $bloque['texto'] ?? '';

                                ?>

                                <?php if ($tipo === 'parrafo'): ?>
                                    <p>

                                        <?= nl2br($esc($texto)) ?>
                                    </p>

                                <?php elseif ($tipo === 'titulo'): ?>
                                    <h2>

                                        <?= $esc($texto) ?>
                                    </h2>

                                <?php elseif ($tipo === 'cita'): ?>
                                    <blockquote class="cita-articulo">

                                        <?= $esc($texto) ?>
                                    </blockquote>

                                <?php elseif ($tipo === 'lista'): ?>
                                    <ul class="lista-articulo">
                                        <?php foreach (($bloque['elementos'] ?? []) as $elemento): ?>
                                            <li>

                                                <?= $esc($elemento) ?>

                                            </li>
                                        <?php endforeach; ?>
                                    </ul>

                                <?php elseif ($tipo === 'imagen'): ?>
                                    <figure class="imagen-contenido-articulo">

                                        <img
                                            src="<?= $esc($bloque['src'] ?? '') ?>"
                                            alt="<?= $esc($bloque['alt'] ?? '') ?>"
                                        >

                                        <?php if (!empty($bloque['pie'])): ?>
                                            <figcaption>

                                                <?= $esc($bloque['pie']) ?>

                                            </figcaption>
                                        <?php endif; ?>
                                    </figure>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else: ?>

                            <!-- CONTENIDO PROVISIONAL -->

                            <p>

                                <?= nl2br($esc($resumen)) ?>
                            </p>

                            <div class="aviso-contenido">

                                El contenido completo de este artículo estará disponible próximamente.
                            </div>
                        <?php endif; ?>

                        <!-- LIMPIAR EL FLOTADO DE LA IMAGEN -->

                        <div class="limpiar-flotado"></div>
                    </article>

                    <div class="final-articulo">
                        <p>

                            El conocimiento impulsa el cambio.
                        </p>

                        <p>

                            En Icare Coaching creemos que el desarrollo personal y empresarial comienza con nuevas ideas y decisiones conscientes.
                        </p>
                    </div>
                </div>

                <!-- ======================================
                     NAVEGACIÓN DE REGRESO
                ====================================== -->

                <nav class="navegacion-articulo">
                    <a
                        href="index.php"
                        class="boton-volver"
                    >
                        Volver a Conocimiento
                    </a>

                    <a
                        href="../index.php"
                        class="enlace-inicio"
                    >
                        Página principal
                    </a>
                </nav>

                <!-- ======================================
                     ARTÍCULOS RELACIONADOS
                ====================================== -->

                <?php if (!empty($articulosRelacionados)): ?>
                    <section class="relacionados-articulo">
                        <div class="cabecera-relacionados">
                            <span class="eyebrow">

                                CONTINÚA EXPLORANDO
                            </span>

                            <h2>

                                Artículos relacionados
                            </h2>
                        </div>

                        <div class="rejilla-relacionados">
                            <?php foreach ($articulosRelacionados as $relacionado): ?>
                                <article class="tarjeta-relacionada">
                                    <a
                                        href="articulo.php?id=<?= urlencode($relacionado['id']) ?>"
                                        class="imagen-relacionada"
                                    >
                                        <img
                                            src="<?= $esc($relacionado['imagen'] ?? '') ?>"
                                            alt="<?= $esc($relacionado['alt'] ?? '') ?>"
                                        >
                                    </a>

                                    <div class="contenido-relacionado">
                                        <span>

                                            <?= $esc($relacionado['categoria'] ?? '') ?>
                                        </span>

                                        <h3>
                                            <a href="articulo.php?id=<?= urlencode($relacionado['id']) ?>">

                                                <?= $esc($relacionado['titulo'] ?? '') ?>
                                            </a>
                                        </h3>

                                        <p>

                                            <?= $esc($relacionado['resumen'] ?? '') ?>
                                        </p>

                                        <a
                                            href="articulo.php?id=<?= urlencode($relacionado['id']) ?>"
                                            class="leer-relacionado"
                                        >
                                            Leer artículo →
                                        </a>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>
            <?php else: ?>

                <!-- ======================================
                     ARTÍCULO NO ENCONTRADO
                ====================================== -->

                <section class="articulo-no-encontrado">
                    <h1>

                        Artículo no encontrado
                    </h1>

                    <p>

                        El artículo que buscas no existe o ha sido trasladado.
                    </p>

                    <a
                        href="index.php"
                        class="boton-volver"
                    >
                        Volver a Conocimiento
                    </a>
                </section>
            <?php endif; ?>
        </div>
    </main>

    <?php

    if (file_exists(__DIR__ . '/footer.php')) {

        include __DIR__ . '/footer.php';
    }
    ?>
</body>
</html>
