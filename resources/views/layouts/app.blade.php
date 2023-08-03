<!doctype html>
<html lang="{{str_replace('_', '-', app()->getLocale())}}">
    <head>
        @include('layouts.partials._head')
    </head>
    <body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed" style="--kt-toolbar-height:20px;--kt-toolbar-height-tablet-and-mobile:20px">
        <div class="d-flex flex-column flex-root">
			<!--begin::Page-->
			<div class="page d-flex flex-row flex-column-fluid">

				<!--begin::Wrapper-->
				<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">

                    <!--begin::Header-->
					<div id="kt_header" style="" class="header align-items-stretch">
						<!--begin::Container-->
						<div class="container-fluid d-flex align-items-stretch justify-content-between">
							<!--begin::Aside mobile toggle-->
							<div class="d-flex align-items-center d-lg-none ms-n3 me-1 d-none" title="Show aside menu">
								<div class="btn btn-icon btn-active-light-primary w-30px h-30px w-md-40px h-md-40px" id="kt_aside_mobile_toggle">
									<!--begin::Svg Icon | path: icons/duotune/abstract/abs015.svg-->
									<span class="svg-icon svg-icon-2x mt-1 d-none">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
											<path d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z" fill="black"/>
											<path opacity="0.3" d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z" fill="black"/>
										</svg>
									</span>
									<!--end::Svg Icon-->
								</div>
							</div>
							<!--end::Aside mobile toggle-->
							<!--begin::Mobile logo-->
							<div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
								<a href="{{route('home')}}" class="d-lg-none">
                                    <img alt="Logo" src="{{asset('assets/img/logo-left.svg')}}" class="h-45px logo"/>
								</a>
							</div>
							<!--end::Mobile logo-->
							<!--begin::Wrapper-->
							<div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
								<!-- navbar --->
                                @include('layouts.partials.navbar')
                                <!-- /navbar --->
							</div>
							<!--end::Wrapper-->
						</div>
						<!--end::Container-->
					</div>
					<!--end::Header-->

					<!-- begin::Content -->
                    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                        @yield('breadcrums')                        
                            <!-- contenido princial-->							
							<div class="container-fluid mt-12">
								@yield('content')
							</div>                           
                    </div>
					<!-- end::Content -->
					<!--begin::Footer-->
					<div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
						<!--begin::Container-->
						<div class="container-fluid d-flex flex-column align-items-center justify-content-between">
							<!--begin::Copyright-->
							<div class="text-dark order-2 order-md-1 flex-center">
								<span class="text-muted fw-bold me-1">
                                    Copyright {{date('Y')}}
                                </span>
								Calle Mariano Matamoros No. 106, Delegación Centro Histórico, Colonia Centro Toluca, Estado de México C.P. 50000. Tels. (772)-167-8450
							</div>
							<!--end::Copyright-->
							<!--begin::Menu-->
							<!--end::Menu-->
						</div>
						<!--end::Container-->
					</div>
					<!--end::Footer--> 					
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
        </div>

        <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="false">
			<!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
			<span class="svg-icon">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
					<rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="black"/>
					<path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="black"/>
				</svg>
			</span>
			<!--end::Svg Icon-->
		</div>
        @include('layouts.partials._foot')
        @yield('script')
    </body>
</html>

{{-- <!--begin::Footer-->
<div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
	<!--begin::Container-->
	<div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
		<!--begin::Copyright-->
		<div class="text-dark order-2 order-md-1">
			<span class="text-muted fw-bold me-1">2022©</span>
			<a href="https://keenthemes.com" target="_blank" class="text-gray-800 text-hover-primary">Keenthemes</a>
		</div>
		<!--end::Copyright-->
		<!--begin::Menu-->
		<ul class="menu menu-gray-600 menu-hover-primary fw-bold order-1">
			<li class="menu-item">
				<a href="https://keenthemes.com" target="_blank" class="menu-link px-2">About</a>
			</li>
			<li class="menu-item">
				<a href="https://devs.keenthemes.com" target="_blank" class="menu-link px-2">Support</a>
			</li>
			<li class="menu-item">
				<a href="https://1.envato.market/EA4JP" target="_blank" class="menu-link px-2">Purchase</a>
			</li>
		</ul>
		<!--end::Menu-->
	</div>
	<!--end::Container-->
</div>
<!--end::Footer--> 



<!--begin::Footer-->
					<div class="footer py-4 d-flex flex-lg-column mt-5" id="kt_footer">
						<!--begin::Container-->
						<div class="container-fluid align-items-center justify-content-between">
							<!--begin::Copyright-->
							<div class="text-dark order-2 order-md-1 text-center align-content-center justify-content-center">
								<span class="text-muted fw-bold me-1">
                                    Copyright {{date('Y')}}
                                </span>
								Calle Mariano Matamoros No. 106, Delegación Centro Histórico, Colonia Centro Toluca, Estado de México C.P. 50000. Tels. (772)-167-8450
							</div>
							<!--end::Copyright-->
							<!--begin::Menu-->
							<!--end::Menu-->
						</div>
						<!--end::Container-->
					</div>
					<!--end::Footer-->

--}}