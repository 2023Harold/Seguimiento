
<div class="col-md-3 mt-2" style="height: 100% !important;">
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
                        @can('radicacion.index')
                        <div class="menu-item mb-1">                     
                            <a href="{{ route('radicacion.index') }}" class="menu-link py-3 {{ (str_contains(Route::current()->getName(), 'radicacion') || str_contains(Route::current()->getName(), 'comparecenciaacuse')|| str_contains(Route::current()->getName(), 'comparecenciaagenda')) ? 'active' : '' }}">
                                <span class="menu-bullet">
                                    <span class="fa fa-file-text"></span>
                                </span>
                                <span class="menu-title">Radicación</span>
                            </a>
                        </div>
                        @endcan
                        @can('comparecenciaacta.index')
                        <div class="menu-item mb-1">
                            <a href="{{ route('comparecenciaacta.index') }}" class="menu-link py-3 {{ str_contains(Route::current()->getName(), 'comparecenciaacta') ? 'active' : '' }}">
                                <span class="menu-bullet">
                                    <span class="fa fa-file-text"></span>
                                </span>
                                <span class="menu-title">Comparecencia</span>
                            </a>
                        </div>
                        @endcan
                        <div class="menu-item menu-accordion {{ (str_contains(Route::current()->getName(), 'prasacciones')||
                                                                 str_contains(Route::current()->getName(), 'prasturno')||
                                                                 str_contains(Route::current()->getName(), 'prasseguimiento')||
                                                                 str_contains(Route::current()->getName(), 'prasmedida')||
                                                                 str_contains(Route::current()->getName(), 'recomendacionesacciones')||
                                                                 str_contains(Route::current()->getName(), 'recomendacionesatencion')||
                                                                 str_contains(Route::current()->getName(), 'recomendacionescontestaciones')||
                                                                 str_contains(Route::current()->getName(), 'recomendacionesdocumentos')||
                                                                 str_contains(Route::current()->getName(), 'recomendacionesanalisis')||
                                                                 str_contains(Route::current()->getName(), 'recomendacionesanexos')||
                                                                 str_contains(Route::current()->getName(), 'recomendacion.anexos')||
                                                                 str_contains(Route::current()->getName(), 'recomendacionesrevision01')||
                                                                 str_contains(Route::current()->getName(), 'recomendacionesrevision')||
                                                                 str_contains(Route::current()->getName(), 'recomendacionesvalidacion')||
                                                                 str_contains(Route::current()->getName(), 'recomendacionesautorizacion')||
                                                                 str_contains(Route::current()->getName(), 'solicitudesaclaracionacciones')||
                                                                 str_contains(Route::current()->getName(), 'solicitudesaclaracionatencion')||
                                                                 str_contains(Route::current()->getName(), 'solicitudesaclaracioncontestacion')||
                                                                 str_contains(Route::current()->getName(), 'solicitudesaclaraciondocumentos')||
                                                                 str_contains(Route::current()->getName(), 'solicitudesaclaracionanalisis')||
                                                                 str_contains(Route::current()->getName(), 'solicitudesaclaracionanexos')||
                                                                 str_contains(Route::current()->getName(), 'solicitudesaclaracionrevision')||
                                                                 str_contains(Route::current()->getName(), 'solicitudesaclaracionvalidacion')||
                                                                 str_contains(Route::current()->getName(), 'solicitudesaclaracionautorizacion')||
                                                                 str_contains(Route::current()->getName(), 'pliegosobservacionacciones')||
                                                                 str_contains(Route::current()->getName(), 'pliegosobservacionatencion')||
                                                                 str_contains(Route::current()->getName(), 'pliegosobservaciondocumentos')||
                                                                 str_contains(Route::current()->getName(), 'pliegosobservacionanalisis')||
                                                                 str_contains(Route::current()->getName(), 'pliegosobservacionanexos')||
                                                                 str_contains(Route::current()->getName(), 'pliegosobservacionrevision')||
                                                                 str_contains(Route::current()->getName(), 'pliegosobservacionvalidacion')||
                                                                 str_contains(Route::current()->getName(), 'pliegosobservacionautorizacion')||
                                                                 str_contains(Route::current()->getName(), 'informeprimeraetapa')||
                                                                 str_contains(Route::current()->getName(), 'cedulainicial')||
                                                                 str_contains(Route::current()->getName(), 'cedulageneralrecomendacion')||
                                                                 str_contains(Route::current()->getName(), 'cedulageneralpras')||
                                                                 str_contains(Route::current()->getName(), 'cedulaanalitica')||
                                                                 str_contains(Route::current()->getName(), 'cedulaanaliticarecomendacion')
                                                                ) ? 'show' : ''  }}" 
                                                                data-kt-menu-trigger="click">
                            <a href="#" class="menu-link py-3" >
                                <span class="menu-icon">
                                    <i class="fa fa-folder-open fs-3"></i>
                                </span>
                                <span class="menu-title">Primera etapa de aclaración</span>
                                <span class="menu-arrow"></span>
                            </a>                            
                            <div class="menu-sub menu-sub-accordion {{ (str_contains(Route::current()->getName(), 'prasacciones')||
                                                                        str_contains(Route::current()->getName(), 'prasturno')||
                                                                        str_contains(Route::current()->getName(), 'prasseguimiento')||
                                                                        str_contains(Route::current()->getName(), 'prasmedida')||
                                                                        str_contains(Route::current()->getName(), 'recomendacionesacciones')||
                                                                        str_contains(Route::current()->getName(), 'recomendacionesatencion')||
                                                                        str_contains(Route::current()->getName(), 'recomendacionescontestaciones')||
                                                                        str_contains(Route::current()->getName(), 'recomendacionesdocumentos')||
                                                                        str_contains(Route::current()->getName(), 'recomendacionesanalisis')||
                                                                        str_contains(Route::current()->getName(), 'recomendacionesanexos')||
                                                                        str_contains(Route::current()->getName(), 'recomendacion.anexos')||
                                                                        str_contains(Route::current()->getName(), 'recomendacionesrevision01')||
                                                                        str_contains(Route::current()->getName(), 'recomendacionesrevision')||
                                                                        str_contains(Route::current()->getName(), 'recomendacionesvalidacion')||
                                                                        str_contains(Route::current()->getName(), 'recomendacionesautorizacion')||
                                                                        str_contains(Route::current()->getName(), 'solicitudesaclaracionacciones')||
                                                                        str_contains(Route::current()->getName(), 'solicitudesaclaracionatencion')||
                                                                        str_contains(Route::current()->getName(), 'solicitudesaclaracioncontestacion')||
                                                                        str_contains(Route::current()->getName(), 'solicitudesaclaraciondocumentos')||
                                                                        str_contains(Route::current()->getName(), 'solicitudesaclaracionanalisis')||
                                                                        str_contains(Route::current()->getName(), 'solicitudesaclaracionrevision')||
                                                                        str_contains(Route::current()->getName(), 'solicitudesaclaracionvalidacion')||
                                                                        str_contains(Route::current()->getName(), 'solicitudesaclaracionautorizacion')||
                                                                        str_contains(Route::current()->getName(), 'solicitudesaclaracionanexos')||
                                                                        str_contains(Route::current()->getName(), 'pliegosobservacionacciones')||
                                                                        str_contains(Route::current()->getName(), 'pliegosobservacionatencion')||
                                                                        str_contains(Route::current()->getName(), 'pliegosobservaciondocumentos')||
                                                                        str_contains(Route::current()->getName(), 'pliegosobservacionanalisis')||
                                                                        str_contains(Route::current()->getName(), 'pliegosobservacionanexos')||
                                                                        str_contains(Route::current()->getName(), 'pliegosobservacionrevision')||
                                                                        str_contains(Route::current()->getName(), 'pliegosobservacionvalidacion')||
                                                                        str_contains(Route::current()->getName(), 'pliegosobservacionautorizacion')||
                                                                        str_contains(Route::current()->getName(), 'informeprimeraetapa')||
                                                                        str_contains(Route::current()->getName(), 'cedulainicial')||
                                                                        str_contains(Route::current()->getName(), 'cedulageneralrecomendacion')||
                                                                        str_contains(Route::current()->getName(), 'cedulageneralpras')||
                                                                        str_contains(Route::current()->getName(), 'cedulaanalitica')||
                                                                        str_contains(Route::current()->getName(), 'cedulaanaliticarecomendacion')
                                                                        ) ? 'show' : ''  }} mx-5 me-0 pt-3">
                                 <div class="menu-item mb-1">
                                    <a href="{{route('acuerdoconclusion.index')}}" class="menu-link py-3">
                                        <span class="menu-bullet">
                                            <span class="fa fa-file-text"></span>
                                        </span>
                                        <span class="menu-title">Acuerdo de conclusión</span>
                                    </a>
                                </div>     
                                @can('prasacciones.index')
                                <div class="menu-item mb-1">
                                    <a href="{{ route('prasacciones.index') }}" class="menu-link py-3 {{ (str_contains(Route::current()->getName(), 'prasacciones')||
                                                                                                          str_contains(Route::current()->getName(), 'prasturno')||
                                                                                                          str_contains(Route::current()->getName(), 'prasseguimiento')||
                                                                                                          str_contains(Route::current()->getName(), 'prasmedida')
                                                                                                         ) ? 'active' : '' }}">
                                        <span class="menu-bullet">
                                            <span class="fa fa-file-text"></span>
                                        </span>
                                        <span class="menu-title">PRAS</span>
                                    </a>
                                </div>
                                @endcan
                                @can('recomendacionesacciones.index')
                                <div class="menu-item mb-1">
                                    <a href="{{ route('recomendacionesacciones.index') }}" class="menu-link py-3 {{ (str_contains(Route::current()->getName(), 'recomendacionesacciones')||
                                                                                                                     str_contains(Route::current()->getName(), 'recomendacionesatencion')||
                                                                                                                     str_contains(Route::current()->getName(), 'recomendacionescontestaciones')||
                                                                                                                     str_contains(Route::current()->getName(), 'recomendacionesdocumentos')||
                                                                                                                     str_contains(Route::current()->getName(), 'recomendacionesanalisis')||
                                                                                                                     str_contains(Route::current()->getName(), 'recomendacion.anexos')||
                                                                                                                     str_contains(Route::current()->getName(), 'recomendacionesanexos')||
                                                                                                                     str_contains(Route::current()->getName(), 'recomendacionesrevision01')||
                                                                                                                     str_contains(Route::current()->getName(), 'recomendacionesrevision')||
                                                                                                                     str_contains(Route::current()->getName(), 'recomendacionesvalidacion')||
                                                                                                                     str_contains(Route::current()->getName(), 'recomendacionesautorizacion')
                                                                                                                     ) ? 'active' : '' }}">
                                        <span class="menu-bullet">
                                            <span class="fa fa-file-text"></span>
                                        </span>
                                        <span class="menu-title">Recomendaciones</span>
                                    </a>
                                </div>
                                @endcan
                                @can('solicitudesaclaracionacciones.index')
                                <div class="menu-item mb-1">
                                    <a href="{{ route('solicitudesaclaracionacciones.index') }}" class="menu-link py-3 {{ (str_contains(Route::current()->getName(), 'solicitudesaclaracionacciones')||
                                                                                                                           str_contains(Route::current()->getName(), 'solicitudesaclaracionatencion')||
                                                                                                                           str_contains(Route::current()->getName(), 'solicitudesaclaracioncontestacion')||
                                                                                                                           str_contains(Route::current()->getName(), 'solicitudesaclaraciondocumentos')||
                                                                                                                           str_contains(Route::current()->getName(), 'solicitudesaclaracionanalisis')||
                                                                                                                           str_contains(Route::current()->getName(), 'solicitudesaclaracionrevision')||
                                                                                                                           str_contains(Route::current()->getName(), 'solicitudesaclaracionvalidacion')||
                                                                                                                           str_contains(Route::current()->getName(), 'solicitudesaclaracionautorizacion')||
                                                                                                                           str_contains(Route::current()->getName(), 'solicitudesaclaracionanexos')
                                                                                                                        ) ? 'active' : '' }}">
                                        <span class="menu-bullet">
                                            <span class="fa fa-file-text"></span>
                                        </span>
                                        <span class="menu-title">Solicitudes de aclaración</span>
                                    </a>
                                </div>
                                @endcan
                                @can('pliegosobservacionacciones.index')
                                <div class="menu-item mb-1">
                                    <a href="{{ route('pliegosobservacionacciones.index') }}" class="menu-link py-3 {{ (str_contains(Route::current()->getName(), 'pliegosobservacionacciones')||
                                                                                                                        str_contains(Route::current()->getName(), 'pliegosobservacionatencion')||
                                                                                                                        str_contains(Route::current()->getName(), 'pliegosobservaciondocumentos')||
                                                                                                                        str_contains(Route::current()->getName(), 'pliegosobservacionanalisis')||
                                                                                                                        str_contains(Route::current()->getName(), 'pliegosobservacionanexos')||
                                                                                                                        str_contains(Route::current()->getName(), 'pliegosobservacionrevision')||
                                                                                                                        str_contains(Route::current()->getName(), 'pliegosobservacionvalidacion')||
                                                                                                                        str_contains(Route::current()->getName(), 'pliegosobservacionautorizacion')
                                                                                                                        ) ? 'active' : '' }}">
                                        <span class="menu-bullet">
                                            <span class="fa fa-file-text"></span>
                                        </span>
                                        <span class="menu-title">Pliegos de observación</span>
                                    </a>
                                </div>
                                @endcan  
                                <div class="menu-item mb-1">
                                    <a href="{{ route('cedulainicial.index') }}"
                                        class="menu-link py-3 {{ (str_contains(Route::current()->getName(), 'cedulainicial')||
                                                                  str_contains(Route::current()->getName(), 'cedulageneralrecomendacion')||
                                                                  str_contains(Route::current()->getName(), 'cedulageneralpras')||
                                                                  str_contains(Route::current()->getName(), 'cedulaanalitica')||
                                                                  str_contains(Route::current()->getName(), 'cedulaanaliticarecomendacion')
                                                                 ) ? 'active' : '' }}">
                                        <span class="menu-bullet">
                                            <span class="fa fa-file-text"></span>
                                        </span>
                                        <span class="menu-title">Cédulas</span>
                                    </a>
                                </div>   
                                <div class="menu-item mb-1">
                                    <a href="{{ route('informeprimeraetapa.index') }}"
                                        class="menu-link py-3 {{ str_contains(Route::current()->getName(), 'informeprimeraetapa') ? 'active' : '' }}">
                                        <span class="menu-bullet">
                                            <span class="fa fa-file-text"></span>
                                        </span>
                                        <span class="menu-title">Informe</span>
                                    </a>
                                </div>                            
                            </div>
                        </div>     
                        <div class="menu-item menu-accordion" data-kt-menu-trigger="click">
                            <a href="{{ route('pliegosobservacionacciones.index') }}" class="menu-link py-3" >
                                <span class="menu-icon">
                                    <i class="fa fa-folder-open fs-3"></i>
                                </span>
                                <span class="menu-title">Segunda etapa de aclaración</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="menu-sub menu-sub-accordion mx-5 me-0 pt-3">
                                @can('prasacciones.index')
                                <div class="menu-item mb-1">
                                    <a href="#" class="menu-link py-3">
                                        <span class="menu-bullet">
                                            <span class="fa fa-file-text"></span>
                                        </span>
                                        <span class="menu-title">PRAS</span>
                                    </a>
                                </div>
                                @endcan
                                @can('recomendacionesacciones.index')
                                <div class="menu-item mb-1">
                                    <a href="#" class="menu-link py-3">
                                        <span class="menu-bullet">
                                            <span class="fa fa-file-text"></span>
                                        </span>
                                        <span class="menu-title">Recomendaciones</span>
                                    </a>
                                </div>
                                @endcan                               
                                @can('pliegosobservacionacciones.index')
                                <div class="menu-item mb-1">
                                    <a href="#" class="menu-link py-3">
                                        <span class="menu-bullet">
                                            <span class="fa fa-file-text"></span>
                                        </span>
                                        <span class="menu-title">Pliegos de observación</span>
                                    </a>
                                </div>
                                @endcan    
                                <div class="menu-item mb-1">
                                    <a href="#"
                                        class="menu-link py-3">
                                        <span class="menu-bullet">
                                            <span class="fa fa-file-text"></span>
                                        </span>
                                        <span class="menu-title">Informe</span>
                                    </a>
                                </div>                            
                            </div>
                        </div> 
                        <div class="menu-item menu-accordion" data-kt-menu-trigger="click">                     
                            <a href="#" class="menu-link py-3">
                                <span class="menu-icon">
                                    <i class="fa fa-folder-open fs-3"></i>
                                </span>
                                <span class="menu-title">Turno</span>
                            </a>
                            <div class="menu-sub menu-sub-accordion mx-5 me-0 pt-3">                              
                                <div class="menu-item mb-1">
                                    <a href="{{route('turnoui.index')}}" class="menu-link py-3">
                                        <span class="menu-bullet">
                                            <span class="fa fa-file-text"></span>
                                        </span>
                                        <span class="menu-title">Turno a la UI</span>
                                    </a>
                                </div>
                                <div class="menu-item mb-1">
                                    <a href="{{route('turnooic.index')}}" class="menu-link py-3">
                                        <span class="menu-bullet">
                                            <span class="fa fa-file-text"></span>
                                        </span>
                                        <span class="menu-title">Turno OIC</span>
                                    </a>
                                </div>                              
                                
                                <div class="menu-item mb-1">
                                    <a href="{{route('turnoarchivo.index')}}" class="menu-link py-3">
                                        <span class="menu-bullet">
                                            <span class="fa fa-file-text"></span>
                                        </span>
                                        <span class="menu-title">Acuse envio archivo</span>
                                    </a>
                                </div>
                                          
                            </div>
                        </div> 
                        <div class="menu-item mb-1">                     
                            <a href="{{ route('pacauditoria.index') }}" class="menu-link py-3 {{ (str_contains(Route::current()->getName(), 'pacauditoria') || str_contains(Route::current()->getName(), 'comparecenciaacuse')|| str_contains(Route::current()->getName(), 'comparecenciaagenda')) ? 'active' : '' }}">
                                <span class="menu-bullet">
                                    <span class="bi bi-archive"></span>
                                </span>
                                <span class="menu-title">PAC</span>
                            </a>
                        </div>                      
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
