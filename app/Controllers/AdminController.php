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
            return redirect()->to(base_url('/'))->with('error', 'Please log in as Admin to access the admin dashboard.');
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
            $BookingData[$m] = [
                'month' => date('F', mktime(0, 0, 0, $m, 1)),
                'total_bookings' => 0
            ];
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
    //  API METHODS FOR THE REGISTRATIONS PAGE
    // ==========================================================

    /**
     * API endpoint to fetch all registration data.
     */
    public function getRegistrationList()
    {
        $businessModel = new BusinessModel();
        $data = $businessModel->getAllRegistrations();
        return $this->response->setJSON($data);
    }

    /**
     * API endpoint to fetch details for a single registration.
     */
    public function viewRegistration($id = null)
    {
        $businessModel = new BusinessModel();
        $registration = $businessModel->find($id);

        if ($registration) {
            return $this->response->setJSON($registration);
        }

        return $this->response->setStatusCode(404)->setJSON(['error' => 'Registration not found']);
    }

    /**
     * API endpoint to handle APPROVING a registration.
     */
    public function approveRegistration($id = null)
    {
        $businessModel = new BusinessModel();
        $data = ['status' => 'approved', 'rejection_reason' => null]; // Also clear any previous rejection reason

        if ($businessModel->update($id, $data)) {
            // Optional: Add logic here to send an "Approved" email notification.
            return $this->response->setJSON(['success' => 'Registration approved successfully.']);
        }

        return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to approve registration in the database.']);
    }

    /**
     * API endpoint to handle REJECTING a registration.
     */
    public function rejectRegistration($id = null)
    {
        $businessModel = new BusinessModel();
        $reason = $this->request->getPost('reason');

        if (empty($reason)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Reason for rejection is required.']);
        }

        $data = [
            'status' => 'rejected',
            'rejection_reason' => $reason
        ];

        if ($businessModel->update($id, $data)) {
            // Optional: Add logic here to send a "Rejected" email notification with the reason.
            return $this->response->setJSON(['success' => 'Registration rejected successfully.']);
        }

        return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to reject registration in the database.']);
    }
}