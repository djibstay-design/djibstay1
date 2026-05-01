<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
   public function showLoginForm()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'SUPER_ADMIN') {
                return redirect()->route('admin.dashboard');
            }
            if ($user->role === 'ADMIN') {
                return redirect()->route('hoteladmin.dashboard');
            }
            return redirect()->route('client.compte');
        }
        return view('auth.login');
    }

   public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Email ou mot de passe incorrect.',
            ]);
        }

        $user = Auth::user();
        $request->session()->regenerate();

        if ($user->role === 'SUPER_ADMIN') {
            return redirect()->intended(route('admin.dashboard'));
        }

        if ($user->role === 'ADMIN') {
            return redirect()->intended(route('hoteladmin.dashboard'));
        }

        return redirect()->intended(route('client.compte'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    public function showRegisterForm()
    {
        if (\App\Models\SiteSetting::get('inscription_active', '1') != '1') {
            return redirect()->route('login')->with('error', 'Les inscriptions sont temporairement fermées.');
        }

        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        if (\App\Models\SiteSetting::get('inscription_active', '1') != '1') {
            return redirect()->route('login')->with('error', 'Les inscriptions sont temporairement fermées.');
        }
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'prenom'   => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'phone'    => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'min:8', 'confirmed'],
        ], [
            'name.required'      => 'Le nom est requis.',
            'prenom.required'    => 'Le prénom est requis.',
            'email.required'     => 'L\'email est requis.',
            'email.unique'       => 'Cet email est déjà utilisé.',
            'password.required'  => 'Le mot de passe est requis.',
            'password.min'       => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'prenom'   => $validated['prenom'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role'     => 'CLIENT',
        ]);

        Auth::login($user);

        return redirect()->route('client.compte')
            ->with('success', 'Bienvenue ' . $user->prenom . ' ! Votre compte a été créé avec succès.');
    }

    public function monCompte()
    {
        $user = Auth::user();
        $reservations = Reservation::where('email_client', $user->email)
            ->orWhere('user_id', $user->id)
            ->with('chambre.typeChambre.hotel')
            ->latest()
            ->take(5)
            ->get();

        return view('client.compte', compact('user', 'reservations'));
    }

    public function mesReservations()
    {
        $user = Auth::user();
        $reservations = Reservation::where('email_client', $user->email)
            ->orWhere('user_id', $user->id)
            ->with('chambre.typeChambre.hotel')
            ->latest()
            ->paginate(10);

        return view('client.reservations', compact('user', 'reservations'));
    }
}