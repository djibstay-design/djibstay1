@extends('layouts.admin')
@section('page_title', 'Boîte Mail')
@section('title', 'Boîte Mail — DjibStay')

@push('styles')
<style>
.mail-wrap { background:#f6f8fc; min-height:100vh; }

/* STATS */
.stats-row {
    display:grid;
    grid-template-columns:repeat(5,1fr);
    gap:14px;
    margin-bottom:20px;
}
@media(max-width:1000px){ .stats-row { grid-template-columns:repeat(3,1fr); } }
@media(max-width:600px) { .stats-row { grid-template-columns:repeat(2,1fr); } }

.stat-card {
    background:#fff;
    border-radius:10px;
    border:1px solid #e0e0e0;
    padding:14px;
    display:flex;
    align-items:center;
    gap:10px;
    text-decoration:none;
}

.stat-num { font-size:20px; font-weight:800; }
.stat-lbl { font-size:11px; color:#5f6368; }

/* FILTER */
.filter-bar {
    display:flex;
    gap:8px;
    flex-wrap:wrap;
    margin-bottom:16px;
}

.filter-btn {
    padding:6px 14px;
    border-radius:20px;
    font-size:13px;
    border:1px solid #dadce0;
    background:#fff;
    color:#5f6368;
    text-decoration:none;
}

.filter-btn.active {
    background:#1a73e8;
    color:#fff;
    border-color:#1a73e8;
}

/* LIST */
.mail-list {
    background:#fff;
    border-radius:12px;
    border:1px solid #e0e0e0;
    overflow:hidden;
}

/* ITEM */
.mail-item {
    display:flex;
    align-items:center;
    gap:12px;
    padding:12px 16px;
    border-bottom:1px solid #f1f3f4;
    text-decoration:none;
    color:inherit;
    transition:all .2s;
}

.mail-item:hover {
    background:#f5f7fa;
}

/* NON LU */
.mail-item.non-lu {
    font-weight:600;
    background:#fff;
}

/* AVATAR */
.mail-avatar {
    width:36px;
    height:36px;
    border-radius:50%;
    background:#e8f0fe;
    color:#1967d2;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:700;
}

/* BODY */
.mail-body {
    flex:1;
    display:flex;
    align-items:center;
    gap:10px;
    min-width:0;
}

/* FROM */
.mail-from {
    min-width:150px;
    max-width:180px;
    font-size:14px;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

/* SUBJECT + PREVIEW INLINE */
.mail-subject {
    flex:1;
    font-size:14px;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

.mail-preview {
    color:#5f6368;
    font-weight:400;
}

/* META */
.mail-meta {
    text-align:right;
    min-width:90px;
}

.mail-date {
    font-size:12px;
    color:#5f6368;
}

/* BADGES */
.mail-badges {
    margin-top:4px;
}

.badge-sm {
    font-size:10px;
    padding:2px 6px;
    border-radius:6px;
}

.badge-gmail { background:#fce8e6; color:#c5221f; }
.badge-formulaire { background:#e8f0fe; color:#1967d2; }
.badge-non-lu { background:#fef7e0; color:#b06000; }
.badge-repondu { background:#e6f4ea; color:#137333; }

</style>
@endpush

@section('content')
<div class="mail-wrap">

    {{-- HEADER --}}
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <div>
            <h2 style="margin:0;">Boîte Mail</h2>
            <small style="color:#5f6368;">Formulaire + Gmail</small>
        </div>

        <a href="{{ route('admin.boite-mail.index') }}"
           style="background:#1a73e8;color:#fff;padding:8px 16px;border-radius:8px;text-decoration:none;">
           Synchroniser
        </a>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
    <div style="background:#e6f4ea;padding:10px;border-radius:8px;margin-bottom:15px;">
        {{ session('success') }}
    </div>
    @endif

    {{-- STATS --}}
    <div class="stats-row">
        <a href="{{ route('admin.boite-mail.index') }}" class="stat-card">
            <div>📬</div>
            <div>
                <div class="stat-num">{{ $counts['total'] }}</div>
                <div class="stat-lbl">Total</div>
            </div>
        </a>

        <a href="{{ route('admin.boite-mail.index',['filtre'=>'non_lus']) }}" class="stat-card">
            <div>🔔</div>
            <div>
                <div class="stat-num">{{ $counts['non_lus'] }}</div>
                <div class="stat-lbl">Non lus</div>
            </div>
        </a>

        <a href="{{ route('admin.boite-mail.index',['filtre'=>'gmail']) }}" class="stat-card">
            <div>📧</div>
            <div>
                <div class="stat-num">{{ $counts['gmail'] }}</div>
                <div class="stat-lbl">Gmail</div>
            </div>
        </a>

        <a href="{{ route('admin.boite-mail.index',['filtre'=>'formulaire']) }}" class="stat-card">
            <div>📝</div>
            <div>
                <div class="stat-num">{{ $counts['formulaire'] }}</div>
                <div class="stat-lbl">Formulaire</div>
            </div>
        </a>

        <a href="{{ route('admin.boite-mail.index',['filtre'=>'archives']) }}" class="stat-card">
            <div>📁</div>
            <div>
                <div class="stat-num">{{ $counts['archives'] }}</div>
                <div class="stat-lbl">Archives</div>
            </div>
        </a>
    </div>

    {{-- FILTER --}}
    <div class="filter-bar">
        <a href="{{ route('admin.boite-mail.index') }}" class="filter-btn {{ $filtre==='tous'?'active':'' }}">Tous</a>
        <a href="{{ route('admin.boite-mail.index',['filtre'=>'non_lus']) }}" class="filter-btn {{ $filtre==='non_lus'?'active':'' }}">Non lus</a>
        <a href="{{ route('admin.boite-mail.index',['filtre'=>'gmail']) }}" class="filter-btn {{ $filtre==='gmail'?'active':'' }}">Gmail</a>
        <a href="{{ route('admin.boite-mail.index',['filtre'=>'formulaire']) }}" class="filter-btn {{ $filtre==='formulaire'?'active':'' }}">Formulaire</a>
        <a href="{{ route('admin.boite-mail.index',['filtre'=>'archives']) }}" class="filter-btn {{ $filtre==='archives'?'active':'' }}">Archives</a>
    </div>

    {{-- LIST --}}
    <div class="mail-list">
        @forelse($messages as $msg)
        <a href="{{ route('admin.boite-mail.show',$msg) }}"
           class="mail-item {{ !$msg->lu ? 'non-lu' : '' }}">

            {{-- AVATAR --}}
            <div class="mail-avatar">
                {{ strtoupper(substr($msg->nom,0,1)) }}
            </div>

            {{-- BODY --}}
            <div class="mail-body">

                <div class="mail-from">
                    {{ $msg->nom }}
                </div>

                <div class="mail-subject">
                    <strong>{{ $msg->sujet }}</strong>
                    <span class="mail-preview">
                        — {{ Str::limit($msg->message, 60) }}
                    </span>
                </div>

            </div>

            {{-- META --}}
            <div class="mail-meta">
                <div class="mail-date">
                    {{ $msg->created_at->format('d/m') }}
                </div>

                <div class="mail-badges">
                    <span class="badge-sm {{ $msg->source==='gmail'?'badge-gmail':'badge-formulaire' }}">
                        {{ $msg->source==='gmail'?'Gmail':'Formulaire' }}
                    </span>

                    @if(!$msg->lu)
                        <span class="badge-sm badge-non-lu">Nouveau</span>
                    @endif

                    @if($msg->reponse)
                        <span class="badge-sm badge-repondu">Répondu</span>
                    @endif
                </div>
            </div>

        </a>
        @empty
        <div style="padding:50px;text-align:center;color:#5f6368;">
            Aucun message
        </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    <div style="margin-top:15px;">
        {{ $messages->links() }}
    </div>

</div>
@endsection