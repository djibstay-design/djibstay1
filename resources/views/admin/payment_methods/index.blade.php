@extends('layouts.admin')
@section('page_title', 'Types de paiement')
@section('title', 'Types de paiement')

@section('content')
<div style="display:flex; justify-content:space-between; margin-bottom: 20px;">
    <h2>Types de paiement</h2>
    <a href="{{ route('admin.payment-methods.create') }}" style="background:#003580; color:#fff; padding: 10px 15px; border-radius: 8px; text-decoration:none;">+ Ajouter un type</a>
</div>

@if(session('success'))
<div style="background:#dcfce7; color:#14532d; padding:12px; border-radius:8px; margin-bottom:20px;">
    {{ session('success') }}
</div>
@endif

<div style="background:#fff; border-radius:12px; padding:20px; box-shadow:0 2px 10px rgba(0,0,0,0.05);">
    <table style="width:100%; border-collapse:collapse; text-align:left;">
        <thead>
            <tr style="border-bottom:2px solid #f1f5f9; color:#64748b; font-size:13px; text-transform:uppercase;">
                <th style="padding:12px;">Logo</th>
                <th style="padding:12px;">Nom</th>
                <th style="padding:12px;">Code Marchand</th>
                <th style="padding:12px;">Statut</th>
                <th style="padding:12px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($paymentMethods as $method)
            <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:12px;">
                    @if($method->logo)
                        <img src="{{ asset('storage/'.$method->logo) }}" width="50" style="border-radius:6px;">
                    @else
                        <span style="color:#94a3b8; font-size:12px;">Aucun logo</span>
                    @endif
                </td>
                <td style="padding:12px; font-weight:bold;">{{ $method->nom }}</td>
                <td style="padding:12px; color:#475569;">{{ $method->code_marchand ?? '—' }}</td>
                <td style="padding:12px;">
                    <form method="POST" action="{{ route('admin.payment-methods.toggle', $method) }}">
                        @csrf @method('PATCH')
                        <button type="submit" style="background:none; border:none; cursor:pointer;">
                            @if($method->is_active)
                                <span style="background:#dcfce7; color:#14532d; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:bold;">Actif</span>
                            @else
                                <span style="background:#fee2e2; color:#991b1b; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:bold;">Inactif</span>
                            @endif
                        </button>
                    </form>
                </td>
                <td style="padding:12px;">
                    <a href="{{ route('admin.payment-methods.edit', $method) }}" style="color:#0071c2; text-decoration:none; margin-right:10px;">Modifier</a>
                    <form method="POST" action="{{ route('admin.payment-methods.destroy', $method) }}" style="display:inline;" onsubmit="return confirm('Supprimer ce type ?');">
                        @csrf @method('DELETE')
                        <button type="submit" style="background:none; border:none; color:#dc2626; cursor:pointer; text-decoration:underline;">Supprimer</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding:20px; text-align:center; color:#64748b;">Aucun type de paiement configuré.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
