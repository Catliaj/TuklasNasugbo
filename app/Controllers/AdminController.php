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

    public function dashboard()
    {
        if (!session()->get('isLoggedIn') || session()->get('Role') !== 'Admin') {
<<<<<<< Updated upstream
            return redirect()->to(base_url('/users/login'))->with('error', 'Please log in as Admin to access the admin dashboard.');
        }

         
=======
            return redirect()->to(base_url('/'))->with('error', 'Please log in as Admin.');
        }

        // Instantiate Models
>>>>>>> Stashed changes
        $businessModel = new BusinessModel();
        $bookingModel = new BookingModel();
        $spotModel = new TouristSpotModel();
        $userPrefModel = new UserPreferenceModel();
        $feedbackModel = new FeedbackModel();

        // --------------------------------------------------------
        // 1. KPI CARDS DATA (Matches your new Dashboard Design)
        // --------------------------------------------------------
        $data['satisfactionScore'] = $feedbackModel->getOverallAverageRating(); // Fixes Undefined Variable Error
        $data['TotalPendingRequests'] = $businessModel->getTotalPendingRequests();
        $data['TotalBookingsThisMonth'] = $bookingModel->getTotalBookingsThisMonth();
        $data['TotalTouristSpots'] = $spotModel->getTotalTouristSpots();
        
        // 2. CHARTS DATA (JSON for JavaScript)
        $data['peakVisitTimes'] = json_encode($bookingModel->getPeakVisitTimes());
        $data['userPreferences'] = json_encode($userPrefModel->getUserPreferenceDistribution());

        // 3. LISTS DATA
        $data['topHiddenSpots'] = $spotModel->getTopRecommendedHiddenSpots(5);
        $data['topViewedBusinesses'] = $businessModel->getTopViewedBusinesses(5);

        // 4. LEGACY DATA (Kept for compatibility/backup)
        $data['TotalTodayBookings'] = $bookingModel->getTotalBookingsToday();
        $data['MonthlyBookingsTrend'] = json_encode($bookingModel->getRevenueAndBookingsTrend());
        $data['TotalCategories'] = json_encode($spotModel->getTotalCategories());
        $data['RecentActivity'] = method_exists($bookingModel, 'getRecentActivities') ? $bookingModel->getRecentActivities(5) : [];

        return view('Pages/admin/dashboard', $data);
    }

<<<<<<< Updated upstream
    public function viewAttraction($id = null)
    {
        $spotModel = new \App\Models\TouristSpotModel();

        // Get main attraction info with joined business and user info
        $attraction = $spotModel->select('tourist_spots.*, businesses.business_name, users.FirstName, users.LastName')
            ->join('businesses', 'businesses.business_id = tourist_spots.business_id')
            ->join('users', 'users.UserID = businesses.user_id')
            ->where('tourist_spots.spot_id', $id)
            ->first();

        if (!$attraction) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Attraction not found']);
        }

        // Get gallery images
        $galleryModel = new \App\Models\SpotGalleryModel();
        $images = $galleryModel->where('spot_id', $id)->findAll();

        // Map gallery images to full URLs
        $attraction['images'] = !empty($images)
            ? array_map(fn($g) => base_url('uploads/spots/gallery/' . $g['image']), $images)
            : [];

        // If no gallery images, include primary image
        if (empty($attraction['images']) && !empty($attraction['primary_image'])) {
            $attraction['images'][] = base_url('uploads/spots/' . $attraction['primary_image']);
        }

        // If still empty, fallback to placeholder
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
        $data = ['status' => 'suspended', 'status_reason' => $reason];
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
=======
    public function registrations() { return view('Pages/admin/registrations'); }
    public function attractions() { return view('Pages/admin/attractions'); }
    public function reports() { return view('Pages/admin/reports'); }
    public function settings() { return view('Pages/admin/settings'); }

>>>>>>> Stashed changes

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

            $totalRevenue = $bookingModel->selectSum('total_price')
                                         ->where('booking_status', 'Confirmed')
                                         ->where('DATE(booking_date) >=', $startDate)
                                         ->where('DATE(booking_date) <=', $endDate)
                                         ->get()->getRow()->total_price ?? 0;

            $avgRating = $feedbackModel->selectAvg('rating')
                                       ->where('DATE(created_at) >=', $startDate)
                                       ->where('DATE(created_at) <=', $endDate)
                                       ->get()->getRow()->rating ?? 0;

            $data = [
                'success' => true,
                // 'summary' object required by Reports JS
                'summary' => [
                    'totalBookings' => number_format($totalBookings),
                    'totalRevenue' => $totalRevenue,
                    'averageRevenuePerBooking' => $totalBookings > 0 ? round($totalRevenue / $totalBookings, 2) : 0,
                    'averageRating' => number_format((float)$avgRating, 1),
                    'activeAttractions' => $spotModel->where('status', 'approved')->countAllResults()
                ],
                // 'charts' object required by Reports JS
                'charts' => [
                    'visitorDemographics' => $bookingModel->getVisitorDemographics($startDate, $endDate),
                    'peakBookingDays' => $bookingModel->getPeakDays($startDate, $endDate)['peak_booking_days'] ?? [],
                    'bookingLeadTime' => $bookingModel->getBookingLeadTime($startDate, $endDate),
                    'revenueByCategory' => $bookingModel->getRevenueByCategory($startDate, $endDate),
                    'performanceMetrics' => $bookingModel->getTopSpotsPerformanceMetrics($startDate, $endDate)
                ],
                // 'tables' object required by Reports JS
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

<<<<<<< Updated upstream
    

    
}
=======

    // ==========================================================
    //  REGISTRATIONS API (CRUD)
    // ==========================================================
    public function getRegistrationList() 
    { 
        $businessModel = new BusinessModel(); 
        return $this->response->setJSON($businessModel->getAllRegistrations()); 
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
    //  ATTRACTIONS API (CRUD)
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
        
        $galleryModel = new SpotGalleryModel(); 
        $images = $galleryModel->where('spot_id', $id)->findAll(); 
        
        // Format images for the carousel
        $attraction['images'] = !empty($images) ? array_map(fn($g) => base_url('uploads/spots/gallery/' . $g['image']), $images) : []; 
        if (empty($attraction['images']) && !empty($attraction['primary_image'])) { 
            $attraction['images'][] = base_url('uploads/spots/' . $attraction['primary_image']); 
        } 
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
}
>>>>>>> Stashed changes
