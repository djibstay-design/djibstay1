@extends('layouts.admin')
@section('title', 'Avis clients')
@section('content')

<div class="avis-admin-page">
    <header class="avis-admin-header">
        <h1 class="avis-admin-title">Avis clients</h1>
        <p class="avis-admin-count">
            <svg class="avis-admin-star-icon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            {{ $avis->total() ?? 0 }} avis au total
        </p>
    </header>

    <div class="crud-toolbar" style="margin-top: -0.5rem;">
        <form method="GET" action="{{ route('admin.avis.index') }}" class="flex flex-wrap items-center gap-2">
            <div class="crud-search-wrap">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Client, email, hôtel..." class="crud-search" autocomplete="off">
            </div>
            <button type="submit" class="crud-btn-submit">Rechercher</button>
            @if(request('q'))<a href="{{ route('admin.avis.index') }}" class="crud-btn-reset">Réinitialiser</a>@endif
        </form>
    </div>

    @if ($avis->isEmpty())
        <div class="avis-admin-empty">
            <svg class="avis-admin-empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            <p>Aucun avis client pour le moment.</p>
        </div>
    @else
        @if (session('success'))
            <div class="avis-admin-alert avis-admin-alert-success">{{ session('success') }}</div>
        @endif
        <div class="avis-admin-list">
            @foreach ($avis as $a)
                <article class="avis-admin-card">
                    <div class="avis-admin-card-main">
                        <p class="avis-admin-card-name">{{ $a->nom_client }}</p>
                        @if($a->email_client)<p class="avis-admin-card-email">{{ $a->email_client }}</p>@endif
                        <p class="avis-admin-card-meta">
                            <span class="avis-admin-stars" aria-hidden="true">
                                @for($i = 1; $i <= 5; $i++)<span class="{{ $i <= $a->note ? 'avis-admin-star-full' : 'avis-admin-star-empty' }}">{{ $i <= $a->note ? '★' : '☆' }}</span>@endfor
                            </span>
                            <span class="avis-admin-note-num">{{ $a->note }}/5</span>
                            <span class="avis-admin-card-hotel"> – {{ $a->hotel->nom ?? '' }}</span>
                        </p>
                        @if($a->commentaire)
                            <p class="avis-admin-card-comment">{{ $a->commentaire }}</p>
                        @endif
                        {{-- Réponse admin --}}
                        @if($a->reponse_admin)
                            <div class="avis-admin-reply-block">
                                <p class="avis-admin-reply-label">Réponse de l’établissement</p>
                                <p class="avis-admin-reply-text">{{ $a->reponse_admin }}</p>
                                <p class="avis-admin-reply-meta">
                                    {{ $a->reponse_admin_at?->format('d/m/Y à H:i') }}
                                    @if($a->reponseAdminUser) — {{ $a->reponseAdminUser->prenom ?? $a->reponseAdminUser->name ?? 'Admin' }}@endif
                                </p>
                            </div>
                        @endif
                        <form action="{{ route('admin.avis.repondre', $a) }}" method="POST" class="avis-admin-reply-form">
                            @csrf
                            <label for="reponse_{{ $a->id }}" class="avis-admin-reply-form-label">{{ $a->reponse_admin ? 'Modifier la réponse' : 'Répondre à cet avis' }}</label>
                            <textarea name="reponse_admin" id="reponse_{{ $a->id }}" rows="3" class="avis-admin-reply-textarea" placeholder="Écrivez votre réponse...">{{ old('avi_id') == $a->id ? old('reponse_admin', $a->reponse_admin) : $a->reponse_admin }}</textarea>
                            <input type="hidden" name="avi_id" value="{{ $a->id }}">
                            @if(old('avi_id') == $a->id)
                            @error('reponse_admin')
                                <p class="avis-admin-reply-error">{{ $message }}</p>
                            @enderror
                            @endif
                            <button type="submit" class="avis-admin-reply-btn">{{ $a->reponse_admin ? 'Mettre à jour' : 'Publier la réponse' }}</button>
                        </form>
                    </div>
                    <div class="avis-admin-card-right">
                        <p class="avis-admin-card-date">{{ $a->date_avis ? $a->date_avis->format('d/m/Y') : '—' }}</p>
                        <p class="avis-admin-card-sentiment">
                            @if($a->note >= 4)<span class="avis-admin-sentiment-pos">Positif</span>
                            @elseif($a->note >= 3)<span class="avis-admin-sentiment-neutre">Neutre</span>
                            @else<span class="avis-admin-sentiment-neg">Négatif</span>
                            @endif
                        </p>
                    </div>
                </article>
            @endforeach
        </div>
        <div class="avis-admin-pagination mt-6">{{ $avis->links() }}</div>
    @endif
</div>

<style>
.avis-admin-page { background: #f8fafc; margin: -1rem -1.25rem; padding: 1.25rem 1.5rem; min-height: 60vh; }
.avis-admin-header { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 0.75rem; margin-bottom: 1.5rem; }
.avis-admin-title { font-size: 1.5rem; font-weight: 700; color: #0f172a; margin: 0; }
.avis-admin-count { display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: #64748b; margin: 0; }
.avis-admin-star-icon { width: 1.125rem; height: 1.125rem; color: #64748b; flex-shrink: 0; }
.avis-admin-empty { background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 3rem 2rem; text-align: center; }
.avis-admin-empty-icon { width: 3rem; height: 3rem; color: #cbd5e1; margin: 0 auto 0.75rem; display: block; }
.avis-admin-empty p { margin: 0; font-size: 0.875rem; color: #64748b; }
.avis-admin-list { display: flex; flex-direction: column; gap: 1rem; }
.avis-admin-card { display: grid; grid-template-columns: 1fr auto; gap: 1.25rem; align-items: start; background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem 1.5rem; box-shadow: 0 1px 2px rgba(0,0,0,0.04); }
@media (max-width: 640px) { .avis-admin-card { grid-template-columns: 1fr; } }
.avis-admin-card-main { min-width: 0; }
.avis-admin-card-name { font-size: 1rem; font-weight: 700; color: #1e293b; margin: 0 0 0.25rem; }
.avis-admin-card-email { font-size: 0.8125rem; color: #64748b; margin: 0 0 0.5rem; }
.avis-admin-card-meta { font-size: 0.875rem; color: #475569; margin: 0; display: flex; align-items: center; gap: 0.35rem; flex-wrap: wrap; }
.avis-admin-stars { letter-spacing: 0.05em; }
.avis-admin-star-full { color: #0f172a; }
.avis-admin-star-empty { color: #cbd5e1; }
.avis-admin-note-num { font-weight: 600; color: #1e293b; }
.avis-admin-card-hotel { color: #475569; }
.avis-admin-card-comment { font-size: 0.9375rem; color: #334155; line-height: 1.55; margin: 0.75rem 0 0; }
.avis-admin-card-right { text-align: right; flex-shrink: 0; }
.avis-admin-card-date { font-size: 0.8125rem; color: #64748b; margin: 0 0 0.25rem; }
.avis-admin-card-sentiment { margin: 0; font-size: 0.8125rem; }
.avis-admin-sentiment-pos { color: #2196f3; font-weight: 600; }
.avis-admin-sentiment-neutre { color: #f59e0b; font-weight: 600; }
.avis-admin-sentiment-neg { color: #ef4444; font-weight: 600; }
.avis-admin-pagination { display: flex; justify-content: center; }
.avis-admin-alert { padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.875rem; }
.avis-admin-alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
.avis-admin-reply-block { margin-top: 1rem; padding: 1rem; background: #f0f9ff; border-left: 3px solid #2196f3; border-radius: 8px; }
.avis-admin-reply-label { font-size: 0.8125rem; font-weight: 600; color: #1e40af; margin: 0 0 0.5rem; }
.avis-admin-reply-text { font-size: 0.9375rem; color: #334155; line-height: 1.5; margin: 0 0 0.35rem; }
.avis-admin-reply-meta { font-size: 0.75rem; color: #64748b; margin: 0; }
.avis-admin-reply-form { margin-top: 1rem; }
.avis-admin-reply-form-label { display: block; font-size: 0.8125rem; font-weight: 600; color: #475569; margin: 0 0 0.5rem; }
.avis-admin-reply-textarea { width: 100%; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.9375rem; resize: vertical; min-height: 80px; }
.avis-admin-reply-textarea:focus { outline: none; border-color: #2196f3; box-shadow: 0 0 0 2px rgba(33,150,243,0.2); }
.avis-admin-reply-error { font-size: 0.8125rem; color: #dc2626; margin: 0.25rem 0 0; }
.avis-admin-reply-btn { margin-top: 0.5rem; padding: 0.5rem 1rem; background: #2196f3; color: #fff; border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 600; cursor: pointer; }
.avis-admin-reply-btn:hover { background: #1976d2; }
</style>
@endsection
