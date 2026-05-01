<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\HotelImage;
use Illuminate\Database\Seeder;

class AylaGrandHotelImagesSeeder extends Seeder
{
    public function run(): void
    {
        $hotel = Hotel::whereRaw('LOWER(TRIM(nom)) = ?', [strtolower(trim('Ayla Grand Hotel'))])
            ->orWhereRaw('LOWER(nom) LIKE ?', ['%ayla%grand%'])
            ->first();

        if (! $hotel) {
            $this->command?->warn('Ayla Grand Hotel introuvable en base : créez l’hôtel ou exécutez HotelSeeder.');

            return;
        }

        $prefix = 'images/hotels/ayla-grand/';
        HotelImage::where('hotel_id', $hotel->id)
            ->where('path', 'like', $prefix.'%')
            ->delete();

        $files = ['01.png', '02.png', '03.png', '04.png', '05.png', '06.png', '07.png'];
        foreach ($files as $i => $file) {
            HotelImage::create([
                'hotel_id' => $hotel->id,
                'path' => $prefix.$file,
                'is_main' => $i === 0,
                'sort_order' => $i + 1,
            ]);
        }
    }
}
