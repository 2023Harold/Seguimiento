<div class="col-md-3 mt-2">
    <div class="card p-2">
        <div class="card-body">
            <div class="menu menu-rounded menu-column menu-gray-800 menu-hover-light menu-here-light menu-show-light menu-state-bg-primary fw-bold w-175p"
                data-kt-menu="true">
                <div class="menu-item menu-accordion mb-1 {{ str_contains(Route::current()->getName(), 'radicacion') || str_contains(Route::current()->getName(), 'designacionanalista') || str_contains(Route::current()->getName(), 'asignacionlider') || str_contains(Route::current()->getName(), 'reasignaciondepartamentoseguimiento') || str_contains(Route::current()->getName(), 'designaciondepartamento') || str_contains(Route::current()->getName(), 'asignaciondireccionseguimiento')|| str_contains(Route::current()->getName(), 'movimientoanalista') ? 'show' : '' }} "
                    data-kt-menu-trigger="click">
                    <a href="#" class="menu-link py-3  {{ str_contains(Route::current()->getName(), 'radicacion') || str_contains(Route::current()->getName(), 'asignaciondir') || str_contains(Route::current()->getName(), 'constanciaasignacionseguimiento') || str_contains(Route::current()->getName(), 'constanciareasignacionseguimiento') || str_contains(Route::current()->getName(), 'movimientoanalista') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="far fa-bookmark fs-3"></i>
                        </span>
                        <span class="menu-title">Radicación</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="menu-sub menu-sub-accordion mx-5 me-0 pt-3 {{ str_contains(Route::current()->getName(), 'radicacion') || str_contains(Route::current()->getName(), 'designacionanalista') || str_contains(Route::current()->getName(), 'asignacionlider') || str_contains(Route::current()->getName(), 'reasignaciondepartamentoseguimiento') || str_contains(Route::current()->getName(), 'designaciondepartamento') || str_contains(Route::current()->getName(), 'asignaciondireccionseguimiento') || str_contains(Route::current()->getName(), 'asignacionseguimiento') || str_contains(Route::current()->getName(), 'movimientoanalista') ? 'show' : '' }}">
                        <div class="menu-item mb-1">
                            <a href="{{ route('asignaciondireccionseguimiento.index') }}" class="menu-link py-3 {{ str_contains(Route::current()->getName(), 'asignaciondireccionseguimiento') || str_contains(Route::current()->getName(), 'asignaciondir') || str_contains(Route::current()->getName(), 'constanciaasignacionseguimiento') || str_contains(Route::current()->getName(), 'constanciareasignacionseguimiento')|| str_contains(Route::current()->getName(), 'movimientoanalista') ? 'active' : '' }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Designación de dirección</span>
                            </a>
                        </div>
                        <div class="menu-item mb-1">
                            <a href="{{ route('designaciondepartamento.index') }}"
                                class="menu-link py-3 {{ str_contains(Route::current()->getName(), 'designaciondepartamento') || str_contains(Route::current()->getName(), 'reasignaciondepartamentoseguimiento') || str_contains(Route::current()->getName(), 'designaciondepartamento') ? 'active' : '' }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Designación del departamento</span>
                            </a>
                        </div>
                        <div class="menu-item mb-1">
                            <a href="{{ route('asignacionlider.index') }}"
                                class="menu-link py-3 {{ str_contains(Route::current()->getName(), 'asignacionlider') || str_contains(Route::current()->getName(), 'movimientoanalista') ? 'active' : '' }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Designación del líder de proyecto y equipo de analistas</span>
                            </a>
                        </div>
                        <div class="menu-item mb-1">
                            <a href="{{ route('radicacion.index') }}"
                                class="menu-link py-3 {{ str_contains(Route::current()->getName(), 'radicacion') ? 'active' : '' }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Datos de radicación</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="menu-item mb-1">
                    <a href="{{ route('comparecencia.index') }}" class="menu-link py-3 {{ str_contains(Route::current()->getName(), 'comparecencia') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="far fa-bookmark fs-3"></i>
                        </span>
                        <span class="menu-title">Comparecencia</span>
                    </a>
                </div>

                @canany(['prasauditoria.index', 'prasreconduccioncedula.index'])
                    <div class="menu-item menu-accordion mb-1 {{ (str_contains(Route::current()->getName(), 'pras') &&
                        !str_contains(Route::current()->getName(), 'aclaracionresultadoprorroga')) ||str_contains(Route::current()->getName(), 'aclaracionresultadoprorroga')? 'show'  : '' }}" data-kt-menu-trigger="click">
                        <a href="#" class="menu-link py-3  {{ str_contains(Route::current()->getName(), 'pras')||str_contains(Route::current()->getName(), 'prareconduccion') ? 'active' : '' }}">
                            <span class="menu-icon">
                                <i class="far fa-bookmark fs-3"></i>
                            </span>
                            <span class="menu-title">PRAS</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="menu-sub menu-sub-accordion mb-1 mx-5 me-0 pt-3 {{ (str_contains(Route::current()->getName(), 'aclaracionresultado') &&
                                !str_contains(Route::current()->getName(), 'prasauditoria')) || str_contains(Route::current()->getName(), 'praseguimiento') || str_contains(Route::current()->getName(), 'prasauditoria')||str_contains(Route::current()->getName(), 'prareconduccion') ? 'show' : '' }}">
                            <div class="menu-item mb-1">
                                <a href="{{ route('prasauditoriacedula.index') }}" class="menu-link py-3 {{ str_contains(Route::current()->getName(), 'praseguimiento') || str_contains(Route::current()->getName(), 'prasauditoria') ? 'active' : '' }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">PRAS turnados de auditoría</span>
                                </a>
                            </div>
                            <div class="menu-item mb-1">
                                <a href="{{ route('prasreconduccioncedula.index') }}" class="menu-link py-3 {{ str_contains(Route::current()->getName(), 'prasreconduccion')||str_contains(Route::current()->getName(), 'prareconduccion') ? 'active' : '' }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">PRAS (Reconducción de acciones en etapa de aclaración)</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endcanany

                <div class="menu-item menu-accordion mb-1 {{ str_contains(Route::current()->getName(), 'solventacionprimera') ||str_contains(Route::current()->getName(), 'solventacionsegundaetapacedula')||str_contains(Route::current()->getName(), 'prorrogasolventacion')||str_contains(Route::current()->getName(), 'solventacionsegundaetapadocumento') || str_contains(Route::current()->getName(), 'validacionsolventacion') || str_contains(Route::current()->getName(), 'revisionsolventacion')? 'show': '' }}" data-kt-menu-trigger="click">
                    <a href="#" class="menu-link py-3  {{ str_contains(Route::current()->getName(), 'solventacion') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="far fa-bookmark fs-3"></i>
                        </span>
                        <span class="menu-title">Solventación de la entidad fiscalizable</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="menu-sub menu-sub-accordion mb-1 mx-5 me-0 pt-3 {{ str_contains(Route::current()->getName(), 'solventacionprimera') || str_contains(Route::current()->getName(), 'promocion') ? 'show': '' }}">
                        <div class="menu-item mb-1">
                            <a href="{{ route('solventacionprimera.index')  }}"
                                class="menu-link py-3 {{ str_contains(Route::current()->getName(), 'solventacionprimera') ? 'active' : '' }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Solventación primera etapa</span>
                            </a>
                        </div>
                        <div class="menu-item mb-1">
                            <a href="{{ route('solventacionsegundaetapacedula.index')  }}"
                                class="menu-link py-3 {{ str_contains(Route::current()->getName(), 'solventacionsegundaetapadocumento') || str_contains(Route::current()->getName(), 'solventacionsegundaetapacedula') ? 'active' : '' }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Solventación segunda etapa</span>
                            </a>
                        </div>
                        <div class="menu-item mb-1">
                            <a href="{{ route('prorrogasolventacion.index') }}"
                                class="menu-link py-3 {{ str_contains(Route::current()->getName(), 'prorrogasolventacion') || str_contains(Route::current()->getName(), 'revisionsolventacion') || str_contains(Route::current()->getName(), 'validacionsolventacion') ? 'active' : '' }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Prórroga</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="menu-item mb-1">
                    <a href="{{ route('conclusionarchivocedula.index') }}"
                        class="menu-link py-3 {{ str_contains(Route::current()->getName(), 'conclusionarchivo') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="far fa-bookmark fs-3"></i></span><span class="menu-title">Conclusión y archivo</span>
                    </a>
                </div>

                <div class="menu-item menu-accordion mb-1 {{ str_contains(Route::current()->getName(), 'recomendacion')? 'show': '' }}" data-kt-menu-trigger="click">
                    <a href="#" class="menu-link py-3  {{ str_contains(Route::current()->getName(), 'recomendacion') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="far fa-bookmark fs-3"></i></span><span
                            class="menu-title">Recomendaciones</span><span class="menu-arrow"></span>
                    </a>
                    <div class="menu-sub menu-sub-accordion mb-1 mx-5 me-0 pt-3 {{ str_contains(Route::current()->getName(), 'turnodenunciajuicio') ||
                        str_contains(Route::current()->getName(), 'promocion') || str_contains(Route::current()->getName(), 'turnodenunciahecho')  ? 'show' : '' }}">
                        <div class="menu-item mb-1">
                            <a href="{{ route('recomendacionauditoria.index') }}" class="menu-link py-3 {{ str_contains(Route::current()->getName(), 'recomendacionaudit') || str_contains(Route::current()->getName(), 'recomendaciondato')
                            || str_contains(Route::current()->getName(), 'recomendacionestado') || str_contains(Route::current()->getName(), 'recomendacionconclusion') ? 'active' : '' }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Recomendaciones de auditoría</span>
                            </a>
                        </div>
                        <div class="menu-item mb-1">
                            <a href="{{ route('recomendacionseguimiento.index') }}" class="menu-link py-3 {{ str_contains(Route::current()->getName(), 'recomendacionsegui') ? 'active' : '' }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Recomendaciones de seguimiento</span>
                            </a>
                        </div>
                        {{-- <div class="menu-item mb-1">
                            <a href="{{ route('recomendaciondatoatencion.index') }}" class="menu-link py-3 {{ str_contains(Route::current()->getName(), 'recomendaciondatoatencion') ? 'active' : '' }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Datos de atención</span>
                            </a>
                        </div> --}}
                        {{-- <div class="menu-item mb-1">
                            <a href="{{ route('recomendacionestado.index') }}" class="menu-link py-3 {{ str_contains(Route::current()->getName(), 'recomendacionestado') ? 'active' : '' }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Oficio del estado</span>
                            </a>
                        </div> --}}
                        {{-- <div class="menu-item mb-1">
                            <a href="{{ route('recomendacionconclusion.index') }}" class="menu-link py-3 {{ str_contains(Route::current()->getName(), 'recomendacionconclusion') ? 'active' : '' }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Conclusión de atención</span>
                            </a>
                        </div> --}}
                    </div>
                </div>
                <div class="menu-item mb-1">
                    <a href="{{ route('verificacioneinspeccion.index') }}"
                        class="menu-link py-3 {{ str_contains(Route::current()->getName(), 'verificacion') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="far fa-bookmark fs-3"></i>
                        </span>
                        <span class="menu-title">Verificaciones e inspecciones</span>
                    </a>
                </div>
                <div class="menu-item mb-1">
                    <a href="{{ route('seguimientorequerimiento.index') }}"
                        class="menu-link py-3 {{ str_contains(Route::current()->getName(), 'seguimientorequerimiento') || str_contains(Route::current()->getName(), 'seguimientoinformacion') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="far fa-bookmark fs-3"></i>
                        </span>
                        <span class="menu-title">Requerimiento</span>
                    </a>
                </div>
                <div class="menu-item menu-accordion mb-1 {{ str_contains(Route::current()->getName(), 'turnodenunciajuicio') ||str_contains(Route::current()->getName(), 'informeprimeraetapa') ||str_contains(Route::current()->getName(), 'informesegundaetapa')? 'show': '' }}" data-kt-menu-trigger="click">
                    <a href="#"
                        class="menu-link py-3  {{ str_contains(Route::current()->getName(), 'turnodenunciajuicio') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="far fa-bookmark fs-3"></i>
                        </span>
                        <span class="menu-title">Informe de seguimiento</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div
                        class="menu-sub menu-sub-accordion mx-5 me-0 pt-3 {{ str_contains(Route::current()->getName(), 'turnodenunciajuicio') ||
                        str_contains(Route::current()->getName(), 'informeprimeraetapa') ||
                        str_contains(Route::current()->getName(), 'informesegundaetapa')
                            ? 'show'
                            : '' }}">
                        @can('turnodenunciajuicio.index')
                            <div class="menu-item mb-1">
                                <a href="{{ route('turnodenunciajuicio.index') }}"
                                    class="menu-link py-3 {{ str_contains(Route::current()->getName(), 'turnodenunciajuicio') ? 'active' : '' }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Datos de atención</span>
                                </a>
                            </div>
                        @endcan
                        <div class="menu-item mb-1">
                            <a href="{{ route('informeprimeraetapacedula.index') }}"
                                class="menu-link py-3 {{ str_contains(Route::current()->getName(), 'informeprimeraetapa') ? 'active' : '' }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Primera etapa de aclaración</span>
                            </a>
                        </div>
                        <div class="menu-item mb-1">
                            <a href="{{ route('informesegundaetapacedula.index') }}"
                                class="menu-link py-3 {{ str_contains(Route::current()->getName(), 'informesegundaetapa') ? 'active' : '' }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Segunda etapa de aclaración</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
