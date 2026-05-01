<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DemandePartenaire;
use App\Models\Hotel;
use App\Models\User;
use App\Mail\PartenaireInvitationMail;
use App\Mail\PartenaireCredentialsMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PartenaireController extends Controller
{
    // Liste toutes les demandes
    public function index(Request $request)
    {
        $statut = $request->get('statut');
        $demandes = DemandePartenaire::query()
            ->when($statut, fn($q) => $q->where('statut', $statut))
            ->latest()
            ->paginate(15);

        $counts = [
            'total'         => DemandePartenaire::count(),
            'en_attente'    => DemandePartenaire::where('statut','en_attente')->count(),
            'en_discussion' => DemandePartenaire::where('statut','en_discussion')->count(),
            'valide'        => DemandePartenaire::where('statut','valide')->count(),
            'refuse'        => DemandePartenaire::where('statut','refuse')->count(),
        ];

        return view('admin.partenaires.index', compact('demandes','counts','statut'));
    }

    // Formulaire de création manuelle
    public function create(Request $request)
    {
        $prefill = [
            'nom'   => $request->get('nom'),
            'email' => $request->get('email'),
            'tel'   => $request->get('tel'),
            'hotel' => $request->get('hotel'),
            'ville' => $request->get('ville'),
        ];
        return view('admin.partenaires.create', compact('prefill'));
    }

    // Traitement de la création manuelle
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_contact'     => 'required|string|max:100',
            'email_contact'   => 'required|email|unique:users,email',
            'telephone'       => 'nullable|string|max:30',
            'nom_hotel'       => 'required|string|max:150',
            'ville'           => 'required|string|max:100',
            'adresse'         => 'required|string|max:255',
            'description'     => 'nullable|string|max:2000',
            'password'        => 'required|string|min:8|confirmed',
        ]);

        \DB::beginTransaction();
        try {
            // 1. Créer l'utilisateur
            $user = User::create([
                'name'     => $validated['nom_contact'],
                'email'    => $validated['email_contact'],
                'phone'    => $validated['telephone'],
                'password' => Hash::make($validated['password']),
                'role'     => 'ADMIN',
            ]);

            // 2. Créer l'hôtel
            $hotel = Hotel::create([
                'nom'         => $validated['nom_hotel'],
                'ville'       => $validated['ville'],
                'adresse'     => $validated['adresse'],
                'description' => $validated['description'],
                'user_id'     => $user->id,
                'admin_id'    => $user->id,
            ]);

            // 3. Créer une demande factice validée pour l'historique (optionnel)
            DemandePartenaire::create([
                'nom_contact'   => $validated['nom_contact'],
                'email_contact' => $validated['email_contact'],
                'telephone'     => $validated['telephone'],
                'nom_hotel'     => $validated['nom_hotel'],
                'ville'         => $validated['ville'],
                'statut'        => 'valide',
                'formulaire_rempli' => true,
                'valide_le'     => now(),
                'traite_par'    => auth()->id(),
            ]);

            \DB::commit();

            // 4. Envoyer les identifiants par email
            try {
                Mail::to($user->email)->send(
                    new \App\Mail\PartenaireCredentialsMail($user, $validated['password'], $hotel)
                );
            } catch (\Exception $e) {
                \Log::error("Erreur envoi mail identifiants manuel: " . $e->getMessage());
                // On ne bloque pas la redirection car le compte est créé
            }

            return redirect()->route('admin.partenaires.index')
                ->with('success', "Partenaire et hôtel créés avec succès pour {$user->name}. Les identifiants ont été envoyés par email.");

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Erreur lors de la création : ' . $e->getMessage())->withInput();
        }
    }

    // Détail d'une demande
    public function show(DemandePartenaire $partenaire)
    {
        return view('admin.partenaires.show', compact('partenaire'));
    }

    // Changer le statut
    public function updateStatut(Request $request, DemandePartenaire $partenaire)
    {
        $request->validate([
            'statut' => 'required|in:en_attente,en_discussion,valide,refuse'
        ]);

        $partenaire->update([
            'statut'     => $request->statut,
            'traite_par' => auth()->id(),
        ]);

        return back()->with('success', 'Statut mis à jour.');
    }

    // Sauvegarder note admin
    public function saveNote(Request $request, DemandePartenaire $partenaire)
    {
        $request->validate(['notes_admin' => 'nullable|string|max:2000']);
        $partenaire->update(['notes_admin' => $request->notes_admin]);
        return back()->with('success', 'Note sauvegardée.');
    }

    // Envoyer lien formulaire d'inscription
    public function envoyerInvitation(DemandePartenaire $partenaire)
    {
        // Générer un token si inexistant
        if (!$partenaire->token_invitation) {
            $partenaire->token_invitation = Str::random(60);
        }
        
        $partenaire->token_expire_le = now()->addDays(7);
        $partenaire->invitation_envoyee_le = now();
        $partenaire->statut = 'en_discussion';
        $partenaire->save();

        $lienFormulaire = route('partenaire.formulaire', $partenaire->token_invitation);
        
        $partenaire->update([
            'lien_formulaire' => $lienFormulaire,
        ]);

        // Envoyer email avec le lien
        Mail::send([], [], function ($mail) use ($partenaire, $lienFormulaire) {
            $appName = \App\Models\SiteSetting::get('app_name', 'DjibStay');
            $mail->to($partenaire->email_contact)
                 ->subject($appName . ' — Votre formulaire de partenariat')
                 ->html("
                    <div style='font-family:sans-serif;max-width:600px;margin:0 auto;'>

                        <div style='background:linear-gradient(135deg,#003580,#0071c2);padding:30px;border-radius:12px 12px 0 0;text-align:center;'>
                            <h1 style='color:#fff;margin:0;font-size:24px;font-weight:900;'>🏨 {$appName}</h1>
                            <p style='color:rgba(255,255,255,0.8);margin:8px 0 0;font-size:14px;'>Plateforme hôtelière de Djibouti</p>
                        </div>

                        <div style='background:#fff;padding:32px;border:1px solid #e2e8f0;'>
                            <p style='font-size:16px;color:#1e293b;margin-bottom:16px;'>
                                Bonjour <strong>{$partenaire->nom_contact}</strong>,
                            </p>
                            <p style='color:#475569;line-height:1.8;font-size:14px;margin-bottom:20px;'>
                                Suite à nos échanges, nous sommes heureux de vous inviter à rejoindre la plateforme <strong>{$appName}</strong>
                                en tant que partenaire hôtelier.
                            </p>
                            <p style='color:#475569;line-height:1.8;font-size:14px;margin-bottom:28px;'>
                                Veuillez remplir le formulaire ci-dessous avec vos informations personnelles
                                et celles de votre hôtel. Cela ne prend que <strong>2 minutes</strong>.
                            </p>

                            <div style='text-align:center;margin:28px 0;'>
                                <a href='{$lienFormulaire}'
                                   style='background:linear-gradient(135deg,#003580,#0071c2);color:#fff;padding:16px 36px;border-radius:10px;text-decoration:none;font-weight:800;font-size:15px;display:inline-block;box-shadow:0 4px 16px rgba(0,53,128,0.3);'>
                                    📋 Remplir le formulaire
                                </a>
                            </div>

                            <div style='background:#f0f7ff;border-radius:10px;padding:16px 20px;border-left:4px solid #0071c2;margin-bottom:20px;'>
                                <p style='font-size:13px;color:#475569;margin:0;line-height:1.7;'>
                                    ⏱️ <strong>Durée estimée :</strong> 2 minutes<br>
                                    📅 <strong>Délai de traitement :</strong> 24-48h après réception<br>
                                    🔒 <strong>Vos données</strong> sont sécurisées et confidentielles
                                </p>
                            </div>

                            <p style='color:#94a3b8;font-size:13px;line-height:1.6;margin:0;'>
                                Une fois votre formulaire soumis, notre équipe l'examinera et vous recevrez
                                vos identifiants de connexion par email sous <strong>48h</strong>.
                            </p>
                        </div>

                        <div style='background:#f8fafc;padding:18px 32px;border-radius:0 0 12px 12px;border:1px solid #e2e8f0;border-top:none;text-align:center;'>
                            <p style='color:#94a3b8;font-size:12px;margin:0;'>
                                © " . date('Y') . " {$appName} — Tous droits réservés<br>
                                Si vous n'êtes pas à l'origine de cette demande, ignorez cet email.
                            </p>
                        </div>

                    </div>
                 ");
        });

        return back()->with('success', '✅ Invitation envoyée à ' . $partenaire->email_contact);
    }

    // Afficher formulaire d'inscription hôtelier (lien unique)
    public function formulaireInscription(string $token)
    {
        $demande = DemandePartenaire::where('token_invitation', $token)
            ->where('formulaire_rempli', false)
            ->firstOrFail();

        if (!$demande->isTokenValid()) {
            abort(410, 'Ce lien a expiré.');
        }

        return view('partenaire.formulaire', compact('demande', 'token'));
    }

    // Soumettre le formulaire d'inscription hôtelier
    public function soumettreFormulaire(Request $request, string $token)
    {
        $demande = DemandePartenaire::where('token_invitation', $token)
            ->where('formulaire_rempli', false)
            ->firstOrFail();

        if (!$demande->isTokenValid()) {
            abort(410, 'Ce lien a expiré.');
        }

        $request->validate([
            'nom_contact'     => 'required|string|max:100',
            'email_contact'   => 'required|email',
            'telephone'       => 'nullable|string|max:30',
            'nom_hotel'       => 'required|string|max:150',
            'ville'           => 'required|string|max:100',
            'adresse'         => 'required|string|max:255',
            'description'     => 'nullable|string|max:2000',
            'nombre_chambres' => 'nullable|integer|min:1',
            'site_web'        => 'nullable|url|max:255',
        ]);

        $demande->update([
            'nom_contact'     => $request->nom_contact,
            'email_contact'   => $request->email_contact,
            'telephone'       => $request->telephone,
            'nom_hotel'       => $request->nom_hotel,
            'ville'           => $request->ville,
            'description'     => $request->description,
            'nombre_chambres' => $request->nombre_chambres,
            'site_web'        => $request->site_web,
            'formulaire_rempli' => true,
            'statut'          => 'en_attente',
        ]);

        return view('partenaire.merci');
    }

    // Valider et créer compte hôtelier
    public function valider(DemandePartenaire $partenaire)
    {
        if ($partenaire->statut === 'valide') {
            return back()->with('error', 'Cette demande est déjà validée.');
        }

        // Générer mot de passe
        $password = Str::random(10);

        // Créer user hotel_admin
        $user = User::create([
            'name'     => $partenaire->nom_contact,
            'prenom'   => '',
            'email'    => $partenaire->email_contact,
            'phone'    => $partenaire->telephone,
            'password' => Hash::make($password),
            'role'     => 'ADMIN',
        ]);

        // Créer l'hôtel
        $hotel = Hotel::create([
            'nom'             => $partenaire->nom_hotel,
            'ville'           => $partenaire->ville,
            'adresse'         => $partenaire->description ?? '',
            'description'     => $partenaire->description,
            'nombre_chambres' => $partenaire->nombre_chambres,
            'user_id'         => $user->id,
            'admin_id'        => $user->id,
        ]);

        // Mettre à jour la demande
        $partenaire->update([
            'statut'    => 'valide',
            'valide_le' => now(),
            'traite_par'=> auth()->id(),
        ]);

        // Envoyer email avec identifiants
        Mail::to($partenaire->email_contact)
            ->send(new PartenaireCredentialsMail($user, $password, $hotel));

        return back()->with('success',
            'Compte créé pour '.$partenaire->nom_contact.' — email envoyé avec les identifiants.');
    }

    // Refuser
    public function refuser(DemandePartenaire $partenaire)
    {
        $partenaire->update([
            'statut'     => 'refuse',
            'traite_par' => auth()->id(),
        ]);

        return back()->with('success', 'Demande refusée.');
    }
}