<?php

namespace App\Models;

use CodeIgniter\Model;

class FeedbackModel extends Model
{
    protected $table            = 'feedbacks';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['spot_id', 'customer_id', 'rating', 'comment', 'created_at', 'updated_at'];

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

    // ==========================================================
    //  NEW ANALYTICS METHODS
    // ==========================================================
    public function getAverageRating($startDate = null, $endDate = null)
    {
        $builder = $this->builder();
        $builder->selectAvg('rating', 'averageRating');

        if ($startDate && $endDate) {
            $builder->where('created_at >=', $startDate);
            $builder->where('created_at <=', $endDate);
        }

        $result = $builder->get()->getRowArray();
        return ($result['averageRating']) ? number_format($result['averageRating'], 2) : "0.00";
    }

    public function getLowestRatedSpots($startDate, $endDate, $limit = 5)
    {
        return $this->select('ts.spot_name, AVG(f.rating) as average_rating, COUNT(f.id) as review_count')
                    ->from('feedbacks f')
                    ->join('tourist_spots ts', 'f.spot_id = ts.spot_id')
                    ->where('f.created_at >=', $startDate)
                    ->where('f.created_at <=', $endDate)
                    ->groupBy('ts.spot_name')
                    ->orderBy('average_rating', 'ASC')
                    ->limit($limit)
                    ->get()->getResultArray();
    }
}