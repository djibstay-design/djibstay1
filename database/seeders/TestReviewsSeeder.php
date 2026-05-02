<?php

namespace Database\Seeders;

use App\Models\Avis;
use App\Models\Hotel;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TestReviewsSeeder extends Seeder
{
    public function run(): void
    {
        $hotels = Hotel::all();
        
        if ($hotels->isEmpty()) {
            return;
        }

        $names = [
            'Jean Dupont', 'Marie Curie', 'Abdoulaye Mohamed', 'Sarah Williams', 
            'Ahmed Ali', 'Fatima Zahra', 'David Smith', 'Elena Rodriguez', 
            'Marc Lefebvre', 'Sophie Martin', 'Yasmine Aden', 'Omar Hassan',
            'Ismael Omar', 'Khadra Barkhad', 'Yassin Daher', 'Ayan Dileita'
        ];

        $comments = [
            'Très bel hôtel, service impeccable !',
            'Chambre propre et spacieuse. Je recommande vivement.',
            'Bon rapport qualité-prix, personnel très accueillant.',
            'Lieu magnifique avec une vue imprenable sur la ville.',
            'Séjour agréable, le personnel est aux petits soins.',
            'Excellent séjour, tout était parfait de l\'arrivée au départ.',
            'Petit déjeuner varié et délicieux, beaucoup de choix.',
            'Emplacement idéal au centre-ville, proche de tout.',
            'Personnel très attentionné et professionnel, merci !',
            'Literie très confortable, j\'ai passé une excellente nuit.',
            'Hôtel calme et reposant, parfait pour un voyage d\'affaires.',
            'Décoration moderne et chaleureuse, on s\'y sent bien.',
            'Great experience, will definitely come back soon!',
            'The staff was very helpful and the room was absolutely beautiful.',
            'Good location, very convenient for sightseeing.',
            'Very peaceful atmosphere, perfect for a weekend getaway.',
            'Service exceptionnel et propreté irréprochable.'
        ];

        foreach ($hotels as $hotel) {
            // On s'assure qu'il y a au moins 5 avis par hôtel
            $count = max(5, 5); 
            
            for ($i = 0; $i < $count; $i++) {
                Avis::create([
                    'nom_client' => $names[array_rand($names)],
                    'email_client' => 'client' . rand(1, 10000) . '@test.com',
                    'note' => rand(4, 5), // On met des bonnes notes pour le test
                    'commentaire' => $comments[array_rand($comments)],
                    'date_avis' => Carbon::now()->subDays(rand(1, 60)),
                    'hotel_id' => $hotel->id,
                ]);
            }
        }
    }
}
