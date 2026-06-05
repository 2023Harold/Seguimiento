(function () {
    // ===== GLOBAL SPINNER =====
    window.GlobalSpinner = {
        show() {
            // Guardia principal: si SweetAlert2 está visible, NO mostrar el spinner encima.
            // Esto evita que el spinner bloquee cualquier diálogo de Swal activo.
            if (typeof Swal !== 'undefined' && Swal.isVisible()) return;
            document.getElementById('global-spinner-overlay')?.classList.add('active');
        },
        // showForced: úsalo cuando necesitas mostrar el spinner incluso si Swal acaba de cerrar
        // (ej: justo después de que un Swal confirm cierra y vas a hacer submit/navigate).
        showForced() {
            document.getElementById('global-spinner-overlay')?.classList.add('active');
        },
        hide() {
            document.getElementById('global-spinner-overlay')?.classList.remove('active');
        }
    };

    // ===== LOAD =====
    window.addEventListener('load', () => GlobalSpinner.hide());

    // ===== BACK/FORWARD CACHE FIX =====
    window.addEventListener('pageshow', () => GlobalSpinner.hide());

    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') {
            GlobalSpinner.hide();
        }
    });

    // ===== EXPORTAR EXCEL (SheetJS) =====
    // Fase de CAPTURA (true) para correr ANTES que cualquier listener de burbuja.
    // stopImmediatePropagation cancela el listener de links genérico para que no active el spinner.
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-export-movimientos');
        if (!btn) return;

        e.stopImmediatePropagation();

        const id   = btn.dataset.id;
        const rows = JSON.parse(btn.dataset.rows);

        if (!rows.length) return;

        const ws = XLSX.utils.json_to_sheet(rows);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Movimientos');

        const colWidths = Object.keys(rows[0]).map(key => ({
            wch: Math.max(key.length, ...rows.map(r => String(r[key] ?? '').length))
        }));
        ws['!cols'] = colWidths;

        XLSX.writeFile(wb, `movimientos-${id}.xlsx`);

        // Ocultar spinner por si otro handler lo activó antes de llegar aquí
        setTimeout(() => GlobalSpinner.hide(), 300);
    }, true);

    // ===== SWEET ALERT — BOTONES DE CONFIRMACIÓN (.sweet-*) =====
    // Este handler se registra ANTES del handler genérico de links,
    // por lo que e.defaultPrevented ya estará en true cuando el handler de links evalúe el mismo clic.
    document.addEventListener('click', function (e) {

        const btn = e.target.closest('.sweet-comentarios, .sweet-sincronizar, .sweet-contestacion, .js-confirm-delete');
        if (!btn) return;

        // Prevenir navegación inmediata y que el handler de links genérico active el spinner
        e.preventDefault();

        Swal.fire({
            icon: 'warning',
            title: btn.dataset.title || '¿Seguro?',
            text: btn.dataset.text || 'Esta acción no se puede deshacer.',
            showCancelButton: true,
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'Cancelar'
        }).then(result => {

            if (result.isConfirmed) {
                // Mostrar feedback visual y luego navegar.
                // El spinner aparece dentro del willClose del Swal de éxito,
                // asegurando que Swal ya cerró cuando el spinner entra.
                Swal.fire({
                    icon: 'success',
                    title: 'Procesando...',
                    timer: 800,
                    showConfirmButton: false,
                    // willClose: justo antes de que la animación de cierre termine → spinner listo
                    willClose: () => GlobalSpinner.showForced()
                });

                // Navegar después del timer del Swal de éxito
                setTimeout(() => { window.location.href = btn.href; }, 800);
            }

        });

    });

    // ===== LINKS — SPINNER GENÉRICO =====
    document.addEventListener('click', function (e) {

        const link = e.target.closest('a');
        if (!link) return;

        // Si otro handler ya llamó preventDefault (Swal, jQuery Colorbox, Bootstrap modal, etc.),
        // el link NO va a navegar → no tiene sentido mostrar el spinner.
        if (e.defaultPrevented) return;

        // Atributo explícito en el link para deshabilitar el spinner: data-no-spinner
        if (link.dataset.noSpinner !== undefined) return;

        // Excluir clases de jQuery Colorbox (popups internos que NO navegan la página actual)
        if (link.classList.contains('popupventana') ||
            link.classList.contains('popupcomentario') ||
            link.classList.contains('popuprevisar')) return;

        // Excluir triggers de Bootstrap modal/collapse/tab/dropdown
        if (link.dataset.bsToggle || link.dataset.toggle) return;

        if (
            link.href &&
            !link.href.startsWith('javascript:') &&
            !link.href.includes('#') &&
            !link.target &&                             // excluye target="_blank" y similares
            link.origin === window.location.origin
        ) {
            GlobalSpinner.show();
        }

    });
    const SwalBT = Swal.mixin({
        buttonsStyling: false,
        customClass: {
            confirmButton: 'btn btn-sm btn-primary',
            cancelButton: 'btn btn-sm btn-secondary',
            denyButton: 'btn btn-sm btn-danger'
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

    window.sweetConfirm = function (message = '¿Confirmas?', opts = {}) {
        return Swal.fire({
            icon: opts.icon || 'question',
            title: opts.title || 'Confirmar',
            text: message,
            showCancelButton: true,
            confirmButtonText: opts.confirmText || 'Sí',
            cancelButtonText: opts.cancelText || 'Cancelar',
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-sm btn-primary',
                cancelButton: 'btn btn-sm btn-secondary'
            }
        }).then(r => !!r.isConfirmed);
    };
    // === Helpers rápidos (por si prefieres nombres explícitos) ===
    window.SwalNotify = {
        success: (msg, title = 'Listo') => Toast.fire({ icon: 'success', title: title, text: String(msg || '') }),
        info: (msg, title = 'Aviso') => Toast.fire({ icon: 'info', title: title, text: String(msg || '') }),
        warn: (msg, title = 'Atención') => Toast.fire({ icon: 'warning', title: title, text: String(msg || '') }),
        error: (msg, title = 'Error') => SwalBT.fire({ icon: 'error', title: title, html: `<div style="text-align:left;">${String(msg || '')}</div>` })
    };
    // ===== FORMS CON data-swal-confirm =====
    // Intercepta el submit, muestra confirmación Swal, y sólo envía si el usuario confirma.
    // IMPORTANTE: form.submit() nativo NO dispara el evento 'submit', por eso hay que
    // llamar GlobalSpinner.showForced() manualmente antes de enviarlo.
    (function attachSwalConfirmToForms() {
        document.addEventListener('submit', function (ev) {
            const form = ev.target;
            if (!(form instanceof HTMLFormElement)) return;

            const msg = form.dataset.swalConfirm;
            if (!msg) return; // solo forms marcados con data-swal-confirm

            // Detener el submit automático del navegador
            ev.preventDefault();
            ev.stopImmediatePropagation();

            // Anti doble ejecución mientras el Swal está abierto
            if (form.dataset.confirming === '1') return;
            form.dataset.confirming = '1';

            Swal.fire({
                icon: 'warning',
                title: '¿Desea eliminar el registro?',
                text: 'Esta acción no se puede deshacer.',
                showCancelButton: true,
                confirmButtonText: form.dataset.swalConfirmText || 'Confirmar',
                cancelButtonText:  form.dataset.swalCancelText  || 'Cancelar',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-sm btn-danger',
                    cancelButton:  'btn btn-sm btn-primary'
                },
                allowOutsideClick: false,
                allowEscapeKey: true
            }).then(res => {
                if (res.isConfirmed) {
                    // Mostrar Swal de éxito y, cuando cierre, activar spinner y hacer submit.
                    // willClose: el spinner entra ANTES de que Swal termine su animación de salida,
                    // dando una transición visual fluida sin lapso en blanco.
                    Swal.fire({
                        icon: 'success',
                        title: 'Registro eliminado',
                        timer: 1200,
                        showConfirmButton: false,
                        willClose: () => GlobalSpinner.showForced()
                    }).then(() => {
                        // form.submit() nativo no dispara el evento 'submit',
                        // así que GlobalSpinner.show() del listener de forms no corre aquí.
                        // Por eso usamos showForced() en willClose arriba.
                        form.submit();
                    });
                } else {
                    // Usuario canceló → limpiar flag para permitir reintento
                    delete form.dataset.confirming;
                }
            });
        }, true); // fase capture: corre antes que otros listeners de submit

    })();

    // ===== FORMS =====
    document.addEventListener('submit', function (e) {

        const form = e.target;

        // excluir swal forms
        if (form.dataset.swalConfirm !== undefined) return;

        GlobalSpinner.show();
    });

    // ===== AXIOS =====
    if (window.axios) {

        axios.interceptors.request.use(config => {
            GlobalSpinner.show();
            return config;
        });

        axios.interceptors.response.use(
            response => {
                GlobalSpinner.hide();
                return response;
            },
            error => {
                GlobalSpinner.hide();
                return Promise.reject(error);
            }
        );
    }

    // ===== JQUERY AJAX =====
    if (window.$) {
        $(document).ajaxStart(() => GlobalSpinner.show());
        $(document).ajaxStop(() => GlobalSpinner.hide());
    }

})();

