
<div class="col-md-3 mt-2">
    <div class="card p-2">
        <div class="card-body">
            <div class="menu menu-rounded menu-column menu-gray-800 menu-hover-light menu-here-light menu-show-light menu-state-bg-primary fw-bold w-175p" data-kt-menu="true">
                <div class="menu-item menu-accordion show" data-kt-menu-trigger="click">
                    <a href="#" class="menu-link py-3 active" >
                        <span class="menu-icon">
                            <i class="fa fa-folder-open fs-3"></i>
                        </span>
                        <span class="menu-title">Auditoría</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="menu-sub menu-sub-accordion mx-5 me-0 pt-3 show">
                        <div class="menu-item mb-1">
                            <a href="{{ route('radicacion.index') }}" class="menu-link py-3 {{ (str_contains(Route::current()->getName(), 'radicacion') || str_contains(Route::current()->getName(), 'comparecenciaacuse')) ? 'active' : '' }}">
                                <span class="menu-bullet">
                                    <span class="fa fa-file-text"></span>
                                </span>
                                <span class="menu-title">Radicación</span>
                            </a>
                        </div>
                        <div class="menu-item mb-1">
                            <a href="{{ route('comparecenciaacta.index') }}" class="menu-link py-3 {{ str_contains(Route::current()->getName(), 'comparecenciaacta') ? 'active' : '' }}">
                                <span class="menu-bullet">
                                    <span class="fa fa-file-text"></span>
                                </span>
                                <span class="menu-title">Comparecencia</span>
                            </a>
                        </div>     
                        <div class="menu-item mb-1">
                            <a href="{{ route('prasacciones.index') }}" class="menu-link py-3 {{ str_contains(Route::current()->getName(), 'prasacciones') ? 'active' : '' }}">
                                <span class="menu-bullet">
                                    <span class="fa fa-file-text"></span>
                                </span>
                                <span class="menu-title">PRAS</span>
                            </a>
                        </div>                    
                        <div class="menu-item mb-1">
                            <a href="{{ route('recomendacionesacciones.index') }}" class="menu-link py-3 {{ str_contains(Route::current()->getName(), 'recomendacionesacciones') ? 'active' : '' }}">
                                <span class="menu-bullet">
                                    <span class="fa fa-file-text"></span>
                                </span>
                                <span class="menu-title">Recomendaciónes</span>
                            </a>
                        </div> 
                        <div class="menu-item mb-1">
                            <a href="{{ route('solicitudesaclaracionacciones.index') }}" class="menu-link py-3 {{ str_contains(Route::current()->getName(), 'solicitudesaclaracionacciones') ? 'active' : '' }}">
                                <span class="menu-bullet">
                                    <span class="fa fa-file-text"></span>
                                </span>
                                <span class="menu-title">Solicitudes de aclaración</span>
                            </a>
                        </div> 
                        <div class="menu-item mb-1">
                            <a href="{{ route('pliegosobservacionacciones.index') }}" class="menu-link py-3 {{ str_contains(Route::current()->getName(), 'pliegosobservacionacciones') ? 'active' : '' }}">
                                <span class="menu-bullet">
                                    <span class="fa fa-file-text"></span>
                                </span>
                                <span class="menu-title">Pliegos de observación</span>
                            </a>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
