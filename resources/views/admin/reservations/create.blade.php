@extends('layouts.admin')
@section('page_title', 'Nouvelle réservation')
@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Réserver sur place</h1>
        <p class="text-slate-500 text-sm mt-1">Enregistrer un client présent à l’hôtel — la réservation est créée comme <strong>confirmée</strong>, sans paiement en ligne.</p>
    </div>
    <a href="{{ route('admin.reservations.index') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm font-medium hover:bg-slate-50">
        ← Retour à la liste
    </a>
</div>

@if($chambres->isEmpty())
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 text-amber-900 text-sm">
        Aucune chambre <strong>disponible</strong> n’est associée à vos hôtels. Ajoutez ou libérez des chambres avant de réserver sur place.
    </div>
@else
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 md:p-8 max-w-3xl">
        <form action="{{ route('admin.reservations.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label for="chambre_id" class="block text-sm font-medium text-slate-700 mb-1">Chambre *</label>
                <select name="chambre_id" id="chambre_id" required class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-slate-800 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none">
                    <option value="">— Choisir —</option>
                    @foreach($chambres as $c)
                        @php $h = $c->typeChambre->hotel; $t = $c->typeChambre; @endphp
                        <option value="{{ $c->id }}" {{ (string) old('chambre_id') === (string) $c->id ? 'selected' : '' }}>
                            {{ $h->nom }} — Ch. n°{{ $c->numero }} — {{ $t->nom_type }} ({{ number_format($t->prix_par_nuit, 0, ',', ' ') }} DJF/nuit)
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label for="nom_client" class="block text-sm font-medium text-slate-700 mb-1">Nom *</label>
                    <input type="text" name="nom_client" id="nom_client" required value="{{ old('nom_client') }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label for="prenom_client" class="block text-sm font-medium text-slate-700 mb-1">Prénom *</label>
                    <input type="text" name="prenom_client" id="prenom_client" required value="{{ old('prenom_client') }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none">
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label for="email_client" class="block text-sm font-medium text-slate-700 mb-1">Email <span class="text-slate-400 font-normal">(optionnel)</span></label>
                    <input type="email" name="email_client" id="email_client" value="{{ old('email_client') }}" placeholder="Si inconnu, laisser vide" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label for="telephone_client" class="block text-sm font-medium text-slate-700 mb-1">Téléphone</label>
                    <input type="text" name="telephone_client" id="telephone_client" value="{{ old('telephone_client') }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none">
                </div>
            </div>

            <div>
                <label for="code_identite" class="block text-sm font-medium text-slate-700 mb-1">N° pièce d’identité <span class="text-slate-400 font-normal">(optionnel)</span></label>
                <input type="text" name="code_identite" id="code_identite" value="{{ old('code_identite') }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none">
            </div>

            <div class="grid sm:grid-cols-3 gap-4">
                <div>
                    <label for="date_debut" class="block text-sm font-medium text-slate-700 mb-1">Arrivée *</label>
                    <input type="date" name="date_debut" id="date_debut" required value="{{ old('date_debut') }}" min="{{ now()->format('Y-m-d') }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label for="date_fin" class="block text-sm font-medium text-slate-700 mb-1">Départ *</label>
                    <input type="date" name="date_fin" id="date_fin" required value="{{ old('date_fin') }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label for="quantite" class="block text-sm font-medium text-slate-700 mb-1">Voyageurs *</label>
                    <input type="number" name="quantite" id="quantite" required min="1" value="{{ old('quantite', 1) }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none">
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Photo pièce d’identité <span class="text-slate-400 font-normal">(optionnel)</span></label>
                    <input type="file" name="photo_carte" accept="image/jpeg,image/png,image/jpg" class="w-full text-sm text-slate-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Photo visage <span class="text-slate-400 font-normal">(optionnel)</span></label>
                    <input type="file" name="photo_visage" accept="image/jpeg,image/png,image/jpg" class="w-full text-sm text-slate-600">
                </div>
            </div>

            @if ($errors->any())
                <div class="rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="flex flex-wrap gap-3 pt-2">
                <button type="submit" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-sm transition-colors">
                    Enregistrer la réservation
                </button>
                <a href="{{ route('admin.reservations.index') }}" class="inline-flex items-center justify-center px-6 py-3 border border-slate-200 text-slate-700 font-medium rounded-xl hover:bg-slate-50">Annuler</a>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var deb = document.getElementById('date_debut');
        var fin = document.getElementById('date_fin');
        if (!deb || !fin) return;
        function ymd(d) {
            var y = d.getFullYear(), m = String(d.getMonth() + 1).padStart(2, '0'), day = String(d.getDate()).padStart(2, '0');
            return y + '-' + m + '-' + day;
        }
        function parseYMD(s) {
            if (!s) return null;
            var p = s.split('-');
            if (p.length !== 3) return null;
            var dt = new Date(parseInt(p[0], 10), parseInt(p[1], 10) - 1, parseInt(p[2], 10));
            return isNaN(dt.getTime()) ? null : dt;
        }
        function addDays(date, n) {
            return new Date(date.getFullYear(), date.getMonth(), date.getDate() + n);
        }
        var t0 = new Date(); t0.setHours(0, 0, 0, 0);
        var tomorrow0 = addDays(t0, 1);
        function syncFin() {
            var minOut = tomorrow0;
            var st = parseYMD(deb.value);
            if (st) {
                var after = addDays(st, 1);
                if (after.getTime() > minOut.getTime()) minOut = after;
            }
            var minStr = ymd(minOut);
            fin.setAttribute('min', minStr);
            if (fin.value && fin.value < minStr) fin.value = minStr;
        }
        deb.addEventListener('change', syncFin);
        deb.addEventListener('input', syncFin);
        syncFin();
    });
    </script>
    @endpush
@endif
@endsection
