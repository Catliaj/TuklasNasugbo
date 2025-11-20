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
        'spot_id', 'customer_id', 'booking_date', 'visit_date', 'visit_time', 'num_adults', 'num_children', 'num_seniors', 'total_guests', 'price_per_person', 'subtotal', 'discount_amount', 'tax_amount', 'total_price', 'booking_status', 'payment_status', 'special_requests', 'cancellation_reason', 'internal_notes', 'created_at', 'updated_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // ==========================================================
    //  DASHBOARD KPI METHODS
    // ==========================================================
    public function getTotalBookingsThisMonth()
    {
        return $this->where('MONTH(booking_date)', date('m'))
                    ->where('YEAR(booking_date)', date('Y'))
                    ->countAllResults();
    }

    public function getTotalBookingsToday()
    {
        return $this->where('DATE(booking_date)', date('Y-m-d'))
                    ->countAllResults();
    }

    public function getRevenueThisMonth()
    {
        $row = $this->selectSum('total_price', 'totalRevenue')
                    ->where('booking_status', 'Confirmed')
                    ->where('MONTH(booking_date)', date('m'))
                    ->where('YEAR(booking_date)', date('Y'))
                    ->get()->getRowArray();

        return (float) ($row['totalRevenue'] ?? 0);
    }

    // ==========================================================
    //  DASHBOARD CHARTS
    // ==========================================================
    public function getRevenueAndBookingsTrend()
    {
        return $this->select("DATE_FORMAT(booking_date, '%M') as month, COUNT(booking_id) as total_bookings, SUM(total_price) as total_revenue")
                    ->where('YEAR(booking_date)', date('Y'))
                    ->where('booking_status', 'Confirmed')
                    ->groupBy('month')
                    ->orderBy('booking_date', 'ASC')
                    ->findAll();
    }

    // Used for Dashboard Bar Chart (Last 7 Days)
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

    // ==========================================================
    //  REPORTS PAGE ANALYTICS
    // ==========================================================

    /**
     * Get peak booking days and peak visit days within a date range.
     *
     * @param string $startDate YYYY-MM-DD
     * @param string $endDate   YYYY-MM-DD
     * @return array
     */
    public function getPeakDays($startDate, $endDate)
    {
        $booking_sql = "SELECT DAYNAME(booking_date) as day, COUNT(booking_id) as total
                        FROM bookings
                        WHERE DATE(booking_date) BETWEEN ? AND ?
                        GROUP BY day
                        ORDER BY FIELD(day, 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday')";

        $visit_sql = "SELECT DAYNAME(visit_date) as day, COUNT(booking_id) as total
                      FROM bookings
                      WHERE DATE(visit_date) BETWEEN ? AND ?
                        AND booking_status = 'Confirmed'
                      GROUP BY day
                      ORDER BY FIELD(day, 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday')";

        return [
            'peak_booking_days' => $this->db->query($booking_sql, [$startDate, $endDate])->getResultArray(),
            'peak_visit_days'   => $this->db->query($visit_sql, [$startDate, $endDate])->getResultArray()
        ];
    }

    /**
     * Get repeat visitor trends within a date range: counts of first-time vs returning customers.
     *
     * @param string $startDate YYYY-MM-DD
     * @param string $endDate   YYYY-MM-DD
     * @return array ['first_time' => int, 'returning' => int]
     */
    public function getRepeatVisitorTrends($startDate, $endDate)
    {
        // Subquery: per-customer booking counts within the period
        $subquery = $this->db->table('bookings')
             ->select('customer_id, COUNT(booking_id) as booking_count')
             ->where('booking_status', 'Confirmed')
             ->where('DATE(booking_date) >=', $startDate)
             ->where('DATE(booking_date) <=', $endDate)
             ->groupBy('customer_id')
             ->getCompiledSelect();

        $sql = "SELECT 
                    SUM(CASE WHEN booking_count = 1 THEN 1 ELSE 0 END) as first_time,
                    SUM(CASE WHEN booking_count > 1 THEN 1 ELSE 0 END) as returning_visitors
                FROM ({$subquery}) as user_counts";

        $result = $this->db->query($sql)->getRowArray();

        return [
            'first_time' => (int)($result['first_time'] ?? 0),
            'returning'  => (int)($result['returning_visitors'] ?? 0)
        ];
    }

    /**
     * Visitor demographics totals for a date range.
     */
    public function getVisitorDemographics($startDate, $endDate)
    {
        $result = $this->selectSum('num_adults', 'total_adults')
                    ->selectSum('num_children', 'total_children')
                    ->selectSum('num_seniors', 'total_seniors')
                    ->where('booking_status', 'Confirmed')
                    ->where('DATE(visit_date) >=', $startDate)
                    ->where('DATE(visit_date) <=', $endDate)
                    ->get()->getRowArray();
        
        return $result ?: ['total_adults' => 0, 'total_children' => 0, 'total_seniors' => 0];
    }

    /**
     * Booking lead time distribution.
     */
    public function getBookingLeadTime($startDate, $endDate)
    {
        $sql = "
            SELECT 
                CASE
                    WHEN DATEDIFF(CAST(visit_date AS DATE), CAST(booking_date AS DATE)) = 0 THEN 'Same Day'
                    WHEN DATEDIFF(CAST(visit_date AS DATE), CAST(booking_date AS DATE)) BETWEEN 1 AND 7 THEN '1-7 Days'
                    WHEN DATEDIFF(CAST(visit_date AS DATE), CAST(booking_date AS DATE)) BETWEEN 8 AND 30 THEN '8-30 Days'
                    ELSE '30+ Days'
                END as lead_time_group,
                COUNT(booking_id) as total
            FROM bookings
            WHERE CAST(booking_date AS DATE) BETWEEN ? AND ?
            GROUP BY lead_time_group
            ORDER BY FIELD(lead_time_group, 'Same Day', '1-7 Days', '8-30 Days', '30+ Days')
        ";
        return $this->db->query($sql, [$startDate, $endDate])->getResultArray();
    }

    // ==========================================================
    //  BUSINESS-FOCUSED ANALYTICS (per-business helpers)
    // ==========================================================

    /**
     * Get monthly revenue for the last N months by business.
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
     * Get weekly revenue for the last N weeks by business.
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
     * Get booking trends by status for the last N months.
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
     * Get all-time total revenue for a business.
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
     * Get revenue for current month.
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
     * Get average revenue per booking.
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
     * Get pending revenue (from pending bookings).
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
     * Get recent transactions with customer details.
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
     * Get top performing days of the current month.
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
     * Month-over-month revenue comparison for a business.
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

    /**
     * Top performing spots between date range.
     */
    public function getTopPerformingSpots($startDate, $endDate, $limit = 5)
    {
        $builder = $this->db->table('bookings b');
        $builder->select('ts.spot_name, ts.category, COUNT(b.booking_id) as total_bookings, SUM(b.total_price) as total_revenue');
        $builder->join('tourist_spots ts', 'b.spot_id = ts.spot_id');
        $builder->where('b.booking_date >=', $startDate);
        $builder->where('b.booking_date <=', $endDate);
        $builder->where('b.booking_status', 'Confirmed');
        $builder->groupBy('ts.spot_id, ts.spot_name, ts.category');
        $builder->orderBy('total_revenue', 'DESC');
        $builder->limit($limit);

        return $builder->get()->getResultArray();
    }

    /**
     * Average revenue per bookings in a date range.
     */
    public function getAverageRevenuePerBookings($startDate, $endDate)
    {
        $result = $this->select('SUM(total_price) as total_revenue, COUNT(booking_id) as total_bookings')
                       ->where('booking_status', 'Confirmed')
                       ->where('DATE(booking_date) >=', $startDate)
                       ->where('DATE(booking_date) <=', $endDate)
                       ->get()->getRowArray();

        if (empty($result) || $result['total_bookings'] == 0) {
            return 0;
        }
        return (float)$result['total_revenue'] / (int)$result['total_bookings'];
    }

    /**
     * Revenue by spot category within date range.
     */
    public function getRevenueByCategory($startDate, $endDate)
    {
        return $this->select('ts.category, SUM(b.total_price) as total_revenue')
                    ->from('bookings b')
                    ->join('tourist_spots ts', 'b.spot_id = ts.spot_id', 'left')
                    ->where('b.booking_status', 'Confirmed')
                    ->where('DATE(b.booking_date) >=', $startDate)
                    ->where('DATE(b.booking_date) <=', $endDate)
                    ->groupBy('ts.category')
                    ->orderBy('total_revenue', 'DESC')
                    ->get()->getResultArray();
    }
    
    /**
     * Top spots performance metrics (combined).
     */
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
            'ts.spot_name',
            'COUNT(DISTINCT b.booking_id) as total_bookings',
            'SUM(b.total_price) as total_revenue',
            'SUM(b.total_guests) as total_visitors',
            '(SELECT AVG(rating) FROM review_feedback WHERE spot_id = ts.spot_id AND DATE(created_at) >= \''.$startDate.'\' AND DATE(created_at) <= \''.$endDate.'\') as avg_rating'
        ]);
        $builder->join("({$topSpotsQuery}) top", 'ts.spot_id = top.spot_id');
        $builder->join('bookings b', 'ts.spot_id = b.spot_id', 'left');
        $builder->where('b.booking_status', 'Confirmed');
        $builder->where('DATE(b.booking_date) >=', $startDate);
        $builder->where('DATE(b.booking_date) <=', $endDate);
        $builder->groupBy('ts.spot_id, ts.spot_name');
        $builder->orderBy('total_revenue', 'DESC');
        
        return $builder->get()->getResultArray();
    }
}