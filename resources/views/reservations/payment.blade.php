@extends('layouts.app')
@section('title', 'Paiement — '.(\App\Models\SiteSetting::get('app_name','DjibStay')))

@push('styles')
<style>
.pay-hero { background:linear-gradient(135deg,#003580,#0071c2); padding:32px 0; color:#fff; }
.pay-hero h1 { font-size:clamp(18px,3vw,26px); font-weight:900; }
.stepper { display:flex; align-items:center; gap:0; margin-top:14px; }
.step { display:flex; align-items:center; gap:7px; font-size:13px; font-weight:600; }
.step .dot { width:26px; height:26px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:800; }
.step.done .dot  { background:#22c55e; color:#fff; }
.step.active .dot{ background:#febb02; color:#003580; }
.step.pending .dot{ background:rgba(255,255,255,0.25); color:#fff; }
.step.done span  { color:rgba(255,255,255,0.75); }
.step.active span{ color:#fff; }
.step.pending span{ color:rgba(255,255,255,0.5); }
.step-line { flex:1; height:2px; background:rgba(255,255,255,0.25); margin:0 8px; min-width:30px; }
.step-line.done { background:#22c55e; }

.pay-layout { display:grid; grid-template-columns:1fr 320px; gap:24px; align-items:start; }
@media(max-width:991px){ .pay-layout { grid-template-columns:1fr; } }

/* Montant --*/
.amount-hero { background:linear-gradient(135deg,#003580,#0071c2); border-radius:14px; padding:24px; color:#fff; text-align:center; margin-bottom:24px; }
.amount-hero .label { font-size:13px; color:rgba(255,255,255,0.8); margin-bottom:4px; }
.amount-hero .amount { font-size:44px; font-weight:900; color:#febb02; line-height:1; }
.amount-hero .currency { font-size:20px; font-weight:700; color:#febb02; }
.amount-hero .sub { font-size:13px; color:rgba(255,255,255,0.75); margin-top:6px; }

/* Méthodes paiement */
.payment-methods { display:grid; grid-template-columns:repeat(auto-fit,minmax(120px,1fr)); gap:10px; margin-bottom:20px; }
.method-option { display:none; }
.method-label { border:2px solid #e2e8f0; border-radius:10px; padding:14px 10px; text-align:center; cursor:pointer; transition:all .2s; background:#fff; display:flex; flex-direction:column; align-items:center; gap:6px; }
.method-label:hover { border-color:#0071c2; background:#f0f7ff; }
.method-option:checked + .method-label { border-color:#003580; background:#e8f0fb; box-shadow:0 0 0 3px rgba(0,53,128,0.12); }
.method-icon { font-size:26px; line-height:1; }
.method-name { font-size:12px; font-weight:700; color:#003580; }
.method-desc { font-size:10px; color:#94a3b8; }

/* Simulation paiement */
.pay-simulation { background:#fff; border-radius:14px; border:1px solid #e2e8f0; box-shadow:0 2px 16px rgba(0,53,128,0.08); overflow:hidden; }
.pay-sim-header { background:#f8fafc; border-bottom:1px solid #e2e8f0; padding:14px 22px; display:flex; align-items:center; gap:10px; }
.pay-sim-header h2 { font-size:15px; font-weight:800; color:#003580; margin:0; }
.pay-sim-body { padding:22px; }

/* Sécurité badge */
.secure-badge { display:flex; align-items:center; gap:8px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; padding:10px 14px; font-size:12px; color:#166534; font-weight:600; margin-top:12px; }

/* Bouton payer */
.btn-pay { background:linear-gradient(135deg,#16a34a,#15803d); color:#fff; border:none; border-radius:10px; font-weight:800; font-size:16px; padding:15px; width:100%; cursor:pointer; transition:all .2s; box-shadow:0 4px 16px rgba(21,128,61,0.3); margin-top:8px; display:flex; align-items:center; justify-content:center; gap:8px; }
.btn-pay:hover { transform:translateY(-1px); box-shadow:0 8px 24px rgba(21,128,61,0.4); }
.btn-pay:active { transform:translateY(0); }

/* Recap sidebar */
.recap-card { background:#fff; border-radius:14px; border:1px solid #e2e8f0; box-shadow:0 2px 16px rgba(0,53,128,0.08); overflow:hidden; position:sticky; top:80px; }

/* Form labels */
.form-label-djib { font-size:12px; font-weight:700; color:#003580; text-transform:uppercase; letter-spacing:.4px; margin-bottom:6px; display:block; }
.form-control-djib { border:2px solid #e2e8f0; border-radius:8px; padding:10px 13px; font-size:14px; color:#1a1a2e; width:100%; transition:border-color .2s; }
.form-control-djib:focus { border-color:#0071c2; box-shadow:0 0 0 3px rgba(0,113,194,0.11); outline:none; }

/* Conditions */
.conditions-box { background:#f8fafc; border-radius:10px; border:1px solid #e2e8f0; padding:14px; font-size:13px; color:#475569; line-height:1.7; margin-bottom:16px; }

/* Processing overlay */
.processing-overlay { display:none; position:fixed; inset:0; background:rgba(0,53,128,0.85); z-index:9999; align-items:center; justify-content:center; flex-direction:column; gap:16px; }
.processing-overlay.show { display:flex; }
.processing-spinner { width:60px; height:60px; border:4px solid rgba(255,255,255,0.3); border-top-color:#febb02; border-radius:50%; animation:spin 1s linear infinite; }
@keyframes spin { to { transform:rotate(360deg); } }
</style>
@endpush

@section('content')
@php
    $appName      = \App\Models\SiteSetting::get('app_name','DjibStay');
    $logoPath     = \App\Models\SiteSetting::get('app_logo','');
    $acomptePercent = \App\Models\SiteSetting::get('resa_acompte_percent','30');
    $annulHours     = \App\Models\SiteSetting::get('resa_annulation_heures', '48');
    $conditions     = \App\Models\SiteSetting::get('resa_conditions', "L'acompte est non remboursable en cas d'annulation moins de {$annulHours}h avant l'arrivée.");
    $waafiMerchant  = \App\Models\SiteSetting::get('payment_waafi_merchant', '123456');
    $dmoneyMerchant = \App\Models\SiteSetting::get('payment_dmoney_merchant', '654321');
@endphp

{{-- Processing overlay --}}
<div class="processing-overlay" id="processingOverlay">
    <div class="processing-spinner"></div>
    <div style="color:#fff;font-size:16px;font-weight:700;">Traitement du paiement en cours...</div>
    <div style="color:rgba(255,255,255,0.75);font-size:13px;">Veuillez ne pas fermer cette page</div>
</div>

{{-- HERO + STEPPER --}}
<section class="pay-hero">
    <div class="container" style="max-width:1200px;">
        <nav style="font-size:12px;color:rgba(255,255,255,0.7);margin-bottom:8px;">
            <a href="{{ route('home') }}" style="color:rgba(255,255,255,0.7);text-decoration:none;">Accueil</a> ›
            <span style="color:#fff;">Paiement</span>
        </nav>
        <h1><i class="bi bi-credit-card me-2"></i>Paiement de l'acompte</h1>
        <div style="font-size:13px;color:rgba(255,255,255,0.8);margin-top:4px;">Réservation {{ $reservation->code_reservation }}</div>
        <div class="stepper">
            <div class="step done"><div class="dot"><i class="bi bi-check"></i></div><span>Formulaire</span></div>
            <div class="step-line done"></div>
            <div class="step active"><div class="dot">2</div><span>Paiement</span></div>
            <div class="step-line"></div>
            <div class="step pending"><div class="dot">3</div><span>Confirmation</span></div>
        </div>
    </div>
</section>

<div class="container py-4" style="max-width:1200px;">
    <div class="pay-layout">

        {{-- PAIEMENT --}}
        <div>
            {{-- Montant --}}
            <div class="amount-hero">
                <div class="label">Acompte à régler maintenant ({{ $acomptePercent }}%)</div>
                <div class="amount">{{ number_format($depositAmount,0,',',' ') }} <span class="currency">{{ \App\Models\SiteSetting::get('app_devise','DJF') }}</span></div>
                <div class="sub">Solde restant : {{ number_format($balanceAmount,0,',',' ') }} {{ \App\Models\SiteSetting::get('app_devise','DJF') }} — à régler à l'hôtel</div>
            </div>
            <form method="POST" action="{{ route('reservations.payment.store',$reservation) }}" id="paymentForm" enctype="multipart/form-data">
                @csrf
                <div class="pay-simulation">
                    <div class="pay-sim-header">
                        <i class="bi bi-wallet2 text-primary fs-5"></i>
                        <h2>Choisissez votre mode de paiement</h2>
                    </div>
                    <div class="pay-sim-body">
                           {{-- Méthodes --}}
                        <div class="payment-methods">
                            @foreach($paymentMethods as $pm)
                            @php 
                                $nom = strtolower($pm->nom);
                                $isWallet = in_array($nom, ['waafi', 'dmoney', 'cac pay']);
                                $isCard = str_contains($nom, 'card') || str_contains($nom, 'mastercard');
                                $icon = $isWallet ? '📱' : ($isCard ? '💳' : '🏦');
                            @endphp
                            <div>
                                <input type="radio" name="payment_method_id" id="pm_{{ $pm->id }}" value="{{ $pm->id }}" 
                                       data-type="{{ $nom }}" 
                                       class="method-option" {{ old('payment_method_id') == $pm->id ? 'checked' : '' }}>
                                <label for="pm_{{ $pm->id }}" class="method-label">
                                    @if($pm->logo)
                                        <img src="{{ asset('storage/'.$pm->logo) }}" alt="{{ $pm->nom }}" style="height:28px;object-fit:contain;margin-bottom:4px;" onerror="this.outerHTML='<span class=\'method-icon\'>{{ $icon }}</span>'">
                                    @else
                                        <span class="method-icon">{{ $icon }}</span>
                                    @endif
                                    <span class="method-name">{{ $pm->nom }}</span>
                                    <span class="method-desc">{{ $pm->description }}</span>
                                </label>
                            </div>
                            @endforeach
                        </div>

                        {{-- Section Wallet (Waafi, Dmoney, CAC PAY) --}}
                        <div id="section-wallet" class="payment-section" style="display:none;background:#f0f7ff;border-radius:12px;padding:20px;margin-bottom:20px;border:1px solid #bfdbfe;">
                            <div id="merchant-instruction" style="background:#fff; border-radius:10px; padding:15px; margin-bottom:20px; border-left:4px solid #003580;">
                                <div style="font-size:12px; color:#64748b; margin-bottom:5px; font-weight:700; text-transform:uppercase;">Instructions de paiement</div>
                                <div id="waafi-instruction" style="display:none;">
                                    Effectuez votre transfert vers le numéro marchand WAAFI : <strong style="font-size:18px; color:#003580;">{{ $waafiMerchant }}</strong>
                                </div>
                                <div id="dmoney-instruction" style="display:none;">
                                    Effectuez votre transfert vers le numéro marchand D-MONEY : <strong style="font-size:18px; color:#003580;">{{ $dmoneyMerchant }}</strong>
                                </div>
                                <div id="generic-instruction">
                                    Veuillez effectuer le transfert vers notre numéro marchand et remplir les informations ci-dessous.
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label class="form-label-djib">Nom de l'envoyeur *</label>
                                    <input type="text" name="sender_name" class="form-control-djib" placeholder="Nom complet" value="{{ old('sender_name') }}">
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label-djib">Numéro de l'envoyeur *</label>
                                    <input type="tel" name="sender_phone" class="form-control-djib" placeholder="Ex: 77 00 00 00" value="{{ old('sender_phone') }}">
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label-djib">ID Transaction (SMS) *</label>
                                    <input type="text" name="transaction_sms_code" class="form-control-djib" placeholder="Ex: 8XJ92..." value="{{ old('transaction_sms_code') }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label-djib">Capture d'écran du reçu *</label>
                                    <div style="position:relative;border:2px dashed #bfdbfe;border-radius:10px;padding:20px;text-align:center;background:#fff;">
                                        <input type="file" name="screenshot" id="screenshot_input" accept="image/*" style="position:absolute;inset:0;opacity:0;cursor:pointer;">
                                        <div id="screenshot_preview_zone">
                                            <i class="bi bi-cloud-upload text-primary fs-3"></i>
                                            <div style="font-size:13px;color:#64748b;margin-top:8px;">Cliquez ou glissez la capture d'écran ici</div>
                                            <div style="font-size:11px;color:#94a3b8;">PNG, JPG (max 4Mo)</div>
                                        </div>
                                        <div id="screenshot_preview" style="display:none;">
                                            <img src="" style="max-height:150px;border-radius:8px;margin-bottom:10px;">
                                            <div style="font-size:12px;color:#003580;font-weight:700;">Fichier sélectionné</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Section Carte (Mastercard / Visa) --}}
                        <div id="section-card" class="payment-section" style="display:none;background:#faf5ff;border-radius:12px;padding:20px;margin-bottom:20px;border:1px solid #e9d5ff;">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span style="font-size:20px;">💳</span>
                                <div style="font-size:14px;font-weight:800;color:#7c3aed;">Paiement par Carte Bancaire</div>
                            </div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label-djib">Numéro de carte</label>
                                    <div style="position:relative;">
                                        <input type="text" name="card_number" class="form-control-djib" placeholder="0000 0000 0000 0000" maxlength="19"
                                               oninput="this.value=this.value.replace(/\D/g,'').replace(/(.{4})/g,'$1 ').trim()">
                                        <div style="position:absolute;right:12px;top:50%;transform:translateY(-50%);display:flex;gap:4px;">
                                            <span style="font-size:18px;">💳</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label-djib">Nom du titulaire</label>
                                    <input type="text" name="card_holder" class="form-control-djib" placeholder="NOM PRÉNOM">
                                </div>
                                <div class="col-6">
                                    <label class="form-label-djib">Expiration</label>
                                    <input type="text" name="expiry" class="form-control-djib" placeholder="MM/AA" maxlength="5"
                                           oninput="this.value=this.value.replace(/\D/g,'').replace(/^(\d{2})/,'$1/').slice(0,5)">
                                </div>
                                <div class="col-6">
                                    <label class="form-label-djib">CVV</label>
                                    <input type="password" name="cvv" class="form-control-djib" placeholder="***" maxlength="3">
                                </div>
                            </div>
                            <div style="background:#f3e8ff;border-radius:8px;padding:10px;margin-top:14px;font-size:11px;color:#7c3aed;font-weight:600;">
                                <i class="bi bi-shield-check me-1"></i>Simulation de transaction sécurisée par SSL
                            </div>
                        </div>

                        {{-- Conditions --}}
                        <div class="conditions-box">
                            <div style="font-size:12px;font-weight:700;color:#003580;margin-bottom:6px;text-transform:uppercase;letter-spacing:.4px;">
                                <i class="bi bi-file-text me-1"></i>Conditions de réservation
                            </div>
                            <div>{{ $conditions }}</div>
                        </div>

                        <div style="display:flex;align-items:flex-start;gap:10px;margin-bottom:16px;">
                            <input type="checkbox" id="accept_conditions" name="accept_conditions" required
                                   style="width:18px;height:18px;margin-top:2px;accent-color:#003580;cursor:pointer;">
                            <label for="accept_conditions" style="font-size:13px;color:#475569;cursor:pointer;line-height:1.6;">
                                J'ai lu et j'accepte les <strong style="color:#003580;">conditions de réservation</strong> et la politique d'annulation.
                            </label>
                        </div>

                        <button type="submit" class="btn-pay" id="btnPay">
                            <i class="bi bi-lock-fill"></i>
                            Payer {{ number_format($depositAmount,0,',',' ') }} {{ \App\Models\SiteSetting::get('app_devise','DJF') }} maintenant
                        </button>
                        <div class="secure-badge mt-3">
                            <i class="bi bi-shield-lock-fill fs-5"></i>
                            <span>Paiement 100% sécurisé — Vos données sont protégées et chiffrées</span>
                        </div>

                        <div style="display:flex;justify-content:center;gap:16px;margin-top:14px;opacity:.6;">
                            <span style="font-size:18px;">📱</span>
                            <span style="font-size:18px;">💳</span>
                            <span style="font-size:18px;">🏦</span>
                            <span style="font-size:18px;">🔒</span>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- RECAP SIDEBAR --}}
        <div>
            <div class="recap-card">
                <div style="background:linear-gradient(135deg,#003580,#0071c2);color:#fff;padding:16px 20px;">
                    <h3 style="font-size:14px;font-weight:800;margin:0;"><i class="bi bi-receipt me-2"></i>Votre réservation</h3>
                </div>
                <div style="padding:18px 20px;">
                    @php
                        $type  = $reservation->chambre->typeChambre;
                        $hotel = $type->hotel;
                        $nuits = $reservation->date_debut->diffInDays($reservation->date_fin);
                    @endphp
                    <div style="font-size:15px;font-weight:800;color:#003580;margin-bottom:2px;">{{ $hotel->nom }}</div>
                    <div style="font-size:13px;color:#0071c2;font-weight:600;margin-bottom:12px;">{{ $type->nom_type }}</div>

                    @foreach([
                        ['bi-hash','Code',         $reservation->code_reservation],
                        ['bi-door-open','Chambre',  'N° '.$reservation->chambre->numero],
                        ['bi-calendar-event','Arrivée', $reservation->date_debut->format('d/m/Y')],
                        ['bi-calendar-check','Départ',  $reservation->date_fin->format('d/m/Y')],
                        ['bi-moon-stars','Nuits',    $nuits.' nuit(s)'],
                        ['bi-people','Capacité',     $type->capacite.' pers.'],
                    ] as [$icon,$label,$val])
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:7px 0;border-bottom:1px solid #f1f5f9;font-size:13px;">
                        <span style="color:#64748b;"><i class="bi {{ $icon }} me-1"></i>{{ $label }}</span>
                        <span style="font-weight:700;color:#1e293b;">{{ $val }}</span>
                    </div>
                    @endforeach

                    <div style="background:#f0f7ff;border-radius:10px;padding:14px;margin-top:14px;">
                        <div style="display:flex;justify-content:space-between;margin-bottom:6px;font-size:13px;">
                            <span style="color:#64748b;">Total séjour</span>
                            <span style="font-weight:700;">{{ number_format($reservation->montant_total,0,',',' ') }} {{ \App\Models\SiteSetting::get('app_devise','DJF') }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;margin-bottom:6px;font-size:13px;">
                            <span style="color:#64748b;">Acompte ({{ $acomptePercent }}%)</span>
                            <span style="font-weight:700;color:#16a34a;">{{ number_format($depositAmount,0,',',' ') }} {{ \App\Models\SiteSetting::get('app_devise','DJF') }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;padding-top:8px;border-top:1px solid #bfdbfe;font-size:13px;">
                            <span style="color:#64748b;">Solde à l'hôtel</span>
                            <span style="font-weight:700;color:#f59e0b;">{{ number_format($balanceAmount,0,',',' ') }} {{ \App\Models\SiteSetting::get('app_devise','DJF') }}</span>
                        </div>
                    </div>

                    <div style="background:#fef3c7;border-radius:8px;padding:9px 12px;font-size:12px;color:#92400e;font-weight:600;margin-top:10px;">
                        <i class="bi bi-info-circle me-1"></i>Le solde sera payé directement à l'hôtel
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
// Gestion des sections de paiement
document.querySelectorAll('input[name="payment_method_id"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const type = this.dataset.type;
        const isWallet = ['waafi', 'dmoney', 'cac pay'].includes(type);
        const isCard   = type.includes('card') || type.includes('mastercard');

        document.querySelectorAll('.payment-section').forEach(s => s.style.display = 'none');
        document.getElementById('waafi-instruction').style.display = 'none';
        document.getElementById('dmoney-instruction').style.display = 'none';
        document.getElementById('generic-instruction').style.display = 'none';

        if (isWallet) {
            document.getElementById('section-wallet').style.display = 'block';
            if(type === 'waafi') {
                document.getElementById('waafi-instruction').style.display = 'block';
            } else if(type === 'dmoney') {
                document.getElementById('dmoney-instruction').style.display = 'block';
            } else {
                document.getElementById('generic-instruction').style.display = 'block';
            }
            document.getElementById('section-wallet').scrollIntoView({behavior:'smooth',block:'nearest'});
        } else if (isCard) {
            document.getElementById('section-card').style.display = 'block';
            document.getElementById('section-card').scrollIntoView({behavior:'smooth',block:'nearest'});
        }
    });
});

// Prévisualisation de la capture d'écran
document.getElementById('screenshot_input')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('screenshot_preview_zone').style.display = 'none';
            document.getElementById('screenshot_preview').style.display = 'block';
            document.getElementById('screenshot_preview').querySelector('img').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
});

// Initialisation au chargement
const checked = document.querySelector('input[name="payment_method_id"]:checked');
if (checked) {
    checked.dispatchEvent(new Event('change'));
}

// Animation de traitement
document.getElementById('paymentForm').addEventListener('submit', function(e) {
    const method = document.querySelector('input[name="payment_method_id"]:checked');
    const accept = document.getElementById('accept_conditions');
    
    if (!method) {
        e.preventDefault();
        alert('Veuillez choisir un mode de paiement.');
        return;
    }
    
    if (!accept.checked) {
        e.preventDefault();
        alert('Veuillez accepter les conditions.');
        return;
    }

    // Afficher l'overlay de traitement
    document.getElementById('processingOverlay').classList.add('show');
    document.getElementById('btnPay').disabled = true;
    document.getElementById('btnPay').innerHTML = '<span class="spinner-border spinner-border-sm"></span> Traitement en cours...';
});
</script>
@endpush