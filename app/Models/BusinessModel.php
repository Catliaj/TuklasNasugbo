<?php

namespace App\Models;

use CodeIgniter\Model;

class BusinessModel extends Model
{
    protected $table            = 'businesses';
    protected $primaryKey       = 'business_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    // ==========================================================
    //  MAKE SURE 'status' AND 'rejection_reason' ARE ALLOWED
    // ==========================================================
    protected $allowedFields = [
    'user_id', 'business_name', 'contact_email', 'contact_phone',
    'business_address', 'logo_url', 'status', 'rejection_reason',
    'gov_id_type', 'gov_id_number', 'gov_id_image',
    'created_at', 'updated_at'
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


    //get Total Pending Request
    public function getTotalPendingRequests()
    {
        return $this->where('status', 'pending')->countAllResults();
    }

    //get all BusinessID by UserID
    public function getBusinessIDByUserID($userID)
    {
        return $this->select('business_id')
                    ->where('user_id', $userID)
                    ->first();
    }

    /**
     * Get all registrations and join with the users table to get the owner's name.
     */
    public function getAllRegistrations()
    {
        return $this->select('businesses.*, users.FirstName, users.LastName')
                    ->join('users', 'users.UserID = businesses.user_id')
                    ->orderBy('businesses.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get the top 5 most viewed local businesses.
     */
    public function getTopViewedBusinesses($limit = 5)
    {
        // Preferred: aggregate views from spot_view_logs if it exists
        try {
            $db = $this->db;
            $sql = "
                SELECT b.business_id,
                       b.business_name,
                       COALESCE(SUM(v.views), 0) AS view_count
                FROM businesses b
                LEFT JOIN tourist_spots ts
                  ON ts.business_id = b.business_id
                LEFT JOIN (
                    SELECT spot_id, COUNT(*) AS views
                    FROM spot_view_logs
                    GROUP BY spot_id
                ) v
                  ON v.spot_id = ts.spot_id
                GROUP BY b.business_id, b.business_name
                ORDER BY view_count DESC
                LIMIT ?
            ";
            $rows = $db->query($sql, [(int)$limit])->getResultArray();
            if (is_array($rows) && count($rows)) return $rows;
        } catch (\Throwable $e) {
            // fall through to fallback
            log_message('debug', 'spot_view_logs not available, fallback to bookings: '.$e->getMessage());
        }

        // Fallback: use confirmed bookings as a proxy for popularity
        try {
            $builder = $this->builder();
            $builder->select('businesses.business_id, businesses.business_name, COUNT(b.booking_id) as view_count')
                ->join('tourist_spots ts', 'ts.business_id = businesses.business_id', 'left')
                ->join('bookings b', 'b.spot_id = ts.spot_id AND b.booking_status = "Confirmed"', 'left')
                ->groupBy('businesses.business_id, businesses.business_name')
                ->orderBy('view_count', 'DESC')
                ->limit((int)$limit);
            $rows = $builder->get()->getResultArray();
            if (is_array($rows) && count($rows)) return $rows;
        } catch (\Throwable $e) {
            log_message('warning', 'Fallback to bookings for top views failed: '.$e->getMessage());
        }

        // Final fallback: return businesses with 0 view_count to keep UI consistent
        try {
            $builder = $this->builder();
            $builder->select('businesses.business_id, businesses.business_name, 0 as view_count')
                ->orderBy('businesses.created_at', 'DESC')
                ->limit((int)$limit);
            return $builder->get()->getResultArray();
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function insert($data = null, $returnID = true)
{
    $result = parent::insert($data, $returnID);
    
    if ($result) {
        // Create notification for admins
        $notificationModel = new \App\Models\NotificationModel();
        $notificationModel->notifyAdmins(
            'registration',
            'New Business Registration',
            'A new business has registered and is pending approval.',
            '/admin/registrations'
        );
    }
    
    return $result;
}
}