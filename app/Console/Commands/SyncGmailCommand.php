<?php

namespace App\Console\Commands;

use App\Models\ContactMessage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Webklex\IMAP\Facades\Client;

class SyncGmailCommand extends Command
{
    protected $signature   = 'gmail:sync';
    protected $description = 'Synchronise les emails Gmail toutes les minutes';

    public function handle(): void
    {
        try {
            $client = Client::account('default');
            $client->connect();

            $folder = $client->getFolder('INBOX');
            $emails = $folder->messages()->all()->get();

            $nouveaux = 0;

            foreach ($emails as $email) {
                $uid = $email->getUid();

                if (ContactMessage::where('gmail_uid', $uid)->exists()) {
                    continue;
                }

                $from = $email->getFrom()->first() ?? null;
                if (!$from) continue;

                $nom  = $from->personal ?? $from->mail ?? 'Inconnu';
                $mail = $from->mail ?? '';
                if (empty($mail)) continue;

                $sujetObj = $email->getSubject();
                $sujet    = is_object($sujetObj) ? (string)$sujetObj : ($sujetObj ?? '(Sans objet)');

                $bodyText = $email->getTextBody();
                $bodyHtml = $email->getHTMLBody();
                $body     = strip_tags(is_object($bodyText ?? $bodyHtml) ? (string)($bodyText ?? $bodyHtml) : ($bodyText ?? $bodyHtml ?? ''));
                $body     = trim($body) ?: '(Message vide)';

                ContactMessage::create([
                    'nom'       => $nom,
                    'email'     => $mail,
                    'sujet'     => $sujet,
                    'message'   => $body,
                    'source'    => 'gmail',
                    'gmail_uid' => $uid,
                    'lu'        => false,
                ]);

                $nouveaux++;
            }

            $client->disconnect();
            $this->info("Sync OK — {$nouveaux} nouveau(x) email(s)");

        } catch (\Exception $e) {
            Log::error('Gmail sync error: ' . $e->getMessage());
            $this->error('Erreur: ' . $e->getMessage());
        }
    }
}