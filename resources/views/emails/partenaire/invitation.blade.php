@component('mail::message')
# Bonjour {{ $nom }},

Nous avons le plaisir de vous inviter à rejoindre notre plateforme en tant que partenaire hôtelier.

Veuillez cliquer sur le bouton ci-dessous pour remplir votre formulaire d'inscription :

@component('mail::button', ['url' => $lien, 'color' => 'primary'])
Remplir mon formulaire
@endcomponent

**Ce lien expire le {{ $expiration }}.**

Si vous avez des questions, répondez simplement à cet email.

Cordialement,<br>
L'équipe {{ \App\Models\SiteSetting::get('app_name', 'DjibStay') }}
@endcomponent