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
    <script>
			// Mixin de SweetAlert2 que usa clases de Bootstrap/Metronic


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
</html>
