<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\BusinessModel;
use App\Models\TouristSpotModel;
use App\Models\BookingModel;

class AdminController extends BaseController
{
   public function dashboard()
    {
        if (!session()->get('isLoggedIn') || session()->get('Role') !== 'Admin') {
            return redirect()->to(base_url('/users/login'))->with('error', 'Please log in as Admin to access the admin dashboard.');
        }

         
        $businessModel = new BusinessModel();
        $touristSpotModel = new TouristSpotModel();
        $bookingModel = new BookingModel();

        $data['totalPendingRequests'] = $businessModel->getTotalPendingRequests();
        $userID = session()->get('UserID');
        $data['totalTouristSpots'] = $touristSpotModel->getTotalTouristSpots();
        $data['totalBookingsThisMonth'] = $bookingModel->getTotalBookingsThisMonth();
        $data['totalTodayBookings'] = $bookingModel->getTotalBookingsToday();
        $data['totalCategories'] = $touristSpotModel->getTotalCategories();

        $getTotalBookingsByMonth = $bookingModel->getMonthlyBookingsTrend();
        $BookingData = array_fill(1, 12, ['month' => '', 'total_bookings' => 0]);

        foreach (range(1, 12) as $m) {
            $BookingData[$m] = ['month' => date('F', mktime(0, 0, 0, $m, 1)), 'total_bookings' => 0];
        }
        foreach ($getTotalBookingsByMonth as $row) {
            $month = (int)$row['month'];
            $BookingData[$month]['total_bookings'] = (int)$row['total'];
        }
        $currentMonth = (int)date('n');
        $BookingData = array_slice($BookingData, 0, $currentMonth, true);

        $data['monthlyBookingsTrend'] = json_encode(array_values($BookingData), JSON_NUMERIC_CHECK);
        $data['totalCategoriesJSON'] = json_encode($data['totalCategories'], JSON_NUMERIC_CHECK);

        return view('Pages/admin/dashboard', [
            'data' => $data,
            'userID' => $userID,
            'FullName' => session()->get('FirstName') . ' ' . session()->get('LastName'),
            'email' => session()->get('Email'),
            'currentID' => $userID,
            'TotalPendingRequests' => $data['totalPendingRequests'],
            'TotalTouristSpots' => $data['totalTouristSpots'],
            'TotalBookingsThisMonth' => $data['totalBookingsThisMonth'],
            'TotalTodayBookings' => $data['totalTodayBookings'],
            'MonthlyBookingsTrend' => $data['monthlyBookingsTrend'],
            'TotalCategories' => $data['totalCategoriesJSON'],
        ]);
    }

    public function registrations()
    {
        return view('Pages/admin/registrations');
    }

    public function attractions()
    {
        return view('Pages/admin/attractions');
    }

    public function reports()
    {
        return view('Pages/admin/reports');
    }

    public function settings()
    {
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
        return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to approve registration in the database.']);
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
        return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to reject registration in the database.']);
    }

    // ==========================================================
    //  ATTRACTIONS API METHODS (NEWLY ADDED)
    // ==========================================================
    public function getAttractionList()
    {
        $spotModel = new TouristSpotModel();
        $data = $spotModel->getAllTouristSpots();
        return $this->response->setJSON($data);
    }

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
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Reason for suspension is required.']);
        }
        $data = ['status' => 'suspended', 'status_reason' => $reason];
        if ($spotModel->update($id, $data)) {
            return $this->response->setJSON(['success' => 'Attraction suspended successfully.']);
        }
        return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to suspend the attraction.']);
    }

    public function deleteAttraction($id = null)
    {
        $spotModel = new TouristSpotModel();
        // NOTE: For a real application, you should also delete related bookings, reviews, gallery images, etc.
        // This is a basic delete for now.
        if ($spotModel->delete($id)) {
            return $this->response->setJSON(['success' => 'Attraction has been permanently deleted.']);
        }
        return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to delete the attraction.']);
    }

    

    
}