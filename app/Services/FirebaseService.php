<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    /**
     * Envoyer une notification Push à un utilisateur spécifique.
     */
    public static function sendNotification($deviceToken, $title, $body, $data = [])
    {
        if (empty($deviceToken)) {
            return false;
        }

        $serverKey = config('services.firebase.server_key');
        
        if (empty($serverKey)) {
            Log::warning("Firebase Server Key non configurée dans services.php");
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'key=' . $serverKey,
                'Content-Type'  => 'application/json',
            ])->post('https://fcm.googleapis.com/fcm/send', [
                'to' => $deviceToken,
                'notification' => [
                    'title' => $title,
                    'body'  => $body,
                    'sound' => 'default',
                ],
                'data' => $data,
                'priority' => 'high',
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error("Erreur d'envoi Firebase: " . $e->getMessage());
            return false;
        }
    }
}
