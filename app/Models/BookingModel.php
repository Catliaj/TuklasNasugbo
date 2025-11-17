<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
    protected $table            = 'bookings';
    protected $primaryKey       = 'booking_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'spot_id', 'customer_id', 'booking_date', 'visit_date', 'visit_time', 'num_adults', 'num_children', 'num_seniors', 'total_guests', 'price_per_person', 'subtotal', 'discount_amount', 'tax_amount', 'total_price', 'booking_status', 'payment_status', 'special_requests', 'cancellation_reason', 'internal_notes', 'created_at', 'updated_at', 'confirmed_at', 'cancelled_at', 'completed_at'
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

    //Total Bookings this months
    public function getTotalBookingsThisMonth()
    {
        $builder = $this->builder();
        $builder->where('MONTH(booking_date)', date('m'));
        $builder->where('YEAR(booking_date)', date('Y'));
        return $builder->countAllResults();
    }

    //Total bookings this day
    public function getTotalBookingsToday()
    {
        $builder = $this->builder();
        $builder->where('DATE(booking_date)', date('Y-m-d'));
        return $builder->countAllResults();
        }

        //Monthly Bookings Trend loadBookingsChart function for Admin Dashboard chart data
    public function getMonthlyBookingsTrend()
    {
        return $this
            ->select('MONTH(booking_date) as month, COUNT(*) as total')
            ->where('YEAR(booking_date)', date('Y')) // ✅ use the parameter instead of date('Y')
            ->groupBy('MONTH(booking_date)')
            ->orderBy('MONTH(booking_date)', 'ASC')
            ->findAll();
    }


    //select  count(bookings) group by month for chart from bookings inner join tourist spots on bookings.spot_id = tourist_spots.spot_id where business_id = ? 
    public function getTotalBookingsThisMonthByBusiness($businessID)
    {
        $builder = $this->db->table('bookings b');
        $builder->select('COUNT(DISTINCT b.booking_id) AS total_bookings');
        $builder->join('tourist_spots ts', 'b.spot_id = ts.spot_id');
        $builder->where('ts.business_id', $businessID);
        $builder->where('MONTH(b.booking_date)', date('m'));
        $builder->where('YEAR(b.booking_date)', date('Y'));
        $builder->where('b.booking_status', 'Confirmed');
        $result = $builder->get()->getRowArray();
        return $result['total_bookings'] ?? 0;
    }

    //select sum(b.total_price) from bookings inner join tourist spots on bookings.spot_id = tourist_spots.spot_id where business_id = ? where booking is confirmed
    public function getTotalRevenueByBusiness($businessID)
    {
        $builder = $this->builder(); 
        $builder->select('SUM(DISTINCT b.total_price) AS total_revenue');
        $builder->from('bookings b');
        $builder->join('tourist_spots ts', 'b.spot_id = ts.spot_id');
        $builder->where('ts.business_id', $businessID);
        $builder->where('b.booking_status', 'Confirmed');
        $result = $builder->get()->getRowArray();
        return $result['total_revenue'] ?? 0;
    }

    public function getMonthlyBookingsTrendByBusiness($businessID)
    {
        $builder = $this->builder(); 
        $builder->select('MONTH(b.booking_date) as month, COUNT(*) as total');
        $builder->from('bookings b');
        $builder->join('tourist_spots ts', 'b.spot_id = ts.spot_id');
        $builder->where('ts.business_id', $businessID);
        $builder->where('YEAR(b.booking_date)', date('Y'));
        $builder->groupBy('MONTH(b.booking_date)');
        $builder->orderBy('MONTH(b.booking_date)', 'ASC');
        return $builder->get()->getResultArray();
    }


    //will get the name of the customer total_guestfrom users table by joining bookings.customer_id = users.user_id and get the booking by business id
    public function getBookingsByBusinessID($businessID)
    {
        
        $builder = $this->builder(); 
        $builder->select('b.*, CONCAT(u.FirstName, " ", u.LastName) as customer_name', 'u.email', 'c.phone_number as phone');
        $builder->from('bookings b');
        $builder->join('tourist_spots ts', 'b.spot_id = ts.spot_id');
        $builder->join('users u', 'b.customer_id = u.UserID');
        $builder->join('customers c', 'b.customer_id = c.customer_id');
        $builder->where('ts.business_id', $businessID);
        $builder->groupBy('b.booking_id');

        return $builder->get()->getResultArray();
    }

    //will get the name of the customer total_guestfrom users table by joining bookings.customer_id = users.user_id and get the booking by spot id email and phone number 
    public function getBookingDetails($bookingID)
    {
        
        $builder = $this->builder(); 
        $builder->select('b.*, CONCAT(u.FirstName, " ", u.LastName) as customer_name, u.email as email, c.phone as phone');
        $builder->from('bookings b');
        $builder->join('users u', 'b.customer_id = u.UserID');
        $builder->join('customers c', 'b.customer_id = c.customer_id');
        $builder->where('b.booking_id', $bookingID);
        $builder->groupBy('b.booking_id');

        return $builder->get()->getRowArray();
    }

    //get total visitors where booking is confirmed
    public function getTotalVisitor($businessID)
    {
        $builder = $this->db->table('bookings b');
        $builder->selectSum('b.total_guests', 'total');
        $builder->join('tourist_spots ts', 'b.spot_id = ts.spot_id');
        $builder->where([
            'ts.business_id' => $businessID,
            'b.booking_status' => 'Confirmed'
        ]);
        $builder->where('MONTH(b.booking_date)', date('m'));
        $builder->where('YEAR(b.booking_date)', date('Y'));

        $result = $builder->get()->getRowArray();
        return isset($result['total']) ? (int)$result['total'] : 0;
    }

    /////////////yeoj/////////////////////////

    /**
 * Get monthly revenue for the last 6 months by business
 */
public function getMonthlyRevenueByBusiness($businessID, $months = 6)
{
    $builder = $this->db->table('bookings b');
    $builder->select('
        DATE_FORMAT(b.booking_date, "%Y-%m") as month,
        MONTHNAME(b.booking_date) as month_name,
        SUM(b.total_price) as revenue,
        COUNT(b.booking_id) as bookings
    ');
    $builder->join('tourist_spots ts', 'b.spot_id = ts.spot_id');
    $builder->where('ts.business_id', $businessID);
    $builder->where('b.booking_status', 'Confirmed');
    $builder->where('b.booking_date >=', date('Y-m-d', strtotime("-{$months} months")));
    $builder->groupBy('DATE_FORMAT(b.booking_date, "%Y-%m")');
    $builder->orderBy('month', 'ASC');
    
    return $builder->get()->getResultArray();
}

/**
 * Get weekly revenue for the last 8 weeks by business
 */
public function getWeeklyRevenueByBusiness($businessID, $weeks = 8)
{
    $builder = $this->db->table('bookings b');
    $builder->select('
        YEARWEEK(b.booking_date, 1) as week_num,
        DATE_FORMAT(MIN(b.booking_date), "%b %d") as week_start,
        DATE_FORMAT(MAX(b.booking_date), "%b %d") as week_end,
        SUM(b.total_price) as revenue,
        COUNT(b.booking_id) as bookings
    ');
    $builder->join('tourist_spots ts', 'b.spot_id = ts.spot_id');
    $builder->where('ts.business_id', $businessID);
    $builder->where('b.booking_status', 'Confirmed');
    $builder->where('b.booking_date >=', date('Y-m-d', strtotime("-{$weeks} weeks")));
    $builder->groupBy('YEARWEEK(b.booking_date, 1)');
    $builder->orderBy('week_num', 'ASC');
    
    return $builder->get()->getResultArray();
}

/**
 * Get booking trends by status for the last 6 months
 */
public function getBookingTrendsByBusiness($businessID, $months = 6)
{
    $builder = $this->db->table('bookings b');
    $builder->select('
        DATE_FORMAT(b.booking_date, "%Y-%m") as month,
        MONTHNAME(b.booking_date) as month_name,
        b.booking_status,
        COUNT(b.booking_id) as count
    ');
    $builder->join('tourist_spots ts', 'b.spot_id = ts.spot_id');
    $builder->where('ts.business_id', $businessID);
    $builder->where('b.booking_date >=', date('Y-m-d', strtotime("-{$months} months")));
    $builder->groupBy(['DATE_FORMAT(b.booking_date, "%Y-%m")', 'b.booking_status']);
    $builder->orderBy('month', 'ASC');
    
    return $builder->get()->getResultArray();
}

/**
 * Get all-time total revenue for a business
 */
public function getTotalRevenueAllTime($businessID)
{
    $builder = $this->db->table('bookings b');
    $builder->select('SUM(b.total_price) AS total_revenue');
    $builder->join('tourist_spots ts', 'b.spot_id = ts.spot_id');
    $builder->where('ts.business_id', $businessID);
    $builder->where('b.booking_status', 'Confirmed');
    
    $result = $builder->get()->getRowArray();
    return $result['total_revenue'] ?? 0;
}

/**
 * Get revenue for current month
 */
public function getMonthlyRevenue($businessID)
{
    $builder = $this->db->table('bookings b');
    $builder->select('SUM(b.total_price) AS monthly_revenue');
    $builder->join('tourist_spots ts', 'b.spot_id = ts.spot_id');
    $builder->where('ts.business_id', $businessID);
    $builder->where('b.booking_status', 'Confirmed');
    $builder->where('MONTH(b.booking_date)', date('m'));
    $builder->where('YEAR(b.booking_date)', date('Y'));
    
    $result = $builder->get()->getRowArray();
    return $result['monthly_revenue'] ?? 0;
}

/**
 * Get average revenue per booking
 */
public function getAverageRevenuePerBooking($businessID)
{
    $builder = $this->db->table('bookings b');
    $builder->select('AVG(b.total_price) AS avg_revenue, COUNT(b.booking_id) as total_bookings');
    $builder->join('tourist_spots ts', 'b.spot_id = ts.spot_id');
    $builder->where('ts.business_id', $businessID);
    $builder->where('b.booking_status', 'Confirmed');
    
    $result = $builder->get()->getRowArray();
    return [
        'average' => $result['avg_revenue'] ?? 0,
        'total_bookings' => $result['total_bookings'] ?? 0
    ];
}

/**
 * Get pending revenue (from pending bookings)
 */
public function getPendingRevenue($businessID)
{
    $builder = $this->db->table('bookings b');
    $builder->select('SUM(b.total_price) AS pending_revenue');
    $builder->join('tourist_spots ts', 'b.spot_id = ts.spot_id');
    $builder->where('ts.business_id', $businessID);
    $builder->where('b.booking_status', 'Pending');
    
    $result = $builder->get()->getRowArray();
    return $result['pending_revenue'] ?? 0;
}

/**
 * Get recent transactions with customer details
 */
public function getRecentTransactionsByBusiness($businessID, $limit = 10)
{
    $builder = $this->db->table('bookings b');
    $builder->select('
        b.booking_id,
        b.total_price,
        b.booking_date,
        b.booking_status,
        b.payment_status,
        CONCAT(u.FirstName, " ", u.LastName) as customer_name
    ');
    $builder->join('tourist_spots ts', 'b.spot_id = ts.spot_id');
    $builder->join('customers c', 'b.customer_id = c.customer_id');
    $builder->join('users u', 'c.user_id = u.UserID');
    $builder->where('ts.business_id', $businessID);
    $builder->orderBy('b.booking_date', 'DESC');
    $builder->limit($limit);
    
    return $builder->get()->getResultArray();
}

/**
 * Get top performing days of the current month
 */
public function getTopPerformingDays($businessID, $limit = 5)
{
    $builder = $this->db->table('bookings b');
    $builder->select('
        DATE(b.booking_date) as booking_day,
        DAYNAME(b.booking_date) as day_name,
        DATE_FORMAT(b.booking_date, "%M %d") as formatted_date,
        SUM(b.total_price) as revenue,
        COUNT(b.booking_id) as bookings
    ');
    $builder->join('tourist_spots ts', 'b.spot_id = ts.spot_id');
    $builder->where('ts.business_id', $businessID);
    $builder->where('b.booking_status', 'Confirmed');
    $builder->where('MONTH(b.booking_date)', date('m'));
    $builder->where('YEAR(b.booking_date)', date('Y'));
    $builder->groupBy('DATE(b.booking_date)');
    $builder->orderBy('revenue', 'DESC');
    $builder->limit($limit);
    
    return $builder->get()->getResultArray();
}

/**
 * Get comparison with previous month
 */
public function getMonthOverMonthComparison($businessID)
{
    // Current month
    $currentBuilder = $this->db->table('bookings b');
    $currentBuilder->select('SUM(b.total_price) AS revenue');
    $currentBuilder->join('tourist_spots ts', 'b.spot_id = ts.spot_id');
    $currentBuilder->where('ts.business_id', $businessID);
    $currentBuilder->where('b.booking_status', 'Confirmed');
    $currentBuilder->where('MONTH(b.booking_date)', date('m'));
    $currentBuilder->where('YEAR(b.booking_date)', date('Y'));
    $current = $currentBuilder->get()->getRowArray();
    
    // Previous month
    $prevMonth = date('m', strtotime('-1 month'));
    $prevYear = date('Y', strtotime('-1 month'));
    
    $prevBuilder = $this->db->table('bookings b');
    $prevBuilder->select('SUM(b.total_price) AS revenue');
    $prevBuilder->join('tourist_spots ts', 'b.spot_id = ts.spot_id');
    $prevBuilder->where('ts.business_id', $businessID);
    $prevBuilder->where('b.booking_status', 'Confirmed');
    $prevBuilder->where('MONTH(b.booking_date)', $prevMonth);
    $prevBuilder->where('YEAR(b.booking_date)', $prevYear);
    $previous = $prevBuilder->get()->getRowArray();
    
    $currentRevenue = $current['revenue'] ?? 0;
    $previousRevenue = $previous['revenue'] ?? 0;
    
    $percentChange = 0;
    if ($previousRevenue > 0) {
        $percentChange = (($currentRevenue - $previousRevenue) / $previousRevenue) * 100;
    }
    
    return [
        'current' => $currentRevenue,
        'previous' => $previousRevenue,
        'change' => $percentChange,
        'direction' => $percentChange >= 0 ? 'up' : 'down'
    ];
}
    

    



}
