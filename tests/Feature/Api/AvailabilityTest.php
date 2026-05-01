<?php

namespace Tests\Feature\Api;

use App\Models\Chambre;
use App\Models\Hotel;
use App\Models\Reservation;
use App\Models\TypeChambre;
use App\Models\User;
use Tests\TestCase;

class AvailabilityTest extends TestCase
{
    private User $user;
    private Hotel $hotel;
    private TypeChambre $typeChambre;
    private Chambre $chambre1;
    private Chambre $chambre2;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer un utilisateur
        $this->user = User::factory()->create();

        // Créer un hôtel
        $this->hotel = Hotel::create([
            'nom' => 'Test Hotel',
            'user_id' => $this->user->id,
        ]);

        // Créer un type de chambre
        $this->typeChambre = TypeChambre::create([
            'nom_type' => 'Suite Prestige',
            'prix_par_nuit' => 150,
            'hotel_id' => $this->hotel->id,
        ]);

        // Créer 2 chambres
        $this->chambre1 = Chambre::create([
            'numero' => '101',
            'etat' => 'DISPONIBLE',
            'type_id' => $this->typeChambre->id,
        ]);

        $this->chambre2 = Chambre::create([
            'numero' => '102',
            'etat' => 'DISPONIBLE',
            'type_id' => $this->typeChambre->id,
        ]);
    }

    /** Test: Vérifier la disponibilité quand aucune réservation */
    public function test_check_availability_when_no_reservations(): void
    {
        $response = $this->postJson('/api/availability/check', [
            'room_type_id' => $this->typeChambre->id,
            'check_in' => now()->addDay()->toDateString(),
            'check_out' => now()->addDays(3)->toDateString(),
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'available' => true,
                'available_rooms' => 2,
                'unavailable_rooms' => 0,
                'total_rooms' => 2,
            ]);
    }

    /** Test: Vérifier la disponibilité avec une réservation confirmée */
    public function test_check_availability_with_confirmed_reservation(): void
    {
        // Créer une réservation confirmée pour chambre1
        Reservation::create([
            'chambre_id' => $this->chambre1->id,
            'nom_client' => 'Test Client',
            'prenom_client' => 'Test',
            'email_client' => 'test@example.com',
            'code_identite' => 'TEST123',
            'date_reservation' => now()->toDateString(),
            'date_debut' => now()->addDay()->toDateString(),
            'date_fin' => now()->addDays(3)->toDateString(),
            'quantite' => 1,
            'prix_unitaire' => 150,
            'montant_total' => 300,
            'statut' => 'CONFIRMEE',
            'code_reservation' => 'RES-001',
        ]);

        $response = $this->postJson('/api/availability/check', [
            'room_type_id' => $this->typeChambre->id,
            'check_in' => now()->addDay()->toDateString(),
            'check_out' => now()->addDays(3)->toDateString(),
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'available' => true,
                'available_rooms' => 1,
                'unavailable_rooms' => 1,
            ]);
    }

    /** Test: Vérifier la disponibilité sans chambre disponible */
    public function test_check_availability_no_rooms_available(): void
    {
        // Créer des réservations pour les deux chambres
        $startDate = now()->addDay()->toDateString();
        $endDate = now()->addDays(3)->toDateString();

        foreach ([$this->chambre1, $this->chambre2] as $chambre) {
            Reservation::create([
                'chambre_id' => $chambre->id,
                'nom_client' => 'Test Client',
                'prenom_client' => 'Test',
                'email_client' => 'test@example.com',
                'code_identite' => 'TEST123',
                'date_reservation' => now()->toDateString(),
                'date_debut' => $startDate,
                'date_fin' => $endDate,
                'quantite' => 1,
                'prix_unitaire' => 150,
                'montant_total' => 300,
                'statut' => 'CONFIRMEE',
                'code_reservation' => 'RES-00' . $chambre->id,
            ]);
        }

        $response = $this->postJson('/api/availability/check', [
            'room_type_id' => $this->typeChambre->id,
            'check_in' => $startDate,
            'check_out' => $endDate,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'available' => false,
                'available_rooms' => 0,
                'unavailable_rooms' => 2,
            ]);
    }

    /** Test: Obtenir le calendrier de disponibilité */
    public function test_get_availability_calendar(): void
    {
        $startDate = now()->toDateString();
        $endDate = now()->addDays(7)->toDateString();

        $response = $this->getJson("/api/rooms/{$this->typeChambre->id}/available-dates?start_date={$startDate}&end_date={$endDate}");

        $response->assertStatus(200);

        $data = $response->json();

        // Vérifier la structure de la réponse
        $this->assertArrayHasKey('room_type', $data);
        $this->assertArrayHasKey('calendar', $data);
        $this->assertEquals($this->typeChambre->id, $data['room_type']['id']);
        $this->assertEquals($this->typeChambre->nom_type, $data['room_type']['name']);

        // Vérifier le calendrier
        $calendar = $data['calendar'];
        $this->assertNotEmpty($calendar);

        // Chaque jour devrait avoir 2 chambres disponibles
        foreach ($calendar as $day) {
            $this->assertEquals(2, $day['available_rooms']);
            $this->assertTrue($day['is_available']);
            $this->assertEquals(0, $day['occupancy_rate']);
        }
    }

    /** Test: Calendrier de disponibilité avec réservation */
    public function test_get_availability_calendar_with_reservation(): void
    {
        $startDate = now()->toDateString();
        $endDate = now()->addDays(7)->toDateString();

        // Créer une réservation pour chambre1 du jour 2 au jour 4
        Reservation::create([
            'chambre_id' => $this->chambre1->id,
            'nom_client' => 'Test Client',
            'prenom_client' => 'Test',
            'email_client' => 'test@example.com',
            'code_identite' => 'TEST123',
            'date_reservation' => now()->toDateString(),
            'date_debut' => now()->addDay()->toDateString(),
            'date_fin' => now()->addDays(4)->toDateString(),
            'quantite' => 1,
            'prix_unitaire' => 150,
            'montant_total' => 450,
            'statut' => 'CONFIRMEE',
            'code_reservation' => 'RES-001',
        ]);

        $response = $this->getJson("/api/rooms/{$this->typeChambre->id}/available-dates?start_date={$startDate}&end_date={$endDate}");

        $response->assertStatus(200);

        $data = $response->json();
        $calendar = $data['calendar'];

        // Jour 0: 2 chambres disponibles
        $day0 = $calendar[now()->toDateString()];
        $this->assertEquals(2, $day0['available_rooms']);
        $this->assertEquals(0, $day0['occupancy_rate']);

        // Jour 1-3: 1 chambre disponible (chambre1 réservée)
        $day1 = $calendar[now()->addDay()->toDateString()];
        $this->assertEquals(1, $day1['available_rooms']);
        $this->assertEquals(50, $day1['occupancy_rate']);

        // Jour 5+: 2 chambres disponibles
        $day5 = $calendar[now()->addDays(5)->toDateString()];
        $this->assertEquals(2, $day5['available_rooms']);
        $this->assertEquals(0, $day5['occupancy_rate']);
    }

    /** Test: Vérifier les dates indisponibles pour une chambre */
    public function test_chambre_get_unavailable_dates(): void
    {
        $month = new \DateTime('2026-04-01');

        // Aucune réservation
        $unavailable = $this->chambre1->getUnavailableDates($month);
        $this->assertEmpty($unavailable);

        // Créer une réservation
        Reservation::create([
            'chambre_id' => $this->chambre1->id,
            'nom_client' => 'Test Client',
            'prenom_client' => 'Test',
            'email_client' => 'test@example.com',
            'code_identite' => 'TEST123',
            'date_reservation' => now()->toDateString(),
            'date_debut' => '2026-04-10',
            'date_fin' => '2026-04-15',
            'quantite' => 1,
            'prix_unitaire' => 150,
            'montant_total' => 750,
            'statut' => 'CONFIRMEE',
            'code_reservation' => 'RES-001',
        ]);

        $unavailable = $this->chambre1->getUnavailableDates($month);
        $this->assertNotEmpty($unavailable);
        $this->assertContains('2026-04-10', $unavailable);
        $this->assertContains('2026-04-14', $unavailable);
        $this->assertNotContains('2026-04-15', $unavailable); // date_fin non incluse
    }

    /** Test: Vérifier la disponibilité pour périodes qui ne se chevauchent pas */
    public function test_check_availability_non_overlapping_dates(): void
    {
        // Créer une réservation du 10 au 15 avril
        Reservation::create([
            'chambre_id' => $this->chambre1->id,
            'nom_client' => 'Test Client',
            'prenom_client' => 'Test',
            'email_client' => 'test@example.com',
            'code_identite' => 'TEST123',
            'date_reservation' => now()->toDateString(),
            'date_debut' => '2026-04-10',
            'date_fin' => '2026-04-15',
            'quantite' => 1,
            'prix_unitaire' => 150,
            'montant_total' => 750,
            'statut' => 'CONFIRMEE',
            'code_reservation' => 'RES-001',
        ]);

        // Vérifier disponibilité après la réservation (15-18 avril)
        $response = $this->postJson('/api/availability/check', [
            'room_type_id' => $this->typeChambre->id,
            'check_in' => '2026-04-15',
            'check_out' => '2026-04-18',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'available' => true,
                'available_rooms' => 2, // Les deux chambres sont disponibles
            ]);
    }
}
