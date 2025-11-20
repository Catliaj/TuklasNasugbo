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
        return $this->selectSum('total_price', 'totalRevenue')
                    ->where('booking_status', 'Confirmed')
                    ->where('MONTH(booking_date)', date('m'))
                    ->where('YEAR(booking_date)', date('Y'))
                    ->get()->getRow()->totalRevenue ?? 0;
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
    //  REPORTS PAGE ANALYTICS (The Missing Part)
    // ==========================================================
    
    // Used for Reports Page (Specific Date Range)
    public function getPeakDays($startDate, $endDate)
    {
        $booking_sql = "SELECT DAYNAME(booking_date) as day, COUNT(booking_id) as total FROM bookings WHERE DATE(booking_date) BETWEEN ? AND ? GROUP BY day ORDER BY FIELD(day, 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday')";
        $visit_sql = "SELECT DAYNAME(visit_date) as day, COUNT(booking_id) as total FROM bookings WHERE DATE(visit_date) BETWEEN ? AND ? AND booking_status = 'Confirmed' GROUP BY day ORDER BY FIELD(day, 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday')";

        return [
            'peak_booking_days' => $this->db->query($booking_sql, [$startDate, $endDate])->getResultArray(),
            'peak_visit_days' => $this->db->query($visit_sql, [$startDate, $endDate])->getResultArray()
        ];
    }

    public function getRepeatVisitorTrends($startDate, $endDate)
    {
        $subquery = $this->db->table('bookings')
             ->select('customer_id, COUNT(booking_id) as booking_count')
             ->where('booking_status', 'Confirmed')
             ->where('DATE(booking_date) >=', $startDate)
             ->where('DATE(booking_date) <=', $endDate)
             ->groupBy('customer_id')
             ->getCompiledSelect();

        $query = $this->db->query("SELECT 
            COUNT(CASE WHEN booking_count = 1 THEN 1 END) as first_time,
            COUNT(CASE WHEN booking_count > 1 THEN 1 END) as returning_visitors
            FROM ($subquery) as user_counts");

<<<<<<< Updated upstream
    // ==========================================================
    //  NEW ANALYTICS METHODS
    // ==========================================================
    public function getTotalRevenue($startDate = null, $endDate = null)
    {
        $builder = $this->builder();
        $builder->selectSum('total_price', 'totalRevenue');
        $builder->where('booking_status', 'Confirmed');

        if ($startDate && $endDate) {
            $builder->where('booking_date >=', $startDate);
            $builder->where('booking_date <=', $endDate);
        }

        $result = $builder->get()->getRowArray();
        return $result['totalRevenue'] ?? 0;
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
=======
        $result = $query->getRowArray();

        return [
            'first_time' => (int)($result['first_time'] ?? 0),
            'returning'  => (int)($result['returning_visitors'] ?? 0)
        ];
>>>>>>> Stashed changes
    }

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

<<<<<<< Updated upstream
    public function getPeakDays($startDate, $endDate)
    {
        $booking_sql = "SELECT DAYNAME(booking_date) as day, COUNT(booking_id) as total FROM bookings WHERE booking_date BETWEEN ? AND ? GROUP BY day ORDER BY FIELD(day, 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday')";
        $visit_sql = "SELECT DAYNAME(visit_date) as day, COUNT(booking_id) as total FROM bookings WHERE visit_date BETWEEN ? AND ? AND booking_status = 'Confirmed' GROUP BY day ORDER BY FIELD(day, 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday')";

        return [
            'peak_booking_days' => $this->db->query($booking_sql, [$startDate, $endDate])->getResultArray(),
            'peak_visit_days' => $this->db->query($visit_sql, [$startDate, $endDate])->getResultArray()
        ];
    }
    
    public function getAverageRevenuePerBooking($startDate, $endDate)
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

=======
>>>>>>> Stashed changes
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