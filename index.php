<?php
$nombreApp = 'icare coaching, s.l.';
$anioActual = date('Y');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Coaching ejecutivo, motivación de equipos, consultoría de datos e Inteligencia Artificial con Emilio Casañ Arolas. Valencia y online.">
    <title><?= htmlspecialchars($nombreApp, ENT_QUOTES, 'UTF-8') ?> | Liderazgo, datos y personas</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
    
</head>

<body>
    <a class="skip" href="#contenido">Saltar al contenido</a>
    <!-- insertamos el menú de navegación -->
    <?php require __DIR__ . '/header.php'; ?>

    <main id="contenido">
        <section class="hero" id="inicio">
            <div class="container">
                <p class="eyebrow">Liderazgo · Datos · Personas</p>
                <h1>Motiva a tus equipos, impulsa tu liderazgo y optimiza tus datos para escalar tu negocio.</h1>
                <p class="lead">Coaching gerencial, implantación de sistemas, consultoría de datos, adopción de Inteligencia Artificial y formación motivacional de equipos. Todo respaldado por la trayectoria de un ex-Adjunto a CEO, Economista y apasionado de la tecnología.</p>
                <p class="pitch">En <strong>icare coaching </strong> unimos el coaching ejecutivo y personal con el pragmatismo del control de gestión, el análisis de datos y la transformación digital. Ayudamos a CEOs, gerentes, mandos intermedios y equipos a evolucionar hacia una cultura <em>data-driven</em>, adoptar nuevas tecnologías y trabajar con cohesión, motivación y alto rendimiento.</p>
                <div class="actions"><a class="button" href="#contacto">Agendar una Sesión Diagnóstica Estratégica</a><a class="button secondary" href="#servicios">Explorar servicios</a></div>
                <div class="tags"><span>Más de 25 años de trayectoria</span><span>Valencia y online</span><span>Empresas y profesionales</span></div>
            </div>
        </section>
        <section class="section" id="enfoque">
            <div class="container">
                <p class="eyebrow">Sobre mí · Mi enfoque</p>
                <h2>Liderazgo directivo, visión financiera, gestión humana y vanguardia tecnológica.</h2>
                <div class="intro">
                    <p>Hola, soy <strong>Emilio Casañ Arolas</strong>. A lo largo de más de 25 años de carrera profesional he liderado la dirección ejecutiva, financiera y operativa en entornos industriales competitivos, complementando la gestión empresarial con una formación continua en desarrollo humano, tecnología, ciencia de datos e Inteligencia Artificial.</p>
                    <p>Sé por experiencia que los retos reales de las organizaciones no se limitan a las finanzas o la tecnología; abarcan de manera fundamental <strong>el compromiso del equipo, la comunicación interdepartamental y la motivación diaria</strong>. Ninguna tecnología o sistema ERP triunfa si el factor humano no está alineado e inspirado.</p>
                    <p>A través de <strong>icare coaching, s.l.</strong>, pongo a tu disposición un acompañamiento integral 360°: la combinación de visión ejecutiva de alta dirección (C-Level), herramientas tecnológicas avanzadas y estrategias de motivación y liderazgo para potenciar a las personas y los resultados.</p>
                </div>
                <div class="grid section-heading">
                    <article class="card"><span class="number">01 / DATOS</span>
                        <h3>Cultura Data-Driven &amp; Eficiente</h3>
                        <p>Transformar datos dispersos en KPIs e información estratégica útil para decidir con seguridad.</p>
                    </article>
                    <article class="card"><span class="number">02 / PERSONAS</span>
                        <h3>Cohesión y Motivación del Factor Humano</h3>
                        <p>Desarrollar el potencial de los equipos reduciendo la resistencia al cambio y fomentando el compromiso.</p>
                    </article>
                    <article class="card"><span class="number">03 / TECNOLOGÍA</span>
                        <h3>Tecnología Útil y Aplicada</h3>
                        <p>Integrar IA, automatizaciones y sistemas ERP/IT alineados con los objetivos de negocio y la productividad personal.</p>
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
                    <article class="card"><span class="number">03 / DATOS</span>
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
                    <article class="card"><span class="number">04 / INNOVACIÓN</span>
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
                        <div class="accordion_bar"></div>
                        <details class="accordion_details">
                            <summary class="accordion_sumary">
                                <span class="accordion_question">
                                    <strong>Inteligencia Artificial aplicada: </strong>
                                </span>
                                <div class="accordion_icon">
                                    <span class="line-1"></span>
                                    <span class="line-2"></span>
                                </div>
                            </summary>
                        </details>
                        <article class="accordion_body">
                            <div class="accordion_inner">
                                <div class="accordion_answer">
                                  Integración de herramientas de vanguardia para ahorrar tiempo gerencial y permitir que las personas se concentren en tareas de alto valor.  
                                </div>
                            </div>
                        </article>
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
                        <div><strong>Email</strong><br><a href="mailto:ecarolas@gmail.com">ecarolas@gmail.com</a></div>
                        <div><strong>Ubicación</strong><br>Valencia, España<br>Modalidad presencial y online</div>
                        <div><strong>LinkedIn</strong><br><a href="https://www.linkedin.com/in/emilio-casa%C3%B1-arolas-81502ab7" target="_blank" rel="noopener noreferrer">Emilio Casañ Arolas ↗</a></div>
                    </div>
                </div>
                <form id="contact-form">
                    <div class="fields">
                        <div class="field full"><label for="nombre">Nombre y Apellidos *</label><input id="nombre" name="nombre" autocomplete="name" required maxlength="150"></div>
                        <div class="field full"><label for="email">Correo electrónico *</label><input id="email" name="email" type="email" autocomplete="email" required maxlength="200"></div>
                        <div class="field"><label for="perfil">Empresa / Particular *</label><select id="perfil" name="perfil" required>
                                <option value="">Selecciona una opción</option>Empresa</option>
                                <option>Particular</option>
                            </select></div>
                        <div class="field"><label for="empresa">Nombre de la empresa</label><input id="empresa" name="empresa" autocomplete="organization" maxlength="200"></div>
                        <div class="field"><label for="cargo">Cargo</label><input id="cargo" name="cargo" autocomplete="organization-title" maxlength="150"></div>
                        <div class="field"><label for="servicio">Servicio de Interés *</label><select id="servicio" name="servicio" required>
                                <option value="">Selecciona un servicio</option>Coaching</option>
                                <option>Formación</option>
                                <option>Datos</option>
                                <option>IA</option>
                            </select></div>
                        <div class="field full"><label for="telefono">Teléfono de contacto</label><input id="telefono" name="telefono" type="tel" autocomplete="tel" maxlength="40"></div>
                        <div class="field full"><label for="mensaje">Mensaje *</label><textarea id="mensaje" name="mensaje" rows="5" required maxlength="4000"></textarea></div>
                    </div>
                    <p class="note">Este formulario abrirá tu aplicación de correo con los datos introducidos. Revisa el mensaje y envíalo desde allí. No se envía automáticamente.</p><button class="button" type="submit">Preparar correo de contacto</button>
                    <p class="note" id="form-status" role="status"></p><noscript>
                        <p>Para contactar, escribe directamente a <a href="mailto:ecarolas@gmail.com">ecarolas@gmail.com</a>.</p>
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
        // Se prepara un correo; el envío lo realiza el usuario desde su aplicación de correo.
        document.getElementById('contact-form').addEventListener('submit', function(event) {
            event.preventDefault(); //Por defecto, cuando un documento se envia, el navegador recarga la pagina.
            //con este event.preventDefault() evitamos que se recargue la pagina para poder validar los datos con JS o enviarlos de forma
            //asincrona con fetch() o XMLHttpRequest. En este caso, vamos a preparar un correo para que el usuario lo envie desde su aplicación de correo.
            if (!this.reportValidity()) return; //validamos los campos del formulario, si no son validos, no se envia el correo.
            const data = new FormData(this); //Crea un objeto especial de JavaScript llamado FormData que extrae automáticamente todos los pares de clave-valor de los inputs, selects y textareas que están dentro de ese formulario (this).
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
            const body = fields.map(([key, label]) => label + ': ' + String(data.get(key) || '').trim()).join('\r\n');
            const subject = 'Sesión Diagnóstica Estratégica - ' + data.get('servicio');
            window.location.href = 'mailto:ecarolas@gmail.com?subject=' + encodeURIComponent(subject) + '&body=' + encodeURIComponent(body);
            document.getElementById('form-status').textContent = 'Si no se abre tu aplicación de correo, escribe directamente a ecarolas@gmail.com.';
        });

    </script>

    <script src="js/menu.js"></script>
</body>

</html>
