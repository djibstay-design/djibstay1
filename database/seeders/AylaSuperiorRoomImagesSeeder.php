<?php

namespace Database\Seeders;

use App\Models\Chambre;
use App\Models\Hotel;
use App\Models\RoomImage;
use App\Models\TypeChambre;
use Illuminate\Database\Seeder;

class AylaSuperiorRoomImagesSeeder extends Seeder
{
    public function run(): void
    {
        $hotel = Hotel::whereRaw('LOWER(TRIM(nom)) = ?', [strtolower(trim('Ayla Grand Hotel'))])
            ->orWhereRaw('LOWER(nom) LIKE ?', ['%ayla%grand%'])
            ->first();

        if (! $hotel) {
            $this->command?->warn('Ayla Grand Hotel introuvable.');

            return;
        }

        $type = TypeChambre::where('hotel_id', $hotel->id)
            ->where(function ($q) {
                $q->whereRaw('LOWER(nom_type) LIKE ?', ['%supérieure%'])
                    ->orWhereRaw('LOWER(nom_type) LIKE ?', ['%superieure%'])
                    ->orWhereRaw('LOWER(nom_type) LIKE ?', ['%superior%'])
                    ->orWhereRaw('LOWER(nom_type) LIKE ?', ['%chambre sup%']);
            })
            ->orderByRaw('CASE WHEN LOWER(nom_type) LIKE ? THEN 0 ELSE 1 END', ['%chambre%supérieure%'])
            ->orderBy('id')
            ->first();

        if (! $type) {
            $type = TypeChambre::where('hotel_id', $hotel->id)
                ->whereRaw('LOWER(TRIM(nom_type)) = ?', [strtolower(trim('Chambre Deluxe'))])
                ->first();
            if ($type) {
                $type->update(['nom_type' => 'Chambre Supérieure']);
            }
        }

        if ($type && strcasecmp(trim($type->nom_type), 'Supérieure') === 0) {
            $type->update(['nom_type' => 'Chambre Supérieure']);
        }

        if (! $type) {
            $type = TypeChambre::create([
                'nom_type' => 'Chambre Supérieure',
                'capacite' => 2,
                'description' => 'Chambre spacieuse, literie premium, vue ou confort haut de gamme.',
                'prix_par_nuit' => 42000,
                'hotel_id' => $hotel->id,
            ]);
            for ($i = 1; $i <= 4; $i++) {
                Chambre::create([
                    'numero' => 'Supérieure '.$i,
                    'etat' => 'DISPONIBLE',
                    'type_id' => $type->id,
                ]);
            }
            $this->command?->info('Type « Chambre Supérieure » créé avec 4 chambres.');
        }

        $prefix = 'images/hotels/ayla-grand/rooms/superieure/';
        // Retirer toutes les images du type (anciens uploads storage + ancien seed) pour éviter doublons / mauvais ordre
        RoomImage::where('type_chambre_id', $type->id)->delete();

        $files = ['01.png', '02.png', '03.png', '04.png', '05.png', '06.png', '07.png'];
        foreach ($files as $i => $file) {
            RoomImage::create([
                'type_chambre_id' => $type->id,
                'path' => $prefix.$file,
                'sort_order' => $i + 1,
            ]);
        }
    }
}
