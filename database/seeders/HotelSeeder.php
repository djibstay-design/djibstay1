<?php

namespace Database\Seeders;

use App\Models\Chambre;
use App\Models\Hotel;
use App\Models\HotelImage;
use App\Models\RoomImage;
use App\Models\TypeChambre;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class HotelSeeder extends Seeder
{
    public function run(): void
    {
        // ── SUPER ADMIN ──
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@djibstay.dj'],
            [
                'name'     => 'Super Admin',
                'prenom'   => 'Super',
                'email'    => 'superadmin@djibstay.dj',
                'password' => Hash::make('password123'),
                'role'     => 'SUPER_ADMIN',
            ]
        );

        // ── ADMINS HÔTELS ──
        $admin1 = User::updateOrCreate(
            ['email' => 'admin.kempinski@djibstay.dj'],
            [
                'name'     => 'Kempinski',
                'prenom'   => 'Admin',
                'email'    => 'admin.kempinski@djibstay.dj',
                'password' => Hash::make('password123'),
                'role'     => 'ADMIN',
            ]
        );

        $admin2 = User::updateOrCreate(
            ['email' => 'admin.sheraton@djibstay.dj'],
            [
                'name'     => 'Sheraton',
                'prenom'   => 'Admin',
                'email'    => 'admin.sheraton@djibstay.dj',
                'password' => Hash::make('password123'),
                'role'     => 'ADMIN',
            ]
        );

        $admin3 = User::updateOrCreate(
            ['email' => 'admin.ayla@djibstay.dj'],
            [
                'name'     => 'Ayla',
                'prenom'   => 'Admin',
                'email'    => 'admin.ayla@djibstay.dj',
                'password' => Hash::make('password123'),
                'role'     => 'ADMIN',
            ]
        );

        // ── CLIENT TEST ──
        User::updateOrCreate(
            ['email' => 'client@djibstay.dj'],
            [
                'name'     => 'Mohamed',
                'prenom'   => 'Ali',
                'email'    => 'client@djibstay.dj',
                'phone'    => '+253 77 00 00 01',
                'password' => Hash::make('password123'),
                'role'     => 'CLIENT',
            ]
        );

        // ── HÔTELS ──
        $hotels = [
            [
                'nom'         => 'Kempinski Palace Djibouti',
                'adresse'     => 'Plateau du Serpent',
                'ville'       => 'Djibouti-Ville',
                'description' => 'Le Kempinski Palace Djibouti est un hôtel de luxe 5 étoiles situé en bord de mer. Avec ses chambres spacieuses, sa piscine à débordement, ses restaurants gastronomiques et son spa de classe mondiale, il offre une expérience unique dans la Corne de l\'Afrique. Vue imprenable sur le Golfe de Tadjourah.',
                'user_id'     => $superAdmin->id,
                'admin_id'    => $admin1->id,
                'image'       => 'kempinski.jpeg',
                'types'       => [
                    [
                        'nom_type'            => 'Chambre Standard',
                        'capacite'            => 2,
                        'prix_par_nuit'       => 25000,
                        'superficie_m2'       => 32,
                        'lit_description'     => '1 lit double Queen Size',
                        'has_wifi'            => true,
                        'has_climatisation'   => true,
                        'has_minibar'         => false,
                        'description'         => 'Chambre confortable avec vue sur la ville ou le jardin.',
                        'equipements_salle_bain' => "Douche\nBaignoire\nSèche-cheveux\nProduits de toilette",
                        'equipements_generaux'   => "TV écran plat\nCoffre-fort\nBureau\nTéléphone",
                        'chambres'            => ['101', '102', '103', '104', '105'],
                    ],
                    [
                        'nom_type'            => 'Chambre Supérieure Vue Mer',
                        'capacite'            => 2,
                        'prix_par_nuit'       => 35000,
                        'superficie_m2'       => 42,
                        'lit_description'     => '1 lit King Size',
                        'has_wifi'            => true,
                        'has_climatisation'   => true,
                        'has_minibar'         => true,
                        'description'         => 'Chambre luxueuse avec vue panoramique sur le Golfe de Tadjourah.',
                        'equipements_salle_bain' => "Douche à effet pluie\nBaignoire jacuzzi\nSèche-cheveux\nProduits Hermès",
                        'equipements_generaux'   => "TV 55 pouces\nCoffre-fort\nBureau\nMinibar\nBalcon privé",
                        'chambres'            => ['201', '202', '203'],
                    ],
                    [
                        'nom_type'            => 'Suite Présidentielle',
                        'capacite'            => 4,
                        'prix_par_nuit'       => 85000,
                        'superficie_m2'       => 120,
                        'lit_description'     => '1 lit King Size + salon séparé',
                        'has_wifi'            => true,
                        'has_climatisation'   => true,
                        'has_minibar'         => true,
                        'description'         => 'Suite somptueuse avec salon privé, salle à manger et terrasse panoramique.',
                        'equipements_salle_bain' => "Bain à remous\nDouche hammam\nDouble vasque\nProduits de luxe",
                        'equipements_generaux'   => "Home cinéma\nCuisine équipée\nTerrasse privée\nService butler 24h",
                        'chambres'            => ['501'],
                    ],
                ],
            ],
            [
                'nom'         => 'Sheraton Djibouti Hotel',
                'adresse'     => 'Place Mahmoud Harbi',
                'ville'       => 'Djibouti-Ville',
                'description' => 'L\'hôtel Sheraton Djibouti est idéalement situé au cœur de la ville, à quelques pas du port et des principaux centres d\'affaires. Ses chambres modernes, sa piscine extérieure et ses restaurants font de lui un choix de premier ordre pour les voyageurs d\'affaires et de loisirs.',
                'user_id'     => $superAdmin->id,
                'admin_id'    => $admin2->id,
                'image'       => 'sheraton.jpeg',
                'types'       => [
                    [
                        'nom_type'            => 'Chambre Classic',
                        'capacite'            => 2,
                        'prix_par_nuit'       => 18000,
                        'superficie_m2'       => 28,
                        'lit_description'     => '1 lit double ou 2 lits simples',
                        'has_wifi'            => true,
                        'has_climatisation'   => true,
                        'has_minibar'         => false,
                        'description'         => 'Chambre moderne et confortable pour un séjour agréable.',
                        'equipements_salle_bain' => "Douche\nSèche-cheveux\nAmenités standard",
                        'equipements_generaux'   => "TV\nBureau\nCoffre-fort",
                        'chambres'            => ['101', '102', '103', '104', '105', '106'],
                    ],
                    [
                        'nom_type'            => 'Chambre Deluxe',
                        'capacite'            => 3,
                        'prix_par_nuit'       => 28000,
                        'superficie_m2'       => 38,
                        'lit_description'     => '1 lit King Size',
                        'has_wifi'            => true,
                        'has_climatisation'   => true,
                        'has_minibar'         => true,
                        'description'         => 'Chambre spacieuse avec vue sur la mer et équipements premium.',
                        'equipements_salle_bain' => "Douche\nBaignoire\nSèche-cheveux\nProduits de qualité",
                        'equipements_generaux'   => "TV écran plat\nMinibar\nCoffre-fort\nBureau",
                        'chambres'            => ['201', '202', '203', '204'],
                    ],
                ],
            ],
            [
                'nom'         => 'Ayla Grand Hotel',
                'adresse'     => 'Boulevard de la République',
                'ville'       => 'Djibouti-Ville',
                'description' => 'L\'Ayla Grand Hotel est un établissement moderne offrant un excellent rapport qualité-prix. Situé dans un quartier calme de Djibouti-Ville, il propose des chambres spacieuses, une piscine, un restaurant et des salles de conférence.',
                'user_id'     => $superAdmin->id,
                'admin_id'    => $admin3->id,
                'image'       => 'ayla.jpg',
                'types'       => [
                    [
                        'nom_type'            => 'Chambre Standard',
                        'capacite'            => 2,
                        'prix_par_nuit'       => 12000,
                        'superficie_m2'       => 25,
                        'lit_description'     => '1 lit double',
                        'has_wifi'            => true,
                        'has_climatisation'   => true,
                        'has_minibar'         => false,
                        'description'         => 'Chambre propre et confortable à prix abordable.',
                        'equipements_salle_bain' => "Douche\nSèche-cheveux",
                        'equipements_generaux'   => "TV\nBureau\nAir conditionné",
                        'chambres'            => ['101', '102', '103', '104', '105', '106', '107'],
                    ],
                    [
                        'nom_type'            => 'Chambre Familiale',
                        'capacite'            => 4,
                        'prix_par_nuit'       => 20000,
                        'superficie_m2'       => 45,
                        'lit_description'     => '1 lit King + 2 lits simples',
                        'has_wifi'            => true,
                        'has_climatisation'   => true,
                        'has_minibar'         => false,
                        'description'         => 'Grande chambre idéale pour les familles avec enfants.',
                        'equipements_salle_bain' => "Douche\nBaignoire\nSèche-cheveux",
                        'equipements_generaux'   => "TV\nBureau\nEspace salon",
                        'chambres'            => ['301', '302', '303'],
                    ],
                ],
            ],
            [
                'nom'         => 'Hôtel Escale International',
                'adresse'     => 'Rue de Venise',
                'ville'       => 'Djibouti-Ville',
                'description' => 'Hôtel Escale International est un hôtel 3 étoiles idéalement placé dans le centre-ville de Djibouti. Son ambiance chaleureuse, son restaurant et sa proximité avec les commerces en font un choix apprécié des voyageurs.',
                'user_id'     => $superAdmin->id,
                'admin_id'    => $admin1->id,
                'image'       => 'escale.jpg',
                'types'       => [
                    [
                        'nom_type'            => 'Chambre Simple',
                        'capacite'            => 1,
                        'prix_par_nuit'       => 8000,
                        'superficie_m2'       => 18,
                        'lit_description'     => '1 lit simple',
                        'has_wifi'            => true,
                        'has_climatisation'   => true,
                        'has_minibar'         => false,
                        'description'         => 'Chambre simple et pratique pour un voyageur seul.',
                        'equipements_salle_bain' => "Douche\nAmenités de base",
                        'equipements_generaux'   => "TV\nAir conditionné",
                        'chambres'            => ['101', '102', '103'],
                    ],
                    [
                        'nom_type'            => 'Chambre Double',
                        'capacite'            => 2,
                        'prix_par_nuit'       => 12000,
                        'superficie_m2'       => 24,
                        'lit_description'     => '1 lit double',
                        'has_wifi'            => true,
                        'has_climatisation'   => true,
                        'has_minibar'         => false,
                        'description'         => 'Chambre double confortable au cœur de Djibouti.',
                        'equipements_salle_bain' => "Douche\nSèche-cheveux",
                        'equipements_generaux'   => "TV\nBureau\nAir conditionné",
                        'chambres'            => ['201', '202', '203', '204'],
                    ],
                ],
            ],
            [
                'nom'         => 'Best Western Djibouti',
                'adresse'     => 'Avenue Georges Clemenceau',
                'ville'       => 'Djibouti-Ville',
                'description' => 'Le Best Western Djibouti offre des chambres modernes avec toutes les commodités nécessaires pour un séjour confortable. Idéal pour les voyageurs d\'affaires, l\'hôtel propose des salles de réunion et un accès internet haut débit.',
                'user_id'     => $superAdmin->id,
                'admin_id'    => $admin2->id,
                'image'       => 'best western.jpeg',
                'types'       => [
                    [
                        'nom_type'            => 'Chambre Business',
                        'capacite'            => 2,
                        'prix_par_nuit'       => 22000,
                        'superficie_m2'       => 30,
                        'lit_description'     => '1 lit Queen Size',
                        'has_wifi'            => true,
                        'has_climatisation'   => true,
                        'has_minibar'         => true,
                        'description'         => 'Chambre équipée pour les voyageurs d\'affaires avec bureau ergonomique.',
                        'equipements_salle_bain' => "Douche\nSèche-cheveux\nAmenités premium",
                        'equipements_generaux'   => "TV\nBureau ergonomique\nMinibar\nCoffre-fort",
                        'chambres'            => ['101', '102', '103', '104'],
                    ],
                ],
            ],
            [
                'nom'         => 'Waafi Hotel',
                'adresse'     => 'Quartier Arhiba',
                'ville'       => 'Djibouti-Ville',
                'description' => 'L\'hôtel Waafi est un établissement familial offrant une hospitalité authentique djiboutienne. Ses chambres propres et confortables, son restaurant servant des plats locaux et sa situation centrale en font une option économique et chaleureuse.',
                'user_id'     => $superAdmin->id,
                'admin_id'    => $admin3->id,
                'image'       => 'waafi.jpg',
                'types'       => [
                    [
                        'nom_type'            => 'Chambre Économique',
                        'capacite'            => 2,
                        'prix_par_nuit'       => 6000,
                        'superficie_m2'       => 20,
                        'lit_description'     => '1 lit double',
                        'has_wifi'            => true,
                        'has_climatisation'   => true,
                        'has_minibar'         => false,
                        'description'         => 'Chambre simple et propre à prix très abordable.',
                        'equipements_salle_bain' => "Douche\nAmenités de base",
                        'equipements_generaux'   => "TV\nAir conditionné",
                        'chambres'            => ['101', '102', '103', '104', '105'],
                    ],
                    [
                        'nom_type'            => 'Chambre Confort',
                        'capacite'            => 2,
                        'prix_par_nuit'       => 10000,
                        'superficie_m2'       => 26,
                        'lit_description'     => '1 lit Queen Size',
                        'has_wifi'            => true,
                        'has_climatisation'   => true,
                        'has_minibar'         => false,
                        'description'         => 'Chambre améliorée avec plus d\'espace et de confort.',
                        'equipements_salle_bain' => "Douche\nSèche-cheveux",
                        'equipements_generaux'   => "TV\nBureau\nAir conditionné",
                        'chambres'            => ['201', '202', '203'],
                    ],
                ],
            ],
        ];

        foreach ($hotels as $hotelData) {
            // Créer l'hôtel
            $hotel = Hotel::updateOrCreate(
                ['nom' => $hotelData['nom']],
                [
                    'adresse'     => $hotelData['adresse'],
                    'ville'       => $hotelData['ville'],
                    'description' => $hotelData['description'],
                    'user_id'     => $hotelData['user_id'],
                    'admin_id'    => $hotelData['admin_id'],
                ]
            );

            // Image principale depuis public/images
            if (!$hotel->mainImage) {
                HotelImage::create([
                    'hotel_id'   => $hotel->id,
                    'path'       => 'images/' . $hotelData['image'],
                    'is_main'    => true,
                    'sort_order' => 0,
                ]);
            }

            // Types de chambres
            foreach ($hotelData['types'] as $typeData) {
                $type = TypeChambre::updateOrCreate(
                    [
                        'hotel_id' => $hotel->id,
                        'nom_type' => $typeData['nom_type'],
                    ],
                    [
                        'capacite'               => $typeData['capacite'],
                        'prix_par_nuit'          => $typeData['prix_par_nuit'],
                        'superficie_m2'          => $typeData['superficie_m2'],
                        'lit_description'        => $typeData['lit_description'],
                        'has_wifi'               => $typeData['has_wifi'],
                        'has_climatisation'      => $typeData['has_climatisation'],
                        'has_minibar'            => $typeData['has_minibar'],
                        'description'            => $typeData['description'],
                        'equipements_salle_bain' => $typeData['equipements_salle_bain'],
                        'equipements_generaux'   => $typeData['equipements_generaux'],
                    ]
                );

                // Chambres
                foreach ($typeData['chambres'] as $numero) {
                    Chambre::updateOrCreate(
                        [
                            'type_id' => $type->id,
                            'numero'  => $numero,
                        ],
                        ['etat' => 'DISPONIBLE']
                    );
                }
            }
        }
    }
}