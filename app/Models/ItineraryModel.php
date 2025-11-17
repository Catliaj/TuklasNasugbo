<?php

namespace App\Models;

use CodeIgniter\Model;

class ItineraryModel extends Model
{
    protected $table            = 'itinerary';
    protected $primaryKey       = 'itinerary_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
       'preference_id', 'spot_id', 'description', 'day', 'budget', 'adults', 'children', 'seniors', 'trip_title', 'start_date', 'end_date', 'created_at', 'updated_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];


    
    public function getFullItinerary($trip_title, $start_date)
    {
        return $this->select("
                itinerary.*,
                tourist_spots.spot_name,
                tourist_spots.category,
                tourist_spots.price_per_person,
                tourist_spots.child_price,
                tourist_spots.senior_price,
                tourist_spots.latitude,
                tourist_spots.longitude,
                tourist_spots.location
            ")
            ->join('tourist_spots', 'tourist_spots.spot_id = itinerary.spot_id')
            ->where('itinerary.trip_title', $trip_title)
            ->where('itinerary.start_date', $start_date)
            ->orderBy('itinerary.day', 'ASC')
            ->orderBy('itinerary.itinerary_id', 'ASC')
            ->findAll();
    }
}
