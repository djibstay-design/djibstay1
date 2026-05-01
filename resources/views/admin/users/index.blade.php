@extends('layouts.admin')
@section('page_title', 'Utilisateurs')

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:24px;">
    <div>
        <h1 style="font-size:22px;font-weight:900;color:#1e293b;margin:0;">👥 Utilisateurs</h1>
        <p style="font-size:13px;color:#64748b;margin:4px 0 0;">{{ $users->total() }} utilisateur(s) au total</p>
    </div>
    <a href="{{ route('admin.users.create') }}"
       style="background:#003580;color:#fff;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:700;font-size:14px;display:inline-flex;align-items:center;gap:8px;">
        <i class="bi bi-plus-lg"></i> Nouvel utilisateur
    </a>
</div>

{{-- Stats --}}
@php
    $totalUsers  = $users->total();
    $superAdmins = \App\Models\User::where('role','SUPER_ADMIN')->count();
    $admins      = \App\Models\User::where('role','ADMIN')->count();
@endphp
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:24px;">
    <div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:16px;text-align:center;box-shadow:0 2px 8px rgba(0,53,128,0.07);">
        <div style="font-size:24px;font-weight:900;color:#003580;">{{ $totalUsers }}</div>
        <div style="font-size:12px;color:#64748b;font-weight:600;margin-top:3px;">Total admins</div>
    </div>
    <div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:16px;text-align:center;box-shadow:0 2px 8px rgba(0,53,128,0.07);">
        <div style="font-size:24px;font-weight:900;color:#7c3aed;">{{ $superAdmins }}</div>
        <div style="font-size:12px;color:#64748b;font-weight:600;margin-top:3px;">🏆 Super Admins</div>
    </div>
    <div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:16px;text-align:center;box-shadow:0 2px 8px rgba(0,53,128,0.07);">
        <div style="font-size:24px;font-weight:900;color:#0071c2;">{{ $admins }}</div>
        <div style="font-size:12px;color:#64748b;font-weight:600;margin-top:3px;">🏨 Admins Hôtel</div>
    </div>
</div>

{{-- Filtres --}}
<form method="GET" action="{{ route('admin.users.index') }}" style="margin-bottom:20px;">
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <input type="text" name="q" value="{{ request('q') }}"
               placeholder="Nom, email..."
               style="flex:1;min-width:200px;border:2px solid #e2e8f0;border-radius:8px;padding:9px 14px;font-size:14px;max-width:350px;">
       <select name="role"
                style="border:2px solid #e2e8f0;border-radius:8px;padding:9px 14px;font-size:13px;font-weight:600;color:#003580;">
            <option value="">Tous les rôles</option>
            <option value="SUPER_ADMIN" {{ request('role')==='SUPER_ADMIN' ? 'selected':'' }}>🏆 Super Admin</option>
            <option value="ADMIN"       {{ request('role')==='ADMIN'       ? 'selected':'' }}>🏨 Admin Hôtel</option>
        </select><select name="role"
                style="border:2px solid #e2e8f0;border-radius:8px;padding:9px 14px;font-size:13px;font-weight:600;color:#003580;">
            <option value="">Tous les rôles</option>
            <option value="SUPER_ADMIN" {{ request('role')==='SUPER_ADMIN' ? 'selected':'' }}>🏆 Super Admin</option>
            <option value="ADMIN"       {{ request('role')==='ADMIN'       ? 'selected':'' }}>🏨 Admin Hôtel</option>
        </select>
        <button type="submit"
                style="background:#003580;color:#fff;border:none;border-radius:8px;padding:9px 18px;font-weight:700;font-size:14px;cursor:pointer;">
            <i class="bi bi-search"></i> Filtrer
        </button>
        @if(request('q') || request('role'))
        <a href="{{ route('admin.users.index') }}"
           style="background:#f1f5f9;color:#64748b;border-radius:8px;padding:9px 14px;text-decoration:none;font-size:14px;font-weight:600;">
            <i class="bi bi-x"></i> Effacer
        </a>
        @endif
    </div>
</form>

@if($users->isEmpty())
<div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;padding:60px;text-align:center;">
    <div style="font-size:48px;margin-bottom:12px;">👥</div>
    <h3 style="color:#003580;font-weight:700;">Aucun utilisateur trouvé</h3>
    <p style="color:#64748b;margin-bottom:16px;">Essayez d'autres filtres ou créez un nouvel utilisateur.</p>
    <a href="{{ route('admin.users.create') }}"
       style="background:#003580;color:#fff;padding:10px 24px;border-radius:8px;text-decoration:none;font-weight:700;font-size:14px;">
        <i class="bi bi-plus-lg me-1"></i> Créer un utilisateur
    </a>
</div>
@else
<div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 2px 12px rgba(0,53,128,0.07);overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;font-size:14px;">
        <thead>
            <tr style="background:#f8fafc;">
                <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Utilisateur</th>
                <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Email</th>
                <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Téléphone</th>
                <th style="padding:12px 16px;text-align:center;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Rôle</th>
                <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Hôtel géré</th>
                <th style="padding:12px 16px;text-align:center;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Membre depuis</th>
                <th style="padding:12px 16px;text-align:center;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr style="border-bottom:1px solid #f1f5f9;"
                onmouseover="this.style.background='#f8fafc'"
                onmouseout="this.style.background=''">

                {{-- Avatar + Nom --}}
                <td style="padding:14px 16px;">
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#003580,#0071c2);display:flex;align-items:center;justify-content:center;color:#fff;font-size:16px;font-weight:800;flex-shrink:0;">
                            {{ strtoupper(substr($user->prenom ?? $user->name, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight:700;color:#1e293b;">
                                {{ $user->prenom ?? '' }} {{ $user->name }}
                            </div>
                            <div style="font-size:11px;color:#94a3b8;">ID #{{ $user->id }}</div>
                        </div>
                    </div>
                </td>

                {{-- Email --}}
                <td style="padding:14px 16px;color:#475569;font-size:13px;">
                    {{ $user->email }}
                </td>

                {{-- Téléphone --}}
                <td style="padding:14px 16px;color:#475569;font-size:13px;">
                    {{ $user->phone ?? '—' }}
                </td>

                {{-- Rôle --}}
                <td style="padding:14px 16px;text-align:center;">
                    @if($user->role === 'SUPER_ADMIN')
                        <span style="background:#ede9fe;color:#6d28d9;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:800;">
                            🏆 Super Admin
                        </span>
                    @elseif($user->role === 'ADMIN')
                        <span style="background:#dbeafe;color:#1e40af;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:800;">
                            🏨 Admin Hôtel
                        </span>
                    @else
                        <span style="background:#dcfce7;color:#14532d;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:800;">
                            👤 Client
                        </span>
                    @endif
                </td>

                {{-- Hôtel géré --}}
                <td style="padding:14px 16px;font-size:13px;">
                    @php $hotelGere = $user->managedHotels->first() ?? $user->hotels->first(); @endphp
                    @if($hotelGere)
                        <div style="display:flex;align-items:center;gap:6px;">
                            <i class="bi bi-building" style="color:#0071c2;"></i>
                            <span style="font-weight:600;color:#1e293b;">{{ $hotelGere->nom }}</span>
                        </div>
                        <div style="font-size:11px;color:#94a3b8;margin-top:2px;">{{ $hotelGere->ville ?? 'Djibouti' }}</div>
                    @else
                        <span style="color:#94a3b8;font-size:13px;">—</span>
                    @endif
                </td>

                {{-- Membre depuis --}}
                <td style="padding:14px 16px;text-align:center;font-size:12px;color:#64748b;">
                    {{ $user->created_at->format('d/m/Y') }}
                </td>

                {{-- Actions --}}
                <td style="padding:14px 16px;">
                    <div style="display:flex;align-items:center;justify-content:center;gap:8px;">
                        <a href="{{ route('admin.users.edit', $user) }}"
                           style="background:#fef3c7;color:#92400e;padding:6px 12px;border-radius:6px;text-decoration:none;font-size:12px;font-weight:700;display:inline-flex;align-items:center;gap:4px;">
                            <i class="bi bi-pencil"></i> Modifier
                        </a>
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                              onsubmit="return confirm('Supprimer l\'utilisateur {{ $user->name }} ?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    style="background:#fee2e2;color:#991b1b;padding:6px 12px;border-radius:6px;border:none;font-size:12px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:4px;">
                                <i class="bi bi-trash"></i> Suppr.
                            </button>
                        </form>
                        @else
                        <span style="font-size:12px;color:#94a3b8;padding:6px 8px;">Vous</span>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if($users->hasPages())
<div style="display:flex;justify-content:center;margin-top:24px;">
    {{ $users->links() }}
</div>
@endif
@endif

@endsection