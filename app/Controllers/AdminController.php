<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\BusinessModel;
use App\Models\TouristSpotModel;
use App\Models\BookingModel;
use App\Models\FeedbackModel;
use App\Models\SpotGalleryModel;
use App\Models\UsersModel;
use App\Models\UserPreferenceModel;
use App\Models\NotificationModel;
use Dompdf\Dompdf;

class AdminController extends BaseController
{
    // ==========================================================
    //  HELPER: ENFORCE ADMIN ROLE
    // ==========================================================
    protected function ensureAdmin()
    {
        if (!session()->get('isLoggedIn') || session()->get('Role') !== 'Admin') {
            return redirect()->to(base_url('/'))->with('error', 'Please log in as Admin to access the admin dashboard.');
        }
        return null;
    }

    // ==========================================================
    //  PAGE RENDERERS
    // ==========================================================

    public function dashboard()
    {
        // Enforce admin check
        if ($redirect = $this->ensureAdmin()) return $redirect;

        $businessModel = new BusinessModel();
        $touristSpotModel = new TouristSpotModel();
        $bookingModel = new BookingModel();
        $feedbackModel = new FeedbackModel();
        $userPrefModel = new UsersModel();

        // 1. KPI CARDS DATA
        $data['TotalPendingRequests']   = $businessModel->getTotalPendingRequests();
        $data['TotalTouristSpots']      = $touristSpotModel->getTotalTouristSpots();
        $data['TotalPendingSpots']      = $touristSpotModel->getTotalPendingSpots();
        $data['TotalBookingsThisMonth'] = $bookingModel->getTotalBookingsThisMonth();
        $data['TotalTodayBookings']     = $bookingModel->getTotalBookingsToday();
        $data['satisfactionScore']      = $feedbackModel->getOverallAverageRating();
        $data['totalFeedbackCount']     = $feedbackModel->countAll();

        // 2. CHARTS DATA (Safely Encoded for JS)
        // Normalize shapes for the frontend charts
        $peakRaw = $bookingModel->getPeakVisitTimes();
        $peakNorm = array_map(function($row){
            return [
                'label' => $row['day'] ?? ($row['label'] ?? ''),
                'value' => (int)($row['total_visits'] ?? ($row['total'] ?? 0))
            ];
        }, $peakRaw ?: []);
        $data['peakVisitTimes'] = json_encode($peakNorm, JSON_NUMERIC_CHECK);

        $prefRaw = $userPrefModel->getUserPreferenceDistribution();
        $prefNorm = array_map(function($row){
            return [
                'label' => $row['category'] ?? ($row['label'] ?? ''),
                'count' => (int)($row['total'] ?? ($row['count'] ?? 0))
            ];
        }, $prefRaw ?: []);
        $data['userPreferences'] = json_encode($prefNorm, JSON_NUMERIC_CHECK);

        // 3. MONTHLY TREND (Manual Construction) with revenue
        $getTotalBookingsByMonth = method_exists($bookingModel, 'getMonthlyBookingsTrend') ? $bookingModel->getMonthlyBookingsTrend() : [];
        // Monthly revenue (confirmed/finalized bookings only)
        $db = \Config\Database::connect();
        $statuses = [
            'Confirmed','Completed','Checked-in','Checked-out','Checked-In','Checked-Out'
        ];
        $placeholders = implode(',', array_fill(0, count($statuses), '?'));
        $revQuery = $db->query(
            "SELECT MONTH(booking_date) as month, COALESCE(SUM(total_price),0) as total_revenue
             FROM bookings
             WHERE YEAR(booking_date) = YEAR(CURDATE())
               AND booking_status IN ($placeholders)
             GROUP BY MONTH(booking_date)
             ORDER BY MONTH(booking_date)",
            $statuses
        )->getResultArray();
        $revenueByMonth = [];
        foreach (($revQuery ?: []) as $row) {
            $revenueByMonth[(int)$row['month']] = (float)$row['total_revenue'];
        }
        
        // Initialize array for 12 months
        $BookingData = [];
        for ($m = 1; $m <= 12; $m++) {
            $BookingData[$m] = [
                'month' => date('F', mktime(0, 0, 0, $m, 1)),
                'total_bookings' => 0,
                'total_revenue'  => 0.0
            ];
        }
        
        // Fill with actual data
        if (!empty($getTotalBookingsByMonth)) {
            foreach ($getTotalBookingsByMonth as $row) {
                $month = (int)($row['month'] ?? 0);
                if ($month >= 1 && $month <= 12) {
                    $BookingData[$month]['total_bookings'] = (int)($row['total'] ?? 0);
                }
            }
        }
        // Inject revenue
        foreach ($BookingData as $m => &$row) {
            $row['total_revenue'] = isset($revenueByMonth[$m]) ? (float)$revenueByMonth[$m] : 0.0;
        }
        
        // Slice to current month so the line chart doesn't flatline for future months
        $currentMonth = (int)date('n');
        $BookingData = array_slice($BookingData, 0, $currentMonth, true);
        
        $data['monthlyBookingsTrend'] = json_encode(array_values($BookingData), JSON_NUMERIC_CHECK);

        // 4. CONVERSION (Confirmed / All bookings) current vs previous month
        $currentYm = date('Y-m');
        $prevYm = date('Y-m', strtotime('-1 month'));
        $allCurrent = $db->query(
            "SELECT COUNT(*) as c FROM bookings WHERE DATE_FORMAT(booking_date,'%Y-%m') = ?",
            [$currentYm]
        )->getRowArray();
        $confCurrent = $db->query(
            "SELECT COUNT(*) as c FROM bookings WHERE DATE_FORMAT(booking_date,'%Y-%m') = ? AND booking_status IN ($placeholders)",
            array_merge([$currentYm], $statuses)
        )->getRowArray();
        $allPrev = $db->query(
            "SELECT COUNT(*) as c FROM bookings WHERE DATE_FORMAT(booking_date,'%Y-%m') = ?",
            [$prevYm]
        )->getRowArray();
        $confPrev = $db->query(
            "SELECT COUNT(*) as c FROM bookings WHERE DATE_FORMAT(booking_date,'%Y-%m') = ? AND booking_status IN ($placeholders)",
            array_merge([$prevYm], $statuses)
        )->getRowArray();

        $convCurrent = ((int)($allCurrent['c'] ?? 0)) > 0
            ? round(((int)($confCurrent['c'] ?? 0)) / max(1, (int)$allCurrent['c']) * 100, 1)
            : 0.0;
        $convPrev = ((int)($allPrev['c'] ?? 0)) > 0
            ? round(((int)($confPrev['c'] ?? 0)) / max(1, (int)$allPrev['c']) * 100, 1)
            : 0.0;
        $convTrend = round($convCurrent - $convPrev, 1);

        $data['metrics'] = json_encode([
            'conversionRate' => $convCurrent,
            'conversionTrend' => $convTrend
        ], JSON_NUMERIC_CHECK);

        // 5. LISTS DATA
        $data['topHiddenSpots'] = $touristSpotModel->getTopRecommendedHiddenSpots(5);
        $data['topViewedBusinesses'] = $businessModel->getTopViewedBusinesses(5);
        // Performance metrics: Top 3 spots by revenue (last 30 days)
        $perfStart = date('Y-m-d', strtotime('-29 days'));
        $perfEnd   = date('Y-m-d');
        $data['topSpotsPerformance'] = method_exists($bookingModel, 'getTopSpotsPerformanceMetrics')
            ? ($bookingModel->getTopSpotsPerformanceMetrics($perfStart, $perfEnd, 3) ?: [])
            : [];
        
        // Categories
        $data['TotalCategories'] = $touristSpotModel->getTotalCategories();
        $data['TotalCategoriesJSON'] = json_encode($data['TotalCategories'] ?: [], JSON_NUMERIC_CHECK);

        // Get unread notifications count for initial badge
        $notifModel = new NotificationModel();
        $unread = (int) $notifModel->getUnreadCount(null);

        // Pass everything to View
        return view('Pages/admin/dashboard', [
            'data'                    => $data,
            'userID'                  => session()->get('UserID'),
            'FullName'                => session()->get('FirstName') . ' ' . session()->get('LastName'),
            'email'                   => session()->get('Email'),
            'Email'                   => session()->get('Email'),
            'currentID'               => session()->get('UserID'),
            
            // Explicit variables for View
            'TotalPendingRequests'    => $data['TotalPendingRequests'],
            'TotalPendingSpots'       => $data['TotalPendingSpots'],
            'TotalTouristSpots'       => $data['TotalTouristSpots'],
            'TotalBookingsThisMonth'  => $data['TotalBookingsThisMonth'],
            'TotalTodayBookings'      => $data['TotalTodayBookings'],
            'satisfactionScore'       => $data['satisfactionScore'],
            
            // JSON Strings for JS
            'MonthlyBookingsTrend'    => $data['monthlyBookingsTrend'],
            'peakVisitTimes'          => $data['peakVisitTimes'],
            'userPreferences'         => $data['userPreferences'],
            'TotalCategories'         => $data['TotalCategoriesJSON'],
            'metricsJSON'             => $data['metrics'],
            
            // Lists
            'topHiddenSpots'          => $data['topHiddenSpots'],
            'topViewedBusinesses'     => $data['topViewedBusinesses'],
            'topSpotsPerformance'     => $data['topSpotsPerformance']
            , 'unreadNotifications'    => $unread
        ]);
    }

    public function registrations()
    {
        if ($redirect = $this->ensureAdmin()) return $redirect;
        $notifModel = new NotificationModel();
        $unread = (int) $notifModel->getUnreadCount(null);
        return view('Pages/admin/registrations', ['unreadNotifications' => $unread]);
    }

    public function attractions()
    {
        if ($redirect = $this->ensureAdmin()) return $redirect;
        $notifModel = new NotificationModel();
        $unread = (int) $notifModel->getUnreadCount(null);
        return view('Pages/admin/attractions', ['unreadNotifications' => $unread]);
    }

    public function reports()
    {
        if ($redirect = $this->ensureAdmin()) return $redirect;
        $notifModel = new NotificationModel();
        $unread = (int) $notifModel->getUnreadCount(null);
        return view('Pages/admin/reports', ['unreadNotifications' => $unread]);
    }

    public function settings()
    {
        if ($redirect = $this->ensureAdmin()) return $redirect;
        return view('Pages/admin/settings');
    }

    public function profile()
    {
        if ($redirect = $this->ensureAdmin()) return $redirect;
        $notifModel = new NotificationModel();
        $unread = (int) $notifModel->getUnreadCount(null);
        // Provide minimal user info from session
        $user = [
            'FullName' => session()->get('FirstName') . ' ' . session()->get('LastName'),
            'Email' => session()->get('Email'),
            'UserID' => session()->get('UserID')
        ];
        return view('Pages/admin/profile', ['unreadNotifications' => $unread, 'user' => $user]);
    }

    // Endpoint to update admin profile info
    public function updateProfile()
    {
        if ($redirect = $this->ensureAdmin()) return $redirect;
        $rules = [
            'FirstName' => 'required|min_length[2]|max_length[80]',
            'LastName'  => 'required|min_length[2]|max_length[80]',
            'email'     => 'required|valid_email',
            'current_password' => 'permit_empty',
            'new_password' => 'permit_empty|min_length[8]',
            'confirm_password' => 'permit_empty'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON(['errors' => $this->validator->getErrors()]);
        }

        $userId = session()->get('UserID');
        $usersModel = new UsersModel();
        $data = [
            'FirstName' => $this->request->getPost('FirstName'),
            'LastName'  => $this->request->getPost('LastName'),
            'email'     => $this->request->getPost('email')
        ];

        // Email uniqueness check (ignore current user)
        $existing = $usersModel->where('email', $data['email'])->where('UserID !=', $userId)->countAllResults();
        if ($existing > 0) {
            return $this->response->setStatusCode(422)->setJSON(['errors' => ['email' => 'This email is already in use by another account.']]);
        }

        // Handle password change if requested
        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');
        if (!empty($newPassword)) {
            if (empty($currentPassword)) {
                return $this->response->setStatusCode(422)->setJSON(['errors' => ['current_password' => 'Current password is required to change password.']]);
            }
            $userRow = $usersModel->find($userId);
            if (!$userRow || !password_verify($currentPassword, $userRow['password'])) {
                return $this->response->setStatusCode(422)->setJSON(['errors' => ['current_password' => 'Current password is incorrect.']]);
            }
            if ($newPassword !== $confirmPassword) {
                return $this->response->setStatusCode(422)->setJSON(['errors' => ['confirm_password' => 'The new password confirmation does not match.']]);
            }
            $data['password'] = password_hash($newPassword, PASSWORD_BCRYPT);
        }

        if ($usersModel->update($userId, $data)) {
            // Update session values
            session()->set('FirstName', $data['FirstName']);
            session()->set('LastName', $data['LastName']);
            session()->set('Email', $data['email']);
            return $this->response->setJSON(['success' => 'Profile updated successfully.']);
        }

        return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to update profile.']);
    }

    // Endpoint to update administrative settings — persists to writable/settings.json
    public function updateSettings()
    {
        if ($redirect = $this->ensureAdmin()) return $redirect;
        // Validate settings
        $rules = [
            'site_title' => 'required|min_length[3]|max_length[120]',
            'primary_color' => 'required',
            'items_per_page' => 'required|integer|greater_than[0]'
        ];
        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON(['errors' => $this->validator->getErrors()]);
        }

        // Accept small settings: site_title, primary_color, items_per_page
        $siteTitle = $this->request->getPost('site_title');
        $primaryColor = $this->request->getPost('primary_color');
        $itemsPerPage = (int)$this->request->getPost('items_per_page');

        $settings = [];
        $path = WRITEPATH . 'settings.json';
        if (file_exists($path)) {
            $raw = file_get_contents($path);
            $settings = json_decode($raw, true) ?: [];
        }

        $settings['site_title'] = $siteTitle ?: ($settings['site_title'] ?? 'Tuklas Nasugbo');
        // Normalize color: ensure it starts with # and is a 3 or 6-digit hex
        $primaryColor = trim($primaryColor);
        if ($primaryColor && strpos($primaryColor, '#') !== 0) $primaryColor = '#' . $primaryColor;
        if (!preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $primaryColor)) {
            return $this->response->setStatusCode(422)->setJSON(['errors' => ['primary_color' => 'Invalid color hex code']]);
        }
        $settings['primary_color'] = $primaryColor ?: ($settings['primary_color'] ?? '#004a7c');
        $settings['items_per_page'] = $itemsPerPage > 0 ? $itemsPerPage : ($settings['items_per_page'] ?? 12);

        if (file_put_contents($path, json_encode($settings, JSON_PRETTY_PRINT))) {
            return $this->response->setJSON(['success' => 'Settings saved successfully.']);
        }

        return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to save settings.']);
    }

    // ==========================================================
    //  REGISTRATIONS API METHODS
    // ==========================================================
    public function getRegistrationList()
    {
        $businessModel = new BusinessModel();
        $data = $businessModel->getAllRegistrations();
        return $this->response->setJSON($data ?: []);
    }

    public function viewRegistration($id = null)
    {
        $businessModel = new BusinessModel();
        $registration = $businessModel->find($id);
        if ($registration) {
            return $this->response->setJSON($registration);
        }
        return $this->response->setStatusCode(404)->setJSON(['error' => 'Registration not found']);
    }

    public function approveRegistration($id = null)
    {
        $businessModel = new BusinessModel();
        $data = ['status' => 'approved', 'rejection_reason' => null];
        if ($businessModel->update($id, $data)) {
            return $this->response->setJSON(['success' => 'Registration approved successfully.']);
        }
        return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to approve registration.']);
    }

    public function rejectRegistration($id = null)
    {
        $businessModel = new BusinessModel();
        $reason = $this->request->getPost('reason');
        if (empty($reason)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Reason for rejection is required.']);
        }
        $data = ['status' => 'rejected', 'rejection_reason' => $reason];
        if ($businessModel->update($id, $data)) {
            return $this->response->setJSON(['success' => 'Registration rejected successfully.']);
        }
        return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to reject registration.']);
    }

    // ==========================================================
    //  ATTRACTIONS API METHODS
    // ==========================================================
    public function getAttractionList()
    {
        $spotModel = new TouristSpotModel();
        $data = $spotModel->getAllTouristSpots();
        return $this->response->setJSON($data ?: []);
    }

    /**
     * Return all pending attraction registration requests for admin review
     */
    public function getPendingAttractionList()
    {
        $spotModel = new TouristSpotModel();
        $builder = $spotModel->select('tourist_spots.*, businesses.business_name, users.FirstName, users.LastName')
                             ->join('businesses', 'businesses.business_id = tourist_spots.business_id')
                             ->join('users', 'users.UserID = businesses.user_id')
                             ->where('tourist_spots.status', 'pending')
                             ->orderBy('tourist_spots.created_at', 'DESC');

        $data = $builder->get()->getResultArray();
        return $this->response->setJSON($data ?: []);
    }

    /**
     * Return count of pending registrations (businesses)
     */
    public function getRegistrationsPendingCount()
    {
        $businessModel = new \App\Models\BusinessModel();
        $count = (int) $businessModel->getTotalPendingRequests();
        return $this->response->setJSON(['pending' => $count]);
    }

    /**
     * Return count of pending attractions (tourist_spots)
     */
    public function getAttractionsPendingCount()
    {
        $spotModel = new \App\Models\TouristSpotModel();
        $count = (int) $spotModel->getTotalPendingSpots();
        return $this->response->setJSON(['pending' => $count]);
    }

    public function viewAttraction($id = null)
    {
        $spotModel = new TouristSpotModel();

        $attraction = $spotModel->select('tourist_spots.*, businesses.business_name, users.FirstName, users.LastName')
            ->join('businesses', 'businesses.business_id = tourist_spots.business_id', 'left')
            ->join('users', 'users.UserID = businesses.user_id', 'left')
            ->where('tourist_spots.spot_id', $id)
            ->first();

        if (!$attraction) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Attraction not found']);
        }

        // Fetch gallery
        $galleryModel = new SpotGalleryModel();
        $images = $galleryModel->where('spot_id', $id)->findAll();

        $attraction['images'] = !empty($images)
            ? array_map(fn($g) => base_url('uploads/spots/gallery/' . $g['image']), $images)
            : [];

        // Fallback to primary image
        if (empty($attraction['images']) && !empty($attraction['primary_image'])) {
            $attraction['images'][] = base_url('uploads/spots/' . $attraction['primary_image']);
        }

        // Final fallback
        if (empty($attraction['images'])) {
            $attraction['images'][] = base_url('uploads/spots/Spot-No-Image.png');
        }

        return $this->response->setJSON($attraction);
    }

    public function suspendAttraction($id = null)
    {
        $spotModel = new TouristSpotModel();
        $reason = $this->request->getPost('reason');

        if (empty($reason)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Reason is required.']);
        }

        $data = ['status' => 'suspended', 'suspension_reason' => $reason];

        if ($spotModel->update($id, $data)) {
            return $this->response->setJSON(['success' => 'Attraction suspended.']);
        }

        return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to suspend attraction.']);
    }

    public function deleteAttraction($id = null)
    {
        $spotModel = new TouristSpotModel();

        if ($spotModel->delete($id)) {
            return $this->response->setJSON(['success' => 'Attraction permanently deleted.']);
        }

        return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to delete attraction.']);
    }

    // ==========================================================
    // NOTIFICATIONS API (Admin)
    // ==========================================================
    public function getNotificationsList()
    {
        $notifModel = new NotificationModel();
        // Return both read and unread (latest first)
        $data = $notifModel->getRecentNotifications(null, 20);
        return $this->response->setJSON($data ?: []);
    }

    public function getUnreadNotificationsCount()
    {
        $notifModel = new NotificationModel();
        $count = (int) $notifModel->getUnreadCount();
        return $this->response->setJSON(['unread' => $count]);
    }

    public function markNotificationsRead()
    {
        $notifModel = new NotificationModel();
        try {
            $notifModel->markAllRead();
            return $this->response->setJSON(['success' => 'Notifications marked as read.']);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to mark notifications read.']);
        }
    }

    /**
     * Approve a pending attraction (set status to 'approved')
     */
    public function approveAttraction($id = null)
    {
        $spotModel = new TouristSpotModel();
        if (!$id) return $this->response->setStatusCode(400)->setJSON(['error' => 'Missing ID']);

        $data = ['status' => 'approved', 'suspension_reason' => null];
        if ($spotModel->update($id, $data)) {
            return $this->response->setJSON(['success' => 'Attraction approved.']);
        }
        return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to approve attraction.']);
    }

    /**
     * Reject a pending attraction (set status to 'rejected' and save reason)
     */
    public function rejectAttraction($id = null)
    {
        $spotModel = new TouristSpotModel();
        $reason = $this->request->getPost('reason');
        if (empty($reason)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Reason is required.']);
        }
        $data = ['status' => 'rejected', 'suspension_reason' => null, 'status_reason' => $reason];
        if ($spotModel->update($id, $data)) {
            return $this->response->setJSON(['success' => 'Attraction rejected.']);
        }
        return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to reject attraction.']);
    }

    // ==========================================================
    //  REPORTS & ANALYTICS API
    // ==========================================================
    public function getAnalytics()
    {
        try {
            $startDate = $this->request->getPost('startDate') ?: date('Y-m-d', strtotime('-29 days'));
            $endDate = $this->request->getPost('endDate') ?: date('Y-m-d');

            $bookingModel = new BookingModel();
            $feedbackModel = new FeedbackModel();
            $spotModel = new TouristSpotModel();
            $db = \Config\Database::connect();

            // Calculate Summary Metrics dynamically
            $totalBookings = $bookingModel->where('booking_status', 'Confirmed')
                                          ->where('DATE(booking_date) >=', $startDate)
                                          ->where('DATE(booking_date) <=', $endDate)
                                          ->countAllResults();

            $rowRevenue = $bookingModel->selectSum('total_price', 'total_price')
                                         ->where('booking_status', 'Confirmed')
                                         ->where('DATE(booking_date) >=', $startDate)
                                         ->where('DATE(booking_date) <=', $endDate)
                                         ->get()->getRowArray();

            $totalRevenue = isset($rowRevenue['total_price']) ? (float)$rowRevenue['total_price'] : 0;

            $rowAvg = $feedbackModel->selectAvg('rating', 'rating')
                                       ->where('DATE(created_at) >=', $startDate)
                                       ->where('DATE(created_at) <=', $endDate)
                                       ->get()->getRowArray();

            $avgRating = isset($rowAvg['rating']) ? (float)$rowAvg['rating'] : 0;

            // Monthly bookings: include Confirmed + Checked-in/Checked-out across date range
            $statusList = ['Confirmed', 'Checked-in', 'Checked-out', 'Checked-In', 'Checked-Out'];
            $placeholders = implode(',', array_fill(0, count($statusList), '?'));
            $monthlyRows = $db->query(
                "SELECT DATE_FORMAT(booking_date, '%Y-%m') as ym, COUNT(*) as total
                 FROM bookings
                 WHERE booking_status IN ($placeholders)
                   AND DATE(booking_date) BETWEEN ? AND ?
                 GROUP BY ym
                 ORDER BY ym ASC",
                array_merge($statusList, [$startDate, $endDate])
            )->getResultArray();

            // Build zero-filled series for each month in range
            $series = [];
            try {
                $map = [];
                foreach (($monthlyRows ?: []) as $r) { $map[$r['ym']] = (int)($r['total'] ?? 0); }
                $startMonth = new \DateTime(date('Y-m-01', strtotime($startDate)));
                $endMonth = new \DateTime(date('Y-m-01', strtotime($endDate)));
                $endMonth->modify('first day of next month');
                for ($dt = clone $startMonth; $dt < $endMonth; $dt->modify('+1 month')) {
                    $ym = $dt->format('Y-m');
                    $series[] = [
                        'month' => $dt->format('F'),
                        'total_bookings' => (int)($map[$ym] ?? 0)
                    ];
                }
            } catch (\Throwable $e) {
                // If DateTime fails, fallback to raw rows mapping without zero fill
                foreach (($monthlyRows ?: []) as $r) {
                    $label = date('F', strtotime(($r['ym'] ?? '') . '-01'));
                    $series[] = [ 'month' => $label, 'total_bookings' => (int)($r['total'] ?? 0) ];
                }
            }

            $data = [
                'success' => true,
                'summary' => [
                    'totalBookings' => (int)$totalBookings,
                    'totalRevenue' => $totalRevenue,
                    'averageRevenuePerBooking' => $totalBookings > 0 ? round($totalRevenue / $totalBookings, 2) : 0,
                    'averageRating' => number_format($avgRating, 1),
                    'activeAttractions' => $spotModel->where('status', 'approved')->countAllResults()
                ],
                'charts' => [
                    'visitorDemographics' => $bookingModel->getVisitorDemographics($startDate, $endDate) ?: [],
                    'peakBookingDays' => $bookingModel->getPeakDays($startDate, $endDate)['peak_booking_days'] ?? [],
                    'bookingLeadTime' => $bookingModel->getBookingLeadTime($startDate, $endDate) ?: [],
                    'revenueByCategory' => $bookingModel->getRevenueByCategory($startDate, $endDate) ?: [],
                    'performanceMetrics' => $bookingModel->getTopSpotsPerformanceMetrics($startDate, $endDate) ?: [],
                    'monthlyBookings' => $series
                ],
                'tables' => [
                    'topPerformingSpots' => $bookingModel->getTopSpotsPerformanceMetrics($startDate, $endDate),
                    'lowestRatedSpots' => $feedbackModel->getLowestRatedSpots($startDate, $endDate)
                ]
            ];

            return $this->response->setJSON($data);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Server Error', 'message' => $e->getMessage()]);
        }
    }

    // ==========================================================
    //  EXPORT: FULL REPORT (CSV)
    // ==========================================================
    public function exportReportsCSV()
    {
        if ($redirect = $this->ensureAdmin()) return $redirect;
        $startDate = $this->request->getGet('startDate') ?: date('Y-m-d', strtotime('-29 days'));
        $endDate   = $this->request->getGet('endDate') ?: date('Y-m-d');

        $bookingModel  = new BookingModel();
        $feedbackModel = new FeedbackModel();
        $db = \Config\Database::connect();

        // Data blocks
        $topPerforming   = $bookingModel->getTopSpotsPerformanceMetrics($startDate, $endDate) ?: [];
        $lowestRated     = $feedbackModel->getLowestRatedSpots($startDate, $endDate) ?: [];
        $revenueByCat    = $bookingModel->getRevenueByCategory($startDate, $endDate) ?: [];

        // Monthly bookings (Confirmed + Checked-in/out) zero-filled
        $statusList = ['Confirmed', 'Checked-in', 'Checked-out', 'Checked-In', 'Checked-Out'];
        $placeholders = implode(',', array_fill(0, count($statusList), '?'));
        $monthlyRows = $db->query(
            "SELECT DATE_FORMAT(booking_date, '%Y-%m') as ym, COUNT(*) as total
             FROM bookings
             WHERE booking_status IN ($placeholders)
               AND DATE(booking_date) BETWEEN ? AND ?
             GROUP BY ym
             ORDER BY ym ASC",
            array_merge($statusList, [$startDate, $endDate])
        )->getResultArray();

        $monthlySeries = [];
        $map = [];
        foreach (($monthlyRows ?: []) as $r) { $map[$r['ym']] = (int)($r['total'] ?? 0); }
        $startMonth = new \DateTime(date('Y-m-01', strtotime($startDate)));
        $endMonth = new \DateTime(date('Y-m-01', strtotime($endDate)));
        $endMonth->modify('first day of next month');
        for ($dt = clone $startMonth; $dt < $endMonth; $dt->modify('+1 month')) {
            $ym = $dt->format('Y-m');
            $monthlySeries[] = [
                'month' => $dt->format('F'),
                'total_bookings' => (int)($map[$ym] ?? 0)
            ];
        }

        // CSV builder
        $esc = function($v){
            $s = (string)$v;
            $s = str_replace('"', '""', $s); // escape quotes by doubling
            return '"' . $s . '"';
        };
        $lines = [];
        $lines[] = "Admin Report from $startDate to $endDate";
        $lines[] = '';
        // Top Performing Attractions
        $lines[] = 'Top Performing Attractions';
        $lines[] = 'Rank,Attraction,Bookings,Revenue,Avg Rating';
        $rank = 1;
        foreach ($topPerforming as $row) {
            $lines[] = implode(',', [
                $rank++,
                $esc($row['spot_name'] ?? $row['business_name'] ?? 'N/A'),
                (int)($row['bookings'] ?? $row['total_bookings'] ?? 0),
                number_format((float)($row['revenue'] ?? $row['total_revenue'] ?? 0), 2, '.', ''),
                isset($row['avg_rating']) ? number_format((float)$row['avg_rating'], 2, '.', '') : ''
            ]);
        }
        $lines[] = '';
        // Lowest Rated
        $lines[] = 'Lowest Rated';
        $lines[] = 'Attraction,Rating,Reviews';
        foreach ($lowestRated as $row) {
            $lines[] = implode(',', [
                $esc($row['spot_name'] ?? $row['business_name'] ?? 'N/A'),
                number_format((float)($row['rating'] ?? 0), 2, '.', ''),
                (int)($row['reviews'] ?? $row['count'] ?? 0)
            ]);
        }
        $lines[] = '';
        // Revenue by Category
        $lines[] = 'Revenue by Category';
        $lines[] = 'Category,Revenue';
        foreach ($revenueByCat as $row) {
            $lines[] = implode(',', [
                $esc($row['category'] ?? 'N/A'),
                number_format((float)($row['revenue'] ?? $row['total_revenue'] ?? 0), 2, '.', '')
            ]);
        }
        $lines[] = '';
        // Monthly Bookings
        $lines[] = 'Monthly Bookings';
        $lines[] = 'Month,Total Bookings';
        foreach ($monthlySeries as $row) {
            $lines[] = implode(',', [
                $esc($row['month'] ?? ''),
                (int)($row['total_bookings'] ?? 0)
            ]);
        }

        $csv = implode("\r\n", $lines) . "\r\n";
        $filename = 'admin_report_' . $startDate . '_to_' . $endDate . '.csv';
        return $this->response
            ->setHeader('Content-Type', 'text/csv; charset=utf-8')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($csv);
    }

    // ==========================================================
    //  EXPORT: FULL REPORT (PDF)
    // ==========================================================
    public function exportReportsPDF()
    {
        if ($redirect = $this->ensureAdmin()) return $redirect;
        $startDate = $this->request->getGet('startDate') ?: date('Y-m-d', strtotime('-29 days'));
        $endDate   = $this->request->getGet('endDate') ?: date('Y-m-d');

        $bookingModel  = new BookingModel();
        $feedbackModel = new FeedbackModel();
        $db = \Config\Database::connect();

        $topPerforming   = $bookingModel->getTopSpotsPerformanceMetrics($startDate, $endDate) ?: [];
        $lowestRated     = $feedbackModel->getLowestRatedSpots($startDate, $endDate) ?: [];
        $revenueByCat    = $bookingModel->getRevenueByCategory($startDate, $endDate) ?: [];

        // Monthly bookings (Confirmed + Checked-in/out) zero-filled
        $statusList = ['Confirmed', 'Checked-in', 'Checked-out', 'Checked-In', 'Checked-Out'];
        $placeholders = implode(',', array_fill(0, count($statusList), '?'));
        $monthlyRows = $db->query(
            "SELECT DATE_FORMAT(booking_date, '%Y-%m') as ym, COUNT(*) as total
             FROM bookings
             WHERE booking_status IN ($placeholders)
               AND DATE(booking_date) BETWEEN ? AND ?
             GROUP BY ym
             ORDER BY ym ASC",
            array_merge($statusList, [$startDate, $endDate])
        )->getResultArray();
        $monthlySeries = [];
        $map = [];
        foreach (($monthlyRows ?: []) as $r) { $map[$r['ym']] = (int)($r['total'] ?? 0); }
        $startMonth = new \DateTime(date('Y-m-01', strtotime($startDate)));
        $endMonth = new \DateTime(date('Y-m-01', strtotime($endDate)));
        $endMonth->modify('first day of next month');
        for ($dt = clone $startMonth; $dt < $endMonth; $dt->modify('+1 month')) {
            $ym = $dt->format('Y-m');
            $monthlySeries[] = [
                'month' => $dt->format('F'),
                'total_bookings' => (int)($map[$ym] ?? 0)
            ];
        }

        // Build HTML
        $style = '<style>body{font-family: DejaVu Sans, Arial, Helvetica, sans-serif;color:#111;font-size:12px}h1{font-size:18px;margin:0 0 8px}h2{font-size:14px;margin:18px 0 8px}table{width:100%;border-collapse:collapse;margin-bottom:8px}th,td{border:1px solid #ddd;padding:6px}th{background:#f5f5f5;text-align:left}.muted{color:#666;font-size:11px;margin-bottom:12px}</style>';
        $html = '<h1>Admin Report</h1>';
        $html .= '<div class="muted">Period: ' . htmlentities($startDate) . ' to ' . htmlentities($endDate) . '</div>';

        // Top Performing
        $html .= '<h2>Top Performing Attractions</h2><table><thead><tr><th>#</th><th>Attraction</th><th>Bookings</th><th>Revenue</th><th>Avg Rating</th></tr></thead><tbody>';
        $rank = 1;
        foreach ($topPerforming as $row) {
            $html .= '<tr>'
                . '<td>' . $rank++ . '</td>'
                . '<td>' . htmlentities($row['spot_name'] ?? $row['business_name'] ?? 'N/A') . '</td>'
                . '<td>' . (int)($row['bookings'] ?? $row['total_bookings'] ?? 0) . '</td>'
                . '<td>' . number_format((float)($row['revenue'] ?? $row['total_revenue'] ?? 0), 2) . '</td>'
                . '<td>' . (isset($row['avg_rating']) ? number_format((float)$row['avg_rating'], 2) : '') . '</td>'
                . '</tr>';
        }
        if ($rank === 1) $html .= '<tr><td colspan="5">No data</td></tr>';
        $html .= '</tbody></table>';

        // Lowest Rated
        $html .= '<h2>Lowest Rated</h2><table><thead><tr><th>Attraction</th><th>Rating</th><th>Reviews</th></tr></thead><tbody>';
        $count = 0;
        foreach ($lowestRated as $row) {
            $count++;
            $html .= '<tr>'
                . '<td>' . htmlentities($row['spot_name'] ?? $row['business_name'] ?? 'N/A') . '</td>'
                . '<td>' . number_format((float)($row['rating'] ?? 0), 2) . '</td>'
                . '<td>' . (int)($row['reviews'] ?? $row['count'] ?? 0) . '</td>'
                . '</tr>';
        }
        if ($count === 0) $html .= '<tr><td colspan="3">No data</td></tr>';
        $html .= '</tbody></table>';

        // Revenue by Category
        $html .= '<h2>Revenue by Category</h2><table><thead><tr><th>Category</th><th>Revenue</th></tr></thead><tbody>';
        $count = 0;
        foreach ($revenueByCat as $row) {
            $count++;
            $html .= '<tr>'
                . '<td>' . htmlentities($row['category'] ?? 'N/A') . '</td>'
                . '<td>' . number_format((float)($row['revenue'] ?? $row['total_revenue'] ?? 0), 2) . '</td>'
                . '</tr>';
        }
        if ($count === 0) $html .= '<tr><td colspan="2">No data</td></tr>';
        $html .= '</tbody></table>';

        // Monthly Bookings
        $html .= '<h2>Monthly Bookings</h2><table><thead><tr><th>Month</th><th>Total Bookings</th></tr></thead><tbody>';
        $count = 0;
        foreach ($monthlySeries as $row) {
            $count++;
            $html .= '<tr>'
                . '<td>' . htmlentities($row['month'] ?? '') . '</td>'
                . '<td>' . (int)($row['total_bookings'] ?? 0) . '</td>'
                . '</tr>';
        }
        if ($count === 0) $html .= '<tr><td colspan="2">No data</td></tr>';
        $html .= '</tbody></table>';

        $html = '<html><head>' . $style . '</head><body>' . $html . '</body></html>';

        // Stream via Dompdf if available; fallback to HTML
        try {
            if (!class_exists(Dompdf::class)) {
                return $this->response->setHeader('Content-Type', 'text/html; charset=utf-8')->setBody($html);
            }
            $dompdf = new Dompdf();
            $dompdf->set_option('isRemoteEnabled', true);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $pdf = $dompdf->output();
            $filename = 'admin_report_' . $startDate . '_to_' . $endDate . '.pdf';
            return $this->response
                ->setHeader('Content-Type', 'application/pdf')
                ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->setBody($pdf);
        } catch (\Throwable $e) {
            // Fallback to HTML with error note
            $html .= '<div class="muted">PDF generation error: ' . htmlentities($e->getMessage()) . '</div>';
            return $this->response->setHeader('Content-Type', 'text/html; charset=utf-8')->setBody($html);
        }
    }

    public function getUnreadNotificationCount()
    {
        $notificationModel = new \App\Models\NotificationModel();
        
        // Get all unread notifications where user_id is NULL (for all admins)
        $count = $notificationModel->getUnreadCount(null);
        
        return $this->response->setJSON(['count' => $count]);
    }
    
    public function getNotifications()
    {
        $notificationModel = new \App\Models\NotificationModel();
        
        // Get all notifications where user_id is NULL (for all admins)
        $notifications = $notificationModel->getUserNotifications(null, 20);
        
        return $this->response->setJSON($notifications);
    }
    
    public function markNotificationAsRead($notificationId)
    {
        $notificationModel = new \App\Models\NotificationModel();
        
        $result = $notificationModel->markAsRead($notificationId);
        
        return $this->response->setJSON(['success' => $result]);
    }
    
    public function markAllNotificationsAsRead()
    {
        $notificationModel = new \App\Models\NotificationModel();
        
        // Mark all unread as read where user_id is NULL (for all admins)
        $result = $notificationModel->markAllAsRead(null);
        
        return $this->response->setJSON(['success' => $result]);
    }
public function notifications()
{
    return view('Pages/admin/notifications');
}
}