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
    }

    public function getVisitorDemographics($startDate, $endDate)
    {
        return $this->selectSum('num_adults', 'total_adults')
                    ->selectSum('num_children', 'total_children')
                    ->selectSum('num_seniors', 'total_seniors')
                    ->where('booking_status', 'Confirmed')
                    ->where('visit_date >=', $startDate)
                    ->where('visit_date <=', $endDate)
                    ->get()->getRowArray();
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

    public function getRevenueByCategory($startDate, $endDate)
    {
        return $this->select('ts.category, SUM(b.total_price) as total_revenue')
                    ->from('bookings b')
                    ->join('tourist_spots ts', 'b.spot_id = ts.spot_id')
                    ->where('b.booking_status', 'Confirmed')
                    ->where('b.booking_date >=', $startDate)
                    ->where('b.booking_date <=', $endDate)
                    ->groupBy('ts.category')
                    ->orderBy('total_revenue', 'DESC')
                    ->get()->getResultArray();
    }
}