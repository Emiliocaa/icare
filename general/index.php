<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$nombreApp = 'icare coaching, s.l.';
$anioActual = date('Y');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Coaching ejecutivo, liderazgo y motivación de equipos, consultoría de datos e inteligencia artificial para empresas y profesionales en Valencia y online.">
    <title>Coaching ejecutivo y consultoría en Valencia | Icare Coaching</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,400,0,0&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/estilos.css?v=<?= filemtime(__DIR__ . '/assets/css/estilos.css') ?>">
    <meta name="keywords" content="rendimiento, liderazgo, personas, mejora continua, finanzas, estrategia, ia, productividad,">   
</head>

<body>
     <!--Este enlace es un enlace de salto (skip link), una práctica estándar de accesibilidad web diseñada para que las personas que navegan con teclado o con lectores de pantalla puedan saltarse directamente bloques repetitivos (como el logotipo, el menú de navegación o los botones de cabecera) e ir directo al contenido principal de la página.-->
    <a class="skip" href="#contenido">Saltar al contenido</a>
    <!-- insertamos el menú de navegación -->
    <?php require __DIR__ . '/header.php'; ?>

    <main id="contenido">
        <section class="hero" id="inicio">
            <div class="container"> 
                <p class="eyebrow">Personas · Liderazgo · Sistemas · Rentabilidad </p>
                <h1>Transformamos la complejidad operativa en rentabilidad mediante la alineación de personas, tecnología y datos.</h1>
                <p class="lead">Impulsamos el crecimiento de PYMES y Grandes Organizaciones a través de consultoría estratégica 360º y herramientas de control de gestión. Una visión integral respaldada por más de 25 años de trayectoria como CEO, Economista y experto en IA.</p>
                <p class="pitch">En <strong>icare coaching</strong> unimos el rigor del análisis financiero con la psicología del alto rendimiento. Le invitamos a <strong>registrarse en nuestra plataforma</strong> para descubrir un ecosistema de herramientas avanzadas diseñadas para optimizar su productividad y liderar la transformación digital de su negocio.</p>
                <div class="actions"><a class="button" href="#contacto">Agendar una Sesión Diagnóstica Estratégica</a><a class="button secondary" href="#servicios">Explorar servicios</a></div>
                <div class="tags"><span>Más de 25 años de trayectoria</span><span>Valencia y online</span><span>Empresas y Profesionales</span></div>
            </div>
        </section>
        <section class="section" id="enfoque">
            <div class="container">
                <p class="eyebrow">Mi enfoque</p>
                <h2>Liderazgo directivo, visión financiera, gestión humana y vanguardia tecnológica.</h2>
                <div class="intro">
                    <p>Hola, soy <strong>Emilio Casañ Arolas - Economista</strong>. A lo largo de más de 25 años de carrera profesional he liderado la dirección ejecutiva, financiera, operativa y personas en entornos industriales competitivos. Mi metodología une el rigor del análisis financiero con la psicología del alto rendimiento: un dato sin motivación es una cifra muerta, y una motivación sin datos es un esfuerzo sin rumbo.</p>
                    <p>Sé por experiencia que los retos reales de las organizaciones no se limitan a las finanzas o la tecnología; abarcan de manera fundamental <strong>el compromiso del equipo y la eficiencia-eficacia de los sistemas</strong>. Ninguna tecnología o sistema ERP triunfa si el factor humano no está alineado y los procesos no están optimizados.</p>
                    <p>A través de <strong>icare coaching, s.l.</strong>, le ofrecemos una ventaja competitiva única: la combinación de visión estratégica C-Level y el acceso a nuestra <strong>área privada de herramientas</strong>, donde podrá medir, analizar y escalar su negocio con precisión quirúrgica.</p>
                </div>
                <div class="grid one section-heading">
                    <article class="card"><span class="number">01 / PERSONAS</span>
                        <h3>Cohesión y Motivación del Factor Humano</h3>
                        <p>Desarrollar el potencial de los equipos reduciendo la resistencia al cambio y fomentando el compromiso.</p>
                    </article>
                    <article class="card"><span class="number">02 / TECNOLOGÍA</span>
                        <h3>Tecnología Útil y Aplicada</h3>
                        <p>Integrar IA, automatizaciones y sistemas ERP/IT alineados con los objetivos de negocio y la productividad personal.</p>
                    </article>
                    <article class="card"><span class="number">03 / DATOS</span>
                        <h3>Tecnología Útil y Aplicada Cultura Data-Driven &amp; Eficiente</h3>
                        <p>Transformar datos dispersos en KPIs e información estratégica útil para decidir con criterio.</p>
                    </article>
                    <article class="card"><span class="number">04 / RENTABILIDAD</span>
                        <h3>La 4 / RENTABILIDAD es el resultado natural de tener a 1 / PERSONAS comprometidas, 2 / TECNOLOGIAS adecuadas y 3 / DATOS optimizados para la toma de decisiones.</h3>
                        <p></p>
                    </article>
                </div>
            </div>
        </section>
        <section class="section tinted" id="servicios">
            <div class="container">
                <p class="eyebrow">Servicios</p>
                <h2>Soluciones para avanzar con claridad.</h2>
                <p class="intro">Ofrecemos soluciones integrales tanto para <strong>empresas y organizaciones</strong> como para <strong>directivos y personas</strong> que buscan dar un salto cualitativo en su desarrollo profesional y personal.</p>
                <h3 class="section-heading">A) Servicios para empresas y organizaciones</h3>
                <div class="grid two">
                    <article class="card"><span class="number">01 / LIDERAZGO</span>
                        <h4>Coaching Gerencial, Acompañamiento a la Dirección y Mandos Intermedios</h4>
                        <p><strong>Para quién:</strong> Gerentes, CEOs, comités de dirección y responsables de área.</p>
                        <p><strong>Objetivo:</strong> Fortalecer las competencias directivas, la toma de decisiones estratégicas y la gestión del liderazgo ante entornos de incertidumbre y digitalización.</p>
                        <p><strong>Qué trabajamos:</strong></p>
                        <ul>
                            <li>Liderazgo directivo, comunicación estratégica y alineación de departamentos (Finanzas, Operaciones, Comercial, I+D).</li>
                            <li>Gestión del cambio y superación de la resistencia a la transformación digital.</li>
                            <li>Planificación estratégica, gestión del tiempo gerencial y delegación efectiva.</li>
                        </ul>
                    </article>
                    <article class="card"><span class="number">02 / EQUIPOS</span>
                        <h4>Formación Motivacional, Talleres e Impulso de Equipos (Team Empowerment)</h4>
                        <p><strong>Para quién:</strong> Equipos de trabajo, departamentos operativos, comerciales o multidisciplinares que requieren dinamización y renovar su compromiso.</p>
                        <p><strong>Objetivo:</strong> Elevar el clima laboral, la motivación intrínseca, la cohesión del equipo y la orientación colectiva a objetivos.</p>
                        <p><strong>Formatos y Talleres:</strong></p>
                        <ul>
                            <li><strong>Talleres de Motivación y Trabajo en Equipo:</strong> Dinámicas participativas para potenciar el sentido de pertenencia, la resiliencia ante retos corporativos y la actitud positiva.</li>
                            <li><strong>Gestión del Estrés y Productividad Consciente:</strong> Metodologías para trabajar con enfoque, reducir la fricción diaria y mejorar el bienestar del equipo.</li>
                            <li><strong>Comunicación Eficiente y Gestión de Conflictos:</strong> Estrategias para mejorar el flujo de información y la colaboración interdepartamental.</li>
                            <li><strong>Cultura Digital e IA sin Miedo:</strong> Capacitación amigable para sensibilizar a las plantillas sobre el uso positivo de nuevas tecnologías e IA en su trabajo diario.</li>
                        </ul>
                    </article>
                    <article class="card"><span class="number">03 / INNOVACIÓN</span>
                        <h4>Transformación Tecnológica e Inteligencia Artificial Aplicada</h4>
                        <p><strong>Para quién:</strong> Pymes y organizaciones que buscan optimizar procesos sin incurrir en estructuras de costes sobredimensionadas.</p>
                        <p><strong>Objetivo:</strong> Diagnosticar, simplificar e implantar herramientas tecnológicas de alto impacto.</p>
                        <p><strong>Qué trabajamos:</strong></p>
                        <ul>
                            <li>Diagnóstico de cuellos de botella y diseño de automatizaciones de procesos operativos y administrativos.</li>
                            <li>Implementación práctica de Inteligencia Artificial (IA) para el análisis documental, aceleración de tareas recurrentes y generación de insights.</li>
                            <li>Asesoramiento directivo en la elección e implantación de software corporativo y conectividad de sistemas.</li>
                        </ul>
                    </article>
                    <article class="card"><span class="number">04 / DATOS</span>
                        <h4>Consultoría de Datos, Reporting y Business Intelligence</h4>
                        <p><strong>Para quién:</strong> Empresas que necesitan visibilidad clara y en tiempo real de su rendimiento operativo y financiero.</p>
                        <p><strong>Objetivo:</strong> Convertir el dato operativo disperso en una ventaja competitiva para decisiones ágiles y rentables.</p>
                        <p><strong>Qué trabajamos:</strong></p>
                        <ul>
                            <li>Diseño de cuadros de mando (Dashboards) ejecutivos y definición de KPIs clave por departamento.</li>
                            <li>Análisis de rentabilidad avanzada por cliente, línea de producto, canal o mercado.</li>
                            <li>Integración de datos de ERPs (como sistemas ABAS), CRMs y control de costes industriales/operativos.</li>
                        </ul>
                    </article>
                </div>
                <h3 class="section-heading">B) Servicios para personas y directivos (Coaching Individual)</h3>
                <div class="grid two">
                    <article class="card">
                        <h4>Executive &amp; Career Coaching (Coaching de Carrera Ejecutiva)</h4>
                        <p><strong>Para quién:</strong> Directivos, profesionales Senior o perfiles técnicos que buscan un salto profesional o asumir puestos de responsabilidad directiva.</p>
                        <p><strong>Qué trabajamos:</strong> Transición de carrera, desarrollo de marca personal directiva, habilidades de negociación, liderazgo reflexivo y equilibrio personal-profesional.</p>
                    </article>
                    <article class="card">
                        <h4>Coaching de Rendimiento y Desarrollo Personal</h4>
                        <p><strong>Para quién:</strong> Profesionales que desean mejorar su claridad mental, foco, organización personal y autoliderazgo.</p>
                        <p><strong>Qué trabajamos:</strong> Identificación de fortalezas, superación de bloqueos, gestión de hábitos de alto rendimiento y uso de la tecnología a favor del desarrollo individual.</p>
                    </article>
                </div>
            </div>
        </section>
        <section class="section" id="fortalezas">
            <div class="container">
                <p class="eyebrow">Fortalezas y diferenciación</p>
                <h2>Una visión integral. Un impacto práctico.</h2>
                <ol class="benefits">
                    <li><strong>Visión Holística (Tecnología + Datos + Personas):</strong> Entendemos la cuenta de resultados, la analítica avanzada de datos y el motor fundamental del negocio: <strong>las personas y su motivación</strong>.</li>
                    <li><strong>Experiencia Directiva Real:</strong> Transmitimos vivencias reales tras más de 25 años en la alta dirección, gestión de crisis, reorganización de plantillas y expansión.</li>
                    <li><strong>Cultura Data-Driven Accesible:</strong> Traducimos conceptos técnicos y métricas analíticas a un lenguaje directivo y claro.</li>
                    <li><strong>Capacitación e Inspiración Práctica:</strong> Formaciones y talleres con dinámicas participativas que generan cambios de actitud tangibles desde el primer día.</li>
                    <li><strong>Inteligencia Artificial y Eficiencia:</strong> Integración de herramientas de vanguardia para ahorrar tiempo gerencial y permitir que las personas se concentren en tareas de alto valor.</li>
                    <li class="accordion">
                        <details>
                            <summary>
                                <strong>Inteligencia Artificial aplicada:</strong>
                                <span class="accordion_icon" aria-hidden="true"></span>
                            </summary>
                            <div class="accordion_body">
                                <div class="accordion_inner">
                                    Integración de herramientas de vanguardia para ahorrar tiempo gerencial y permitir que las personas se concentren en tareas de alto valor.
                                </div>
                            </div>
                        </details>
                    </li>

                </ol>
            </div>
        </section>
        <section class="section tinted" id="experiencia">
            <div class="container">
                <p class="eyebrow">Experiencia y trayectoria</p>
                <h2>Experiencia que conecta estrategia y ejecución.</h2>
                <div class="grid two">
                    <article class="card">
                        <h3>Liderazgo y Dirección de Equipos</h3>
                        <p>Acompañamiento, reorganización de layout y dirección de equipos multidisciplinares en sectores industriales competitivos.</p>
                    </article>
                    <article class="card">
                        <h3>Capacitación y Cambio Cultural</h3>
                        <p>Coordinación de procesos de cambio e implantación de nuevas metodologías operativas incentivando la participación activa del personal.</p>
                    </article>
                    <article class="card">
                        <h3>Transformación Digital y ERP</h3>
                        <p>Liderazgo e implantación integral del ERP ABAS en 9 meses para toda la organización, unificando procesos y mejorando el clima de colaboración interna.</p>
                    </article>
                    <article class="card">
                        <h3>Control de Gestión y Analítica</h3>
                        <p>Creación de modelos de reporting estratégico para Consejos de Administración orientados a decisiones basadas en datos e indicadores fiables.</p>
                    </article>
                </div>
            </div>
        </section>
        <section class="section" id="contacto">
            <div class="container contact-grid">
                <div>
                    <p class="eyebrow">Contacto</p>
                    <h2>¿Hablamos de impulsar tus equipos y digitalizar tu negocio?</h2>
                    <p>Cuéntame qué necesitas y preparemos una sesión diagnóstica estratégica.</p>
                    <div class="contact-links">
                        <div class="contact-item">
                            <svg class="contact-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                                <path d="m3 7 9 6 9-6"></path>
                            </svg>
                            <div><strong>Email</strong><br><a href="https://mail.google.com/mail/?view=cm&amp;fs=1&amp;to=ecarolas%40gmail.com" target="_blank" rel="noopener noreferrer" aria-label="Escribir a ecarolas@gmail.com en Gmail (se abre en una pestaña nueva)">ecarolas@gmail.com</a></div>
                        </div>
                        <div class="contact-item">
                            <svg class="contact-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                <path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"></path>
                                <circle cx="12" cy="10" r="2.5"></circle>
                            </svg>
                            <div><strong>Ubicación</strong><br>Valencia, España<br>Modalidad presencial y online</div>
                        </div>
                        <div class="contact-item">
                            <svg class="contact-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <text x="2" y="20" fill="currentColor" font-family="Arial, sans-serif" font-size="21" font-weight="700">in</text>
                            </svg>
                            <div><strong>LinkedIn</strong><br><a href="https://www.linkedin.com/in/emilio-casa%C3%B1-arolas-81502ab7" target="_blank" rel="noopener noreferrer" aria-label="Emilio Casañ Arolas en LinkedIn (se abre en una pestaña nueva)">Emilio Casañ Arolas ↗</a></div>
                        </div>
                    </div>
                </div>
                <form id="contact-form">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                    <p class="note">Los campos marcados con * son obligatorios.</p>
                    <div class="fields">
                        <div class="field full"><label for="nombre">Nombre y Apellidos *</label><input id="nombre" name="nombre" autocomplete="name" required maxlength="150"></div>
                        <div class="field full"><label for="email">Correo electrónico *</label><input id="email" name="email" type="email" autocomplete="email" required maxlength="200"></div>
                        <div class="field"><label for="perfil">Empresa / Particular *</label><select id="perfil" name="perfil" required>
                                <option value="">Selecciona una opción</option>
                                <option>Empresa</option>
                                <option>Particular</option>
                            </select></div>
                        <div class="field"><label for="empresa">Nombre de la empresa</label><input id="empresa" name="empresa" autocomplete="organization" maxlength="200"></div>
                        <div class="field"><label for="cargo">Cargo</label><input id="cargo" name="cargo" autocomplete="organization-title" maxlength="150"></div>
                        <div class="field"><label for="servicio">Servicio de Interés *</label><select id="servicio" name="servicio" required>
                                <option value="">Selecciona un servicio</option>
                                <option>Coaching</option>
                                <option>Formación</option>
                                <option>Datos</option>
                                <option>IA</option>
                            </select></div>
                        <div class="field full"><label for="telefono">Teléfono de contacto</label><input id="telefono" name="telefono" type="tel" autocomplete="tel" maxlength="40"></div>
                        <div class="field full"><label for="mensaje">Mensaje *</label><textarea id="mensaje" name="mensaje" rows="5" required maxlength="4000"></textarea></div>
                    </div>
                    <p class="note">Este formulario abrirá Gmail con un borrador del mensaje. Inicia sesión si te lo solicita, revísalo y envíalo desde allí. No se envía automáticamente.</p>
                    <button class="button" type="submit">Preparar correo de contacto</button>
                    <p class="note" id="form-status" role="status"></p>
                    <p id="correo-alternativo" hidden>
                        <a id="abrir-correo" href="https://mail.google.com/mail/?view=cm&amp;fs=1&amp;to=ecarolas%40gmail.com" target="_blank" rel="noopener noreferrer" aria-label="Abrir el correo preparado en Gmail (se abre en una pestaña nueva)">Abrir el correo preparado en Gmail</a>
                    </p>
                    <p class="note">
                        También puedes escribir directamente a
                        <a href="https://mail.google.com/mail/?view=cm&amp;fs=1&amp;to=ecarolas%40gmail.com" target="_blank" rel="noopener noreferrer" aria-label="Escribir a ecarolas@gmail.com en Gmail (se abre en una pestaña nueva)">ecarolas@gmail.com</a>.
                    </p>
                    <noscript>
                        <p>JavaScript está desactivado. Utiliza el enlace de correo anterior para contactar.</p>
                    </noscript>
                </form>
            </div>
        </section>
    </main>
    <footer>
        <div class="container footer-inner"><small>&copy; <?= htmlspecialchars($anioActual, ENT_QUOTES, 'UTF-8') ?> <?= htmlspecialchars($nombreApp, ENT_QUOTES, 'UTF-8') ?>.</small>
            <div><a href="#inicio">Volver al inicio</a><a href="login.php">Área privada</a></div>
        </div>
    </footer>
    <script>
            const formulario = document.getElementById('contact-form');
            const estado = document.getElementById('form-status');
            const alternativa = document.getElementById('correo-alternativo');
            const enlaceCorreo = document.getElementById('abrir-correo');

            if (formulario && estado && alternativa && enlaceCorreo) {
                formulario.addEventListener('submit', async function(event) {
                    // Impide el envío normal del formulario.
                    event.preventDefault();

                    // Comprueba los campos y muestra los errores de validación.
                    if (!this.reportValidity()) return;

                    // Recoge los datos mediante los atributos name del formulario.
                    const data = new FormData(this);
                    const botonEnviar = this.querySelector('button[type="submit"]');
                    botonEnviar.disabled = true;
                    estado.textContent = 'Guardando los datos del contacto...';
                    alternativa.hidden = true;

                    try {
                        const respuesta = await fetch('../controladores/contacto.php', {
                            method: 'POST',
                            headers: { 'Accept': 'application/json' },
                            body: data
                        });
                        const resultado = await respuesta.json();

                        if (!respuesta.ok || !resultado.success) {
                            throw new Error(resultado.message || 'No se pudieron guardar los datos del contacto.');
                        }
                    } catch (error) {
                        estado.textContent =
                            error instanceof Error
                                ? error.message
                                : 'No se pudieron guardar los datos del contacto. Inténtalo de nuevo.';
                        botonEnviar.disabled = false;
                        return;
                    }

                    // Define las etiquetas y el orden de los datos del mensaje.
                    const fields = [
                        ['nombre', 'Nombre y Apellidos'],
                        ['email', 'Correo electrónico'],
                        ['perfil', 'Empresa / Particular'],
                        ['empresa', 'Empresa'],
                        ['cargo', 'Cargo'],
                        ['servicio', 'Servicio de Interés'],
                        ['telefono', 'Teléfono de contacto'],
                        ['mensaje', 'Mensaje']
                    ];

                    // Construye el cuerpo del correo.
                    const body = fields
                        .map(([key, label]) =>
                            label + ': ' + String(data.get(key) ?? '').trim()
                        )
                        .join('\r\n');

                    // Prepara el asunto con un valor alternativo.
                    const servicio =
                        String(data.get('servicio') ?? '').trim() || 'Consulta general';

                    const subject = 'Sesión Diagnóstica Estratégica - ' + servicio;

                    // Codifica el asunto y el mensaje para incluirlos en la dirección.
                    const gmailUrl =
                        'https://mail.google.com/mail/?view=cm&fs=1&to=ecarolas%40gmail.com&su=' +
                        encodeURIComponent(subject) +
                        '&body=' +
                        encodeURIComponent(body);

                    // Habilita una alternativa para abrir manualmente el borrador en Gmail.
                    enlaceCorreo.href = gmailUrl;
                    alternativa.hidden = false;

                    // Informa sin afirmar que el mensaje se haya enviado.
                    estado.textContent =
                        'Los datos se han guardado y se ha preparado el correo. Si Gmail no se abre, ' +
                        'pulsa «Abrir el correo preparado en Gmail». ' +
                        'Revisa el borrador y envíalo desde Gmail. ' +
                        'Todavía no se ha enviado.';

                    formulario.reset();
                    botonEnviar.disabled = false;

                    // Abre Gmail directamente, sin depender de un gestor mailto del sistema.
                    window.location.assign(gmailUrl);
                });
            }
     </script>
</body>
</html>
