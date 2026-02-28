@extends('layouts.admin')

@section('title', 'Utilisateurs')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold">Administrateurs</h1>
    <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-[#1b1b18] dark:bg-[#EDEDEC] text-white dark:text-[#1b1b18] rounded font-medium">Nouvel utilisateur</a>
</div>

@if (session('error'))
    <div class="bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200 px-4 py-2 rounded mb-4">{{ session('error') }}</div>
@endif

<div class="overflow-x-auto">
    <table class="w-full border border-[#e3e3e0] dark:border-[#3E3E3A]">
        <thead class="bg-gray-100 dark:bg-[#3E3E3A]">
            <tr>
                <th class="px-4 py-2 text-left">Nom</th>
                <th class="px-4 py-2 text-left">Email</th>
                <th class="px-4 py-2 text-left">Rôle</th>
                <th class="px-4 py-2"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr class="border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <td class="px-4 py-2">{{ $user->prenom }} {{ $user->name }}</td>
                    <td class="px-4 py-2">{{ $user->email }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 rounded text-sm {{ $user->role === 'SUPER_ADMIN' ? 'bg-purple-100 dark:bg-purple-900/30' : 'bg-blue-100 dark:bg-blue-900/30' }}">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td class="px-4 py-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="text-sm underline">Modifier</a>
                        @if ($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline ml-2" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-600 hover:underline">Supprimer</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $users->links() }}</div>
@endsection
