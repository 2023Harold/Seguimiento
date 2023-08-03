@unless ($breadcrumbs->isEmpty())
    <div class="toolbar p-8" id="kt_toolbar">
        <!--begin::Container-->
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <ol class="breadcrumb text-muted fs-6 fw-bold">
                @foreach ($breadcrumbs as $breadcrumb)
                    @if (!is_null($breadcrumb->url) && !$loop->last)
                        <li class="breadcrumb-item"><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
                    @else
                        <li class="breadcrumb-item text-muted">{{ $breadcrumb->title }}</li>
                    @endif
                @endforeach
            </ol>
        </div>
        <!--end::Container-->
    </div>
@endunless
