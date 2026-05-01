<?php

namespace App\Models;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeChambre extends Model
{
    protected $table = 'types_chambre';

    protected $fillable = [
        'nom_type',
        'capacite',
        'description',
        'superficie_m2',
        'lit_description',
        'has_climatisation',
        'has_minibar',
        'has_wifi',
        'equipements_salle_bain',
        'equipements_generaux',
        'prix_par_nuit',
        'hotel_id',
    ];

    protected function casts(): array
    {
        return [
            'prix_par_nuit' => 'decimal:2',
            'has_climatisation' => 'boolean',
            'has_minibar' => 'boolean',
            'has_wifi' => 'boolean',
        ];
    }

    /**
     * @return array<int, string>
     */
    public function equipementsSalleBainList(): array
    {
        return $this->linesToList($this->equipements_salle_bain);
    }

    /**
     * @return array<int, string>
     */
    public function equipementsGenerauxList(): array
    {
        return $this->linesToList($this->equipements_generaux);
    }

    private function linesToList(?string $text): array
    {
        if ($text === null || trim($text) === '') {
            return [];
        }

        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $text))));
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function chambres(): HasMany
    {
        return $this->hasMany(Chambre::class, 'type_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(RoomImage::class, 'type_chambre_id')->orderBy('sort_order');
    }

    /**
     * Calcule le nombre de chambres disponibles pour ce type sur une période donnée.
     */
    public function calculerDisponibilite($dateDebut, $dateFin): int
    {
        // Total de chambres physiques de ce type
        $total = $this->chambres()->count();

        // Somme des quantités réservées (Confirmées ou En Attente) sur cette période
        $occupees = Reservation::whereHas('chambre', function ($query) {
                $query->where('type_id', $this->id);
            })
            ->whereIn('statut', ['EN_ATTENTE', 'CONFIRMEE'])
            ->where('date_debut', '<', $dateFin)
            ->where('date_fin', '>', $dateDebut)
            ->sum('quantite');

        $dispo = $total - $occupees;

        return $dispo > 0 ? (int) $dispo : 0;
    }
}
