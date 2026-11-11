<header class="cabecera">
    <nav class="contenedor navegacion" aria-label="Menú principal">
        <a href="index.php" class="logo">
            icare coaching
        </a>

        <div class="enlaces">
            <a href="index.php#enfoque">Mi enfoque</a>
            <a href="index.php#servicios">Servicios</a>
            <a href="index.php#experiencia">Trayectoria</a>
            <a href="conocimiento/index.php">Conocimiento</a>
            <a href="index.php#contacto">Contacto</a>

            <a href="login.php" class="boton boton-secundario">
                Iniciar sesión
            </a>

            <a href="registro.php" class="boton boton-principal">
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

            <button
                type="button"
                class="cerrar-menu"
                id="cerrarMenu"
                aria-label="Cerrar menú">
                &times;
            </button>

            <a href="index.php#enfoque">Mi enfoque</a>
            <a href="index.php#servicios">Servicios</a>
            <a href="index.php#experiencia">Trayectoria</a>
            <a href="conocimiento/index.php">Conocimiento</a>
            <a href="index.php#contacto">Contacto</a>

            <hr>

            <a href="login.php" class="boton boton-secundario">
                Iniciar sesión
            </a>

            <a href="registro.php" class="boton boton-principal">
                Registrarse
            </a>

        </div>
    </nav>
</header>
