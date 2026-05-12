<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Recupera activos del fracaso · Bootcamp Reinventa tu fracaso</title>
<link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
<link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
<style>
  :root {
    --bg: #F7F5F0; --bg2: #EDEAE2; --accent: #1F5C3E; --accent-light: #2E8A5C;
    --accent-pale: #EAF3EC; --text: #1A1A16; --text-muted: #5C5C52; --text-dim: #9A9A8E;
    --border: rgba(26,26,22,0.1); --border-accent: rgba(31,92,62,0.25); --error: #B33A3A;
  }
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  html { scroll-behavior: smooth; }
  body { background: var(--bg); color: var(--text); font-family: 'Sora', sans-serif; font-weight: 300; line-height: 1.7; overflow-x: hidden; }
  nav { position: fixed; top: 0; left: 0; right: 0; z-index: 100; padding: 1.25rem 2rem; display: flex; justify-content: space-between; align-items: center; background: rgba(247,245,240,0.92); backdrop-filter: blur(12px); border-bottom: 1px solid var(--border); }
  .nav-logo { display: flex; align-items: center; }
  .nav-logo img { height: 36px; width: auto; display: block; }
  @media(max-width:520px){ .nav-logo img { height: 30px; } }
  .nav-cta { background: var(--accent); color: #fff; border: none; padding: .55rem 1.25rem; font-family: 'Sora', sans-serif; font-size: .82rem; font-weight: 600; cursor: pointer; letter-spacing: .04em; transition: background .2s; }
  .nav-cta:hover { background: var(--accent-light); }
  .hero { min-height: 100vh; display: grid; grid-template-columns: 1fr 380px; gap: 4rem; align-items: center; padding: 8rem 2rem 5rem; max-width: 1100px; margin: 0 auto; }
  @media(max-width:760px){ .hero { grid-template-columns: 1fr; } .hero-visual { display: none; } }
  .hero-tag { display: inline-flex; align-items: center; gap: .5rem; font-size: .72rem; letter-spacing: .1em; text-transform: uppercase; color: var(--accent); margin-bottom: 2rem; }
  .hero-headline { font-family: 'Libre Baskerville', serif; font-size: clamp(2rem, 4.5vw, 3.4rem); line-height: 1.2; margin-bottom: 1.5rem; }
  .hero-headline em { font-style: italic; color: var(--accent); }
  .hero-sub { font-size: .98rem; color: var(--text-muted); max-width: 520px; line-height: 1.8; margin-bottom: 2.5rem; }
  .hero-sub strong { color: var(--text); font-weight: 600; }
  .hero-actions { display: flex; gap: 1rem; align-items: center; flex-wrap: wrap; }
  .btn-primary { background: var(--accent); color: #fff; border: none; padding: .9rem 2rem; font-family: 'Sora', sans-serif; font-size: .9rem; font-weight: 600; cursor: pointer; transition: background .2s; text-decoration: none; display: inline-block; }
  .btn-primary:hover { background: var(--accent-light); }
  .btn-ghost { color: var(--text-muted); font-size: .85rem; text-decoration: none; border-bottom: 1px solid var(--border); padding-bottom: 2px; transition: color .2s; }
  .btn-ghost:hover { color: var(--text); }
  .hero-visual { background: #fff; border: 1px solid var(--border); padding: 2rem; }
  .hv-label { font-size: .7rem; letter-spacing: .1em; text-transform: uppercase; color: var(--text-dim); margin-bottom: 1.5rem; }
  .hv-asset { display: flex; justify-content: space-between; align-items: center; padding: .85rem 0; border-bottom: 1px solid var(--border); }
  .hv-asset:last-of-type { border-bottom: none; }
  .hv-asset-name { font-size: .85rem; color: var(--text-muted); }
  .hv-asset-val { font-family: 'Libre Baskerville', serif; font-size: 1rem; color: var(--accent); }
  .hv-total { background: var(--accent-pale); border: 1px solid var(--border-accent); padding: 1.25rem; margin-top: 1.5rem; display: flex; justify-content: space-between; align-items: center; }
  .hv-total-label { font-size: .8rem; color: var(--accent); font-weight: 600; }
  .hv-total-val { font-family: 'Libre Baskerville', serif; font-size: 1.4rem; color: var(--accent); }
  .hv-note { font-size: .72rem; color: var(--text-dim); margin-top: .75rem; line-height: 1.5; }
  .hero-proof { margin-top: 3.5rem; display: flex; gap: 2.5rem; flex-wrap: wrap; padding-top: 2rem; border-top: 1px solid var(--border); }
  .proof-stat .num { font-family: 'Libre Baskerville', serif; font-size: 1.9rem; color: var(--accent); }
  .proof-stat .label { font-size: .78rem; color: var(--text-muted); margin-top: .15rem; }
  section { padding: 6rem 2rem; }
  .container { max-width: 840px; margin: 0 auto; }
  .section-tag { font-size: .7rem; letter-spacing: .12em; text-transform: uppercase; color: var(--accent); margin-bottom: .85rem; }
  .section-title { font-family: 'Libre Baskerville', serif; font-size: clamp(1.7rem, 3.5vw, 2.5rem); line-height: 1.25; margin-bottom: 1.5rem; }
  .section-title em { font-style: italic; color: var(--accent); }
  .section-body { color: var(--text-muted); font-size: .97rem; line-height: 1.8; }
  .section-body strong { color: var(--text); font-weight: 600; }
  .pain { background: var(--bg2); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); }
  .pain-pull { font-family: 'Libre Baskerville', serif; font-size: 1.4rem; font-style: italic; color: var(--accent); line-height: 1.55; padding: 2rem 0 2rem 2rem; border-left: 3px solid var(--accent); margin: 2rem 0; }
  .data-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1px; margin-top: 2.5rem; border: 1px solid var(--border); background: var(--border); }
  .data-cell { background: var(--bg); padding: 1.5rem; }
  .data-num { font-family: 'Libre Baskerville', serif; font-size: 2rem; color: var(--accent); margin-bottom: .25rem; }
  .data-label { font-size: .82rem; color: var(--text-muted); line-height: 1.5; }
  .sessions { background: var(--bg); }
  .session-grid { margin-top: 3rem; display: grid; gap: 0; border: 1px solid var(--border); }
  .session-item { display: grid; grid-template-columns: 80px 1fr; align-items: start; border-bottom: 1px solid var(--border); transition: background .2s; }
  .session-item:last-child { border-bottom: none; }
  .session-item:hover { background: var(--bg2); }
  .session-num-col { background: var(--accent-pale); display: flex; align-items: center; justify-content: center; padding: 1.75rem .5rem; border-right: 1px solid var(--border-accent); }
  .session-num { font-family: 'Libre Baskerville', serif; font-size: 1.6rem; color: var(--accent); }
  .session-content { padding: 1.5rem 1.75rem; }
  .session-title { font-size: .95rem; font-weight: 600; margin-bottom: .4rem; }
  .session-desc { font-size: .88rem; color: var(--text-muted); line-height: 1.65; }
  .session-deliverable { display: inline-flex; align-items: center; gap: .4rem; margin-top: .75rem; font-size: .72rem; color: var(--accent); letter-spacing: .06em; text-transform: uppercase; font-weight: 600; background: var(--accent-pale); padding: .3rem .75rem; }
  .testimonial-section { background: var(--accent); padding: 5rem 2rem; }
  .testimonial-card { max-width: 700px; margin: 0 auto; text-align: center; padding: 1rem 0; }
  .testimonial-text { font-family: 'Libre Baskerville', serif; font-style: italic; font-size: 1.2rem; color: rgba(255,255,255,0.95); line-height: 1.75; margin-bottom: 1.5rem; }
  .testimonial-author { font-size: .82rem; color: rgba(255,255,255,0.6); }
  .testimonial-author strong { color: rgba(255,255,255,0.9); font-weight: 600; }
  .for-who { background: var(--bg2); }
  .who-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 2.5rem; }
  @media(max-width:600px){ .who-grid { grid-template-columns: 1fr; } }
  .who-col { background: #fff; border: 1px solid var(--border); padding: 2rem; }
  .who-label { font-size: .7rem; letter-spacing: .1em; text-transform: uppercase; margin-bottom: 1.25rem; font-weight: 600; }
  .who-label.yes { color: var(--accent); }
  .who-label.no { color: var(--text-dim); }
  .who-list { list-style: none; display: grid; gap: .65rem; }
  .who-list li { font-size: .88rem; color: var(--text-muted); display: flex; gap: .75rem; line-height: 1.5; }
  .mark.yes { color: var(--accent); font-weight: 600; flex-shrink: 0; }
  .mark.no { color: var(--text-dim); flex-shrink: 0; }
  .faq { background: var(--bg); border-top: 1px solid var(--border); }
  .faq-item { border-bottom: 1px solid var(--border); }
  .faq-item:last-child { border-bottom: none; }
  .faq-q { width: 100%; background: none; border: none; text-align: left; padding: 1.5rem 0; font-family: 'Sora', sans-serif; font-size: .92rem; font-weight: 600; cursor: pointer; display: flex; justify-content: space-between; align-items: center; gap: 1rem; }
  .faq-q .icon { color: var(--accent); flex-shrink: 0; transition: transform .25s; }
  .faq-q.open .icon { transform: rotate(45deg); }
  .faq-a { max-height: 0; overflow: hidden; transition: max-height .35s ease, padding .25s; }
  .faq-a.open { max-height: 300px; padding-bottom: 1.5rem; }
  .faq-a p { font-size: .88rem; color: var(--text-muted); line-height: 1.8; }
  .cta-final { background: var(--bg2); border-top: 1px solid var(--border); text-align: center; padding: 6rem 2rem; }
  .cta-box { background: #fff; border: 1px solid var(--border); padding: 4rem 3rem; max-width: 580px; margin: 0 auto; }
  .cta-title { font-family: 'Libre Baskerville', serif; font-size: 1.9rem; margin-bottom: 1rem; }
  .cta-sub { color: var(--text-muted); font-size: .92rem; line-height: 1.75; margin-bottom: 2rem; }
  .cta-note { font-size: .78rem; color: var(--text-dim); margin-top: 1rem; }
  .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(26,26,22,0.85); z-index: 200; overflow-y: auto; -webkit-overflow-scrolling: touch; padding: 2rem 1rem; }
  .modal-overlay.open { display: flex; }
  .modal { background: #fff; border: 1px solid var(--border); max-width: 520px; width: 100%; padding: 2.5rem; position: relative; margin: auto; animation: slideUp .3s ease; }
  @media(max-width:520px){ .modal-overlay { padding: 1rem .5rem; } .modal { padding: 2rem 1.5rem; } }
  @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
  .modal-close { position: absolute; top: 1.25rem; right: 1.5rem; background: none; border: none; color: var(--text-muted); font-size: 1.1rem; cursor: pointer; z-index: 2; }
  .modal-tag { font-size: .7rem; letter-spacing: .1em; text-transform: uppercase; color: var(--accent); margin-bottom: 1.25rem; font-weight: 600; padding-right: 2rem; }
  .modal-title { font-family: 'Libre Baskerville', serif; font-size: 1.5rem; margin-bottom: .75rem; }
  .modal-desc { font-size: .88rem; color: var(--text-muted); line-height: 1.7; margin-bottom: 1.75rem; }
  .price-options { display: grid; gap: .65rem; margin-bottom: 1.75rem; }
  .price-opt { padding: 1rem 1.25rem; border: 1px solid var(--border); cursor: pointer; transition: all .2s; display: flex; justify-content: space-between; align-items: center; gap: 1rem; }
  .price-opt:hover, .price-opt.selected { border-color: var(--accent); background: var(--accent-pale); }
  .price-opt-name { font-size: .88rem; font-weight: 600; }
  .price-opt-desc { font-size: .76rem; color: var(--text-muted); margin-top: 2px; }
  .price-opt-val { font-family: 'Libre Baskerville', serif; font-size: 1.05rem; color: var(--accent); white-space: nowrap; }
  .modal-form { display: grid; gap: .65rem; }
  .modal-input { width: 100%; background: var(--bg); border: 1px solid var(--border); padding: .8rem 1rem; font-family: 'Sora', sans-serif; font-size: .88rem; outline: none; transition: border-color .2s; }
  .modal-input:focus { border-color: var(--accent); }
  .modal-input.error { border-color: var(--error); }
  .modal-input::placeholder { color: var(--text-dim); }
  .modal-submit { background: var(--accent); color: #fff; border: none; padding: .9rem; font-family: 'Sora', sans-serif; font-size: .9rem; font-weight: 600; cursor: pointer; width: 100%; transition: background .2s; }
  .modal-submit:hover { background: var(--accent-light); }
  .modal-submit:disabled { background: var(--text-dim); cursor: not-allowed; }
  .modal-fine { font-size: .73rem; color: var(--text-dim); text-align: center; margin-top: .75rem; line-height: 1.6; }
  .modal-feedback { padding: .75rem 1rem; font-size: .82rem; margin-bottom: 1rem; display: none; }
  .modal-feedback.error { display: block; background: rgba(179,58,58,0.08); border: 1px solid var(--error); color: var(--error); }
  .modal-success-state { display: none; text-align: center; padding: 2rem 0; }
  .modal-success-state.show { display: block; }
  .modal-success-icon { font-size: 2.5rem; color: var(--accent); margin-bottom: 1rem; }
  .modal-success-title { font-family: 'Libre Baskerville', serif; font-size: 1.4rem; margin-bottom: .75rem; }
  .modal-success-text { font-size: .92rem; color: var(--text-muted); line-height: 1.75; }
  footer { padding: 2rem; border-top: 1px solid var(--border); display: flex; justify-content: space-between; flex-wrap: wrap; gap: 1rem; background: var(--bg); }
  .footer-logo { font-family: 'Libre Baskerville', serif; font-size: .9rem; color: var(--accent); }
  .footer-text { font-size: .78rem; color: var(--text-dim); }
  .reveal { opacity: 0; transform: translateY(20px); transition: opacity .55s ease, transform .55s ease; }
  .reveal.visible { opacity: 1; transform: translateY(0); }
  body.modal-locked { overflow: hidden; }
</style>
</head>
<body>

<nav>
  <a href="#" class="nav-logo" aria-label="Reinventa"><img src="{{ asset('logo.png') }}" alt="Reinventa"></a>
  <button class="nav-cta" onclick="openModal()">Calcular mis activos</button>
</nav>

<section class="hero">
  <div>
    <div class="hero-tag">Bootcamp · 5 sesiones · Metodología Reinventa</div>
    <h1 class="hero-headline">Tu fracaso dejó activos<br>sobre la mesa.<br><em>Aprende a recuperarlos.</em></h1>
    <p class="hero-sub">Cada empresa que cierra deja contactos, datos de mercado, relaciones, código y procesos que valen dinero. <strong>El problema no es que perdiste: es que no sabes lo que tienes.</strong></p>
    <div class="hero-actions">
      <button class="btn-primary" onclick="openModal()">Ver mis opciones de acceso</button>
      <a href="#programa" class="btn-ghost">Ver el programa completo</a>
    </div>
    <div class="hero-proof">
      <div class="proof-stat"><div class="num">5</div><div class="label">Sesiones con entregables concretos</div></div>
      <div class="proof-stat"><div class="num">18-36</div><div class="label">Meses de inteligencia generada</div></div>
      <div class="proof-stat"><div class="num">3</div><div class="label">Rutas de monetización al finalizar</div></div>
    </div>
  </div>
  <div class="hero-visual">
    <div class="hv-label">Balance de activos típicos de un fracaso no auditado</div>
    <div class="hv-asset"><span class="hv-asset-name">Base de datos de clientes</span><span class="hv-asset-val">Activo</span></div>
    <div class="hv-asset"><span class="hv-asset-name">Inteligencia de mercado</span><span class="hv-asset-val">Activo</span></div>
    <div class="hv-asset"><span class="hv-asset-name">Red de proveedores</span><span class="hv-asset-val">Activo</span></div>
    <div class="hv-asset"><span class="hv-asset-name">Código y productos</span><span class="hv-asset-val">Activo</span></div>
    <div class="hv-asset"><span class="hv-asset-name">Errores de pricing pagados</span><span class="hv-asset-val">Activo</span></div>
    <div class="hv-asset"><span class="hv-asset-name">Patrones de cliente</span><span class="hv-asset-val">Activo</span></div>
    <div class="hv-total"><span class="hv-total-label">Total no auditado por el 91% de founders</span><span class="hv-total-val">Incalculable</span></div>
    <div class="hv-note">La mayoría de founders cierra creyendo que perdió todo. Este bootcamp demuestra que casi nunca es así.</div>
  </div>
</section>

<section class="pain">
  <div class="container">
    <div class="section-tag reveal">El problema real</div>
    <h2 class="section-title reveal">Invertiste tiempo, dinero y energía.<br>¿Qué queda de <em>todo eso?</em></h2>
    <div class="pain-pull reveal">Un fracaso empresarial genera entre 18 y 36 meses de inteligencia de mercado que ningún competidor tiene. El problema es que nadie te enseña a leerla.</div>
    <p class="section-body reveal">Los founders que cierran su empresa se enfocan en lo que se fue. Y en ese enfoque pierden lo más valioso: todo lo que aprendieron del mercado, los clientes y la operación. <strong>Ese conocimiento no desapareció. Solo cambió de forma.</strong></p>
    <div class="data-row reveal">
      <div class="data-cell"><div class="data-num">91%</div><div class="data-label">de founders no audita los activos de su empresa al cerrarla</div></div>
      <div class="data-cell"><div class="data-num">3x</div><div class="data-label">más rápido escala el segundo proyecto cuando usa la inteligencia del primero</div></div>
      <div class="data-cell"><div class="data-num">$0</div><div class="data-label">es lo que la mayoría cree que tiene. Casi siempre hay activos recuperables.</div></div>
    </div>
  </div>
</section>

<section class="sessions" id="programa">
  <div class="container">
    <div class="section-tag reveal">El programa</div>
    <h2 class="section-title reveal">Cinco sesiones para auditar,<br>valorar y <em>activar</em> lo que dejó.</h2>
    <p class="section-body reveal">Cada sesión produce un entregable concreto. No motivación: herramientas, análisis y un plan real.</p>
    <div class="session-grid reveal">
      <div class="session-item"><div class="session-num-col"><div class="session-num">01</div></div><div class="session-content"><div class="session-title">Autopsia financiera sin culpa</div><div class="session-desc">Diseccionar el P&L del fracaso. Separar causas reales de percibidas. Mapear dónde fue el dinero y por qué.</div><div class="session-deliverable">Diagnóstico financiero estructurado</div></div></div>
      <div class="session-item"><div class="session-num-col"><div class="session-num">02</div></div><div class="session-content"><div class="session-title">El inventario de activos ocultos</div><div class="session-desc">Auditar contactos, datos, código, procesos y relaciones con valor económico real. Ponerle número a lo que quedó.</div><div class="session-deliverable">Balance de activos valorado</div></div></div>
      <div class="session-item"><div class="session-num-col"><div class="session-num">03</div></div><div class="session-content"><div class="session-title">Monetización acelerada</div><div class="session-desc">Diseñar 3 rutas concretas para activar esos activos en los próximos 90 días: consultoría, alianza o nuevo MVP.</div><div class="session-deliverable">3 opciones con proyección de ingresos</div></div></div>
      <div class="session-item"><div class="session-num-col"><div class="session-num">04</div></div><div class="session-content"><div class="session-title">El pitch del aprendizaje</div><div class="session-desc">Presentar el fracaso a inversores como evidencia de due diligence acelerado, no como señal de riesgo.</div><div class="session-deliverable">Deck de 5 slides para capital</div></div></div>
      <div class="session-item"><div class="session-num-col"><div class="session-num">05</div></div><div class="session-content"><div class="session-title">Modelo de ingresos del siguiente proyecto</div><div class="session-desc">Diseñar el revenue model del nuevo proyecto incorporando lecciones reales de pricing, canal y cliente.</div><div class="session-deliverable">Revenue model canvas aplicado</div></div></div>
    </div>
  </div>
</section>

<section class="testimonial-section">
  <div class="testimonial-card">
    <p class="testimonial-text">"En la sesión 3 identifiqué rutas de monetización usando exactamente lo que había aprendido de mis errores de pricing. El bootcamp se pagó en la primera conversación que tuve con un ex-cliente."</p>
    <div class="testimonial-author"><strong>Perfil egresado · Director de producto</strong> · Monterrey, NL</div>
  </div>
</section>

<section class="for-who">
  <div class="container">
    <div class="section-tag reveal">¿Es para ti?</div>
    <h2 class="section-title reveal">Para quien quiere recuperar,<br>no solo <em>sanar.</em></h2>
    <div class="who-grid reveal">
      <div class="who-col">
        <div class="who-label yes">Sí es para ti si...</div>
        <ul class="who-list">
          <li><span class="mark yes">+</span>Cerraste un proyecto en los últimos 3 años</li>
          <li><span class="mark yes">+</span>Tienes perfil analítico y quieres datos, no motivación</li>
          <li><span class="mark yes">+</span>Necesitas un pitch creíble del fracaso para inversores</li>
          <li><span class="mark yes">+</span>Quieres lanzar el siguiente proyecto sobre activos reales</li>
          <li><span class="mark yes">+</span>Buscas rutas de monetización concretas en 90 días</li>
        </ul>
      </div>
      <div class="who-col">
        <div class="who-label no">No es para ti si...</div>
        <ul class="who-list">
          <li><span class="mark no">-</span>Buscas un programa de autoestima emocional</li>
          <li><span class="mark no">-</span>No has operado un proyecto empresarial propio</li>
          <li><span class="mark no">-</span>Solo quieres financiamiento, no metodología</li>
          <li><span class="mark no">-</span>No estás dispuesto al trabajo de auditoría</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<section class="faq">
  <div class="container">
    <div class="section-tag reveal">Preguntas frecuentes</div>
    <h2 class="section-title reveal">Lo que necesitas saber<br>antes de <em>decidir.</em></h2>
    <div class="reveal">
      <div class="faq-item"><button class="faq-q" onclick="toggleFaq(this)">Ya hice el análisis de qué salió mal. ¿En qué es diferente? <span class="icon">+</span></button><div class="faq-a"><p>Diagnosticar solo vs. con metodología produce resultados distintos. Este programa convierte ese análisis en rutas de monetización concretas y un pitch que funciona.</p></div></div>
      <div class="faq-item"><button class="faq-q" onclick="toggleFaq(this)">Perdí todo cuando cerré. ¿Qué activos voy a encontrar? <span class="icon">+</span></button><div class="faq-a"><p>Esa es la creencia que la sesión 2 desmonta. En 12 meses de operación hay al menos 5 activos recuperables: contactos, patrones, errores de pricing pagados, procesos y relaciones.</p></div></div>
      <div class="faq-item"><button class="faq-q" onclick="toggleFaq(this)">¿Cuánto tiempo después del cierre se puede participar? <span class="icon">+</span></button><div class="faq-a"><p>El rango óptimo es entre 6 meses y 2 años del cierre.</p></div></div>
      <div class="faq-item"><button class="faq-q" onclick="toggleFaq(this)">¿Me garantiza recuperar dinero? <span class="icon">+</span></button><div class="faq-a"><p>Te garantizamos un mapa de activos valorado y tres rutas de monetización concretas. La ejecución depende de ti.</p></div></div>
    </div>
  </div>
</section>

<section class="cta-final">
  <div class="cta-box reveal">
    <div class="section-tag">Próxima cohorte · Lugares limitados</div>
    <h2 class="cta-title">¿Listo para ver tus opciones de acceso?</h2>
    <p class="cta-sub">El programa tiene tres niveles de acompañamiento según el resultado que buscas.</p>
    <button class="btn-primary" onclick="openModal()">Ver opciones y reservar mi lugar</button>
    <p class="cta-note">Sin compromiso de pago en este paso. Un asesor te contacta en 24 hrs.</p>
  </div>
</section>

<div class="modal-overlay" id="modal" role="dialog" aria-modal="true">
  <div class="modal">
    <button class="modal-close" onclick="closeModal()" aria-label="Cerrar">✕</button>
    <div id="modal-form-state">
      <div class="modal-tag">Paso de confirmación · Bootcamp Reinventa</div>
      <h3 class="modal-title">Elige tu nivel de acompañamiento</h3>
      <p class="modal-desc">Los tres niveles acceden al mismo programa de 5 sesiones. La diferencia está en el trabajo personalizado y el acceso al ecosistema.</p>
      <div class="price-options">
        <div class="price-opt selected" data-plan="grupal" onclick="selectPrice(this)">
          <div><div class="price-opt-name">Acceso · Grupal</div><div class="price-opt-desc">5 sesiones + entregables + comunidad 30 días</div></div>
          <div class="price-opt-val">$4,500 MXN</div>
        </div>
        <div class="price-opt" data-plan="transformacion" onclick="selectPrice(this)">
          <div><div class="price-opt-name">Transformación · Con acompañamiento</div><div class="price-opt-desc">+ 2 sesiones 1:1 + diagnóstico + comunidad 90 días</div></div>
          <div class="price-opt-val">$9,500 MXN</div>
        </div>
        <div class="price-opt" data-plan="relanzamiento" onclick="selectPrice(this)">
          <div><div class="price-opt-name">Relanzamiento · Con red</div><div class="price-opt-desc">+ 3 meses de seguimiento + acceso al ecosistema</div></div>
          <div class="price-opt-val">$18,000 MXN</div>
        </div>
      </div>
      <div id="modal-feedback" class="modal-feedback"></div>
      <form class="modal-form" id="lead-form" novalidate>
        <input class="modal-input" type="text" name="name" placeholder="Tu nombre completo" required>
        <input class="modal-input" type="email" name="email" placeholder="Correo electrónico" required>
        <input class="modal-input" type="tel" name="phone" placeholder="WhatsApp (para confirmar tu lugar)" required>
        <button type="submit" class="modal-submit" id="lead-submit">Reservar mi lugar → te contactamos en 24 hrs</button>
      </form>
      <p class="modal-fine">No se realiza ningún cobro en este paso. Un asesor del equipo Reinventa te contacta antes de cualquier pago.</p>
    </div>
    <div class="modal-success-state" id="modal-success-state">
      <div class="modal-success-icon">✓</div>
      <h3 class="modal-success-title">Tu lugar está reservado</h3>
      <p class="modal-success-text">Un asesor del equipo Reinventa te contactará por WhatsApp en las próximas 24 horas.</p>
    </div>
  </div>
</div>

<footer>
  <div class="footer-logo">Reinventa · Ecosistema</div>
  <div class="footer-text">Bootcamp "Reinventa tu fracaso"</div>
</footer>

<script>
const LEAD_ENDPOINT = "{{ route('leads.store') }}";
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let selectedPlan = 'grupal';

function openModal() {
  document.getElementById('modal').classList.add('open');
  document.body.classList.add('modal-locked');
  document.getElementById('modal-feedback').className = 'modal-feedback';
  document.getElementById('modal-feedback').textContent = '';
}
function closeModal() {
  document.getElementById('modal').classList.remove('open');
  document.body.classList.remove('modal-locked');
}
document.getElementById('modal').addEventListener('click', function(e) {
  if(e.target === this) closeModal();
});
document.addEventListener('keydown', function(e) {
  if(e.key === 'Escape') closeModal();
});
function selectPrice(el) {
  document.querySelectorAll('.price-opt').forEach(o => o.classList.remove('selected'));
  el.classList.add('selected');
  selectedPlan = el.dataset.plan;
}
function toggleFaq(btn) {
  const ans = btn.nextElementSibling;
  const isOpen = ans.classList.contains('open');
  document.querySelectorAll('.faq-a').forEach(a => a.classList.remove('open'));
  document.querySelectorAll('.faq-q').forEach(q => q.classList.remove('open'));
  if (!isOpen) { ans.classList.add('open'); btn.classList.add('open'); }
}
const observer = new IntersectionObserver((entries) => {
  entries.forEach(e => { if(e.isIntersecting) e.target.classList.add('visible'); });
}, { threshold: 0.1 });
document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

document.getElementById('lead-form').addEventListener('submit', async function(e) {
  e.preventDefault();
  const form = e.target;
  const submitBtn = document.getElementById('lead-submit');
  const feedback = document.getElementById('modal-feedback');
  feedback.className = 'modal-feedback';
  feedback.textContent = '';
  form.querySelectorAll('.modal-input').forEach(i => i.classList.remove('error'));

  const payload = {
    name: form.name.value.trim(),
    email: form.email.value.trim(),
    phone: form.phone.value.trim(),
    plan: selectedPlan,
  };

  if (!payload.name || !payload.email || !payload.phone) {
    feedback.className = 'modal-feedback error';
    feedback.textContent = 'Por favor completa todos los campos.';
    return;
  }

  submitBtn.disabled = true;
  submitBtn.textContent = 'Enviando...';

  try {
    const response = await fetch(LEAD_ENDPOINT, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': CSRF_TOKEN,
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify(payload),
    });
    const data = await response.json();
    if (response.ok && data.success) {
      document.getElementById('modal-form-state').style.display = 'none';
      document.getElementById('modal-success-state').classList.add('show');
    } else if (response.status === 422 && data.errors) {
      const firstError = Object.values(data.errors)[0][0];
      feedback.className = 'modal-feedback error';
      feedback.textContent = firstError;
      Object.keys(data.errors).forEach(field => {
        const input = form.querySelector('[name="' + field + '"]');
        if (input) input.classList.add('error');
      });
    } else {
      throw new Error(data.message || 'Error');
    }
  } catch (err) {
    feedback.className = 'modal-feedback error';
    feedback.textContent = 'Hubo un problema al enviar. Intenta de nuevo.';
    console.error(err);
  } finally {
    submitBtn.disabled = false;
    submitBtn.textContent = 'Reservar mi lugar → te contactamos en 24 hrs';
  }
});
</script>
</body>
</html>
