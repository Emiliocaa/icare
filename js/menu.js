// Busca los elementos principales en tu página web:
const [abrirMenu, cerrarMenu, menuLateral, fondoMenu] = [ // Define varias "cajas" para guardar tus elementos de la página.
    "abrirMenu", // El identificador del botón para abrir el menú.
    "cerrarMenu", // El identificador del botón para cerrar el menú.
    "menuLateral", // El identificador del menú que se desliza.
    "fondoMenu", // El identificador del fondo que se oscurece.
].map((id) => document.getElementById(id)); // Busca cada elemento por su identificador en la página.

// Esta línea detecta si la pantalla es lo suficientemente grande como para considerarse un "escritorio" (más de 700 píxeles de ancho).
const escritorio = window.matchMedia("(min-width: 701px)");
/* indica cómo JavaScript se conecta con el diseño responsivo (CSS)
para cambiar el comportamiento del menú según el tamaño de la pantalla, ejecutando lógica solo cuando
el ancho de la pantalla es mayor o menor a un punto definido. */

// Esta "caja" guardará el último elemento de la página que el usuario estaba usando antes de abrir el menú.
let elementoApertura = null;

// Esta función busca todos los elementos dentro del menú a los que el usuario puede acceder con el teclado (enlaces, botones).
function obtenerElementosEnfocables() { // Define una función para encontrar elementos que el usuario pueda seleccionar.
    return [...menuLateral.querySelectorAll( // Busca dentro del menú lateral...
        'a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])' // ... todos los enlaces, botones activos o elementos que se pueden tabular.
    )].filter(elemento => elemento.getClientRects().length > 0); // Filtra para asegurarse de que solo incluye los elementos que son visibles en la pantalla.
} // Fin de la función `obtenerElementosEnfocables`.
/* `obtenerElementosEnfocables` y su parte "avanzada"que verifica si un elemento es visible en la pantalla antes de considerarlo "enfocable" (accesible con el teclado). */

// Esta función es la encargada de mostrar el menú lateral en la pantalla.
function mostrarMenu() { // Define una función para mostrar el menú.
    if (menuLateral.classList.contains("activo")) { // Si el menú ya tiene la clase "activo" (es decir, ya está abierto)...
        return; // ... no hace nada y sale de la función.
    }

    elementoApertura = document.activeElement; // Guarda el elemento que el usuario estaba usando justo antes de abrir el menú.
    menuLateral.classList.add("activo");     // Añade la clase "activo" al menú para que se vea (el CSS lo muestra).
    fondoMenu.classList.add("activo");       // Añade la clase "activo" al fondo para que se oscurezca.
    document.documentElement.classList.add("menu-abierto"); // Añade una clase al elemento principal de la página (<html>) para estilos globales.
    abrirMenu.setAttribute("aria-expanded", "true"); // Indica a los lectores de pantalla que el menú está abierto.
    abrirMenu.setAttribute("aria-label", "Cerrar menú"); // Cambia la descripción del botón para accesibilidad, indicando que ahora lo cerrará.
    abrirMenu.style.visibility = 'hidden'; // Oculta el botón de abrir el menú sin quitarlo del flujo.NO FUNCIONA

    const [primerElementoEnfocable] = obtenerElementosEnfocables(); // Encuentra el primer elemento dentro del menú que puede ser seleccionado con el teclado.
    (primerElementoEnfocable || menuLateral).focus(); // Mueve la selección del teclado a ese primer elemento, o al menú si no hay ninguno.
} // Fin de la función `mostrarMenu`.

// Esta función es la encargada de ocultar el menú lateral.
function ocultarMenu(devolverFoco = true) { // Define una función para ocultar el menú (por defecto, intenta devolver el foco).
    if (!menuLateral.classList.contains("activo")) { // Si el menú no tiene la clase "activo" (es decir, ya está cerrado)...
        return; // ... no hace nada y sale de la función.
    }

    menuLateral.classList.remove("activo");     // Quita la clase "activo" al menú para que se oculte (el CSS lo esconde).
    fondoMenu.classList.remove("activo");       // Quita la clase "activo" al fondo para que desaparezca el oscurecido.
    document.documentElement.classList.remove("menu-abierto"); // Quita la clase del elemento principal de la página (<html>).
    abrirMenu.setAttribute("aria-expanded", "false"); // Indica a los lectores de pantalla que el menú está cerrado.
    abrirMenu.setAttribute("aria-label", "Abrir menú"); // Cambia la descripción del botón para accesibilidad, indicando que ahora lo abrirá.
    abrirMenu.classList.remove('oculto'); // Muestra el botón de abrir el menú quitando la clase 'oculto'.
    abrirMenu.style.visibility = 'visible'; 

    if (devolverFoco && elementoApertura instanceof HTMLElement) { // Si se debe devolver el foco y tenemos un elemento original guardado...
        elementoApertura.focus(); // ... mueve la selección del teclado a ese elemento original.
    }

    elementoApertura = null; // Borra el elemento guardado para la próxima vez que se abra el menú.
} // Fin de la función `ocultarMenu`.

// Cuando el usuario hace clic en el botón de "abrir menú", se ejecuta la función `mostrarMenu`.
abrirMenu.addEventListener("click", mostrarMenu); // Añade un "escuchador" para el clic en el botón de abrir.
// Para todos los elementos que deben cerrar el menú (botón de cerrar, fondo, y enlaces del menú), se añade un evento de clic.
[cerrarMenu, fondoMenu, ...menuLateral.querySelectorAll("a")].forEach((elemento) => { // Crea una lista con el botón de cerrar, el fondo, y todos los enlaces del menú.
    elemento.addEventListener("click", () => ocultarMenu()); // Cuando se hace clic en cualquiera de ellos, se cierra el menú.
}); // Fin de la adición de eventos para cerrar el menú.

// Esto escucha cada vez que el usuario presiona una tecla en cualquier parte de la página.
document.addEventListener("keydown", evento => { // Añade un "escuchador" para cuando se presiona cualquier tecla.
    if (!menuLateral.classList.contains("activo")) { // Si el menú no está abierto...
        return; // ... no hace nada con las teclas.
    }

    if (evento.key === "Escape") { // Si la tecla presionada es "Escape"...
        evento.preventDefault(); // Detiene el comportamiento normal de la tecla en el navegador.
        ocultarMenu();           // Cierra el menú.
        return;                  // Sale de esta parte del código.
    }

    if (evento.key !== "Tab") { // Si la tecla presionada no es "Tab"...
        return; // ... no hace nada más con esta tecla.
    }

    const elementosEnfocables = obtenerElementosEnfocables(); // Obtiene todos los elementos dentro del menú que se pueden seleccionar con el teclado.
    const primero = elementosEnfocables[0];                   // El primer elemento de esa lista.
    const ultimo = elementosEnfocables[elementosEnfocables.length - 1]; // El último elemento de esa lista.

    if (!primero || !ultimo) { // Si no hay elementos seleccionables o solo hay uno...
        evento.preventDefault(); // Detiene el comportamiento normal de la tecla Tab.
        menuLateral.focus();     // Mueve la selección del teclado al menú en general.
    } else if (evento.shiftKey && ( // Si se presiona "Shift" + "Tab" (para ir hacia atrás) Y...
        document.activeElement === primero || // ... el usuario está en el primer elemento enfocable, O...
        !menuLateral.contains(document.activeElement) // ... la selección del teclado está fuera del menú...
    )) { // Si se cumple la condición de Shift+Tab.
        evento.preventDefault(); // Detiene el comportamiento normal de Shift+Tab.
        ultimo.focus();          // Mueve la selección del teclado al ÚLTIMO elemento del menú.
    } else if (!evento.shiftKey && ( // Si se presiona solo "Tab" (para ir hacia adelante) Y...
        document.activeElement === ultimo || // ... el usuario está en el último elemento enfocable, O...
        !menuLateral.contains(document.activeElement) // ... la selección del teclado está fuera del menú...
    )) { // Si se cumple la condición de Tab normal.
        evento.preventDefault(); // Detiene el comportamiento normal de Tab.
        primero.focus();         // Mueve la selección del teclado al PRIMER elemento del menú.
    }
}); // Fin del escuchador de teclas.

// Esto escucha si el tamaño de la pantalla cambia y ahora coincide con la definición de "escritorio".
escritorio.addEventListener("change", evento => { // Añade un "escuchador" para los cambios de tamaño de pantalla.
    if (evento.matches) { // Si la pantalla ahora es de tamaño "escritorio"...
        ocultarMenu(false); // ... oculta el menú, pero sin intentar devolver el foco (porque en escritorio el menú no debería estar abierto).
    }
}); // Fin del escuchador de cambios de pantalla.
