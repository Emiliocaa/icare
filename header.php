<?php

$basePath = $basePath ?? '';
$paginaActiva = $paginaActiva ?? '';

?>
<header class="cabecera">
    <nav class="contenedor navegacion" aria-label="Menú principal">
        <a href="<?= $basePath ?>index.php" class="logo">
             icare coaching 
        </a>

        <div class="enlaces">
            <a href="<?= $basePath ?>index.php#enfoque">Mi enfoque</a>
            <a href="<?= $basePath ?>index.php#servicios">Servicios</a>
            <a href="<?= $basePath ?>index.php#experiencia">Trayectoria</a>
            <a href="<?= $basePath ?>conocimiento/index.php"<?= $paginaActiva === 'conocimiento' ? ' class="activo"' : '' ?>>Conocimiento</a>
            <a href="<?= $basePath ?>index.php#contacto">Contacto</a>

            <?php if ($paginaActiva === 'conocimiento'): ?>
                <hr>
            <?php endif; ?>

            <a href="<?= $basePath ?>login.php" class="boton boton-secundario">
                Iniciar sesión
            </a>

            <a href="<?= $basePath ?>registro.php" class="boton boton-principal">
                Registrarse
            </a>
        </div>

        <!-- BOTÓN MENÚ MÓVIL -->
        <button
            type="button"
            class="boton-menu"
            id="abrirMenu"
            aria-label="Abrir menú"
            aria-expanded="false"
            aria-controls="menuLateral">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <!-- FONDO OSCURO -->
        <div class="fondo-menu" id="fondoMenu"></div>

        <!-- MENÚ LATERAL -->
        <div
            class="menu-lateral"
            id="menuLateral"
            role="dialog"
            aria-modal="true" 
            aria-label="Navegación móvil"
            tabindex="-1">
            <!-- aria-modal el contenido exterior queda temporalmente inactivo -->

            <button
                type="button"
                class="cerrar-menu"
                id="cerrarMenu"
                aria-label="Cerrar menú">
                &times;
            </button>

            <a href="<?= $basePath ?>index.php" class="logoMenuLateral" style="text-decoration: none;">
             icare coaching 
            </a>
            
            <a href="<?= $basePath ?>index.php#enfoque">Mi enfoque</a>
            <a href="<?= $basePath ?>index.php#servicios">Servicios</a>
            <a href="<?= $basePath ?>index.php#experiencia">Trayectoria</a>
            <a href="<?= $basePath ?>conocimiento/index.php"<?= $paginaActiva === 'conocimiento' ? ' class="activo"' : '' ?>>Conocimiento</a>
            <a href="<?= $basePath ?>index.php#contacto">Contacto</a>

            <hr>

            <a href="<?= $basePath ?>login.php" class="boton boton-secundario">
                Iniciar sesión
            </a>

            <a href="<?= $basePath ?>registro.php" class="boton boton-principal">
                Registrarse
            </a>

        </div>
    </nav>
</header>
<script src="<?= $basePath ?>js/menu.js?v=<?= filemtime(__DIR__ . '/js/menu.js') ?>"></script>
