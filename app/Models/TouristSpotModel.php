<?php

namespace App\Models;

use CodeIgniter\Model;

class TouristSpotModel extends Model
{
    protected $table            = 'tourist_spots';
    protected $primaryKey       = 'spot_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
         'business_id', 'spot_name', 'description', 'latitude', 'longitude','category', 'location', 'capacity', 'opening_time', 'closing_time', 'operating_days', 'status', 'price_per_person', 'child_price', 'senior_price', 'group_discount_percent', 'primary_image', 'created_at', 'updated_at'
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

    //getTotal Tourist Spots
    public function getTotalTouristSpots()
    {
        return $this->countAllResults();
    }

    //get total categories
    public function getTotalCategories()
    {
        return $this->select('category, COUNT(*) as total')
                    ->groupBy('category')
                    ->findAll();
    }

    public function getSpotsByBusinessID($businessID)
    {
        try {
            // Check if business ID exists
            $spots = $this->where('business_id', $businessID)
                         ->orderBy('spot_name', 'ASC') // Order by spot name
                         ->findAll();

            if (empty($spots)) {
                return ['status' => 'error', 'message' => 'No spots found for this business ID'];
            }

            // Get the gallery model
            $galleryModel = new \App\Models\SpotGalleryModel();

            // Enhance each spot with its gallery images
            foreach ($spots as &$spot) {
                $spot['gallery'] = $galleryModel->where('spot_id', $spot['spot_id'])->findAll();
            }

            return ['status' => 'success', 'data' => $spots];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Error retrieving tourist spots: ' . $e->getMessage()];
        }
    }
}
