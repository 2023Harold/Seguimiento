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
						<div class="container-fluid d-flex align-items-stretch justify-content-between" style="background-image: url({{asset('assets/img/fondo_banner.png')}});">
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
							<input type="hidden" value="{{config('session.lifetime')}}" id="tiempo"/>        
							<div class="modal" id="create" role="dialog">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-body">
											<span id="countdown" class="text-primary font-weight-bold"></span>
										</div>
										<div class="modal-footer">
											<input type="submit" class="btn btn-primary" value="Actualizar">
										</div>                   
									</div>
								</div>
							</div>        
							<a href="#" class=""  id="conteo_cierre" data-toggle="modal" data-target="#create"></a>												
							<!--end::Copyright-->
							<!--begin::Menu-->
							<!--end::Menu-->
						</div>
						<!--end::Container-->
					</div>
					<!--end::Footer-->
					
					<!--<aside class="left-sidebar">
						{{--@include('layouts.partials._aside')--}}
					</aside>--> 
					
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
		{{-- SWEET ALERT PARA CUANDO DETECTA ALERT --}}
		@if(session('success') || session('status') || session('message'))
		<script>
			SwalNotify.success(@json(session('success') ?? session('status') ?? session('message')), 'Éxito');
		</script>
		@endif

		@if(session('error'))
		<script>
			SwalNotify.error(@json(session('error')), 'Error');
		</script>
		@endif

		@if(session('warning'))
		<script>
			SwalNotify.warn(@json(session('warning')), 'Atención');
		</script>
		@endif

		@if(session('info'))
		<script>
			SwalNotify.info(@json(session('info')), 'Aviso');
		</script>
		@endif

        @include('layouts.partials._foot')
        @yield('script')
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	
		<script>
			// Mixin de SweetAlert2 que usa clases de Bootstrap/Metronic

			///SWEETALERT para tipologias o para eliminar y confirmar
			document.addEventListener('click', function (e) {
				const btn = e.target.closest('.js-confirm-delete');
				if (!btn) return;

				e.preventDefault();

				const url = btn.getAttribute('href');
				const title = btn.dataset.confirmTitle || '¿Confirmar?';
				const text = btn.dataset.confirmText || '';
				const successText = btn.dataset.successText || 'Eliminado correctamente';

				Swal.fire({
					icon: 'warning',
					title: title,
					text: text,
					showCancelButton: true,
					confirmButtonText: 'Sí, eliminar',
					cancelButtonText: 'Cancelar',
					buttonsStyling: false,
					customClass: {
						confirmButton: 'btn btn-sm btn-danger',
						cancelButton: 'btn btn-sm btn-primary'
					}
				}).then(result => {
					if (!result.isConfirmed) return;

					// Paso 2: mostrar feedback inmediato
					Swal.fire({
						icon: 'success',
						title: 'Eliminado',
						text: successText,
						timer: 1200,
						showConfirmButton: false
					});

					// Paso 3: navegar después del mensaje
					setTimeout(() => {
						window.location.href = url;
					}, 1200);
				});
			});


			$(document).ready(function() {
				// --- Delegación para "Eliminar" usando SweetAlert2 ---
				// NOTA: Esto funciona siempre que el helper NO tenga el confirm() inline.
				document.querySelectorAll('a[id$="-upload-delete-link"]').forEach(function (btn) {
					btn.addEventListener('click', function (e) {
						e.preventDefault();

						const field = btn.getAttribute('data-field');              // nombre base del campo
						const fileInputId = btn.getAttribute('data-fileinput-id'); // p.ej. campo-upload
						const hidden = document.getElementById(field);
						const viewBtn = document.getElementById(field + '-upload-upload-link');
						const delBtn = document.getElementById(field + '-upload-delete-link');
						const $fileInput = $('#' + fileInputId);

						Swal.fire({
							title: '¿Deseas eliminar el archivo?',
							text: 'Esta acción no se puede deshacer.',
							icon: 'warning',
							showCancelButton: true,
							confirmButtonText: 'Sí, eliminar',
							cancelButtonText: 'Cancelar',
							buttonsStyling: false,
							customClass: {
								confirmButton: 'btn btn-sm btn-danger',   // úsalo para “acción destructiva”
								cancelButton:  'btn btn-sm btn-primary'
							}


						}).then((result) => {
							if (result.isConfirmed) {
								if (viewBtn) viewBtn.style.display = 'none';
								if (delBtn) delBtn.style.display = 'none';
								if (hidden) hidden.value = '';
								if ($fileInput && $fileInput.fileinput) {
									$fileInput.fileinput('reset');

								}
								Swal.fire({
									icon: 'success',
									title: 'Archivo eliminado',
									timer: 1200,
									showConfirmButton: false
								});
							}
						});
					});
				});

			}); // fin document.ready

			// === Mixin buttons estilo Bootstrap/Metronic (ya lo tenías como SwalBT) ===
			const SwalBT = Swal.mixin({
				buttonsStyling: false,
				customClass: {
				confirmButton: 'btn btn-sm btn-primary',
				cancelButton:  'btn btn-sm btn-secondary',
				denyButton:    'btn btn-sm btn-danger'
				}
			});

			// === Mixin de "toast" (esquinas, autodesaparición) ===
			const Toast = Swal.mixin({
				toast: true,
				position: 'top-end',    // puedes cambiar a 'bottom-end'
				showConfirmButton: false,
				timer: 2500,
				timerProgressBar: true,
			});
			
			window.sweetConfirm = function(message='¿Confirmas?', opts={}) {
			return Swal.fire({
				icon: opts.icon || 'question',
				title: opts.title || 'Confirmar',
				text: message,
				showCancelButton: true,
				confirmButtonText: opts.confirmText || 'Sí',
				cancelButtonText:  opts.cancelText  || 'Cancelar',
				buttonsStyling: false,
				customClass: {
				confirmButton: 'btn btn-sm btn-primary',
				cancelButton:  'btn btn-sm btn-secondary'
				}
			}).then(r => !!r.isConfirmed);
			};

			// === Helpers rápidos (por si prefieres nombres explícitos) ===
			window.SwalNotify = {
				success: (msg, title='Listo') => Toast.fire({ icon: 'success', title: title, text: String(msg || '') }),
				info:    (msg, title='Aviso') => Toast.fire({ icon: 'info',    title: title, text: String(msg || '') }),
				warn:    (msg, title='Atención') => Toast.fire({ icon: 'warning', title: title, text: String(msg || '') }),
				error:   (msg, title='Error') => SwalBT.fire({ icon: 'error', title: title, html: `<div style="text-align:left;">${String(msg||'')}</div>` })
			};
			// Intercepta cualquier form con data-swal-confirm y muestra SweetAlert2 antes de enviar
			(function attachSwalConfirmToForms() {
				document.addEventListener('submit', function (ev) {
					const form = ev.target;
					if (!(form instanceof HTMLFormElement)) return;

					const msg = form.dataset.swalConfirm;
					if (!msg) return; // solo forms marcados

					// Evita submit automático
					ev.preventDefault();
					ev.stopImmediatePropagation();

					// Anti doble ejecución
					if (form.dataset.confirming === '1') return;
					form.dataset.confirming = '1';

					Swal.fire({
					icon: 'warning',
					title: '¿Desea eliminar el registro?',
					text: "Esta acción no se puede deshacer.",
					showCancelButton: true,
					confirmButtonText: form.dataset.swalConfirmText || 'Confirmar',
					cancelButtonText:  form.dataset.swalCancelText  || 'Cancelar',
					buttonsStyling: false,
					customClass: {
						confirmButton: 'btn btn-sm btn-danger',
						cancelButton:  'btn btn-sm btn-primary'
					},
					// Opcional: evita que la rueda del mouse lo cierre por accidente
					allowOutsideClick: false,
					allowEscapeKey: true
					}).then(res => {
					if (res.isConfirmed) {
						// Submit intencional
						Swal.fire({
							icon: 'success',
							title: 'Accion eliminada',
							timer: 1200,
							showConfirmButton: false
						});
						form.submit();
					} else {
						// Permite reintentar
						delete form.dataset.confirming;
					}
					});
				}, true); // capture para ganarle a otros listeners
				})();

			</script>
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