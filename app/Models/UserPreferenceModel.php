<?php

namespace App\Models;

use CodeIgniter\Model;

class UserPreferenceModel extends Model
{

    protected $table            = 'user_preferences';
    protected $primaryKey       = 'preference_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
       'user_id', 'category', 'created_at', 'updated_at'
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

    // ==========================================================
    //  NEW METHODS FOR DASHBOARD & ANALYTICS
    // ==========================================================

    /**
     * Get distribution of user preferences for the Dashboard Pie Chart.
    * Returns: [{'category': 'Natural', 'total': 15}, ...]
     */
    public function getUserPreferenceDistribution()
    {
        return $this->select('category, COUNT(*) as total')
                    ->groupBy('category')
                    ->orderBy('total', 'DESC')
                    ->findAll();
    }

    /**
     * Get preference trends over a specific date range for the Reports Page.
     */
    public function getPreferenceTrends($startDate, $endDate)
    {
        return $this->select("category, COUNT(*) as count")
                    ->where('DATE(created_at) >=', $startDate)
                    ->where('DATE(created_at) <=', $endDate)
                    ->groupBy('category')
                    ->orderBy('count', 'DESC')
                    ->findAll();
    }
}