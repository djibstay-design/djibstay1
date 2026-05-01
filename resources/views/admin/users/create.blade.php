@extends('layouts.admin')
@section('page_title', 'Nouvel utilisateur')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:24px;">
    <div>
        <h1 style="font-size:22px;font-weight:900;color:#1e293b;margin:0;">👤 Nouvel utilisateur</h1>
        <p style="font-size:13px;color:#64748b;margin:4px 0 0;">Créer un compte administrateur</p>
    </div>
    <a href="{{ route('admin.users.index') }}"
       style="background:#f1f5f9;color:#475569;padding:9px 16px;border-radius:8px;text-decoration:none;font-weight:700;font-size:13px;display:inline-flex;align-items:center;gap:6px;">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 2px 12px rgba(0,53,128,0.07);overflow:hidden;">
            <div style="background:#f8fafc;border-bottom:1px solid #e2e8f0;padding:14px 22px;">
                <span style="font-size:15px;font-weight:800;color:#003580;"><i class="bi bi-person-plus me-2"></i>Informations du compte</span>
            </div>
            <div style="padding:24px;">
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Nom *</label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Nom de famille"
                                   style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;" required>
                            @error('name')<div style="color:#dc2626;font-size:12px;margin-top:3px;">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Prénom *</label>
                            <input type="text" name="prenom" value="{{ old('prenom') }}" placeholder="Prénom"
                                   style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;" required>
                        </div>
                        <div class="col-sm-6">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Email *</label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="email@djibstay.dj"
                                   style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;" required>
                            @error('email')<div style="color:#dc2626;font-size:12px;margin-top:3px;">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Téléphone</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+253 77 00 00 00"
                                   style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;">
                        </div>
                        <div class="col-sm-6">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Rôle *</label>
                            <select name="role" id="roleSelect" style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;" required onchange="toggleHotel()">
                                <option value="ADMIN"       {{ old('role')==='ADMIN'      ?'selected':'' }}>🏨 Admin Hôtel</option>
                                <option value="SUPER_ADMIN" {{ old('role')==='SUPER_ADMIN'?'selected':'' }}>🏆 Super Admin</option>
                            </select>
                        </div>
                        <div class="col-sm-6" id="hotelField">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Hôtel assigné</label>
                            <select name="hotel_id" style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;">
                                <option value="">Aucun</option>
                                @foreach($hotels as $hotel)
                                <option value="{{ $hotel->id }}" {{ old('hotel_id')==$hotel->id?'selected':'' }}>{{ $hotel->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Mot de passe *</label>
                            <input type="password" name="password" placeholder="Minimum 8 caractères"
                                   style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;" required>
                            @error('password')<div style="color:#dc2626;font-size:12px;margin-top:3px;">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Confirmer mot de passe *</label>
                            <input type="password" name="password_confirmation" placeholder="Répétez le mot de passe"
                                   style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;" required>
                        </div>
                        <div class="col-12">
                            <button type="submit"
                                    style="background:linear-gradient(135deg,#003580,#0071c2);color:#fff;border:none;border-radius:8px;font-weight:700;font-size:14px;padding:12px 24px;cursor:pointer;display:inline-flex;align-items:center;gap:7px;">
                                <i class="bi bi-check-lg"></i> Créer l'utilisateur
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div style="background:#f0f7ff;border-radius:12px;border:1px solid #bfdbfe;padding:18px 20px;">
            <div style="font-size:14px;font-weight:800;color:#003580;margin-bottom:12px;"><i class="bi bi-shield-check me-1"></i>Rôles disponibles</div>
            <div style="background:#fff;border-radius:8px;border:1px solid #bfdbfe;padding:12px 14px;margin-bottom:10px;">
                <div style="font-size:14px;font-weight:800;color:#003580;margin-bottom:4px;">🏆 Super Admin</div>
                <div style="font-size:12px;color:#64748b;line-height:1.6;">Accès complet à toute l'application — tous les hôtels, tous les utilisateurs, tous les paramètres.</div>
            </div>
            <div style="background:#fff;border-radius:8px;border:1px solid #bfdbfe;padding:12px 14px;">
                <div style="font-size:14px;font-weight:800;color:#0071c2;margin-bottom:4px;">🏨 Admin Hôtel</div>
                <div style="font-size:12px;color:#64748b;line-height:1.6;">Accès limité à son hôtel uniquement — chambres, réservations, avis et photos de son établissement.</div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleHotel() {
    const role = document.getElementById('roleSelect').value;
    document.getElementById('hotelField').style.display = role === 'ADMIN' ? 'block' : 'none';
}
toggleHotel();
</script>
@endsection