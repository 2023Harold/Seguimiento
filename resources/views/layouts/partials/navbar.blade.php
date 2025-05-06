<!--begin::Navbar-->
<div class="d-flex align-items-stretch flex-shrink-0" id="kt_header_nav">
    <div class="d-flex align-items-center ms-1 ms-lg-3" id="kt_header_user_menu_toggle">
        <span class="text-primary h2">Seguimiento a las observaciones de fiscalización</span>
    </div>
</div>

<div class="d-flex align-items-stretch" id="kt_header_nav">

    <img alt="Logo" src="{{asset('assets/img/legislatura.png')}}" class="w-100" style="border-right: 2px solid gray;"/>
&nbsp;
    <img alt="Logo" src="{{asset('assets/img/logo.png')}}" class="w-100"/>
</div>
<!--end::Navbar-->
<!--begin::Topbar-->

<!--begin::Toolbar wrapper-->
<div class="d-flex align-items-stretch flex-shrink-0">
    <!--begin::Quick links-->
    @if (auth()->check())
    <div class="d-flex align-items-center ms-1 ms-lg-3">
		@include('layouts.partials._notification')
    </div>
    @endif
    <!--end::Quick links-->
    <!--begin::User-->
    @if (auth()->check())
    <div class="d-flex align-items-center ms-1 ms-lg-3" id="kt_header_user_menu_toggle">
        <!--begin::Menu wrapper-->
        <div class="cursor-pointer symbol symbol-30px symbol-md-40px" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
            <div class="menu-content d-flex align-items-center px-3">
                <!--begin::Avatar-->
                <div class="symbol symbol-50px me-5">
                    <span class="text-sistema fw-bolder fs-5">
                        {{ Auth::user()->name }}
                    </span><br>
                    <span class="text-muted fw-bolder fs-9">
                        {{ Auth::user()->puesto }}
                    </span>
                </div>
                <!--end::Avatar-->
                <!--begin::Username-->
                <div class="d-flex flex-column">
                    <!--begin::Svg Icon | path: assets/media/icons/duotune/communication/com006.svg-->
                    <i class="bi bi-person-circle text-sistema fs-2tx"></i>
                    <!--end::Svg Icon-->
                </div>
                <!--end::Username-->
            </div>
        </div>
        <!--begin::Menu-->
        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold py-4 fs-6 w-275px" data-kt-menu="true">
            <!--begin::Menu item-->
            <div class="menu-item px-3">
                <div class="menu-content d-flex align-items-center px-3">
                    <!--begin::Username-->
                    <div class="d-flex flex-column">
                        <div class="fw-bolder text-sistema d-flex align-items-center fs-5">
                            {{ Auth::user()->name }}
                        </div>
                        <a href="#" class="fw-bold text-muted text-hover-primary fs-7">{{ Auth::user()->email }}</a>
                    </div>
                    <!--end::Username-->
                </div>
            </div>
            <!--end::Menu item-->
            <!--begin::Menu separator-->
            <div class="separator my-2"></div>
            <!--end::Menu separator-->
            <!--begin::Menu item-->
            <!--end::Menu item-->
            <!--begin::Menu item-->
            <!--
                <div class="menu-item px-5">
                    <a href="../../demo1/dist/pages/projects/list.html" class="menu-link px-5">
                        <span class="menu-text">My Projects</span>
                        <span class="menu-badge">
                            <span class="badge badge-light-danger badge-circle fw-bolder fs-7">3</span>
                        </span>
                    </a>
                </div> -->
            <!--end::Menu item-->
            <!--begin::Menu separator-->
            <div class="separator my-2"></div>
            <!--end::Menu separator-->


            <!--begin::Menu item-->
            <!--
                <div class="menu-item px-5 my-1">
                    <a href="../../demo1/dist/account/settings.html" class="menu-link px-5">Account Settings</a>
                </div> -->
            <!--end::Menu item-->
            <!--begin::Menu item-->
            <div class="menu-item px-5">
                <a href="{{ route('auth.logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="menu-link px-5">
                    <i class="fa fa-power-off mx-2"></i> Cerrar sesión
                </a>
                <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
            <!--end::Menu item-->
        </div>
        <!--end::Menu-->
        <!--end::Menu wrapper-->
    </div>
    <div class="d-flex align-items-center ms-1 ms-lg-3 d-none">
        <!--begin::Menu wrapper-->
        <div class="btn btn-icon btn-active-light-primary w-30px h-30px w-md-40px h-md-40px position-relative">
            <form id="logout-form-button" action="{{ route('auth.logout') }}" method="POST">
                @csrf
                <i class="fa fa-power-off fs-2x"></i>
            </form>
        </div>
    </div>
    @endif
    <!--end::User -->
    <!--begin::Heaeder menu toggle-->
    <div class="d-flex align-items-center d-none ms-2 me-n3" title="Show header menu">
        <div class="btn btn-icon btn-active-light-primary w-30px h-30px w-md-40px h-md-40px" id="kt_header_menu_mobile_toggle">
            <!--begin::Svg Icon | path: icons/duotune/text/txt001.svg-->
            <span class="svg-icon svg-icon-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M13 11H3C2.4 11 2 10.6 2 10V9C2 8.4 2.4 8 3 8H13C13.6 8 14 8.4 14 9V10C14 10.6 13.6 11 13 11ZM22 5V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4V5C2 5.6 2.4 6 3 6H21C21.6 6 22 5.6 22 5Z" fill="black" />
                    <path opacity="0.3" d="M21 16H3C2.4 16 2 15.6 2 15V14C2 13.4 2.4 13 3 13H21C21.6 13 22 13.4 22 14V15C22 15.6 21.6 16 21 16ZM14 20V19C14 18.4 13.6 18 13 18H3C2.4 18 2 18.4 2 19V20C2 20.6 2.4 21 3 21H13C13.6 21 14 20.6 14 20Z" fill="black" />
                </svg>
            </span>
            <!--end::Svg Icon-->
        </div>
    </div>
    <!--end::Heaeder menu toggle-->

    <div class="menu-link p-10">
        <!--begin::Logo-->
        <a href="#">

        </a>
        <!--end::Logo-->
    </div>
</div>
<!--end::Toolbar wrapper-->
