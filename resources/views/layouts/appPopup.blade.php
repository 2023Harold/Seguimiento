<!doctype html>
<html lang="{{str_replace('_', '-', app()->getLocale())}}">
    <head>
        @include('layouts.partials._head')
    </head>
    <body>
        <div class="d-flex flex-column flex-root">
            <div class="container-fluid" id="kt_content">
                @yield('breadcrums')
                <div class="mt-3 p-2">
                    <!-- contenido princial-->
                    @yield('content')
                </div>
            </div>
        </div>
        @include('layouts.partials._foot')
        @yield('script')
    </body>
</html>
