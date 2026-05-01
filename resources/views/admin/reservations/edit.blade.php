@extends('layouts.admin')
@section('page_title', 'Modifier réservation')

@section('content')
@php $estExpiree = $reservation->statut === 'ANNULEE' && $reservation->date_debut->isPast(); @endphp

{{-- Redirect si expirée --}}
@if($estExpiree)
<div style="background:#f3e8ff;border:1px solid #d8b4fe;border-radius:12px;padding:16px 22px;margin-bottom:24px;display:flex;align-items:center;gap:12px;">
    <span style="font-size:24px;">🕐</span>
    <div>
        <div style="font-weight:800;color:#6b21a8;font-size:15px;">Réservation expirée</div>
        <div style="font-size:13px;color:#7e22ce;">Cette réservation est expirée, elle ne peut plus être modifiée.</div>
    </div>
    <a href="{{ route('admin.reservations.show',$reservation) }}"
       style="margin-left:auto;background:#6b21a8;color:#fff;padding:9px 16px;border-radius:8px;text-decoration:none;font-weight:700;font-size:13px;">
        Voir le détail
    </a>
</div>
@endif

<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:24px;">
    <div>
        <h1 style="font-size:22px;font-weight:900;color:#1e293b;margin:0;">✏️ Modifier — {{ $reservation->code_reservation }}</h1>
        <p style="font-size:13px;color:#64748b;margin:4px 0 0;">Modification de la réservation</p>
    </div>
    <a href="{{ route('admin.reservations.show',$reservation) }}"
       style="background:#f1f5f9;color:#475569;padding:9px 16px;border-radius:8px;text-decoration:none;font-weight:700;font-size:13px;display:inline-flex;align-items:center;gap:6px;">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 2px 12px rgba(0,53,128,0.07);overflow:hidden;{{ $estExpiree ? 'opacity:0.6;pointer-events:none;' : '' }}">
            <div style="background:#f8fafc;border-bottom:1px solid #e2e8f0;padding:14px 22px;">
                <span style="font-size:15px;font-weight:800;color:#003580;"><i class="bi bi-pencil-square me-2"></i>Informations</span>
            </div>
            <div style="padding:24px;">
                <form method="POST" action="{{ route('admin.reservations.update',$reservation) }}">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Nom *</label>
                            <input type="text" name="nom_client" value="{{ old('nom_client',$reservation->nom_client) }}"
                                   style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;" required>
                        </div>
                        <div class="col-sm-6">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Prénom *</label>
                            <input type="text" name="prenom_client" value="{{ old('prenom_client',$reservation->prenom_client) }}"
                                   style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;" required>
                        </div>
                        <div class="col-sm-6">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Email *</label>
                            <input type="email" name="email_client" value="{{ old('email_client',$reservation->email_client) }}"
                                   style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;" required>
                        </div>
                        <div class="col-sm-6">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Téléphone</label>
                            <input type="tel" name="telephone_client" value="{{ old('telephone_client',$reservation->telephone_client) }}"
                                   style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;">
                        </div>
                        <div class="col-12">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">N° Pièce d'identité</label>
                            <input type="text" name="code_identite" value="{{ old('code_identite',$reservation->code_identite) }}"
                                   style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;">
                        </div>
                        <div class="col-sm-6">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Chambre *</label>
                            <select name="chambre_id" style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;" required>
                                @foreach($chambres as $ch)
                                <option value="{{ $ch->id }}" {{ old('chambre_id',$reservation->chambre_id)==$ch->id?'selected':'' }}>
                                    {{ $ch->typeChambre->hotel->nom ?? '' }} — {{ $ch->typeChambre->nom_type ?? '' }} N° {{ $ch->numero }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Statut *</label>
                            <select name="statut" style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;" required>
                                <option value="EN_ATTENTE" {{ old('statut',$reservation->statut)==='EN_ATTENTE'?'selected':'' }}>⏳ En attente</option>
                                <option value="CONFIRMEE"  {{ old('statut',$reservation->statut)==='CONFIRMEE' ?'selected':'' }}>✅ Confirmée</option>
                                <option value="ANNULEE"    {{ old('statut',$reservation->statut)==='ANNULEE'   ?'selected':'' }}>❌ Annulée</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Date d'arrivée *</label>
                            <input type="date" name="date_debut" value="{{ old('date_debut',$reservation->date_debut->format('Y-m-d')) }}"
                                   style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;" required>
                        </div>
                        <div class="col-sm-6">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Date de départ *</label>
                            <input type="date" name="date_fin" value="{{ old('date_fin',$reservation->date_fin->format('Y-m-d')) }}"
                                   style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;" required>
                        </div>
                        <div class="col-sm-6">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Quantité de chambres</label>
                            <input type="number" name="quantite" value="{{ old('quantite',$reservation->quantite) }}" min="1"
                                   style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;">
                        </div>
                        <div class="col-sm-6">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Montant total (DJF)</label>
                            <input type="number" name="montant_total" value="{{ old('montant_total',$reservation->montant_total) }}" min="0"
                                   style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;">
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

    <div class="col-lg-4">
        <div style="background:#f0f7ff;border-radius:12px;border:1px solid #bfdbfe;padding:18px 20px;">
            <div style="font-size:14px;font-weight:800;color:#003580;margin-bottom:12px;"><i class="bi bi-info-circle me-1"></i>Infos actuelles</div>
            @foreach([
                ['Code', $reservation->code_reservation],
                ['Hôtel', $reservation->chambre->typeChambre->hotel->nom ?? '—'],
                ['Chambre', 'N° '.$reservation->chambre->numero],
                ['Créée le', $reservation->date_reservation->format('d/m/Y')],
                ['Statut', $estExpiree ? '🕐 Expirée' : ($reservation->statut === 'CONFIRMEE' ? '✅ Confirmée' : ($reservation->statut === 'EN_ATTENTE' ? '⏳ En attente' : '❌ Annulée'))],
            ] as [$l,$v])
            <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid #bfdbfe;font-size:13px;">
                <span style="color:#64748b;">{{ $l }}</span>
                <span style="font-weight:700;color:{{ $estExpiree && $l === 'Statut' ? '#6b21a8' : '#003580' }};">{{ $v }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection