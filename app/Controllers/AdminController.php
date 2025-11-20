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

class AdminController extends BaseController
{
    // ==========================================================
    //  PAGE RENDERERS
    // ==========================================================

    protected function ensureAdmin()
    {
        if (!session()->get('isLoggedIn') || session()->get('Role') !== 'Admin') {
            return redirect()->to(base_url('/'))->with('error', 'Please log in as Admin to access the admin dashboard.');
        }
        return null;
    }

    public function dashboard()
    {
        // enforce admin
        if ($redirect = $this->ensureAdmin()) return $redirect;

        $businessModel = new BusinessModel();
        $touristSpotModel = new TouristSpotModel();
        $bookingModel = new BookingModel();
        $feedbackModel = new FeedbackModel();
        $userPrefModel = new UsersModel();

        // KPI cards
        $data['TotalPendingRequests']   = $businessModel->getTotalPendingRequests();
        $data['TotalTouristSpots']      = $touristSpotModel->getTotalTouristSpots();
        $data['TotalBookingsThisMonth'] = $bookingModel->getTotalBookingsThisMonth();
        $data['TotalTodayBookings']     = $bookingModel->getTotalBookingsToday();
        $data['satisfactionScore']      = $feedbackModel->getOverallAverageRating();

        // 2. CHARTS DATA (JSON for JavaScript)
        $data['peakVisitTimes'] = json_encode($bookingModel->getPeakVisitTimes());
        $data['userPreferences'] = json_encode($userPrefModel->getUserPreferenceDistribution());

        // 3. LISTS DATA
        $data['topHiddenSpots'] = $touristSpotModel->getTopRecommendedHiddenSpots(5);
        $data['topViewedBusinesses'] = $businessModel->getTopViewedBusinesses(5);

        // Monthly bookings trend (build months until current month)
        $getTotalBookingsByMonth = method_exists($bookingModel, 'getMonthlyBookingsTrend') ? $bookingModel->getMonthlyBookingsTrend() : [];
        $BookingData = array_fill(1, 12, ['month' => '', 'total_bookings' => 0]);
        foreach (range(1, 12) as $m) {
            $BookingData[$m] = [
                'month' => date('F', mktime(0, 0, 0, $m, 1)),
                'total_bookings' => 0
            ];
        }
        foreach ($getTotalBookingsByMonth as $row) {
            $month = (int)($row['month'] ?? 0);
            if ($month >= 1 && $month <= 12) {
                $BookingData[$month]['total_bookings'] = (int)($row['total'] ?? 0);
            }
        }
        $currentMonth = (int)date('n');
        $BookingData = array_slice($BookingData, 0, $currentMonth, true);

        $data['monthlyBookingsTrend'] = json_encode(array_values($BookingData), JSON_NUMERIC_CHECK);
        $data['TotalCategories'] = $touristSpotModel->getTotalCategories();
        $data['TotalCategoriesJSON'] = json_encode($data['TotalCategories'], JSON_NUMERIC_CHECK);

        return view('Pages/admin/dashboard', [
            'data'                    => $data,
            'userID'                  => session()->get('UserID'),
            'FullName'                => session()->get('FirstName') . ' ' . session()->get('LastName'),
            'email'                   => session()->get('Email'),
            'currentID'               => session()->get('UserID'),
            'TotalPendingRequests'    => $data['TotalPendingRequests'],
            'TotalTouristSpots'       => $data['TotalTouristSpots'],
            'TotalBookingsThisMonth'  => $data['TotalBookingsThisMonth'],
            'TotalTodayBookings'      => $data['TotalTodayBookings'],
            'MonthlyBookingsTrend'    => $data['monthlyBookingsTrend'],
            'TotalCategories'         => $data['TotalCategoriesJSON'],
            'satisfactionScore'       => $data['satisfactionScore'],
            'peakVisitTimes'          => $data['peakVisitTimes'],
            'userPreferences'         => $data['userPreferences'],
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
        return $this->response->setJSON($data);
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
        return $this->response->setJSON($spotModel->getAllTouristSpots());
    }

    public function viewAttraction($id = null)
    {
        $spotModel = new TouristSpotModel();

        $attraction = $spotModel->select('tourist_spots.*, businesses.business_name, users.FirstName, users.LastName')
            ->join('businesses', 'businesses.business_id = tourist_spots.business_id')
            ->join('users', 'users.UserID = businesses.user_id')
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
                    'visitorDemographics' => $bookingModel->getVisitorDemographics($startDate, $endDate),
                    'peakBookingDays' => $bookingModel->getPeakDays($startDate, $endDate)['peak_booking_days'] ?? [],
                    'bookingLeadTime' => $bookingModel->getBookingLeadTime($startDate, $endDate),
                    'revenueByCategory' => $bookingModel->getRevenueByCategory($startDate, $endDate),
                    'performanceMetrics' => $bookingModel->getTopSpotsPerformanceMetrics($startDate, $endDate)
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

    
}