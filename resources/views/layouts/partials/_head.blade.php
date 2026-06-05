<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="{{ asset('assets/img/favicon.png')}}" type="image/png">
<meta name="csrf-token" content="{{ csrf_token()}}">

<title>{{config('app.name', 'OSFEM')}}</title>
<link rel="dns-prefetch" href="//fonts.gstatic.com">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700"/>

<!-- /jquery -->
<script src="{{asset('vendor/jquery/jquery-3.6.3.min.js')}}"></script>
<script src="{{asset('vendor/jquery-ui/jquery-ui.js')}}"></script>
<!-- script src="{ { asset('vendor/locales/es.js') }}"></!-- -->
<!-- /jquery -->

<!-- metronic -->
<link href="{{asset('vendor/metronic/plugins/custom/fullcalendar/fullcalendar.bundle.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('vendor/metronic/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('vendor/metronic/css/style.bundle.css')}}" rel="stylesheet" type="text/css"/>
<!-- /metronic -->

<!--FontAwesome-->
<link href="{{asset('assets/icons/fontawesome/css/fontawesome.css')}}" rel="stylesheet">
<link href="{{asset('assets/icons/fontawesome/css/brands.css')}}" rel="stylesheet">
<link href="{{asset('assets/icons/fontawesome/css/solid.css')}}" rel="stylesheet">
<!--/FontAwesome-->


<!-- custom css -->
<link href="{{asset('assets/css/custom.css')}}" rel="stylesheet" type="text/css"/>
<link  href="{{ asset('vendor/bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet" type="text/css">

<link href="{{asset('vendor/icheck/skins/all.css?v=1.0.3')}}" rel="stylesheet">
<!-- /custom css -->

<link href="{{ asset('vendor/jquery-colorbox/archivos/colorbox.css') }}" rel="stylesheet">

<script src="{{ asset('assets/js/cierre.js') }}"></script>

@if (auth()->check())
    <meta name="api-token" content="{{auth()->user()->api_token}}">
@endif


<style type="text/css">
   [data-title]:hover:after {
    opacity: 1;
    transition: all 0.1s ease 0.5s;
    visibility: visible;
}
[data-title]:after {
    content: attr(data-title);
    background-color: #A13B71;
    color: #fff;
    font-size: 14px;
    font-family: Raleway;
    position: absolute;
    padding: 3px 20px;
    bottom: -1.6em;
    left: 100%;
    white-space: nowrap;
    box-shadow: 1px 1px 3px #ffffff00;
    opacity: 0;
    z-index: 99999;
    visibility: hidden;
    border-radius: 6px;

}
[data-title] {
    position: relative;
}


  /* Bot�n de esquinas con animaci�n (versi�n para <a>) */
  a.corner-button {
    /* Variables fluidas (en em para escalar con el font-size del bot�n) */
    --cb-color: #A13B71;          /* texto y borde (currentColor) */
    --cb-font-size: 1.2rem;         /* cambia esto para hacerlo m�s grande/peque�o */
    --cb-border: 0.22em;          /* grosor de borde relativo al font-size */
    --cb-pad-y: 0.5em;
    --cb-pad-x: 0.9em;
    --cb-gap: 0.45em;             /* hueco entre paneles */
    --cb-over: 0.35em;            /* leve superposici�n para cubrir borde */
    --cb-anim: 0.25s;
    --cb-bg: #fff;                /* �paneles� de la animaci�n (fondo) */
    --cb-focus: 2px;              /* grosor del focus ring */

    font-family: var(--cb-font);
    font-size: var(--cb-font-size);
    line-height: 1;               /* evita crecimiento vertical irregular */
    color: var(--cb-color);
    text-decoration: none;        /* estilo �bot�n� */
    display: inline-flex;         /* contenido fluido */
    align-items: center;
    gap: 0.4em;

    cursor: pointer;
    background: transparent;
    border: var(--cb-border) solid currentColor;
    padding: var(--cb-pad-y) var(--cb-pad-x);
    position: relative;
    transition: color var(--cb-anim), border-width var(--cb-anim);
    -webkit-tap-highlight-color: transparent;
  }

  /* Contenido interior: protegido por z-index sobre los paneles */
  a.corner-button > .cb-content {
    position: relative;
    z-index: 2;
    display: inline-flex;
    align-items: center;
    gap: 0.35em; /* espacio entre icono y texto */
  }

  /* Paneles que se �cierran� (100% fluidos, basados en em) */
  a.corner-button::before,
  a.corner-button::after {
    content: '';
    position: absolute;
    background: var(--cb-bg);
    z-index: 1;
    transition: all var(--cb-anim);
    pointer-events: none; /* no bloquea clicks */
  }

  /* Panel horizontal (cubriendo arriba/abajo dejando hueco vertical) */
  a.corner-button::before {
    width: calc(100% - (var(--cb-gap) * 2));
    height: calc(100% + var(--cb-over));
    top: calc(-1 * var(--cb-over) / 2);
    left: 50%;
    transform: translateX(-50%);
  }

  /* Panel vertical (cubriendo lados dejando hueco horizontal) */
  a.corner-button::after {
    height: calc(100% - (var(--cb-gap) * 2));
    width: calc(100% + var(--cb-over));
    left: calc(-1 * var(--cb-over) / 2);
    top: 50%;
    transform: translateY(-50%);
  }

  /* Interacci�n */
  a.corner-button:hover {
    color: var(--cb-color);
  }
  a.corner-button:hover::before { width: 0; }
  a.corner-button:hover::after  { height: 0; }

  /* Active: reduce ligeramente el borde (fluido) */
  a.corner-button:active {
    border-width: calc(var(--cb-border) * 0.8);
  }

  /* Enfoque accesible (teclado) */
  a.corner-button:focus-visible {
    outline: var(--cb-focus) solid currentColor;
    outline-offset: 3px;
    border-radius: 2px; /* leve para diferenciar el outline */
  }

  a.corner-button--sm {
    --cb-font-size: 2rem;
    --cb-border: 0.2em;
    --cb-pad-y: 0.35em;
    --cb-pad-x: 0.6em;
    --cb-gap: 0.4em;
    --cb-over: 0.3em;
  }

  /* Solo �cono (sin fijar width/height ? no se deforma; usa line-height y padding) */
  a.corner-button--icon {
    --cb-font-size: 2rem; /* tama�o base del icono */
    --cb-border: 0.2em;
    --cb-pad-y: 0.35em;
    --cb-pad-x: 0.35em;
    --cb-gap: 0.38em;
    --cb-over: 0.28em;
  }

  /* Reducir movimiento si el usuario lo prefiere */
  @media (prefers-reduced-motion: reduce) {
    a.corner-button,
    a.corner-button::before,
    a.corner-button::after {
      transition: none;
    }
  }
  /******* PARA BOTON DE SUCESS *******/
    /* Bot�n de esquinas con animaci�n (versi�n para <a>) */
  a.corner-button-success {
    /* Variables fluidas (en em para escalar con el font-size del bot�n) */
    --cb-color: #50cd89;          /* texto y borde (currentColor) */
    --cb-font-size: 1.2rem;         /* cambia esto para hacerlo m�s grande/peque�o */
    --cb-border: 0.22em;          /* grosor de borde relativo al font-size */
    --cb-pad-y: 0.5em;
    --cb-pad-x: 0.9em;
    --cb-gap: 0.45em;             /* hueco entre paneles */
    --cb-over: 0.35em;            /* leve superposici�n para cubrir borde */
    --cb-anim: 0.25s;
    --cb-bg: #fff;                /* �paneles� de la animaci�n (fondo) */
    --cb-focus: 2px;              /* grosor del focus ring */

    font-family: var(--cb-font);
    font-size: var(--cb-font-size);
    line-height: 1;               /* evita crecimiento vertical irregular */
    color: var(--cb-color);
    text-decoration: none;        /* estilo �bot�n� */
    display: inline-flex;         /* contenido fluido */
    align-items: center;
    gap: 0.4em;

    cursor: pointer;
    background: transparent;
    border: var(--cb-border) solid currentColor;
    padding: var(--cb-pad-y) var(--cb-pad-x);
    position: relative;
    transition: color var(--cb-anim), border-width var(--cb-anim);
    -webkit-tap-highlight-color: transparent;
  }

  /* Contenido interior: protegido por z-index sobre los paneles */
  a.corner-button-success > .cb-content {
    position: relative;
    z-index: 2;
    display: inline-flex;
    align-items: center;
    gap: 0.35em; /* espacio entre icono y texto */
  }

  /* Paneles que se �cierran� (100% fluidos, basados en em) */
  a.corner-button-success::before,
  a.corner-button-success::after {
    content: '';
    position: absolute;
    background: var(--cb-bg);
    z-index: 1;
    transition: all var(--cb-anim);
    pointer-events: none; /* no bloquea clicks */
  }

  /* Panel horizontal (cubriendo arriba/abajo dejando hueco vertical) */
  a.corner-button-success::before {
    width: calc(100% - (var(--cb-gap) * 2));
    height: calc(100% + var(--cb-over));
    top: calc(-1 * var(--cb-over) / 2);
    left: 50%;
    transform: translateX(-50%);
  }

  /* Panel vertical (cubriendo lados dejando hueco horizontal) */
  a.corner-button-success::after {
    height: calc(100% - (var(--cb-gap) * 2));
    width: calc(100% + var(--cb-over));
    left: calc(-1 * var(--cb-over) / 2);
    top: 50%;
    transform: translateY(-50%);
  }

  /* Interacci�n */
  a.corner-button-success:hover {
    color: var(--cb-color);
  }
  a.corner-button-success:hover::before { width: 0; }
  a.corner-button-success:hover::after  { height: 0; }

  /* Active: reduce ligeramente el borde (fluido) */
  a.corner-button-success:active {
    border-width: calc(var(--cb-border) * 0.8);
  }

  /* Enfoque accesible (teclado) */
  a.corner-button-success:focus-visible {
    outline: var(--cb-focus) solid currentColor;
    outline-offset: 3px;
    border-radius: 2px; /* leve para diferenciar el outline */
  }

  a.corner-button-success--sm {
    --cb-font-size: 2rem;
    --cb-border: 0.2em;
    --cb-pad-y: 0.35em;
    --cb-pad-x: 0.6em;
    --cb-gap: 0.4em;
    --cb-over: 0.3em;
  }

  /* Solo �cono (sin fijar width/height ? no se deforma; usa line-height y padding) */
  a.corner-button-success--icon {
    --cb-font-size: 2rem; /* tama�o base del icono */
    --cb-border: 0.2em;
    --cb-pad-y: 0.35em;
    --cb-pad-x: 0.35em;
    --cb-gap: 0.38em;
    --cb-over: 0.28em;
  }

  /* Reducir movimiento si el usuario lo prefiere */
  @media (prefers-reduced-motion: reduce) {
    a.corner-button-success,
    a.corner-button-success::before,
    a.corner-button-success::after {
      transition: none;
    }
  }
  a.corner-button-success2 {
    /* Variables fluidas (en em para escalar con el font-size del bot�n) */
    --cb-color: #c6d4b0;          /* texto y borde (currentColor) */
    --cb-font-size: 1.2rem;         /* cambia esto para hacerlo m�s grande/peque�o */
    --cb-border: 0.22em;          /* grosor de borde relativo al font-size */
    --cb-pad-y: 0.5em;
    --cb-pad-x: 0.9em;
    --cb-gap: 0.45em;             /* hueco entre paneles */
    --cb-over: 0.35em;            /* leve superposici�n para cubrir borde */
    --cb-anim: 0.25s;
    --cb-bg: #fff;                /* �paneles� de la animaci�n (fondo) */
    --cb-focus: 2px;              /* grosor del focus ring */

    font-family: var(--cb-font);
    font-size: var(--cb-font-size);
    line-height: 1;               /* evita crecimiento vertical irregular */
    color: var(--cb-color);
    text-decoration: none;        /* estilo �bot�n� */
    display: inline-flex;         /* contenido fluido */
    align-items: center;
    gap: 0.4em;

    cursor: pointer;
    background: transparent;
    border: var(--cb-border) solid currentColor;
    padding: var(--cb-pad-y) var(--cb-pad-x);
    position: relative;
    transition: color var(--cb-anim), border-width var(--cb-anim);
    -webkit-tap-highlight-color: transparent;
  }

  /* Contenido interior: protegido por z-index sobre los paneles */
  a.corner-button-success2 > .cb-content {
    position: relative;
    z-index: 2;
    display: inline-flex;
    align-items: center;
    gap: 0.35em; /* espacio entre icono y texto */
  }

  /* Paneles que se �cierran� (100% fluidos, basados en em) */
  a.corner-button-success2::before,
  a.corner-button-success2::after {
    content: '';
    position: absolute;
    background: var(--cb-bg);
    z-index: 1;
    transition: all var(--cb-anim);
    pointer-events: none; /* no bloquea clicks */
  }

  /* Panel horizontal (cubriendo arriba/abajo dejando hueco vertical) */
  a.corner-button-success2::before {
    width: calc(100% - (var(--cb-gap) * 2));
    height: calc(100% + var(--cb-over));
    top: calc(-1 * var(--cb-over) / 2);
    left: 50%;
    transform: translateX(-50%);
  }

  /* Panel vertical (cubriendo lados dejando hueco horizontal) */
  a.corner-button-success2::after {
    height: calc(100% - (var(--cb-gap) * 2));
    width: calc(100% + var(--cb-over));
    left: calc(-1 * var(--cb-over) / 2);
    top: 50%;
    transform: translateY(-50%);
  }

  /* Interacci�n */
  a.corner-button-success2:hover {
    color: var(--cb-color);
  }
  a.corner-button-success2:hover::before { width: 0; }
  a.corner-button-success2:hover::after  { height: 0; }

  /* Active: reduce ligeramente el borde (fluido) */
  a.corner-button-success2:active {
    border-width: calc(var(--cb-border) * 0.8);
  }

  /* Enfoque accesible (teclado) */
  a.corner-button-success2:focus-visible {
    outline: var(--cb-focus) solid currentColor;
    outline-offset: 3px;
    border-radius: 2px; /* leve para diferenciar el outline */
  }

  a.corner-button-success2--sm {
    --cb-font-size: 2rem;
    --cb-border: 0.2em;
    --cb-pad-y: 0.35em;
    --cb-pad-x: 0.6em;
    --cb-gap: 0.4em;
    --cb-over: 0.3em;
  }

  /* Solo �cono (sin fijar width/height ? no se deforma; usa line-height y padding) */
  a.corner-button-success2--icon {
    --cb-font-size: 2rem; /* tama�o base del icono */
    --cb-border: 0.2em;
    --cb-pad-y: 0.35em;
    --cb-pad-x: 0.35em;
    --cb-gap: 0.38em;
    --cb-over: 0.28em;
  }

  /* Reducir movimiento si el usuario lo prefiere */
  @media (prefers-reduced-motion: reduce) {
    a.corner-button-success2,
    a.corner-button-success2::before,
    a.corner-button-success2::after {
      transition: none;
    }
  }
  /******* PARA BOTON DE SUCESS *******/
    /* Bot�n de esquinas con animaci�n (versi�n para <a>) */
  a.corner-button-error {
    /* Variables fluidas (en em para escalar con el font-size del bot�n) */
    --cb-color: #dc3545;          /* texto y borde (currentColor) */
    --cb-font-size: 1.2rem;         /* cambia esto para hacerlo m�s grande/peque�o */
    --cb-border: 0.22em;          /* grosor de borde relativo al font-size */
    --cb-pad-y: 0.5em;
    --cb-pad-x: 0.9em;
    --cb-gap: 0.45em;             /* hueco entre paneles */
    --cb-over: 0.35em;            /* leve superposici�n para cubrir borde */
    --cb-anim: 0.25s;
    --cb-bg: #fff;                /* �paneles� de la animaci�n (fondo) */
    --cb-focus: 2px;              /* grosor del focus ring */

    font-family: var(--cb-font);
    font-size: var(--cb-font-size);
    line-height: 1;               /* evita crecimiento vertical irregular */
    color: var(--cb-color);
    text-decoration: none;        /* estilo �bot�n� */
    display: inline-flex;         /* contenido fluido */
    align-items: center;
    gap: 0.4em;

    cursor: pointer;
    background: transparent;
    border: var(--cb-border) solid currentColor;
    padding: var(--cb-pad-y) var(--cb-pad-x);
    position: relative;
    transition: color var(--cb-anim), border-width var(--cb-anim);
    -webkit-tap-highlight-color: transparent;
  }

  /* Contenido interior: protegido por z-index sobre los paneles */
  a.corner-button-error > .cb-content {
    position: relative;
    z-index: 2;
    display: inline-flex;
    align-items: center;
    gap: 0.35em; /* espacio entre icono y texto */
  }

  /* Paneles que se �cierran� (100% fluidos, basados en em) */
  a.corner-button-error::before,
  a.corner-button-error::after {
    content: '';
    position: absolute;
    background: var(--cb-bg);
    z-index: 1;
    transition: all var(--cb-anim);
    pointer-events: none; /* no bloquea clicks */
  }

  /* Panel horizontal (cubriendo arriba/abajo dejando hueco vertical) */
  a.corner-button-error::before {
    width: calc(100% - (var(--cb-gap) * 2));
    height: calc(100% + var(--cb-over));
    top: calc(-1 * var(--cb-over) / 2);
    left: 50%;
    transform: translateX(-50%);
  }

  /* Panel vertical (cubriendo lados dejando hueco horizontal) */
  a.corner-button-error::after {
    height: calc(100% - (var(--cb-gap) * 2));
    width: calc(100% + var(--cb-over));
    left: calc(-1 * var(--cb-over) / 2);
    top: 50%;
    transform: translateY(-50%);
  }

  /* Interacci�n */
  a.corner-button-error:hover {
    color: var(--cb-color);
  }
  a.corner-button-error:hover::before { width: 0; }
  a.corner-button-error:hover::after  { height: 0; }

  /* Active: reduce ligeramente el borde (fluido) */
  a.corner-button-error:active {
    border-width: calc(var(--cb-border) * 0.8);
  }

  /* Enfoque accesible (teclado) */
  a.corner-button-error:focus-visible {
    outline: var(--cb-focus) solid currentColor;
    outline-offset: 3px;
    border-radius: 2px; /* leve para diferenciar el outline */
  }

  a.corner-button-error--sm {
    --cb-font-size: 2rem;
    --cb-border: 0.2em;
    --cb-pad-y: 0.35em;
    --cb-pad-x: 0.6em;
    --cb-gap: 0.4em;
    --cb-over: 0.3em;
  }

  /* Solo �cono (sin fijar width/height ? no se deforma; usa line-height y padding) */
  a.corner-button-error--icon {
    --cb-font-size: 2rem; /* tama�o base del icono */
    --cb-border: 0.2em;
    --cb-pad-y: 0.35em;
    --cb-pad-x: 0.35em;
    --cb-gap: 0.38em;
    --cb-over: 0.28em;
  }

  /* Reducir movimiento si el usuario lo prefiere */
  @media (prefers-reduced-motion: reduce) {
    a.corner-button-error,
    a.corner-button-error::before,
    a.corner-button-error::after {
      transition: none;
    }
  }

 </style>

<script>
    var uploadFile = "{{ url('archivo') }}";
    var removeFile = "{{ url('removeFile') }}";
    var autorizadoUrl = '{{ url('ingresodetalle/autorizado') }}';
    var autorizadoToken = "{{ csrf_token() }}";
    var privateKeyBuffer = new ArrayBuffer(0); // ArrayBuffer with loaded private key
    var certificateBuffer = new ArrayBuffer(0); // ArrayBuffer with loaded certificate
    var cer64;
    var archivobase64;
    var signaturepck7;
    var resultado;
    var hashPDF;
    var idPDF;
    var parametros;
</script>
