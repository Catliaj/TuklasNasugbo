<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'UserID';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'FirstName', 'MiddleName', 'LastName', 'email', 'password', 'role', 'LastLogin', 'created_at', 'updated_at'
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
<<<<<<< Updated upstream
=======

    public function getUserCategoryString($userId)
    {
        $builder = $this->db->table('user_preferences');
        $builder->select('category');
        $builder->where('user_id', $userId);
        $result = $builder->get()->getRowArray();

        return $result['category'] ?? null; // returns "Historical,Natural,Urban,Adventure"
    }

    // ==========================================================
    //  NEW DASHBOARD METHOD
    // ==========================================================
    
    /**
     * Get the count of new users registered in the current month.
     */
    public function getNewUsersThisMonth()
    {
        return $this->where('MONTH(created_at)', date('m'))
                    ->where('YEAR(created_at)', date('Y'))
                    ->countAllResults();
    }

     /**
     * Get the distribution of user preferences for the dashboard doughnut chart.
     */
    public function getUserPreferenceDistribution()
    {
        return $this->db->table('user_preferences')
            ->select('category, COUNT(preference_id) as total')
            ->groupBy('category')
            ->get()->getResultArray();
    }
    
    /**
     * Get preference trends over time for the reports page.
     */
    public function getPreferenceTrends($startDate, $endDate)
    {
        return $this->db->table('user_preferences up')
            ->select("DATE_FORMAT(up.created_at, '%Y-%m') as month, up.category, COUNT(up.preference_id) as total")
            ->where('DATE(up.created_at) >=', $startDate)
            ->where('DATE(up.created_at) <=', $endDate)
            ->groupBy(['month', 'up.category'])
            ->orderBy('month', 'ASC')
            ->get()->getResultArray();
    }

>>>>>>> Stashed changes
}
