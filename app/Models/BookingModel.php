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
        'spot_id', 'customer_id', 'user_id', 'booking_date', 'visit_date', 'visit_time', 'num_adults', 'num_children', 'num_seniors', 'total_guests', 'price_per_person', 'subtotal', 'discount_amount', 'tax_amount', 'total_price', 'booking_status', 'payment_status', 'special_requests', 'cancellation_reason', 'internal_notes', 'created_at', 'updated_at', 'confirmed_at', 'cancelled_at', 'completed_at'
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
            AND b.booking_status IN ('Confirmed', 'Completed', 'Checked-in', 'Checked-out', 'Checked-In', 'Checked-Out')
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
    // Default: only include paid bookings. Caller may use other model methods for different filters.
    $query = $db->query("
        SELECT COALESCE(SUM(COALESCE(NULLIF(b.total_price,0), NULLIF(b.subtotal,0), (b.price_per_person * b.total_guests), 0)), 0) as monthly_revenue
        FROM bookings b
        INNER JOIN tourist_spots ts ON b.spot_id = ts.spot_id
        WHERE ts.business_id = ?
            AND DATE_FORMAT(b.booking_date, '%Y-%m') = ?
            AND b.booking_status IN ('Confirmed', 'Completed', 'Checked-in', 'Checked-out', 'Checked-In', 'Checked-Out')
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
    $query = $db->query(
        "SELECT 
            COUNT(b.booking_id) as total_bookings,
            COALESCE(AVG(b.total_price), 0) as average_revenue
        FROM bookings b
        INNER JOIN tourist_spots ts ON b.spot_id = ts.spot_id
        WHERE ts.business_id = ?
            AND b.booking_status IN ('Confirmed', 'Completed', 'Checked-in', 'Checked-out', 'Checked-In', 'Checked-Out')
            AND b.payment_status = 'Paid'",
        [$businessID]
    );
    
    $result = $query->getRow();
    if ($result) {
        return [
            'average' => (float)$result->average_revenue,
            'total_bookings' => (int)$result->total_bookings
        ];
    }

    return ['average' => 0.0, 'total_bookings' => 0];
}

/**
 * Get pending revenue (unpaid bookings)
 */
public function getPendingRevenue($businessID)
{
    $db = \Config\Database::connect();
    
    $query = $db->query("
        SELECT COALESCE(SUM(COALESCE(NULLIF(b.total_price,0), NULLIF(b.subtotal,0), (b.price_per_person * b.total_guests), 0)), 0) as pending_revenue
        FROM bookings b
        INNER JOIN tourist_spots ts ON b.spot_id = ts.spot_id
        WHERE ts.business_id = ?
            AND b.booking_status = 'Pending'
    ", [$businessID]);
    
    $result = $query->getRow();
    return $result ? (float)$result->pending_revenue : 0;
}

/**
 * Get month-over-month comparison
 */
public function getMonthOverMonthComparison($businessID, $onlyPaid = true)
    {
        $db = \Config\Database::connect();
        
        $currentMonth = date('Y-m');
        $lastMonth = date('Y-m', strtotime('-1 month'));

        // Current month revenue
        $currentQuery = $db->query("
            SELECT COALESCE(SUM(COALESCE(NULLIF(b.total_price,0), NULLIF(b.subtotal,0), (b.price_per_person * b.total_guests), 0)), 0) as revenue
            FROM bookings b
            INNER JOIN tourist_spots ts ON b.spot_id = ts.spot_id
            WHERE ts.business_id = ?
                AND DATE_FORMAT(b.booking_date, '%Y-%m') = ?
                AND b.booking_status IN ('Confirmed', 'Completed', 'Checked-in', 'Checked-out')
                AND (? = 0 OR b.payment_status = 'Paid')
        ", [$businessID, $currentMonth, $onlyPaidInt]);
    
    $currentResult = $currentQuery->getRow();
    $currentRevenue = $currentResult ? (float)$currentResult->revenue : 0;
    
    // Last month revenue
        $lastQuery = $db->query("
            SELECT COALESCE(SUM(COALESCE(NULLIF(b.total_price,0), NULLIF(b.subtotal,0), (b.price_per_person * b.total_guests), 0)), 0) as revenue
            FROM bookings b
            INNER JOIN tourist_spots ts ON b.spot_id = ts.spot_id
            WHERE ts.business_id = ?
                AND DATE_FORMAT(b.booking_date, '%Y-%m') = ?
                AND b.booking_status IN ('Confirmed', 'Completed', 'Checked-in', 'Checked-out')
                AND (? = 0 OR b.payment_status = 'Paid')
        ", [$businessID, $lastMonth, $onlyPaidInt]);
    
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
     * Get totals grouped by spot for a given business. Useful for cards and per-spot summaries.
     * Returns array of ['spot_id', 'spot_name', 'total_revenue']
     */
    public function getTotalsBySpot($businessID, $onlyPaid = true)
    {
        $db = \Config\Database::connect();
        $sql = "
            SELECT ts.spot_id, ts.name AS spot_name,
                COALESCE(SUM(COALESCE(NULLIF(b.total_price,0), NULLIF(b.subtotal,0), (b.price_per_person * b.total_guests), 0)), 0) as total_revenue
            FROM bookings b
            INNER JOIN tourist_spots ts ON b.spot_id = ts.spot_id
            WHERE ts.business_id = ?
                AND b.booking_status IN ('Confirmed', 'Completed', 'Checked-in', 'Checked-out', 'Checked-In', 'Checked-Out')
                AND (? = 0 OR b.payment_status = 'Paid')
            GROUP BY ts.spot_id, ts.name
            ORDER BY total_revenue DESC
        ";

        $onlyPaidInt = $onlyPaid ? 1 : 0;
        $query = $db->query($sql, [$businessID, $onlyPaidInt]);
        return $query->getResultArray();
    }

    /**
     * Get monthly revenue totals for the business (grouped by YYYY-MM). Useful for graphs.
     * Returns array of ['month' => 'YYYY-MM', 'revenue' => float]
     */
    public function getMonthlyRevenueByBusiness($businessID, $limitMonths = 12, $onlyPaid = true)
    {
        $db = \Config\Database::connect();
        // Build SQL with conditional payment filter. Use booking_date for grouping as requested.
        $sql = "
            SELECT DATE_FORMAT(b.booking_date, '%Y-%m') as month,
                COALESCE(SUM(COALESCE(NULLIF(b.total_price,0), NULLIF(b.subtotal,0), (b.price_per_person * b.total_guests), 0)), 0) as revenue
            FROM bookings b
            INNER JOIN tourist_spots ts ON b.spot_id = ts.spot_id
            WHERE ts.business_id = ?
                AND b.booking_status IN ('Confirmed', 'Completed', 'Checked-in', 'Checked-out', 'Checked-In', 'Checked-Out')
                AND (? = 0 OR b.payment_status = 'Paid')
            GROUP BY month
            ORDER BY month DESC
            LIMIT ?
        ";

        $onlyPaidInt = $onlyPaid ? 1 : 0;
        $query = $db->query($sql, [$businessID, $onlyPaidInt, (int)$limitMonths]);
        $rows = $query->getResultArray();

        // Return in chronological order (oldest first)
        return array_reverse($rows);
    }

    /**
     * Backfill bookings where total_price is 0 or NULL using the computed fallback.
     * Returns number of affected rows. This modifies DB and should be run with caution.
     */
    public function backfillMissingTotals()
    {
        $db = \Config\Database::connect();

        $sql = "
            UPDATE bookings
            SET total_price = COALESCE(NULLIF(total_price,0), NULLIF(subtotal,0), (price_per_person * total_guests), 0)
            WHERE total_price = 0 OR total_price IS NULL
        ";

        $db->query($sql);
        return $db->affectedRows();
    }

/**
 * Get recent transactions
 */
public function getRecentTransactionsByBusiness($businessID, $limit = 5)
{
    // Use Query Builder to avoid multiline SQL escaping issues
    $builder = $this->db->table('bookings b');
    $builder->select([
        'b.booking_id',
        'b.booking_date',
        "COALESCE(NULLIF(b.total_price,0), NULLIF(b.subtotal,0), (b.price_per_person * b.total_guests), 0) AS total_price",
        'b.booking_status',
        "CONCAT(COALESCE(u.FirstName, ''), ' ', COALESCE(u.LastName, '')) AS customer_name",
        'u.email as email',
        'c.phone as phone'
    ]);
    $builder->join('tourist_spots ts', 'b.spot_id = ts.spot_id');
    $builder->join('customers c', 'b.customer_id = c.customer_id', 'left');
    $builder->join('users u', 'u.UserID = COALESCE(c.user_id, b.customer_id)', 'left');
    $builder->where('ts.business_id', $businessID);
    $builder->orderBy('b.booking_date', 'DESC');
    $builder->limit((int)$limit);

    return $builder->get()->getResultArray();
}

/**
 * Get top performing days
 */
public function getTopPerformingDays($businessID, $limit = 5)
{
    $db = \Config\Database::connect();
    $currentMonth = date('Y-m');

    // Some DB drivers don't allow binding LIMIT as a parameter.
    // If you run into errors, use intval($limit) and interpolate it into the SQL.
    $sql = "
        SELECT 
            DATE(b.booking_date) AS booking_date,
            DAYNAME(MIN(b.booking_date)) AS day_name,
            DATE_FORMAT(MIN(DATE(b.booking_date)), '%b %d') AS formatted_date,
            COUNT(b.booking_id) AS bookings,
            SUM(COALESCE(NULLIF(b.total_price,0), NULLIF(b.subtotal,0), (b.price_per_person * b.total_guests), 0)) AS revenue
        FROM bookings b
        INNER JOIN tourist_spots ts ON b.spot_id = ts.spot_id
        WHERE ts.business_id = ?
            AND DATE_FORMAT(b.booking_date, '%Y-%m') = ?
            AND b.booking_status IN ('Confirmed', 'Completed', 'Checked-in', 'Checked-out')
            AND b.payment_status = 'Paid'
        GROUP BY DATE(b.booking_date)
        ORDER BY revenue DESC
        LIMIT ?
    ";

    $query = $db->query($sql, [$businessID, $currentMonth, $limit]);

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
   public function getTotalRevenueByBusiness($businessID, $onlyPaid = true)
{
    $builder = $this->db->table('bookings b');
    // Sum using computed fallback: prefer total_price, else subtotal, else price_per_person * total_guests
    $builder->select("COALESCE(SUM(COALESCE(NULLIF(b.total_price,0), NULLIF(b.subtotal,0), (b.price_per_person * b.total_guests), 0)), 0) AS total_revenue", false);
    $builder->join('tourist_spots ts', 'b.spot_id = ts.spot_id');
    $builder->where('ts.business_id', $businessID);
    $builder->whereIn('b.booking_status', ['Confirmed', 'Completed', 'Checked-in', 'Checked-out', 'Checked-In', 'Checked-Out']);
    if ($onlyPaid) {
        $builder->where('b.payment_status', 'Paid');
    }

    $result = $builder->get()->getRowArray();
    return (float) ($result['total_revenue'] ?? 0);
}

    /**
     * Return raw SUM of the `total_price` column for a business (no fallback).
     * Use this when you explicitly want the DB `total_price` values summed.
     */
    public function getRawTotalRevenueByBusiness($businessID)
    {
        $builder = $this->db->table('bookings b');
        $builder->select('COALESCE(SUM(b.total_price), 0) AS total_revenue', false);
        $builder->join('tourist_spots ts', 'b.spot_id = ts.spot_id');
        $builder->where('ts.business_id', $businessID);
        $builder->whereIn('b.booking_status', ['Confirmed', 'Completed', 'Checked-in', 'Checked-out', 'Checked-In', 'Checked-Out']);
        $builder->where('b.payment_status', 'Paid');

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
        // Join customers first (customer_id refers to customers.customer_id)
        // Then join users using customers.user_id when present, otherwise fallback to bookings.customer_id
        $builder->select('b.*, CONCAT(COALESCE(u.FirstName, ""), " ", COALESCE(u.LastName, "")) as customer_name, u.email as email, c.phone as phone');
        $builder->join('tourist_spots ts', 'b.spot_id = ts.spot_id');
        $builder->join('customers c', 'b.customer_id = c.customer_id', 'left');
        $builder->join('users u', 'u.UserID = COALESCE(c.user_id, b.customer_id)', 'left');
        $builder->where('ts.business_id', $businessID);
        // No GROUP BY needed — booking_id is unique, b.* is safe without grouping
        return $builder->get()->getResultArray();
    }

    //will get the name of the customer total_guestfrom users table by joining bookings.customer_id = users.user_id and get the booking by spot id email and phone number 
    public function getBookingDetails($bookingID)
{
    $builder = $this->db->table('bookings b');
        $builder->select('b.*, CONCAT(COALESCE(u.FirstName, ""), " ", COALESCE(u.LastName, "")) as customer_name, u.email as email, c.phone as phone');
        $builder->join('customers c', 'b.customer_id = c.customer_id', 'left');
        $builder->join('users u', 'u.UserID = COALESCE(c.user_id, b.customer_id)', 'left');
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
            SUM(COALESCE(NULLIF(b.total_price,0), NULLIF(b.subtotal,0), (b.price_per_person * b.total_guests), 0)) as revenue,
        COUNT(b.booking_id) as bookings
    ');
    $builder->join('tourist_spots ts', 'b.spot_id = ts.spot_id');
    $builder->where('ts.business_id', $businessID);
    $builder->whereIn('b.booking_status', ['Confirmed', 'Completed', 'Checked-in', 'Checked-out', 'Checked-In', 'Checked-Out']);
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
public function getTopPerformingDayss($businessID, $limit = 5)
{
    $builder = $this->db->table('bookings b');
    $builder->select('
        DATE(b.booking_date) as booking_day,
        DAYNAME(b.booking_date) as day_name,
        DATE_FORMAT(b.booking_date, "%M %d") as formatted_date,
        SUM(COALESCE(NULLIF(b.total_price,0), NULLIF(b.subtotal,0), (b.price_per_person * b.total_guests), 0)) as revenue,
        COUNT(b.booking_id) as bookings
    ');
    $builder->join('tourist_spots ts', 'b.spot_id = ts.spot_id');
    $builder->where('ts.business_id', $businessID);
    $builder->whereIn('b.booking_status', ['Confirmed', 'Completed', 'Checked-in', 'Checked-out', 'Checked-In', 'Checked-Out']);
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


        
    
    
    public function getTopPerformingSpots($startDate, $endDate, $limit = 5)
    {
        $builder = $this->builder();
        $builder->select('ts.spot_name, ts.category, COUNT(b.booking_id) as total_bookings, SUM(COALESCE(NULLIF(b.total_price,0), NULLIF(b.subtotal,0), (b.price_per_person * b.total_guests), 0)) as total_revenue');
        $builder->from('bookings b');
        $builder->join('tourist_spots ts', 'b.spot_id = ts.spot_id');
        $builder->where('b.booking_date >=', $startDate);
        $builder->where('b.booking_date <=', $endDate);
        $builder->whereIn('b.booking_status', ['Confirmed', 'Completed', 'Checked-in', 'Checked-out', 'Checked-In', 'Checked-Out']);
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
        // Compute average lead time (in days) between booking_date and visit_date for bookings
        // within the provided date range. Returns an array with the average days.
        $sql = "
            SELECT AVG(DATEDIFF(b.visit_date, b.booking_date)) AS avg_lead_time_days
            FROM bookings b
            WHERE DATE(b.booking_date) >= ?
              AND DATE(b.booking_date) <= ?
              AND b.visit_date IS NOT NULL
        ";

        $result = $this->db->query($sql, [$startDate, $endDate])->getRowArray();
        return [
            'average_lead_time_days' => isset($result['avg_lead_time_days']) ? (float)$result['avg_lead_time_days'] : 0
        ];
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
        $result = $this->select('SUM(COALESCE(NULLIF(total_price,0), NULLIF(subtotal,0), (price_per_person * total_guests), 0)) as total_revenue, COUNT(booking_id) as total_bookings')
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
            ->select('ts.category, SUM(COALESCE(NULLIF(b.total_price,0), NULLIF(b.subtotal,0), (b.price_per_person * b.total_guests), 0)) as total_revenue')
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
            'ts.spot_id',
            'ts.spot_name',
            'ts.location',
            'ts.primary_image',
            'rf.review_id'
        ]);

        $builder->join('tourist_spots ts', 'b.spot_id = ts.spot_id', 'left');
        $builder->join('review_feedback rf', 'b.booking_id = rf.booking_id', 'left');

        $builder->where('b.customer_id', $userId);
        // Include common finalized/visited statuses so users' past visits show up
        $builder->whereIn('b.booking_status', ['Checked-in', 'Checked-out', 'Checked-In', 'Checked-Out', 'Confirmed', 'Completed']);

        // Order by visit_date (or booking_date if visit_date is null) desc, then visit_time desc no order by
        $builder->orderBy('COALESCE(b.visit_date, b.booking_date) DESC, b.visit_time DESC');

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



