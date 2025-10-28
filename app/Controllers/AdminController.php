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
        return redirect()->to(base_url('/login'))->with('error', 'Please log in as Admin to access the admin dashboard.');
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

    // ✅ Get monthly bookings (month + total)
    $getTotalBookingsByMonth = $bookingModel->getMonthlyBookingsTrend();

    // ✅ Initialize all months (Jan–Dec) with 0
    $BookingData = array_fill(1, 12, ['month' => '', 'total_bookings' => 0]);

    // ✅ Fill each month properly
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

    // ✅ Limit to current month only
    $currentMonth = (int)date('n');
    $BookingData = array_slice($BookingData, 0, $currentMonth, true);

    // ✅ Pass full object array for JS
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

    
}
