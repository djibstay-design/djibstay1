<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', \App\Models\SiteSetting::get('app_name', 'DjibStay')) — {{ \App\Models\SiteSetting::get('app_slogan', 'Réservation Hôtels') }}</title>
    <meta name="description" content="@yield('meta_description', 'Réservez sur ' . \App\Models\SiteSetting::get('app_name', 'DjibStay'))">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
        :root {
            --blue:        #003580;
            --blue-light:  #0071c2;
            --blue-hover:  #005fa3;
            --yellow:      #febb02;
            --yellow-hover:#f5a623;
            --gray-bg:     #f2f6fc;
            --radius:      8px;
            --shadow:      0 2px 12px rgba(0,53,128,0.10);
            --transition:  all 0.22s cubic-bezier(0.4,0,0.2,1);
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        html { scroll-behavior:smooth; }
        body {
            font-family:'Inter',sans-serif;
            background:#f2f6fc;
            color:#1a1a2e;
            line-height:1.6;
            min-height:100vh;
            display:flex;
            flex-direction:column;
        }
        main { flex:1; }

        /* ── NAVBAR ── */
        .djibstay-nav {
            background: var(--blue) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.25);
            position: sticky;
            top: 0;
            z-index: 1050;
            min-height: 64px;
        }
        .djibstay-nav-inner {
            max-width: 1320px;
            margin: 0 auto;
            padding: 0 24px;
        }
        .djib-brand .logo-text { color: var(--yellow); font-weight:800; font-size:21px; }
        .djib-brand .navbar-tagline { font-size:9.5px; color:rgba(255,255,255,0.7); text-transform:uppercase; letter-spacing:.6px; }
        .djibstay-nav .nav-link {
            color: rgba(255,255,255,0.88) !important;
            font-size: 14px;
            font-weight: 500;
            padding: 7px 14px !important;
            border-radius: 6px;
            border: 1px solid transparent;
            transition: var(--transition);
        }
        .djibstay-nav .nav-link:hover,
        .djibstay-nav .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: #fff !important;
            border-color: rgba(255,255,255,0.3);
        }
        .djibstay-nav .nav-link.nav-cta {
            background: var(--yellow);
            color: var(--blue) !important;
            font-weight: 700;
            border-color: var(--yellow);
        }
        .djibstay-nav .nav-link.nav-cta:hover { background: var(--yellow-hover); }

        /* ── ALERTS ── */
        .alert-djib {
            max-width: 1320px;
            margin: 12px auto 0;
            padding: 0 24px;
        }

    

        /* ── BADGES STATUT ── */
        .badge-en_attente  { background:#fff3cd; color:#856404; }
        .badge-confirmee   { background:#d1e7dd; color:#0f5132; }
        .badge-annulee     { background:#f8d7da; color:#842029; }

        /* ── UTILITAIRES ── */
        .btn-djib-primary {
            background: var(--blue);
            color: #fff;
            border: none;
            border-radius: var(--radius);
            font-weight: 600;
            padding: 10px 24px;
            transition: var(--transition);
        }
        .btn-djib-primary:hover { background: var(--blue-light); color:#fff; }
        .btn-djib-yellow {
            background: var(--yellow);
            color: var(--blue);
            border: none;
            border-radius: var(--radius);
            font-weight: 700;
            padding: 10px 24px;
            transition: var(--transition);
        }
        .btn-djib-yellow:hover { background: var(--yellow-hover); color: var(--blue); }
        .card-djib {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: var(--shadow);
            background: #fff;
            transition: var(--transition);
        }
        .card-djib:hover { box-shadow: 0 8px 32px rgba(0,53,128,0.15); transform: translateY(-2px); }
    </style>
</head>
<body>

    {{-- NAVBAR --}}
    @include('partials.djibstay-nav')

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="alert-djib">
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mt-3" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert-djib">
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mt-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ session('error') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="alert-djib">
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Veuillez corriger les erreurs suivantes :</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    {{-- CONTENU --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}{{-- FOOTER --}}
@include('partials.djibstay-footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
// ══ VÉRIFICATION EMAIL EN TEMPS RÉEL ══
(function() {
    let emailTimers = {};

    function initEmailVerification() {
        document.querySelectorAll('input[type="email"]:not([data-no-verify])').forEach(input => {
            if (input.dataset.emailVerifyInit) return;
            input.dataset.emailVerifyInit = '1';

            // Créer le message d'erreur
            let msg = document.createElement('div');
            msg.className = 'email-verify-msg';
            msg.style.cssText = 'font-size:12px;margin-top:4px;display:none;padding:4px 10px;border-radius:6px;font-weight:600;';
            input.parentNode.insertBefore(msg, input.nextSibling);

            // Créer le spinner
            let spinner = document.createElement('span');
            spinner.className = 'email-spinner';
            spinner.innerHTML = ' ⏳';
            spinner.style.cssText = 'font-size:12px;display:none;';
            input.parentNode.insertBefore(spinner, msg);

            input.addEventListener('input', function() {
                const email = this.value.trim();
                const id    = input.name || Math.random();

                // Reset
                msg.style.display   = 'none';
                spinner.style.display = 'none';
                input.style.borderColor = '';
                clearTimeout(emailTimers[id]);

                if (!email || email.length < 5) return;
                if (!email.includes('@') || !email.includes('.')) return;

                // Délai de 800ms après arrêt de frappe
                emailTimers[id] = setTimeout(async () => {
                    spinner.style.display = 'inline';
                    try {
                        const res = await fetch('/api/verify-email', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                            },
                            body: JSON.stringify({ email })
                        });
                        const data = await res.json();
                        spinner.style.display = 'none';

                        if (data.valid) {
                            input.style.borderColor  = '#22c55e';
                            msg.style.display        = 'block';
                            msg.style.background     = '#dcfce7';
                            msg.style.color          = '#14532d';
                            msg.textContent          = '✅ Email valide';
                            setTimeout(() => { msg.style.display = 'none'; input.style.borderColor = ''; }, 2000);
                        } else {
                            input.style.borderColor  = '#dc2626';
                            msg.style.display        = 'block';
                            msg.style.background     = '#fee2e2';
                            msg.style.color          = '#991b1b';
                            msg.textContent          = '❌ ' + data.message;
                        }
                    } catch(e) {
                        spinner.style.display = 'none';
                    }
                }, 800);
            });

            // Bloquer la soumission si email invalide
            const form = input.closest('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (input.style.borderColor === 'rgb(220, 38, 38)' || input.style.borderColor === '#dc2626') {
                        e.preventDefault();
                        input.focus();
                        msg.style.display = 'block';
                        input.style.animation = 'shake 0.3s';
                        setTimeout(() => input.style.animation = '', 300);
                    }
                });
            }
        });
    }

    // Animation shake
    const style = document.createElement('style');
    style.textContent = `
        @keyframes shake {
            0%,100%{ transform:translateX(0); }
            25%    { transform:translateX(-6px); }
            75%    { transform:translateX(6px); }
        }
    `;
    document.head.appendChild(style);

    // Init au chargement
    document.addEventListener('DOMContentLoaded', initEmailVerification);
    // Réinit si contenu dynamique
    const observer = new MutationObserver(initEmailVerification);
    observer.observe(document.body, { childList: true, subtree: true });
})();
</script>
    @stack('scripts')
</body>
</html>