<?php

namespace Database\Seeders;

use App\Models\Chambre;
use App\Models\Hotel;
use App\Models\TypeChambre;
use App\Models\User;
use Illuminate\Database\Seeder;

class HotelSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = User::where('email', 'admin@example.com')->first();
        if (! $superAdmin) {
            return;
        }

        $adminHotel = User::factory()->create([
            'name' => 'Gestionnaire',
            'prenom' => 'Hotel',
            'email' => 'hotel@example.com',
            'role' => 'ADMIN',
            'password' => bcrypt('password'),
        ]);

        $hotels = [
            [
                'nom' => 'Hôtel Les Cocotiers',
                'adresse' => 'Avenue de la Liberté 45',
                'ville' => 'Dakar',
                'description' => 'Hôtel de charme au cœur de Dakar, vue sur l\'océan. Piscine, restaurant et wifi haut débit.',
                'types' => [
                    ['nom' => 'Standard', 'capacite' => 2, 'prix' => 35000, 'chambres' => 5],
                    ['nom' => 'Superior', 'capacite' => 3, 'prix' => 45000, 'chambres' => 3],
                    ['nom' => 'Suite', 'capacite' => 4, 'prix' => 65000, 'chambres' => 2],
                ],
            ],
            [
                'nom' => 'Résidence Le Baobab',
                'adresse' => 'Boulevard du Sud',
                'ville' => 'Saint-Louis',
                'description' => 'Résidence confortable à Saint-Louis. Idéal pour les familles et les longs séjours.',
                'types' => [
                    ['nom' => 'Chambre double', 'capacite' => 2, 'prix' => 28000, 'chambres' => 6],
                    ['nom' => 'Chambre familiale', 'capacite' => 5, 'prix' => 52000, 'chambres' => 2],
                ],
            ],
            [
                'nom' => 'Hôtel Savannah',
                'adresse' => 'Zone industrielle',
                'ville' => 'Thiès',
                'description' => 'Hôtel d\'affaires moderne avec salle de conférence et parking sécurisé.',
                'types' => [
                    ['nom' => 'Économique', 'capacite' => 1, 'prix' => 15000, 'chambres' => 8],
                    ['nom' => 'Confort', 'capacite' => 2, 'prix' => 22000, 'chambres' => 4],
                ],
            ],
            [
                'nom' => 'Le Désert Rose',
                'adresse' => 'Route de Podor',
                'ville' => 'Touba',
                'description' => 'Hôtel paisible près de la grande mosquée. Cuisine locale et chambres climatisées.',
                'types' => [
                    ['nom' => 'Standard', 'capacite' => 2, 'prix' => 18000, 'chambres' => 10],
                    ['nom' => 'Deluxe', 'capacite' => 4, 'prix' => 38000, 'chambres' => 4],
                ],
            ],
            [
                'nom' => 'Hôtel du Fleuve',
                'adresse' => 'Quai du port',
                'ville' => 'Ziguinchor',
                'description' => 'Vue sur le fleuve Casamance. Bar, terrasse et excursions organisées.',
                'types' => [
                    ['nom' => 'Chambre vue fleuve', 'capacite' => 2, 'prix' => 32000, 'chambres' => 6],
                    ['nom' => 'Bungalow', 'capacite' => 4, 'prix' => 55000, 'chambres' => 3],
                ],
            ],
        ];

        $users = [$superAdmin, $superAdmin, $superAdmin, $adminHotel, $adminHotel];

        foreach ($hotels as $index => $data) {
            $hotel = Hotel::create([
                'nom' => $data['nom'],
                'adresse' => $data['adresse'],
                'ville' => $data['ville'],
                'description' => $data['description'],
                'user_id' => $users[$index]->id,
            ]);

            foreach ($data['types'] as $typeData) {
                $type = TypeChambre::create([
                    'nom_type' => $typeData['nom'],
                    'capacite' => $typeData['capacite'],
                    'description' => null,
                    'prix_par_nuit' => $typeData['prix'],
                    'hotel_id' => $hotel->id,
                ]);

                for ($i = 1; $i <= $typeData['chambres']; $i++) {
                    Chambre::create([
                        'numero' => $typeData['nom'].' '.$i,
                        'etat' => 'DISPONIBLE',
                        'type_id' => $type->id,
                    ]);
                }
            }
        }
    }
}
