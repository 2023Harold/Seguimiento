<!doctype html>
<html lang="{{str_replace('_', '-', app()->getLocale())}}">
    <head>
        @include('layouts.partials._headlog')
    </head>
    <body id="kt_body" class="bg-body">
        <div class="d-flex flex-column flex-root">
			<!--begin::Authentication - Sign-in -->
			<div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed" style="background-image: url({{asset('assets/img/FondoOficial.jpg')}}); background-size:100% 100%">
				<!--begin::Content-->
				<div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
					<!--begin::Logo-->
					<a href="" class="mb-12 w-lg-500px">
						<img alt="Logo" src="{{asset('assets/img/logo1.png')}}" class="h-80px" /><img alt="Logo" src="{{ asset('assets/img/logo.png') }}" class="h-60px" align="right" />
					</a>
					<!--end::Logo-->
					<!--begin::Wrapper-->
					<div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
                        @include('flash::message')
						<!--begin::Form-->
                        {!! BootForm::open(['route' => ['login'],'POST','class'=>'form w-100']) !!}
							<!--begin::Heading-->
							<div class="text-center mb-10">
								<!--begin::Title-->
								<h1 class="font-weight-bold mb-3 text-sistema">{{ __('Plataforma Digital') }}</h1>
								<h1 class="mb-3 text-sistema">{{ __('Componente de Seguimiento') }}</h1>
                                <!--end::Title-->
							</div>
							<!--begin::Heading-->
							<!--begin::Input group-->
							<div class="fv-row mb-10">
								<!--begin::Label-->
								<!--end::Label-->
								<!--begin::Input-->
								{!! BootForm::email('email','Correo electrónico',null,['class'=>$errors->first('email') ? 'is-invalid' : '']) !!}
                                <!--end::Input-->
							</div>
							<!--end::Input group-->
							<!--begin::Input group-->
							<div class="fv-row mb-10">
								<!--begin::Input-->
								{!! BootForm::password('password', 'Contraseña',['class'=>$errors->first('password') ? 'is-invalid' : '']) !!}
								<!--end::Input-->
							</div>
							<!--end::Input group-->
							<!--begin::Actions-->
							<div class="text-center">
								<!--begin::Submit button-->
								<button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-secondary w-100 mb-5">
									<span class="indicator-label">Iniciar sesión</span>
								</button>
								<!--end::Submit button-->
								<!--begin::Separator-->
								<div class="text-center text-muted fw-bolder mb-5">
                                   <!-- Calle Mariano Matamoros No. 106, Delegación Centro Histórico, Colonia Centro Toluca, Estado de México C.P. 50000. Tels. 772 167 84 50 begin::Separator-->
                                </div>
								<!--end::Separator-->
							</div>
							<!--end::Actions-->
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
        <!-- js metronic -->
        @include('layouts.partials._foot')
        @yield('script')
        
    </body>
</html>
