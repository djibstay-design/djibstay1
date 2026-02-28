@extends('layouts.admin')

@section('title', 'Avis clients')

@section('content')
<h1 class="text-2xl font-semibold mb-6">Avis clients</h1>

@if ($avis->isEmpty())
    <p class="text-gray-600 dark:text-gray-400">Aucun avis.</p>
@else
    <div class="space-y-4">
        @foreach ($avis as $a)
            <div class="border border-[#e3e3e0] dark:border-[#3E3E3A] rounded p-4">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-medium">{{ $a->nom_client }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $a->email_client }}</p>
                        <p class="text-yellow-500 mt-1">{{ str_repeat('★', $a->note) }}{{ str_repeat('☆', 5 - $a->note) }} - {{ $a->hotel->nom }}</p>
                        @if ($a->commentaire)
                            <p class="mt-2">{{ $a->commentaire }}</p>
                        @endif
                    </div>
                    <span class="text-sm text-gray-500">{{ $a->date_avis->format('d/m/Y') }}</span>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-6">{{ $avis->links() }}</div>
@endif
@endsection
