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
            ->where('YEAR(booking_date)', date('Y'))
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
        $builder->whereIn('b.booking_status', ['Confirmed', 'Checked-in', 'Checked-out']);
        $result = $builder->get()->getRowArray();
        return $result['total_bookings'] ?? 0;
    }

    //select sum(b.total_price) from bookings inner join tourist spots on bookings.spot_id = tourist_spots.spot_id where business_id = ? where booking is confirmed
   public function getTotalRevenueByBusiness($businessID)
{
    $builder = $this->db->table('bookings b');
    $builder->select('SUM(b.total_price) AS total_revenue');
    $builder->join('tourist_spots ts', 'b.spot_id = ts.spot_id');
    $builder->where('ts.business_id', $businessID);
    $builder->whereIn('b.booking_status', ['Confirmed', 'Checked-in', 'Checked-out']);

    $result = $builder->get()->getRowArray();
    return (float) ($result['total_revenue'] ?? 0);
}
     public function getPeakVisitTimes()
    {
        $defaults = ['Monday'=>0,'Tuesday'=>0,'Wednesday'=>0,'Thursday'=>0,'Friday'=>0,'Saturday'=>0,'Sunday'=>0];
        $results = $this->select("DAYNAME(visit_date) as day, COUNT(*) as total_visits")
                    ->where('booking_status', 'Confirmed')
                    ->groupBy("DAYNAME(visit_date)")
                    ->findAll();
        
        foreach ($results as $row) {
            if (isset($defaults[$row['day']])) {
                $defaults[$row['day']] = (int)$row['total_visits'];
            }
        }
        
        $finalData = [];
        foreach ($defaults as $day => $count) {
            $finalData[] = ['day' => $day, 'total_visits' => $count];
        }
        return $finalData;
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
    $builder = $this->db->table('bookings b');
    $builder->select('b.*, CONCAT(u.FirstName, " ", u.LastName) as customer_name, u.email, c.phone as phone');
    $builder->join('tourist_spots ts', 'b.spot_id = ts.spot_id');
    $builder->join('users u', 'b.customer_id = u.UserID', 'left');
    $builder->join('customers c', 'b.customer_id = c.customer_id', 'left');
    $builder->where('ts.business_id', $businessID);

    // No GROUP BY needed — booking_id is unique, b.* is safe without grouping
    return $builder->get()->getResultArray();
}

    //will get the name of the customer total_guestfrom users table by joining bookings.customer_id = users.user_id and get the booking by spot id email and phone number 
    public function getBookingDetails($bookingID)
{
    $builder = $this->db->table('bookings b');
    $builder->select('b.*, CONCAT(u.FirstName, " ", u.LastName) as customer_name, u.email as email, c.phone as phone');
    $builder->join('users u', 'b.customer_id = u.UserID', 'left');
    $builder->join('customers c', 'b.customer_id = c.customer_id', 'left');
    $builder->where('b.booking_id', $bookingID);

    return $builder->get()->getRowArray();
}

    //get total visitors where booking is confirmed
    public function getTotalVisitor($businessID)
    {
        $builder = $this->db->table('bookings b');
        $builder->selectSum('b.total_guests', 'total');
        $builder->join('tourist_spots ts', 'b.spot_id = ts.spot_id');
       $builder->where('ts.business_id', $businessID)
        ->whereIn('b.booking_status', ['Confirmed', 'Checked-in', 'Checked-out']);
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
        MONTHNAME(MIN(b.booking_date)) as month_name,
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

    // FIX: include all non-aggregated SELECT expressions in the GROUP BY
    $builder->groupBy([
        'DATE(b.booking_date)',
        'DAYNAME(b.booking_date)',
        'DATE_FORMAT(b.booking_date, "%M %d")'
    ]);

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
    

        
    
    
    public function getTopPerformingSpots($startDate, $endDate, $limit = 5)
    {
        $builder = $this->builder();
        $builder->select('ts.spot_name, ts.category, COUNT(b.booking_id) as total_bookings, SUM(b.total_price) as total_revenue');
        $builder->from('bookings b');
        $builder->join('tourist_spots ts', 'b.spot_id = ts.spot_id');
        $builder->where('b.booking_date >=', $startDate);
        $builder->where('b.booking_date <=', $endDate);
        $builder->where('b.booking_status', 'Confirmed');
        $builder->groupBy('ts.spot_id, ts.spot_name, ts.category');
        $builder->orderBy('total_revenue', 'DESC');
        $builder->limit($limit);

        return $builder->get()->getResultArray();
    }

    public function getVisitorDemographics($startDate, $endDate)
    {
        $result = $this->db->table('bookings')
            ->selectSum('num_adults', 'total_adults')
            ->selectSum('num_children', 'total_children')
            ->selectSum('num_seniors', 'total_seniors')
            ->where('booking_status', 'Confirmed')
            ->where('DATE(booking_date) >=', $startDate)
            ->where('DATE(booking_date) <=', $endDate)
            ->get()->getRowArray();

        // Return 0s if result is empty
        return [
            'total_adults'   => (int)($result['total_adults'] ?? 0),
            'total_children' => (int)($result['total_children'] ?? 0),
            'total_seniors'  => (int)($result['total_seniors'] ?? 0)
        ];
    }

    public function getBookingLeadTime($startDate, $endDate)
    {
        $sql = "
            SELECT 
                CASE
                    WHEN DATEDIFF(visit_date, booking_date) = 0 THEN 'Same Day'
                    WHEN DATEDIFF(visit_date, booking_date) BETWEEN 1 AND 7 THEN '1-7 Days'
                    WHEN DATEDIFF(visit_date, booking_date) BETWEEN 8 AND 30 THEN '8-30 Days'
                    ELSE '30+ Days'
                END as lead_time_group,
                COUNT(booking_id) as total
            FROM bookings
            WHERE booking_date BETWEEN ? AND ?
            GROUP BY lead_time_group
            ORDER BY FIELD(lead_time_group, 'Same Day', '1-7 Days', '8-30 Days', '30+ Days')
        ";
        return $this->db->query($sql, [$startDate, $endDate])->getResultArray();
    }

    public function getPeakDays($startDate, $endDate)
    {
        $booking_sql = "SELECT DAYNAME(booking_date) as day, COUNT(booking_id) as total FROM bookings WHERE booking_date BETWEEN ? AND ? GROUP BY day ORDER BY FIELD(day, 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday')";
        $visit_sql = "SELECT DAYNAME(visit_date) as day, COUNT(booking_id) as total FROM bookings WHERE visit_date BETWEEN ? AND ? AND booking_status = 'Confirmed' GROUP BY day ORDER BY FIELD(day, 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday')";

        return [
            'peak_booking_days' => $this->db->query($booking_sql, [$startDate, $endDate])->getResultArray(),
            'peak_visit_days' => $this->db->query($visit_sql, [$startDate, $endDate])->getResultArray()
        ];
    }
    
    public function getAverageRevenuePerBookings($startDate, $endDate)
    {
        $result = $this->select('SUM(total_price) as total_revenue, COUNT(booking_id) as total_bookings')
                       ->where('booking_status', 'Confirmed')
                       ->where('booking_date >=', $startDate)
                       ->where('booking_date <=', $endDate)
                       ->get()->getRowArray();

        if (empty($result) || $result['total_bookings'] == 0) {
            return 0;
        }
        return $result['total_revenue'] / $result['total_bookings'];
    }

    public function getRevenueByCategory($startDate, $endDate)
    {
        return $this->db->table('bookings b')
            ->select('ts.category, SUM(b.total_price) as total_revenue')
            ->join('tourist_spots ts', 'b.spot_id = ts.spot_id')
            ->where('b.booking_status', 'Confirmed')
            ->where('DATE(b.booking_date) >=', $startDate)
            ->where('DATE(b.booking_date) <=', $endDate)
            ->groupBy('ts.category')
            ->orderBy('total_revenue', 'DESC')
            ->get()->getResultArray();
    }


    // Add this method inside the BookingModel class (app/Models/BookingModel.php)
public function getTopSpotsPerformanceMetrics($startDate, $endDate, $limit = 3)
{
    $topSpotsQuery = $this->db->table('bookings')
        ->select('spot_id, SUM(total_price) as revenue')
        ->where('booking_status', 'Confirmed')
        ->where('DATE(booking_date) >=', $startDate)
        ->where('DATE(booking_date) <=', $endDate)
        ->groupBy('spot_id')
        ->orderBy('revenue', 'DESC')
        ->limit($limit)
        ->getCompiledSelect();

    $builder = $this->db->table('tourist_spots ts');
    $builder->select([
        'ts.spot_id',
        'ts.spot_name',
        'COUNT(DISTINCT b.booking_id) as total_bookings',
        'SUM(b.total_price) as total_revenue',
        'SUM(b.total_guests) as total_visitors',
        '(SELECT AVG(rating) FROM review_feedback WHERE spot_id = ts.spot_id AND DATE(created_at) >= \'' . $startDate . '\' AND DATE(created_at) <= \'' . $endDate . '\') as avg_rating'
    ]);
    $builder->join("({$topSpotsQuery}) top", 'ts.spot_id = top.spot_id');
    $builder->join('bookings b', 'ts.spot_id = b.spot_id', 'left');
    $builder->where('b.booking_status', 'Confirmed');
    $builder->where('DATE(b.booking_date) >=', $startDate);
    $builder->where('DATE(b.booking_date) <=', $endDate);
    $builder->groupBy('ts.spot_id, ts.spot_name');
    $builder->orderBy('total_revenue', 'DESC');
    $builder->limit($limit);

    return $builder->get()->getResultArray();
}

     public function getVisitedPlacesByUser(int $userId)
    {
        $builder = $this->db->table('bookings b');

        // Select only the fields you asked for
        $builder->select([
            'b.booking_id',
            'b.booking_date',
            'b.visit_date',
            'b.visit_time',
            'b.total_guests',
            'b.total_price',
            'ts.spot_name',
            'ts.location',
            'ts.primary_image'
        ]);

        $builder->join('tourist_spots ts', 'b.spot_id = ts.spot_id', 'left');

        $builder->where('b.customer_id', $userId);
        // Include common finalized/visited statuses so users' past visits show up
        $builder->whereIn('b.booking_status', ['Checked-in', 'Checked-out', 'Checked-In', 'Checked-Out', 'Confirmed', 'Completed']);

        // Order by visit_date (or booking_date if visit_date is null) desc, then visit_time desc no order by
        $builder->orderBy('COALESCE(b.visit_date, b.booking_date) DESC, b.visit_time DESC');

        return $builder->get()->getResultArray();
    }
}