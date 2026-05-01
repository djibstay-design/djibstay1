<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Webklex\IMAP\Facades\Client;

class BoiteMailController extends Controller
{
    // ── Décodage MIME robuste ──────────────────────────────────────────────
    private static function decodeMime(?string $str): string
    {
        if (empty($str)) return '';

        // Décoder toutes les parties encodées =?charset?B/Q?text?=
        $result = preg_replace_callback(
            '/=\?([^?]+)\?([BbQq])\?([^?]*)\?=\s*/',
            function ($match) {
                $charset  = strtoupper(trim($match[1]));
                $encoding = strtoupper($match[2]);
                $text     = $match[3];

                try {
                    if ($encoding === 'B') {
                        $decoded = base64_decode($text);
                    } else {
                        $decoded = quoted_printable_decode(
                            str_replace('_', ' ', $text)
                        );
                    }
                    return mb_convert_encoding($decoded, 'UTF-8', $charset);
                } catch (\Throwable $e) {
                    return $text;
                }
            },
            $str
        );

        return trim($result ?? $str);
    }

    // ── Domaines et emails à ignorer ──────────────────────────────────────
    private static function doitIgnorer(string $email): bool
    {
        $emailsAIgnorer = [
            'no-reply@google.com',
            'no-reply@accounts.google.com',
            'mailer-daemon@googlemail.com',
            'googleplay-noreply@google.com',
            'noreply@google.com',
            'noreply@youtube.com',
        ];

        $domainesAIgnorer = [
            'accounts.google.com',
            'googlemail.com',
            'googleapis.com',
            'googleplay.com',
            'youtube.com',
        ];

        $email   = strtolower(trim($email));
        $domaine = substr(strrchr($email, '@'), 1);

        return in_array($email, $emailsAIgnorer) ||
               in_array($domaine, $domainesAIgnorer);
    }

    // ── Liste des messages ─────────────────────────────────────────────────
    public function index(Request $request)
    {
        $filtre   = $request->get('filtre', 'tous');
        $messages = ContactMessage::query()
            ->when($filtre !== 'archives', fn($q) => $q->where('archive', false))
            ->when($filtre === 'non_lus',    fn($q) => $q->where('lu', false))
            ->when($filtre === 'formulaire', fn($q) => $q->where('source', 'formulaire'))
            ->when($filtre === 'gmail',      fn($q) => $q->where('source', 'gmail'))
            ->when($filtre === 'archives',   fn($q) => $q->where('archive', true))
            ->latest()
            ->paginate(20);

        $counts = [
            'total'      => ContactMessage::where('archive', false)->count(),
            'non_lus'    => ContactMessage::where('lu', false)->where('archive', false)->count(),
            'formulaire' => ContactMessage::where('source', 'formulaire')->where('archive', false)->count(),
            'gmail'      => ContactMessage::where('source', 'gmail')->where('archive', false)->count(),
            'archives'   => ContactMessage::where('archive', true)->count(),
        ];

        return view('admin.boite-mail.index', compact('messages', 'counts', 'filtre'));
    }

    // ── Détail d'un message ────────────────────────────────────────────────
    public function show(ContactMessage $message)
    {
        $message->update(['lu' => true]);
        return view('admin.boite-mail.show', compact('message'));
    }

    // ── Répondre ──────────────────────────────────────────────────────────
    public function repondre(Request $request, ContactMessage $message)
    {
        $request->validate([
            'reponse' => 'required|string|min:1',
        ]);

        Mail::send([], [], function ($mail) use ($message, $request) {
            $mail->to($message->email, $message->nom)
                 ->subject('Re: ' . ($message->sujet ?? 'Votre message — DjibStay'))
                 ->html("
                    <div style='font-family:sans-serif;max-width:600px;margin:0 auto;'>
                        <div style='background:linear-gradient(135deg,#003580,#0071c2);padding:24px;border-radius:12px 12px 0 0;'>
                            <h2 style='color:#fff;margin:0;font-size:20px;'>🏨 DjibStay</h2>
                        </div>
                        <div style='background:#fff;padding:28px;border:1px solid #e2e8f0;'>
                            <p style='color:#1e293b;font-size:15px;'>Bonjour <strong>{$message->nom}</strong>,</p>
                            <div style='color:#475569;font-size:14px;line-height:1.8;white-space:pre-line;'>
                                {$request->reponse}
                            </div>
                            <hr style='border:none;border-top:1px solid #f1f5f9;margin:20px 0;'>
                            <div style='background:#f8fafc;border-radius:8px;padding:14px;'>
                                <p style='font-size:12px;color:#94a3b8;margin:0 0 6px;font-weight:700;text-transform:uppercase;'>
                                    Votre message original :
                                </p>
                                <p style='font-size:13px;color:#64748b;margin:0;font-style:italic;'>
                                    {$message->message}
                                </p>
                            </div>
                        </div>
                        <div style='background:#f8fafc;padding:14px;border-radius:0 0 12px 12px;text-align:center;'>
                            <p style='color:#94a3b8;font-size:12px;margin:0;'>© ".date('Y')." DjibStay</p>
                        </div>
                    </div>
                 ");
        });

        $message->update([
            'reponse'     => $request->reponse,
            'repondu_le'  => now(),
            'repondu_par' => auth()->id(),
            'lu'          => true,
        ]);

        return back()->with('success', '✅ Réponse envoyée à ' . $message->email);
    }

    // ── Archiver ──────────────────────────────────────────────────────────
    public function archiver(ContactMessage $message)
    {
        $message->update(['archive' => true]);
        return back()->with('success', '📁 Message archivé.');
    }

    // ── Inviter Partenaire ────────────────────────────────────────────────
    public function inviterPartenaire(ContactMessage $message)
    {
        // Créer la demande de partenaire
        $partenaire = \App\Models\DemandePartenaire::create([
            'nom_contact'   => $message->nom,
            'email_contact' => $message->email,
            'telephone'     => $message->telephone ?? '',
            'nom_hotel'     => 'À définir',
            'ville'         => 'À définir',
            'statut'        => 'en_attente',
            'message'       => $message->message,
        ]);

        // Marquer le message comme répondu/traité
        $message->update([
            'reponse'     => 'Invitation partenaire envoyée.',
            'repondu_le'  => now(),
            'repondu_par' => auth()->id(),
            'lu'          => true,
            'archive'     => true,
        ]);

        // Rediriger vers l'espace partenaire pour envoyer l'invitation
        return redirect()->route('admin.partenaires.show', $partenaire)
                         ->with('success', 'Le contact a été basculé dans l\'espace partenaire. Vous pouvez maintenant lui envoyer le formulaire.');
    }

    // ── Supprimer ─────────────────────────────────────────────────────────
    public function destroy(ContactMessage $message)
    {
        $message->delete();
        return redirect()->route('admin.boite-mail.index')
                         ->with('success', '🗑️ Message supprimé.');
    }

    // ── Marquer lu/non lu ─────────────────────────────────────────────────
    public function toggleLu(ContactMessage $message)
    {
        $message->update(['lu' => !$message->lu]);
        return back();
    }

    // ── Sync Gmail ────────────────────────────────────────────────────────
    public static function syncGmailStatic(): int
    {
        $nouveaux = 0;

        try {
            $client = Client::account('default');
            $client->connect();

            $folder = $client->getFolder('INBOX');
            $emails = $folder->messages()->all()->get();

            foreach ($emails as $email) {
                $uid = $email->getUid();

                // Déjà traité
                if (ContactMessage::where('gmail_uid', $uid)->exists()) {
                    continue;
                }

                // Expéditeur
                $from = $email->getFrom()->first() ?? null;
                if (!$from) {
                    // Marquer comme traité pour ne plus le revoir
                    self::marquerIgnore($uid);
                    continue;
                }

                $mail = strtolower(trim($from->mail ?? ''));
                if (empty($mail)) {
                    self::marquerIgnore($uid);
                    continue;
                }

                // Ignorer emails automatiques
                if (self::doitIgnorer($mail)) {
                    self::marquerIgnore($uid);
                    continue;
                }

                // Décoder le nom
                $nom = self::decodeMime($from->personal ?? $from->mail ?? 'Inconnu');

                // Décoder le sujet
                $sujetRaw = $email->getSubject();
                $sujetStr = is_object($sujetRaw) ? (string)$sujetRaw : ($sujetRaw ?? '');
                $sujet    = self::decodeMime($sujetStr) ?: '(Sans objet)';

                // Corps du message
                $bodyText = $email->getTextBody();
                $bodyHtml = $email->getHTMLBody();
                $body     = $bodyText ?? $bodyHtml ?? '';
                $body     = strip_tags(is_object($body) ? (string)$body : $body);
                $body     = trim($body) ?: '(Message vide)';

                ContactMessage::create([
                    'nom'       => $nom,
                    'email'     => $mail,
                    'sujet'     => $sujet,
                    'message'   => $body,
                    'source'    => 'gmail',
                    'gmail_uid' => $uid,
                    'lu'        => false,
                    'archive'   => false,
                ]);

                $nouveaux++;
            }

            $client->disconnect();

        } catch (\Exception $e) {
            Log::error('IMAP sync error: ' . $e->getMessage());
        }

        return $nouveaux;
    }

    // ── Marquer un email comme ignoré (ne plus le retraiter) ──────────────
    private static function marquerIgnore(int $uid): void
    {
        ContactMessage::create([
            'nom'       => 'ignoré',
            'email'     => 'ignore@system.local',
            'sujet'     => 'ignoré',
            'message'   => 'ignoré',
            'source'    => 'gmail',
            'gmail_uid' => $uid,
            'lu'        => true,
            'archive'   => true,
        ]);
    }
}