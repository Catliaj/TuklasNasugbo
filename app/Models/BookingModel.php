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


    /**
 * Get total revenue all time for a business
 */
public function getTotalRevenueAllTime($businessID)
{
    $db = \Config\Database::connect();
    
    $query = $db->query("
        SELECT COALESCE(SUM(b.total_price), 0) as total_revenue
        FROM bookings b
        INNER JOIN tourist_spots ts ON b.spot_id = ts.spot_id
        WHERE ts.business_id = ?
            AND b.booking_status IN ('Confirmed', 'Completed', 'Checked-in', 'Checked-out')
            AND b.payment_status = 'Paid'
    ", [$businessID]);
    
    $result = $query->getRow();
    return $result ? (float)$result->total_revenue : 0;
}

/**
 * Get monthly revenue for current month
 */
public function getMonthlyRevenue($businessID)
{
    $db = \Config\Database::connect();
    $currentMonth = date('Y-m');
    
    $query = $db->query("
        SELECT COALESCE(SUM(b.total_price), 0) as monthly_revenue
        FROM bookings b
        INNER JOIN tourist_spots ts ON b.spot_id = ts.spot_id
        WHERE ts.business_id = ?
            AND DATE_FORMAT(b.booking_date, '%Y-%m') = ?
            AND b.booking_status IN ('Confirmed', 'Completed', 'Checked-in', 'Checked-out')
            AND b.payment_status = 'Paid'
    ", [$businessID, $currentMonth]);
    
    $result = $query->getRow();
    return $result ? (float)$result->monthly_revenue : 0;
}

/**
 * Get average revenue per booking
 */
public function getAverageRevenuePerBooking($businessID)
{
    $db = \Config\Database::connect();
    
    $query = $db->query("
        SELECT 
            COUNT(b.booking_id) as total_bookings,
            COALESCE(AVG(b.total_price), 0) as average_revenue
        FROM bookings b
        INNER JOIN tourist_spots ts ON b.spot_id = ts.spot_id
        WHERE ts.business_id = ?
            AND b.booking_status IN ('Confirmed', 'Completed', 'Checked-in', 'Checked-out')
            AND b.payment_status = 'Paid'
    ", [$businessID]);
    
    $result = $query->getRow();
    return [
        'total_bookings' => $result ? (int)$result->total_bookings : 0,
        'average' => $result ? (float)$result->average_revenue : 0
    ];
}

/**
 * Get pending revenue (unpaid bookings)
 */
public function getPendingRevenue($businessID)
{
    $db = \Config\Database::connect();
    
    $query = $db->query("
        SELECT COALESCE(SUM(b.total_price), 0) as pending_revenue
        FROM bookings b
        INNER JOIN tourist_spots ts ON b.spot_id = ts.spot_id
        WHERE ts.business_id = ?
            AND b.booking_status IN ('Pending', 'Confirmed')
            AND b.payment_status = 'Unpaid'
    ", [$businessID]);
    
    $result = $query->getRow();
    return $result ? (float)$result->pending_revenue : 0;
}

/**
 * Get month-over-month comparison
 */
public function getMonthOverMonthComparison($businessID)
{
    $db = \Config\Database::connect();
    
    $currentMonth = date('Y-m');
    $lastMonth = date('Y-m', strtotime('-1 month'));
    
    // Current month revenue
    $currentQuery = $db->query("
        SELECT COALESCE(SUM(b.total_price), 0) as revenue
        FROM bookings b
        INNER JOIN tourist_spots ts ON b.spot_id = ts.spot_id
        WHERE ts.business_id = ?
            AND DATE_FORMAT(b.booking_date, '%Y-%m') = ?
            AND b.booking_status IN ('Confirmed', 'Completed', 'Checked-in', 'Checked-out')
            AND b.payment_status = 'Paid'
    ", [$businessID, $currentMonth]);
    
    $currentResult = $currentQuery->getRow();
    $currentRevenue = $currentResult ? (float)$currentResult->revenue : 0;
    
    // Last month revenue
    $lastQuery = $db->query("
        SELECT COALESCE(SUM(b.total_price), 0) as revenue
        FROM bookings b
        INNER JOIN tourist_spots ts ON b.spot_id = ts.spot_id
        WHERE ts.business_id = ?
            AND DATE_FORMAT(b.booking_date, '%Y-%m') = ?
            AND b.booking_status IN ('Confirmed', 'Completed', 'Checked-in', 'Checked-out')
            AND b.payment_status = 'Paid'
    ", [$businessID, $lastMonth]);
    
    $lastResult = $lastQuery->getRow();
    $lastRevenue = $lastResult ? (float)$lastResult->revenue : 0;
    
    // Calculate percentage change
    $change = 0;
    $direction = 'same';
    
    if ($lastRevenue > 0) {
        $change = (($currentRevenue - $lastRevenue) / $lastRevenue) * 100;
        $direction = $change > 0 ? 'up' : ($change < 0 ? 'down' : 'same');
    } elseif ($currentRevenue > 0) {
        $change = 100;
        $direction = 'up';
    }
    
    return [
        'current' => $currentRevenue,
        'previous' => $lastRevenue,
        'change' => round($change, 1),
        'direction' => $direction
    ];
}

/**
 * Get recent transactions
 */
public function getRecentTransactionsByBusiness($businessID, $limit = 5)
{
    $db = \Config\Database::connect();
    
    $query = $db->query("
        SELECT 
            b.booking_id,
            b.booking_date,
            b.total_price,
            b.booking_status,
            CONCAT(u.FirstName, ' ', u.LastName) as customer_name
        FROM bookings b
        INNER JOIN tourist_spots ts ON b.spot_id = ts.spot_id
        INNER JOIN customers c ON b.customer_id = c.customer_id
        INNER JOIN users u ON c.user_id = u.UserID
        WHERE ts.business_id = ?
        ORDER BY b.booking_date DESC
        LIMIT ?
    ", [$businessID, $limit]);
    
    return $query->getResultArray();
}

/**
 * Get top performing days
 */
public function getTopPerformingDays($businessID, $limit = 5)
{
    $db = \Config\Database::connect();
    $currentMonth = date('Y-m');
    
    $query = $db->query("
        SELECT 
            DATE(b.booking_date) as booking_date,
            DAYNAME(b.booking_date) as day_name,
            DATE_FORMAT(b.booking_date, '%b %d') as formatted_date,
            COUNT(b.booking_id) as bookings,
            SUM(b.total_price) as revenue
        FROM bookings b
        INNER JOIN tourist_spots ts ON b.spot_id = ts.spot_id
        WHERE ts.business_id = ?
            AND DATE_FORMAT(b.booking_date, '%Y-%m') = ?
            AND b.booking_status IN ('Confirmed', 'Completed', 'Checked-in', 'Checked-out')
            AND b.payment_status = 'Paid'
        GROUP BY DATE(b.booking_date)
        ORDER BY revenue DESC
        LIMIT ?
    ", [$businessID, $currentMonth, $limit]);
    
    return $query->getResultArray();
}
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
        $builder = $this->builder(); 
        $builder->select('SUM(DISTINCT b.total_price) AS total_revenue');
        $builder->from('bookings b');
        $builder->join('tourist_spots ts', 'b.spot_id = ts.spot_id');
        $builder->where('ts.business_id', $businessID);
        $builder->whereIn('b.booking_status', ['Confirmed', 'Checked-in', 'Checked-out']);
        $result = $builder->get()->getRowArray();
        return $result['total_revenue'] ?? 0;
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

/**
 * Get revenue for current month
 */


/**
 * Get average revenue per booking
 */


/**
 * Get pending revenue (from pending bookings)
 */


/**
 * Get recent transactions with customer details
 */

/**
 * Get top performing days of the current month
 */


/**
 * Get comparison with previous month
 */


        
    
    
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


public function insert($data = null, $returnID = true)
{
    $result = parent::insert($data, $returnID);
    
    if ($result) {
        // Create notification for admins
        $notificationModel = new \App\Models\NotificationModel();
        $notificationModel->notifyAdmins(
            'booking',
            'New Booking Received',
            'A new booking has been created. Booking ID: ' . $result,
            '/admin/bookings'
        );
    }
    
    return $result;
}
}