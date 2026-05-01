@component('mail::message')
# Bienvenue {{ $nom }} !

Votre compte hôtelier a été créé avec succès sur **{{ config('app.name') }}**.

**Votre hôtel :** {{ $hotel }}

Voici vos identifiants de connexion :

@component('mail::panel')
**Email :** {{ $email }}
**Mot de passe :** {{ $password }}
@endcomponent

@component('mail::button', ['url' => $lien, 'color' => 'primary'])
Se connecter maintenant
@endcomponent

**Important :** Changez votre mot de passe après votre première connexion.

Cordialement,<br>
L'équipe {{ config('app.name') }}
@endcomponent