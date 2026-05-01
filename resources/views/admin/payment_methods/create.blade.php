@extends('layouts.admin')
@section('page_title', 'Ajouter un type de paiement')
@section('title', 'Nouveau type de paiement')

@section('content')
<div style="max-width:600px; background:#fff; border-radius:12px; padding:24px; box-shadow:0 2px 10px rgba(0,0,0,0.05);">
    <form method="POST" action="{{ route('admin.payment-methods.store') }}" enctype="multipart/form-data">
        @csrf
        <div style="margin-bottom:16px;">
            <label style="display:block; font-weight:bold; margin-bottom:6px; font-size:13px; color:#475569;">Nom du mode de paiement *</label>
            <input type="text" name="nom" value="{{ old('nom') }}" required style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
            @error('nom') <span style="color:#dc2626; font-size:12px;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block; font-weight:bold; margin-bottom:6px; font-size:13px; color:#475569;">Description (optionnel)</label>
            <textarea name="description" rows="3" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">{{ old('description') }}</textarea>
            @error('description') <span style="color:#dc2626; font-size:12px;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block; font-weight:bold; margin-bottom:6px; font-size:13px; color:#475569;">Code Marchand (API)</label>
            <input type="text" name="code_marchand" value="{{ old('code_marchand') }}" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
            <div style="font-size:11px; color:#94a3b8; margin-top:4px;">Ex: 12345, WAAFI_MERCHANT, etc.</div>
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block; font-weight:bold; margin-bottom:6px; font-size:13px; color:#475569;">Logo (Image)</label>
            <input type="file" name="logo_file" accept="image/*" style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px; background:#f8fafc;">
        </div>

        <div style="margin-bottom:24px;">
            <label style="display:flex; align-items:center; gap:8px; font-weight:bold; font-size:13px; color:#475569; cursor:pointer;">
                <input type="checkbox" name="is_active" value="1" checked style="width:18px; height:18px;">
                Activer ce mode de paiement immédiatement
            </label>
        </div>

        <div style="display:flex; gap:12px;">
            <a href="{{ route('admin.payment-methods.index') }}" style="padding:10px 16px; border-radius:8px; text-decoration:none; background:#f1f5f9; color:#475569; font-weight:bold;">Annuler</a>
            <button type="submit" style="padding:10px 20px; border-radius:8px; background:#003580; color:#fff; font-weight:bold; border:none; cursor:pointer;">Enregistrer</button>
        </div>
    </form>
</div>
@endsection
