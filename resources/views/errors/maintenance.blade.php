<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Site en maintenance — {{ \App\Models\SiteSetting::get('app_name', 'DjibStay') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family:'Inter',sans-serif;
            background:linear-gradient(135deg,#003580,#0071c2);
            min-height:100vh; display:flex;
            align-items:center; justify-content:center;
            padding:20px;
        }
        .card {
            background:#fff; border-radius:20px;
            padding:48px 40px; text-align:center;
            max-width:500px; width:100%;
            box-shadow:0 20px 60px rgba(0,0,0,0.2);
        }
        .icon { font-size:64px; margin-bottom:20px; }
        h1 { font-size:28px; font-weight:900; color:#003580; margin-bottom:12px; }
        p  { font-size:16px; color:#64748b; line-height:1.7; margin-bottom:24px; }
        .badge {
            display:inline-flex; align-items:center; gap:8px;
            background:#fef3c7; color:#92400e;
            padding:10px 20px; border-radius:20px;
            font-size:14px; font-weight:700;
        }
        .logo {
            font-size:22px; font-weight:900; color:#003580;
            margin-bottom:28px; display:block;
        }
        .logo span { color:#febb02; }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">🏨 Djib<span>Stay</span></div>
        <div class="icon">🔧</div>
        <h1>Site en maintenance</h1>
        <p>{{ $message ?? 'Nous effectuons des améliorations. Revenez bientôt !' }}</p>
        <div class="badge">
            <i class="bi bi-clock"></i>
            Retour très bientôt
        </div>
    </div>
</body>
</html>