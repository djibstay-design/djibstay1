@extends('layouts.admin')
@section('page_title', 'Modifier utilisateur')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:24px;">
    <div>
        <h1 style="font-size:22px;font-weight:900;color:#1e293b;margin:0;">✏️ {{ $user->prenom ?? '' }} {{ $user->name }}</h1>
        <p style="font-size:13px;color:#64748b;margin:4px 0 0;">Modifier le compte</p>
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
                <span style="font-size:15px;font-weight:800;color:#003580;"><i class="bi bi-pencil-square me-2"></i>Informations</span>
            </div>
            <div style="padding:24px;">
                <form method="POST" action="{{ route('admin.users.update',$user) }}">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Nom *</label>
                            <input type="text" name="name" value="{{ old('name',$user->name) }}"
                                   style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;" required>
                        </div>
                        <div class="col-sm-6">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Prénom</label>
                            <input type="text" name="prenom" value="{{ old('prenom',$user->prenom) }}"
                                   style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;">
                        </div>
                        <div class="col-sm-6">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Email *</label>
                            <input type="email" name="email" value="{{ old('email',$user->email) }}"
                                   style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;" required>
                            @error('email')<div style="color:#dc2626;font-size:12px;margin-top:3px;">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Téléphone</label>
                            <input type="tel" name="phone" value="{{ old('phone',$user->phone) }}"
                                   style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;">
                        </div>
                        <div class="col-sm-6">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Rôle *</label>
                            <select name="role" id="roleSelect" style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;" required onchange="toggleHotel()"
                                    {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                <option value="ADMIN"       {{ old('role',$user->role)==='ADMIN'      ?'selected':'' }}>🏨 Admin Hôtel</option>
                                <option value="SUPER_ADMIN" {{ old('role',$user->role)==='SUPER_ADMIN'?'selected':'' }}>🏆 Super Admin</option>
                            </select>
                            @if($user->id === auth()->id())
                            <input type="hidden" name="role" value="{{ $user->role }}">
                            <div style="font-size:11px;color:#94a3b8;margin-top:3px;">Vous ne pouvez pas modifier votre propre rôle</div>
                            @endif
                        </div>
                        <div class="col-sm-6" id="hotelField">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Hôtel assigné</label>
                            <select name="hotel_id" style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;">
                                <option value="">Aucun</option>
                                @foreach($hotels as $hotel)
                                <option value="{{ $hotel->id }}" {{ old('hotel_id',$user->managedHotels->first()?->id ?? '')==$hotel->id?'selected':'' }}>
                                    {{ $hotel->nom }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Nouveau mot de passe optionnel --}}
                        <div class="col-12">
                            <div style="background:#f8fafc;border-radius:10px;border:1px solid #e2e8f0;padding:16px;">
                                <div style="font-size:13px;font-weight:700;color:#003580;margin-bottom:12px;"><i class="bi bi-lock me-1"></i>Changer le mot de passe (optionnel)</div>
                                <div class="row g-2">
                                    <div class="col-sm-6">
                                        <input type="password" name="password" placeholder="Nouveau mot de passe"
                                               style="border:2px solid #e2e8f0;border-radius:8px;padding:9px 12px;font-size:13px;width:100%;">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" name="password_confirmation" placeholder="Confirmer"
                                               style="border:2px solid #e2e8f0;border-radius:8px;padding:9px 12px;font-size:13px;width:100%;">
                                    </div>
                                </div>
                                <div style="font-size:11px;color:#94a3b8;margin-top:6px;">Laissez vide pour conserver le mot de passe actuel</div>
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit"
                                    style="background:linear-gradient(135deg,#003580,#0071c2);color:#fff;border:none;border-radius:8px;font-weight:700;font-size:14px;padding:12px 24px;cursor:pointer;display:inline-flex;align-items:center;gap:7px;">
                                <i class="bi bi-check-lg"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:18px 20px;margin-bottom:16px;">
            <div style="text-align:center;margin-bottom:14px;">
                <div style="width:64px;height:64px;background:linear-gradient(135deg,#003580,#0071c2);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:26px;font-weight:800;color:#fff;margin:0 auto 10px;">
                    {{ strtoupper(substr($user->prenom ?? $user->name,0,1)) }}
                </div>
                <div style="font-size:16px;font-weight:800;color:#1e293b;">{{ $user->prenom ?? '' }} {{ $user->name }}</div>
                <div style="font-size:13px;color:#64748b;">{{ $user->email }}</div>
            </div>
            @foreach([
                ['Rôle', $user->role==='SUPER_ADMIN'?'🏆 Super Admin':'🏨 Admin Hôtel'],
                ['Membre depuis', $user->created_at->format('d/m/Y')],
                ['Téléphone', $user->phone ?? '—'],
            ] as [$l,$v])
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f1f5f9;font-size:13px;">
                <span style="color:#64748b;">{{ $l }}</span>
                <span style="font-weight:700;color:#1e293b;">{{ $v }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script>
function toggleHotel() {
    const sel = document.getElementById('roleSelect');
    if (!sel) return;
    document.getElementById('hotelField').style.display = sel.value === 'ADMIN' ? 'block' : 'none';
}
toggleHotel();
</script>
@endsection