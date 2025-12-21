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
            'business_id', 'spot_name', 'description', 'latitude', 'longitude','category', 'location', 'capacity', 'opening_time', 'closing_time', 'operating_days', 'status', 'price_per_person', 'child_price', 'senior_price', 'primary_image', 'created_at', 'updated_at', 'status_reason', 'suspension_reason'
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
        // Align with public listing: count only approved spots
        return $this->where('status', 'approved')->countAllResults();
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
                         ->findAll() ?: [];

            $galleryModel = new \App\Models\SpotGalleryModel();
            foreach ($spots as &$spot) {
                $rawGallery = $galleryModel->where('spot_id', $spot['spot_id'])->orderBy('image_id','ASC')->findAll() ?: [];
                $normalized = [];
                $primaryFilename = $spot['primary_image'] ?? null;
                foreach ($rawGallery as $g) {
                    $fname = $g['image'] ?? null;
                    if (!$fname) continue;
                    // exclude any gallery image that matches the primary filename
                    if ($primaryFilename && $fname === $primaryFilename) continue;
                    $normalized[] = [
                        'image_id' => $g['image_id'] ?? null,
                        'image' => $fname,
                        'image_url' => base_url('uploads/spots/gallery/' . $fname)
                    ];
                }

                // keep primary_image as filename for canonical DB reference but expose a URL helper
                $spot['primary_image'] = $primaryFilename;
                $spot['primary_image_url'] = $primaryFilename ? base_url('uploads/spots/' . $primaryFilename) : base_url('uploads/spots/Spot-No-Image.png');

                $spot['gallery'] = $normalized;
            }

            return $spots;
        } catch (\Exception $e) {
            // On error return empty array to avoid breaking callers that expect an array
            log_message('error', '[TouristSpotModel::getSpotsByBusinessID] ' . $e->getMessage());
            return [];
        }
    }

    // ==========================================================
    //  FUNCTION TO GET ALL SPOTS FOR ADMIN
    // ==========================================================
    /**
     * Retrieves all tourist spots, joining with businesses and users tables
     * to get the business name and owner's name.
     */
    public function getTotalSpotsByBusinessID($businessID)
    {
        $builder = $this->builder();
        $builder->where('business_id', $businessID);
        $builder->where('status', 'approved');
        return $builder->countAllResults();
    }
    
    public function getAllTouristSpots()
    {
        // Return all attractions (admin usage). Use new getApprovedTouristSpots() for public lists.
        return $this->select('tourist_spots.*, businesses.business_name, users.FirstName, users.LastName')
                    ->join('businesses', 'businesses.business_id = tourist_spots.business_id')
                    ->join('users', 'users.UserID = businesses.user_id')
                    ->orderBy('tourist_spots.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Returns only approved tourist spots (for public listing)
     */
    public function getApprovedTouristSpots()
    {
        return $this->select('tourist_spots.*, businesses.business_name, users.FirstName, users.LastName')
                    ->join('businesses', 'businesses.business_id = tourist_spots.business_id')
                    ->join('users', 'users.UserID = businesses.user_id')
                    ->where('tourist_spots.status', 'approved')
                    ->orderBy('tourist_spots.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Count total pending attractions (status = 'pending')
     * Useful for admin pending requests UI.
     */
    public function getTotalPendingSpots()
    {
        return $this->where('status', 'pending')->countAllResults();
    }
    
    //get total spots by business id for spot owner dashboard where status is approved
    public function getTotalBookingsThisMonthByBusiness($businessID)
    {
        $builder = $this->db->table('bookings b');
        $builder->select('COUNT(DISTINCT b.booking_id) AS total_bookings');
        $builder->join('tourist_spots ts', 'b.spot_id = ts.spot_id');
        $builder->where('ts.business_id', $businessID);
        $builder->where('MONTH(b.booking_date)', date('m'));
        $builder->where('YEAR(b.booking_date)', date('Y'));
        // Count bookings with finalized statuses (not just 'Confirmed')
        $builder->whereIn('b.booking_status', ['Confirmed', 'Checked-in', 'Checked-out', 'Completed', 'Checked-In', 'Checked-Out']);
        $result = $builder->get()->getRowArray();
        return $result['total_bookings'] ?? 0;
    }

    // ==========================================================
    //  NEW DASHBOARD LOGIC: HIDDEN GEMS / RECOMMENDED
    // ==========================================================
    // FUNCTION TO GET SPOT DETAILS WITH OWNER INFO AND GALLERY
    // ==========================================================
    /**
     * Get detailed info of a single spot, including business owner and gallery
     *
     * @param int $spotID
     * @return array
     */
    public function getSpotDetailsWithGallery(int $spotID)
    {
        try {
            // Get spot details with business and owner info
            $spot = $this->select('tourist_spots.*, businesses.business_name, users.FirstName, users.LastName')
                         ->join('businesses', 'businesses.business_id = tourist_spots.business_id')
                         ->join('users', 'users.UserID = businesses.user_id')
                         ->where('tourist_spots.spot_id', $spotID)
                         ->first();

            if (!$spot) {
                return ['status' => 'error', 'message' => 'Spot not found'];
            }

            // Get gallery images and normalize URLs
            $spotGalleryModel = new \App\Models\SpotGalleryModel();
            $gallery = $spotGalleryModel->where('spot_id', $spotID)->findAll();
            $normalizedGallery = [];
            if (!empty($gallery) && is_array($gallery)) {
                foreach ($gallery as $img) {
                    $filename = $img['image'] ?? null;
                    if ($filename) {
                        $normalizedGallery[] = [
                            'image' => $filename,
                            'image_url' => base_url('uploads/spots/gallery/' . $filename)
                        ];
                    }
                }
            }

            // If no gallery images, provide an empty array (frontend may use primary image instead)
            $spot['gallery'] = $normalizedGallery;

            // Keep primary_image as the canonical filename, but expose a primary_image_url for views
            $primary = $spot['primary_image'] ?? null;
            $spot['primary_image'] = $primary;
            $spot['primary_image_url'] = $primary ? base_url('uploads/spots/' . $primary) : base_url('uploads/spots/Spot-No-Image.png');

            return ['status' => 'success', 'data' => $spot];

        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Error retrieving spot details: ' . $e->getMessage()];
        }
    }

        public function getTopRecommendedHiddenSpots($limit = 5)
    {
        // Highest average ratings among approved spots, with counts
        // Keep alias 'recommendation_count' for compatibility with the view
        $builder = $this->db->table($this->table . ' ts');
        $builder->select('ts.spot_id, ts.spot_name, ts.location');
        $builder->selectAvg('rf.rating', 'recommendation_count');
        $builder->selectCount('rf.review_id', 'rating_count');
        // Join reviews (allowing spots to appear only when they truly have ratings via HAVING)
        $builder->join('review_feedback rf', 'rf.spot_id = ts.spot_id AND rf.rating IS NOT NULL', 'left');
        // Match tourist listing exactly: only lowercase 'approved'
        $builder->where('ts.status', 'approved');
        $builder->groupBy('ts.spot_id, ts.spot_name, ts.location');
        // Only include spots with at least one rating
        $builder->having('rating_count >', 0);
        $builder->orderBy('recommendation_count', 'DESC');
        $builder->orderBy('rating_count', 'DESC');
        $builder->limit((int)$limit);
        $rows = $builder->get()->getResultArray();
        // Normalize null averages to 0.0 to avoid null rendering
        foreach ($rows as &$r) {
            if (!isset($r['recommendation_count']) || $r['recommendation_count'] === null) {
                $r['recommendation_count'] = 0.0;
            } else {
                $r['recommendation_count'] = (float)$r['recommendation_count'];
            }
        }
        return $rows;
    }

    public function getTopSpotsByViews(int $limit = 6): array
    {
        $db = $this->db;

        // Preferred: aggregate from spot_view_logs table
        try {
            $sql = "
                SELECT ts.*, COALESCE(v.views, 0) AS views
                FROM " . $this->table . " ts
                LEFT JOIN (
                    SELECT spot_id, COUNT(*) AS views
                    FROM spot_view_logs
                    GROUP BY spot_id
                ) v ON ts.spot_id = v.spot_id
                WHERE ts.status = 'approved'
                ORDER BY v.views DESC, ts.created_at DESC
                LIMIT ?
            ";
            $result = $db->query($sql, [(int)$limit])->getResultArray();
            if (is_array($result)) {
                return $result;
            }
        } catch (\Throwable $e) {
            // Log and continue to fallback
            log_message('warning', 'getTopSpotsByViews (view_logs) failed: ' . $e->getMessage());
        }

        // Fallback: use view_count column via Query Builder with table alias
        try {
            $builder = $this->db->table($this->table . ' ts');
            $builder->select('ts.*, COALESCE(ts.view_count, 0) AS views');
            $builder->where('ts.status', 'approved');
            $builder->orderBy('ts.view_count', 'DESC');
            $builder->orderBy('ts.created_at', 'DESC');
            $builder->limit((int)$limit);
            $rows = $builder->get()->getResultArray();
            if (is_array($rows)) {
                return $rows;
            }
        } catch (\Throwable $e) {
            log_message('warning', 'getTopSpotsByViews (fallback) failed: ' . $e->getMessage());
        }

        // Last fallback: return active spots ordered by created_at
        try {
            $rows = $this->where('status', 'approved')
                         ->orderBy('created_at', 'DESC')
                         ->limit((int)$limit)
                         ->findAll();
            return is_array($rows) ? $rows : [];
        } catch (\Throwable $e) {
            log_message('error', 'getTopSpotsByViews final fallback failed: ' . $e->getMessage());
            return [];
        }
    }
    
}

   

