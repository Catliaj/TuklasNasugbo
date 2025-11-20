<?php

namespace App\Models;

use CodeIgniter\Model;

class FeedbackModel extends Model
{
    protected $table            = 'review_feedback';
    protected $primaryKey       = 'review_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'booking_id', 'spot_id', 'customer_id', 'business_id', 'rating', 'title', 
        'comment', 'cleanliness_rating', 'staff_rating', 'value_rating', 
        'location_rating', 'status', 'is_verified_visit', 'owner_response', 'response_date'
    ];

    protected $useTimestamps = true;
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

    // ==========================================================
    //  ANALYTICS METHODS (Corrected for your schema)
    // ==========================================================
    public function getOverallAverageRating()
    {
        $result = $this->selectAvg('rating', 'averageRating')
                       ->get()->getRowArray();
        
        return ($result['averageRating']) ? number_format($result['averageRating'], 2) : "0.00";
    }

    public function getLowestRatedSpots($startDate, $endDate, $limit = 5)
    {
        return $this->select('ts.spot_name, AVG(review_feedback.rating) as average_rating')
                    ->from('review_feedback', true)
                    ->join('tourist_spots ts', 'review_feedback.spot_id = ts.spot_id', 'left')
                    ->where('DATE(review_feedback.created_at) >=', $startDate)
                    ->where('DATE(review_feedback.created_at) <=', $endDate)
                    ->groupBy('ts.spot_name')
                    ->orderBy('average_rating', 'ASC')
                    ->limit($limit)
                    ->get()->getResultArray();
    }

    /**
     * Get sentiment analysis data for the reports page.
     */
    public function getSentimentAnalysis($startDate, $endDate)
    {
        $builder = $this->select("DATE_FORMAT(created_at, '%Y-%m') as month, 
            SUM(CASE WHEN rating >= 4 THEN 1 ELSE 0 END) as positive,
            SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as neutral,
            SUM(CASE WHEN rating <= 2 THEN 1 ELSE 0 END) as negative");
        
        $builder->where('DATE(created_at) >=', $startDate)
                ->where('DATE(created_at) <=', $endDate)
                ->groupBy('month')
                ->orderBy('month', 'ASC');

        return $builder->get()->getResultArray();
    }
}