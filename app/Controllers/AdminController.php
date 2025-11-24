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

        // 2. CHARTS DATA (Safely Encoded for JS)
        // We use the ?: [] operator to ensure we don't encode nulls
        $peakRaw = $bookingModel->getPeakVisitTimes();
        $data['peakVisitTimes'] = json_encode($peakRaw ?: []);

        $prefRaw = $userPrefModel->getUserPreferenceDistribution();
        $data['userPreferences'] = json_encode($prefRaw ?: []);

        // 3. MONTHLY TREND (Manual Construction)
        $getTotalBookingsByMonth = method_exists($bookingModel, 'getMonthlyBookingsTrend') ? $bookingModel->getMonthlyBookingsTrend() : [];
        
        // Initialize array for 12 months
        $BookingData = [];
        for ($m = 1; $m <= 12; $m++) {
            $BookingData[$m] = [
                'month' => date('F', mktime(0, 0, 0, $m, 1)),
                'total_bookings' => 0
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
        
        // Slice to current month so the line chart doesn't flatline for future months
        $currentMonth = (int)date('n');
        $BookingData = array_slice($BookingData, 0, $currentMonth, true);
        
        $data['monthlyBookingsTrend'] = json_encode(array_values($BookingData), JSON_NUMERIC_CHECK);

        // 4. LISTS DATA
        $data['topHiddenSpots'] = $touristSpotModel->getTopRecommendedHiddenSpots(5);
        $data['topViewedBusinesses'] = $businessModel->getTopViewedBusinesses(5);
        
        // Categories
        $data['TotalCategories'] = $touristSpotModel->getTotalCategories();
        $data['TotalCategoriesJSON'] = json_encode($data['TotalCategories'] ?: [], JSON_NUMERIC_CHECK);

        // Pass everything to View
        return view('Pages/admin/dashboard', [
            'data'                    => $data,
            'userID'                  => session()->get('UserID'),
            'FullName'                => session()->get('FirstName') . ' ' . session()->get('LastName'),
            'email'                   => session()->get('Email'),
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
            
            // Lists
            'topHiddenSpots'          => $data['topHiddenSpots'],
            'topViewedBusinesses'     => $data['topViewedBusinesses']
        ]);
    }

    public function registrations()
    {
        if ($redirect = $this->ensureAdmin()) return $redirect;
        return view('Pages/admin/registrations');
    }

    public function attractions()
    {
        if ($redirect = $this->ensureAdmin()) return $redirect;
        return view('Pages/admin/attractions');
    }

    public function reports()
    {
        if ($redirect = $this->ensureAdmin()) return $redirect;
        return view('Pages/admin/reports');
    }

    public function settings()
    {
        if ($redirect = $this->ensureAdmin()) return $redirect;
        return view('Pages/admin/settings');
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
        $data = $notifModel->getLatestForAdmin(20);
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
                    'performanceMetrics' => $bookingModel->getTopSpotsPerformanceMetrics($startDate, $endDate) ?: []
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