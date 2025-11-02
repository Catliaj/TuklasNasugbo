<?php

namespace App\Models;

use CodeIgniter\Model;

class BusinessModel extends Model
{
    protected $table            = 'businesses';
    protected $primaryKey       =  'business_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id', 'business_name', 'contact_email', 'contact_phone', 'business_address', 'logo_url', 'status', 'created_at', 'updated_at'
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



}
