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
