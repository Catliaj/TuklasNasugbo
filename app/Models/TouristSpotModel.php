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

    // ==========================================================
    // ADD 'suspension_reason' TO ALLOWED FIELDS
    // ==========================================================
    protected $allowedFields    = [
         'business_id', 'spot_name', 'description', 'latitude', 'longitude','category', 'location', 'capacity', 'opening_time', 'closing_time', 'operating_days', 'status', 'price_per_person', 'child_price', 'senior_price', 'group_discount_percent', 'primary_image', 'created_at', 'updated_at', 'status_reason', 'suspension_reason'
    ];
    // ==========================================================

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true; 
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
            $spots = $this->where('business_id', $businessID)
                         ->orderBy('spot_name', 'ASC')
                         ->findAll();
            if (empty($spots)) {
                return ['status' => 'error', 'message' => 'No spots found for this business ID'];
            }
            $galleryModel = new \App\Models\SpotGalleryModel();
            foreach ($spots as &$spot) {
                $spot['gallery'] = $galleryModel->where('spot_id', $spot['spot_id'])->findAll();
            }
            return ['status' => 'success', 'data' => $spots];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Error retrieving tourist spots: ' . $e->getMessage()];
        }
    }

    // ==========================================================
    //  FUNCTION TO GET ALL SPOTS FOR ADMIN
    // ==========================================================
    /**
     * Retrieves all tourist spots, joining with businesses and users tables
     * to get the business name and owner's name.
     */
    public function getAllTouristSpots()
    {
        return $this->select('tourist_spots.*, businesses.business_name, users.FirstName, users.LastName')
                    ->join('businesses', 'businesses.business_id = tourist_spots.business_id')
                    ->join('users', 'users.UserID = businesses.user_id')
                    ->orderBy('tourist_spots.created_at', 'DESC')
                    ->findAll();
    }
    
    //get total spots by business id for spot owner dashboard where status is approved
    public function getTotalSpotsByBusinessID($businessID)
    {
        $builder = $this->builder();
        $builder->where('business_id', $businessID);
        $builder->where('status', 'approved');
        return $builder->countAllResults();
    }

    // ==========================================================
    //  NEW DASHBOARD LOGIC: HIDDEN GEMS / RECOMMENDED
    // ==========================================================
    
    public function getTopRecommendedHiddenSpots($limit = 5)
    {
        // Logic: Get spots with the Highest Average Rating
        // This replaces the RAND() placeholder.
        return $this->select('tourist_spots.spot_name, tourist_spots.location')
                    ->selectAvg('review_feedback.rating', 'recommendation_count') // Alias as recommendation_count for frontend compatibility
                    ->join('review_feedback', 'review_feedback.spot_id = tourist_spots.spot_id', 'left')
                    ->where('tourist_spots.status', 'approved')
                    ->groupBy('tourist_spots.spot_id')
                    ->orderBy('recommendation_count', 'DESC')
                    ->limit($limit)
                    ->find();
    }
}