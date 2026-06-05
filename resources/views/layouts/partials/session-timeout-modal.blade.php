<div id="session-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:12px; padding:2rem; max-width:400px; width:90%; text-align:center; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
        <div style="font-size:5rem;">
            <span class="fa fa-clock" aria-hidden="true"></span>
        </div>
        <h2 style="margin:0.5rem 0; color:#333;">¿Sigues ahí?</h2>
        <p style="color:#666;">Tu sesión expirará en:</p>
        <div id="session-countdown" class="text-primary h1">
            00:15
        </div>
        <p style="color:#666; font-size:0.9rem;">Si no respondes, cerraremos tu sesión automáticamente.</p>
        <div style="display:flex; gap:1rem; justify-content:center; margin-top:1.5rem;">
            <button onclick="extendSession()" class="btn btn-secondary">
               <span class="fa fa-check"></span> Continuar
            </button>
            <button onclick="logoutNow()" class="btn btn-primary" >
                <span class="fa fa-times"></span> &nbsp;&nbsp;&nbsp;Cerrar sesión
            </button>
        </div>
    </div>
</div>
<script>
    const LIFETIME_SECONDS = {{ (config('session.lifetime') * 60) - 10 }};
    const WARNING_BEFORE   = 15;

    let warningTimer   = null;
    let countdownTimer = null;
    let secondsLeft    = WARNING_BEFORE;
    let modalVisible   = false; // ← bandera para ignorar actividad cuando el modal está abierto

    const modal     = document.getElementById('session-modal');
    const countdown = document.getElementById('session-countdown');

    // ── Detectar actividad — SOLO teclado y clicks, NO mouse movement ──
    ['keydown', 'click', 'touchstart'].forEach(event => {
        document.addEventListener(event, resetTimers, { passive: true });
    });

    function resetTimers() {
        // Si el modal ya está visible, ignorar actividad
        if (modalVisible) return;

        clearTimeout(warningTimer);
        scheduleWarning();
    }

    // ── Programar cuándo mostrar el modal ──────────────────────────
    function scheduleWarning() {
        const delay = (LIFETIME_SECONDS - WARNING_BEFORE) * 1000;
        warningTimer = setTimeout(showWarning, delay);
    }

    function showWarning() {
        modalVisible        = true; // ← bloquea resetTimers
        secondsLeft         = WARNING_BEFORE;
        modal.style.display = 'flex';
        startCountdown();
    }

    // ── Cuenta regresiva ───────────────────────────────────────────
    function startCountdown() {
        clearInterval(countdownTimer);
        updateCountdownDisplay();

        countdownTimer = setInterval(() => {
            secondsLeft--;
            updateCountdownDisplay();

            if (secondsLeft <= 0) {
                clearInterval(countdownTimer);
                logoutNow();
            }
        }, 1000);
    }

    function updateCountdownDisplay() {
        const m = String(Math.floor(secondsLeft / 60)).padStart(2, '0');
        const s = String(secondsLeft % 60).padStart(2, '0');
        countdown.textContent = `${m}:${s}`;
        countdown.style.color = secondsLeft <= 5 ? '#c0392b' : '#e74c3c';
    }

    // ── Acciones del modal ─────────────────────────────────────────
    function extendSession() {
        clearInterval(countdownTimer);
        modalVisible        = false; // ← desbloquea actividad
        modal.style.display = 'none';

        fetch('{{ route("session.extend") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            }
        }).then(() => scheduleWarning());
    }

    function logoutNow() {
        clearInterval(countdownTimer);
        window.location.href = '{{ route("session.timeout.logout") }}';
    }

    // ── Iniciar al cargar la página ────────────────────────────────
    scheduleWarning();
</script>