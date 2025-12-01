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
        'FirstName', 'MiddleName', 'LastName', 'email', 'password', 'role', 'email_verified', 'google_id', 'LastLogin', 'created_at', 'updated_at'
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

    public function getUserCategoryString($userId)
    {
        $builder = $this->db->table('user_preferences');
        $builder->select('category');
        $builder->where('user_id', $userId);
        $result = $builder->get()->getRowArray();

        return $result['category'] ?? null; // returns "Historical,Natural,Urban,Adventure"
    }

    // ==========================================================
    //  DASHBOARD / REPORT METHODS
    // ==========================================================

    /**
     * Get the count of new users registered in the current month.
     *
     * @return int
     */
    public function getNewUsersThisMonth()
    {
        // Note: using DB functions in where clauses; works but may be DB-specific.
        return $this->where('MONTH(created_at)', date('m'))
                    ->where('YEAR(created_at)', date('Y'))
                    ->countAllResults();
    }

    /**
     * Get the distribution of user preferences for the dashboard doughnut chart.
     *
     * The 'category' column may contain comma-separated values per user (e.g., "History,Adventure,Rural").
     * This method splits them and counts each category once per user.
     *
     * Returns array of rows: ['category' => '...', 'total' => int]
     *
     * @return array
     */
    public function getUserPreferenceDistribution()
    {
        $rows = $this->db->table('user_preferences')
            ->select('user_id, category')
            ->get()->getResultArray();

        if (empty($rows)) return [];

        $counts = [];
        $seenPairs = [];

        foreach ($rows as $r) {
            $userId = $r['user_id'] ?? null;
            $cats = $r['category'] ?? '';
            if ($userId === null || $cats === null) continue;

            // Split comma-separated categories and normalize
            $parts = array_filter(array_map(function($s){
                $s = trim($s);
                // Normalize whitespace and casing
                $s = preg_replace('/\s+/', ' ', $s);
                return $s;
            }, explode(',', (string)$cats)), function($v){ return $v !== ''; });

            foreach ($parts as $p) {
                $norm = strtolower($p);
                $key = $userId . '|' . $norm; // ensure one count per user per category
                if (isset($seenPairs[$key])) continue;
                $seenPairs[$key] = true;
                if (!isset($counts[$norm])) $counts[$norm] = 0;
                $counts[$norm]++;
            }
        }

        // Transform to array with Title Case labels and sort by total desc
        $out = [];
        foreach ($counts as $norm => $cnt) {
            $label = ucwords($norm);
            $out[] = [ 'category' => $label, 'total' => (int)$cnt ];
        }
        usort($out, function($a,$b){ return $b['total'] <=> $a['total'] ?: strcmp($a['category'],$b['category']); });
        return $out;
    }

    /**
     * Get preference trends over time for the reports page.
     *
     * Returns rows with columns: ['month' => 'YYYY-MM', 'category' => '...', 'total' => int]
     *
     * @param string $startDate  YYYY-MM-DD
     * @param string $endDate    YYYY-MM-DD
     * @return array
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
}