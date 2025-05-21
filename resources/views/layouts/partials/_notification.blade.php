<!--begin::Menu wrapper-->
<div class="btn btn-icon w-30px h-30px w-md-40px h-md-40px position-relative" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end" id="kt_drawer_chat_toggle">
    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
    <i class="fa fa-bell fs-2x"></i>
    @if (auth()->user()->NotificacionesCount>0)
    <!--end::Svg Icon-->
    <span class="badge badge-square badge-danger h-20px w-20px t-10 position-static translate-middle pulse pulse-danger top-0 start-100 animation-blink" style="visibility:{{ count(auth()->user()->notificaciones)>0?'visible':'hidden'}}" id="badge_id"> 
        <span id="numero_notificaciones">{{ auth()->user()->NotificacionesCount }}</span>
        <span class="pulse-ring" style="visibility:{{ count(auth()->user()->notificaciones)>0?'visible':'hidden'}}" id="pulse_id"></span>
    </span>
    <!--<span class="bullet bullet-dot bg-success h-6px w-6px position-absolute translate-middle top-0 start-50 animation-blink"></span>
    end::Svg Icon-->
    @endif
</div>

<div class="menu menu-sub menu-sub-dropdown menu-column w-450px w-lg-525px" data-kt-menu="true">
    
    <!--begin::Heading-->
    <div class="d-flex flex-column flex-center bgi-no-repeat rounded-top px-9 py-5" style="background-image:url('{{asset('assets/img/gris.jpg')}}')">
        <button id="btn-refrescar" class="btn btn-sm btn-primary mt-2 me-auto" title="Refrescar notificaciones">
            <i class="fa fa-sync-alt fa-6x"></i> <!-- Icono de refrescar más grande -->
        </button>
        
        <!--begin::Title-->
        <h1 class="text-primary fw-bold mb-3">Notificaciones</h1>
        
        
        
        <!--end::Title-->
        @if (auth()->user()->NotificacionesCount>0)
            <!--begin::Status-->
            <span class="badge bg-primary py-2 px-3" style="visibility:{{ count(auth()->user()->notificaciones)>0?'visible':'hidden'}}" id="badge_etiqueta_id"><span id="numero_notificaciones_badge">{{ auth()->user()->NotificacionesCount}}</span> &nbsp;  <span id="span-ntf">{{auth()->user()->NotificacionesCount==1?'notificación':'notificaciones'}}</span></span>
            <!--end::Status-->
        @endif
        <span class="mt-2"><a href="{{route('notificaciones.index')}}">Ver todas las notificaciones</a></span>
    </div>
    <!--end::Heading-->
    <!--begin:Nav-->
    <div class="mb-4 mh-400px scroll-y overflow-x-auto">
        <div class="sin-ntf p-1 w-100 w-md-100 text-center d-none">
            No se han encontrado notificaciones pendientes.
        </div>
        @if (count(auth()->user()->notificaciones) > 0)
            @foreach (auth()->user()->notificaciones->take(10) as $notificacion)<!-- Take para limitar a 10 notifiaciones -->
                <!--begin::Items-->
                <div class="p-0 w-100 pe-5" id="rownotificacion{{ $notificacion->id }}">
                    <div class="mh-300px mh-lg-325px border-1 border-bottom border-top p-2">
                        <div class="d-flex justify-content-between">
								<div>
									<span class="small text-muted fw-bold">
										Cuenta Pública {{$notificacion->cp}}
									</span>
								</div>	
								<div>
									<span class="small text-muted fw-bold">
										{{ fecha($notificacion->fecha_muestra_inicio, 'd/m/Y H:i') }}
									</span>
								</div>	
						</div>	
                        <div class="d-flex align-items-center mb-2">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-40px me-4 d-flex flex-column w-50px text-center">
                                <!--Check box para leido.-->
                                {!! BootForm::checkbox('notificacion' . $notificacion->id, false, $notificacion->id, old('notificacion' . $notificacion->id, $notificacion->estatus) == 'Leído' ? true : false, ['class' => 'i-checks mr-3 casilla', 'id' => 'notificacion' . $notificacion->id]) !!}
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Title-->
                            <div class="d-flex flex-column w-100">
                                <label class="fs-6 text-primary fw-bold">
                                    {{ $notificacion->titulo }}
                                </label>
                                <span class="fs-7 text-justify">
                                    {!! $notificacion->mensaje !!}
                                </span>
                            </div>
                            <!--end::Title-->
                        </div>
                    </div>
                </div>
                <!--end::Items-->
            @endforeach
        @else
            <!--begin::Items-->
            <div class="p-1 w-100 w-md-100 text-center">
                No se han encontrado notificaciones pendientes.
            </div>
            <!--end::Items-->
        @endif

    </div>
    <!--end:Nav-->
    {{-- <!--begin::View more-->
    <div class="py-2 text-center border-top">
        <a href="" class="btn btn-color-gray-600 btn-active-color-primary">Motrar todas las notificaciones
        <!--begin::Svg Icon | path: icons/duotune/arrows/arr064.svg-->
        <span class="svg-icon svg-icon-5">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="black" />
                <path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="black" />
            </svg>
        </span>
        <!--end::Svg Icon--></a>
    </div>
    <!--end::View more--> --}}
</div>

<script>
    $(document).ready(function() {
        var total_notificaciones = '{{ !empty(auth()->user()->notificaciones) && count(auth()->user()->notificaciones) != 0 ? count(auth()->user()->notificaciones) : 0 }}';
        $('.casilla').on('ifChanged', function(event) {
            var idcheck = $(this).attr('id');
            var idrow = '#rownotificacion' + $(this).val();
            var valor = $(this).val();
            console.log(idcheck,idrow,valor);
            $.ajax({
                url: "{{ route('marcarleido') }}",
                dataType: "json",
                method: 'GET',
                data: {
                    id: valor
                },
                success: function(respuesta) {
                    total_notificaciones = total_notificaciones - 1;

                    $('#numero_notificaciones').text(total_notificaciones);
                    $('#numero_notificaciones_badge').text(total_notificaciones);

                    if (total_notificaciones == 1) {
                        $('#span-ntf').text('notificación');
                    }else{
                        $('#span-ntf').text('notificaciones');
                    }

                    $(idrow).hide();
                    if (total_notificaciones == 0) {
                        $('#numero_notificaciones').hide();
                        $('#pulse_id').hide();
                        $('#badge_id').hide();
                        $('#badge_etiqueta_id').hide();

                        $('.sin-ntf').removeClass('d-none');
                    }
                },
                error: function() {
                    alert('Error al generar la peticion');
                }
            });
        });
        $('.no-cerrar').on('click', function(event){
            event.stopPropagation();
            
        });
    });

    $(document).ready(function() {
        $('#btn-refrescar').on('click', function() {
            // Guardar un valor en localStorage para recordar que se debe abrir el menú después de recargar
            localStorage.setItem('abrir_menu_notificaciones', 'true');
            // Recargar la página completa
            window.location.reload();
        });

        // Verificar si se debe abrir el menú automáticamente después de la recarga
        if (localStorage.getItem('abrir_menu_notificaciones') === 'true') {
            // Abrir el menú automáticamente
            $('#kt_drawer_chat_toggle').click();
            // Eliminar el valor de localStorage para que no se vuelva a abrir
            localStorage.removeItem('abrir_menu_notificaciones');
        }
    });



</script>
