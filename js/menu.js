const abrirMenu = document.getElementById("abrirMenu");
const cerrarMenu = document.getElementById("cerrarMenu");
const menuLateral = document.getElementById("menuLateral");
const fondoMenu = document.getElementById("fondoMenu");
const escritorio = window.matchMedia("(min-width: 701px)");
/*Es la forma que tiene JavaScript de comunicarse con los 
puntos de quiebre (breakpoints) de tu diseño CSS para ejecutar 
lógica específica solo cuando la pantalla supera o baja de un 
ancho determinado.*/    

let elementoApertura = null;

function obtenerElementosEnfocables() {
    return [...menuLateral.querySelectorAll(
        'a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])'
    )].filter(elemento => elemento.getClientRects().length > 0);
}
/*Qué hace: Esta función busca dentro del menú lateral todos los
 elementos en los que un usuario pueda hacer clic o tabular 
 (enlaces, botones activos, elementos con tabindex).

El truco avanzado (getClientRects().length > 0): Utiliza un 
filtro para comprobar que el elemento realmente sea visible en 
la pantalla (es decir, que no tenga un display: none o esté 
oculto). Si es visible, se incluye en la lista de elementos 
"navegables".*/

function mostrarMenu() {
    if (menuLateral.classList.contains("activo")) {
        return;
    }

    elementoApertura = document.activeElement;
    menuLateral.classList.add("activo");
    fondoMenu.classList.add("activo");
    document.documentElement.classList.add("menu-abierto");
    abrirMenu.setAttribute("aria-expanded", "true");
    abrirMenu.setAttribute("aria-label", "Cerrar menú");

    const [primerElementoEnfocable] = obtenerElementosEnfocables();
    (primerElementoEnfocable || menuLateral).focus();
}

function ocultarMenu(devolverFoco = true) {
    if (!menuLateral.classList.contains("activo")) {
        return;
    }

    menuLateral.classList.remove("activo");
    fondoMenu.classList.remove("activo");
    document.documentElement.classList.remove("menu-abierto");
    abrirMenu.setAttribute("aria-expanded", "false");
    abrirMenu.setAttribute("aria-label", "Abrir menú");

    if (devolverFoco && elementoApertura instanceof HTMLElement) {
        elementoApertura.focus();
    }

    elementoApertura = null;
}

abrirMenu.addEventListener("click", mostrarMenu);
cerrarMenu.addEventListener("click", () => ocultarMenu());
fondoMenu.addEventListener("click", () => ocultarMenu());

menuLateral.querySelectorAll("a").forEach(enlace => {
    enlace.addEventListener("click", () => ocultarMenu());
});

document.addEventListener("keydown", evento => {
    if (!menuLateral.classList.contains("activo")) {
        return;
    }

    if (evento.key === "Escape") {
        evento.preventDefault();
        ocultarMenu();
        return;
    }

    if (evento.key !== "Tab") {
        return;
    }

    const elementosEnfocables = obtenerElementosEnfocables();
    const primero = elementosEnfocables[0];
    const ultimo = elementosEnfocables[elementosEnfocables.length - 1];

    if (!primero || !ultimo) {
        evento.preventDefault();
        menuLateral.focus();
    } else if (evento.shiftKey && (
        document.activeElement === primero ||
        !menuLateral.contains(document.activeElement)
    )) {
        evento.preventDefault();
        ultimo.focus();
    } else if (!evento.shiftKey && (
        document.activeElement === ultimo ||
        !menuLateral.contains(document.activeElement)
    )) {
        evento.preventDefault();
        primero.focus();
    }
});

escritorio.addEventListener("change", evento => {
    if (evento.matches) {
        ocultarMenu(false);
    }
});
