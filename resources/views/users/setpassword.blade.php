<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<!--begin::Head-->
	<head><base href="../../../">
		<title>{{ config('app.name', 'OSFEM') }}</title>
		<meta charset="utf-8" />
        <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}" type="image/png">
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="Metronic - Bootstrap 5 HTML, VueJS, React, Angular &amp; Laravel Admin Dashboard Theme" />
		<meta property="og:url" content="https://keenthemes.com/metronic" />
		<link rel="canonical" href="https://preview.keenthemes.com/metronic8"/>
		<!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Global Stylesheets Bundle(used by all pages)-->
		<link href="{{ asset('metronic/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('metronic/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->
	</head>
    <style>
        .help-block {color: red !important;}
    </style>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="bg-body">
		<!--begin::Main-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Authentication - Sign-in -->
			<div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed" style="background-image: url({{ asset('metronic/assets/media/illustrations/sketchy-1/FondoOficial.jpg') }}); background-size:100% 100%">
				<!--begin::Content-->
				<div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
					<!--begin::Logo-->
					<a href="" class="mb-12 w-lg-500px">
						<img alt="Logo" src="{{ asset('img/logo1.png') }}" class="h-80px" /><img alt="Logo" src="{{ asset('img/logo.png') }}" class="h-80px" align="right" />
					</a>
					<!--end::Logo-->
					<!--begin::Wrapper-->
					<div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
                        @include('flash::message')
						<!--begin::Form-->
                        {!! BootForm::open(['model' => $user, 'update' => 'user.updatepassword', 'id'=>'form']) !!}
                            <div class="text-center mb-10">
                                <!--begin::Title-->
                                <h1 class="text-dark mb-3" style="color: #A13B71 !important;">{{ __('Plataforma Digital') }}</h1>
                                <h1 class="text-dark mb-3" style="color: #A13B71 !important;">{{ __('Sistema de Fiscalización') }}</h1>
                                <h6 class="text-dark mb-3" style="color: #A13B71 !important;">{{ __('Cambio de contraseña') }}</h6>
                                <!--end::Title-->
                            </div>
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="_setpassword" value="1">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="nombre">Nombre: </label>&nbsp;<span style="color: #A13B71 !important;">{{$user->name}}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="email">Correo electrónico:</label>&nbsp;<span style="color: #A13B71 !important;">{{$user->email}}</span>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    {!! BootForm::password("password", "Contraseña:", ["width" => "col-md-12"]) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    {!! BootForm::password("password_confirmation", "Confirmar contraseña:", ["width" =>
                                    "col-md-12"]) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right mt-2">
                                    {!! BootForm::submit('Guardar',['class'=>'btn btn-lg btn-secondary w-100 mb-5']) !!}
                                </div>
                            </div>
                        {!! BootForm::close() !!}
						<!--end::Form-->
					</div>
					<!--end::Wrapper-->
				</div>
				<!--end::Content-->
				<!--begin::Footer-->
			</div>
			<!--end::Authentication - Sign-in-->
		</div>
		<!--end::Main-->
		<script>var hostUrl = "assets/";</script>
		<!--begin::Javascript-->
		<!--begin::Global Javascript Bundle(used by all pages)-->
		<script src="{{ asset('metronic/assets/plugins/global/plugins.bundle.js') }}"></script>
		<script src="{{ asset('metronic/assets/js/scripts.bundle.js') }}"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Page Custom Javascript(used by this page)-->
		<script src="{{ asset('metronic/assets/js/custom/authentication/sign-in/general.js') }}"></script>
		<!--end::Page Custom Javascript-->
		<!--end::Javascript-->
        <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
        {!! $validator !!}
	</body>
	<!--end::Body-->
</html>
