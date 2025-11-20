<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\BusinessModel;
use App\Models\TouristSpotModel;
use App\Models\BookingModel;
use App\Models\UsersModel;
use App\Models\SpotGalleryModel;
use App\Models\CreateVisitorCheckIn;

class SpotOwnerController extends BaseController
{


// Replace the recordCheckin method in SpotOwnerController with this implementation.

public function recordCheckin()
{
    $session = session();
    $userID = $session->get('UserID');
    if (!$userID || !$session->get('isLoggedIn') || $session->get('Role') !== 'Spot Owner') {
        return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
    }

    $input = $this->request->getJSON(true) ?? $this->request->getPost();

    $bookingId = isset($input['booking_id']) ? (int)$input['booking_id'] : null;
    $customerId = $input['customer_id'] ?? null;
    $actualVisitors = isset($input['actual_visitors']) ? (int)$input['actual_visitors'] : null;
    $notes = $input['notes'] ?? null;
    $token = $input['token'] ?? null; // optional: pass token back for authority

    if (!$bookingId) {
        return $this->response->setJSON(['error' => 'Missing booking_id'])->setStatusCode(400);
    }

    try {
        // Resolve owner's business
        $businessModel = new \App\Models\BusinessModel();
        $business = $businessModel->where('user_id', $userID)->first();
        if (!$business) {
            return $this->response->setJSON(['error' => 'Business not found'])->setStatusCode(404);
        }
        $businessId = $business['business_id'];

        // Load booking and verify ownership
        $bookingModel = new \App\Models\BookingModel();
        $booking = $bookingModel->find((int)$bookingId);
        if (!$booking) {
            return $this->response->setJSON(['error' => 'Booking not found'])->setStatusCode(404);
        }

        $bookingSpotBusinessId = $booking['business_id'] ?? $booking['businessid'] ?? null;
        $bookingSpotId = $booking['spot_id'] ?? $booking['spotId'] ?? null;

        if (!$bookingSpotBusinessId && $bookingSpotId) {
            $spotModel = new \App\Models\TouristSpotModel();
            $spot = $spotModel->find((int)$bookingSpotId);
            $bookingSpotBusinessId = $spot['business_id'] ?? null;
        }

        if ($businessId != $bookingSpotBusinessId) {
            return $this->response->setJSON(['error' => 'Forbidden: booking does not belong to your business'])->setStatusCode(403);
        }

        // Determine canonical visit_date from DB (use visit_date, fallback booking_date/date)
        $booking_date_raw = $booking['visit_date'] ?? $booking['booking_date'] ?? $booking['date'] ?? null;
        $bookingDateDb = $booking_date_raw ? date('Y-m-d', strtotime($booking_date_raw)) : null;

        // If token provided, verify and use visit_date from token as authority
        if ($token) {
            if (!is_string($token) || strpos($token, '.') === false) {
                return $this->response->setJSON(['error' => 'Invalid token format'])->setStatusCode(400);
            }
            list($b64, $sig) = explode('.', $token, 2);
            $secret = getenv('VOUCHER_SECRET') ?: (getenv('app.secret') ?: 'change_this_secret');
            $expected = hash_hmac('sha256', $b64, $secret);
            if (!hash_equals($expected, $sig)) {
                return $this->response->setJSON(['error' => 'Invalid token signature'])->setStatusCode(400);
            }
            $b64padded = strtr($b64, '-_', '+/');
            $padlen = 4 - (strlen($b64padded) % 4);
            if ($padlen < 4) $b64padded .= str_repeat('=', $padlen);
            $json = base64_decode($b64padded);
            $payload = json_decode($json, true) ?: [];
            // use token visit_date if present
            $tokenVisitDate = $payload['visit_date'] ?? $payload['booking_date'] ?? null;
            if ($tokenVisitDate) {
                $bookingDateToCheck = $tokenVisitDate;
            } else {
                $bookingDateToCheck = $bookingDateDb;
            }
        } else {
            $bookingDateToCheck = $bookingDateDb;
        }

        // require that bookingDateToCheck exists and is same-day (policy)
        if (!$bookingDateToCheck) {
            return $this->response->setJSON(['error' => 'Visit date not available; cannot verify token for today'])->setStatusCode(400);
        }
        $today = date('Y-m-d');
        if ($bookingDateToCheck !== $today) {
            return $this->response->setJSON(['error' => 'Booking date mismatch', 'visit_date' => $bookingDateToCheck, 'today' => $today])->setStatusCode(400);
        }

        // Use checkin model to find existing records for this booking/customer
        $checkinModel = new \App\Models\CreateVisitorCheckIn();
        $query = $checkinModel->where('booking_id', $bookingId);
        if ($customerId) $query = $query->where('customer_id', $customerId);
        $existing = $query->orderBy('checkin_id', 'DESC')->first();

        // If there's an active checkin (checkout_time NULL) => perform checkout
        if ($existing && empty($existing['checkout_time'])) {
            $updateData = ['checkout_time' => date('Y-m-d H:i:s')];
            if ($actualVisitors !== null) $updateData['actual_visitors'] = $actualVisitors;
            if ($notes !== null) $updateData['notes'] = ($existing['notes'] ?? '') . "\n" . $notes;

            $updated = $checkinModel->update($existing['checkin_id'], $updateData);
            if ($updated === false) {
                $errors = $checkinModel->errors() ?: [];
                log_message('error', '[recordCheckin][checkout] Update failed for checkin_id=' . $existing['checkin_id'] . ' errors: ' . print_r($errors, true));
                return $this->response->setJSON(['error' => 'Failed to record checkout', 'details' => $errors])->setStatusCode(500);
            }

            // update booking status (best-effort)
            try { $bookingModel->update($bookingId, ['booking_status' => 'Checked-out']); } catch (\Throwable $e) { log_message('warning', $e->getMessage()); }

            return $this->response->setJSON(['success' => true, 'action' => 'checkout', 'checkin_id' => $existing['checkin_id']]);
        }

        // If prior checkin already checked out today -> conflict
        if ($existing && !empty($existing['checkout_time'])) {
            $existingCheckinDate = !empty($existing['checkin_time']) ? date('Y-m-d', strtotime($existing['checkin_time'])) : null;
            if ($existingCheckinDate === date('Y-m-d')) {
                return $this->response->setJSON(['error' => 'Already checked out for today', 'existing' => $existing])->setStatusCode(409);
            }
        }

        // No active checkin: create new check-in record
        $insertData = [
            'customer_id'     => $customerId,
            'booking_id'      => $bookingId,
            'checkin_time'    => date('Y-m-d H:i:s'),
            'actual_visitors' => $actualVisitors,
            'is_walkin'       => 0,
            'notes'           => $notes
        ];

        $db = \Config\Database::connect();
        $db->transStart();
        $insertId = $checkinModel->insert($insertData);
        if ($insertId === false) {
            $db->transRollback();
            $errors = $checkinModel->errors() ?: [];
            log_message('error', '[recordCheckin][checkin] Insert failed for booking_id=' . $bookingId . ' errors: ' . print_r($errors, true));
            return $this->response->setJSON(['error' => 'Failed to record check-in', 'details' => $errors])->setStatusCode(500);
        }

        try { $bookingModel->update($bookingId, ['booking_status' => 'Checked-in']); } catch (\Throwable $e) { log_message('warning', $e->getMessage()); }

        $db->transComplete();
        if ($db->transStatus() === false) {
            log_message('error', '[recordCheckin][checkin] DB transaction failed for booking_id=' . $bookingId);
            return $this->response->setJSON(['error' => 'Database transaction failed'])->setStatusCode(500);
        }

        return $this->response->setJSON(['success' => true, 'action' => 'checkin', 'checkin_id' => $insertId]);
    } catch (\Throwable $e) {
        log_message('error', '[recordCheckin] Exception: ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());
        return $this->response->setJSON(['error' => 'Server error while recording check-in'])->setStatusCode(500);
    }
}


    public function dashboard()
    {

        if (!session()->get('isLoggedIn') || session()->get('Role') !== 'Spot Owner') {
        return redirect()->to(base_url('/login'))->with('error', 'Please log in as Spot Owner to access the Spot Owner dashboard.');
        }

        $userID = session()->get('UserID');
         $businessModel = new BusinessModel();
        $touristSpotModel = new TouristSpotModel();
        $bookingModel = new BookingModel();

        $userID = session()->get('UserID');
        $businessData = $businessModel->where('user_id', $userID)->first();
        $businessID = $businessData['business_id'];
        $spots = $touristSpotModel->getSpotsByBusinessID($businessID);
        $totalspots = $touristSpotModel->getTotalSpotsByBusinessID($businessID);
        $toatlbookings = $bookingModel->getTotalBookingsThisMonthByBusiness($businessID);
        $totalrevenue = $bookingModel->getTotalRevenueByBusiness($businessID);

        $data['spots'] = $spots;
        
        return view('Pages/spotowner/home', [
            'userID' => $userID,
            'FullName' => session()->get('FirstName') . ' ' . session()->get('LastName'),
            'email' => session()->get('Email'),
            'totalspots' => $totalspots,
            'totalbookings' => $toatlbookings,
            'totalrevenue' => $totalrevenue,
            'spots' => $data['spots'],
        ]);
    }

    public function mySpots()
    {
        $businessModel = new BusinessModel();
        $touristSpotModel = new TouristSpotModel();

        $userID = session()->get('UserID');
        $businessData = $businessModel->where('user_id', $userID)->first();
        $businessID = $businessData['business_id'];
        $spots = $touristSpotModel->getSpotsByBusinessID($businessID);
        $data['spots'] = $spots;


        return view('Pages/spotowner/manage-spot', [
            'userID' => $userID,
            'FullName' => session()->get('FirstName') . ' ' . session()->get('LastName'),
            'email' => session()->get('Email'),
            'spots' => $data['spots'],

        ]);
    }

    public function bookings()
    {

        $userID = session()->get('UserID');
        $businessModel = new BusinessModel();
        $businessData = $businessModel->where('user_id', $userID)->first();
        $businessID = $businessData['business_id'];
        $bookingModel = new BookingModel();
        $toatlbookings = $bookingModel->getTotalBookingsThisMonthByBusiness($businessID);
        $totalVisitor = $bookingModel->getTotalVisitor($businessID);
         $totalrevenue = $bookingModel->getTotalRevenueByBusiness($businessID);
        return view('Pages/spotowner/bookings', [
            'totalbookings' => $toatlbookings,
            'totalvisitors'=> $totalVisitor,
            'totalrevenue'=> $totalrevenue
        ]);
    }

    public function earnings()
{
    if (!session()->get('isLoggedIn') || session()->get('Role') !== 'Spot Owner') {
        return redirect()->to(base_url('/login'))->with('error', 'Please log in as Spot Owner to access the Spot Owner dashboard.');
    }

    $userID = session()->get('UserID');
    $businessModel = new BusinessModel();
    $bookingModel = new BookingModel();

    // Get business ID
    $businessData = $businessModel->where('user_id', $userID)->first();
    
    if (!$businessData) {
        return redirect()->back()->with('error', 'Business information not found.');
    }

    $businessID = $businessData['business_id'];

    // Get all earnings data
    $totalRevenue = $bookingModel->getTotalRevenueAllTime($businessID);
    $monthlyRevenue = $bookingModel->getMonthlyRevenue($businessID);
    $averageData = $bookingModel->getAverageRevenuePerBooking($businessID);
    $pendingRevenue = $bookingModel->getPendingRevenue($businessID);
    $comparison = $bookingModel->getMonthOverMonthComparison($businessID);
    $recentTransactions = $bookingModel->getRecentTransactionsByBusiness($businessID, 5);
    $topDays = $bookingModel->getTopPerformingDays($businessID, 4);

    return view('Pages/spotowner/earnings', [
        'totalRevenue' => $totalRevenue,
        'monthlyRevenue' => $monthlyRevenue,
        'averageRevenue' => $averageData['average'],
        'totalBookings' => $averageData['total_bookings'],
        'pendingRevenue' => $pendingRevenue,
        'comparison' => $comparison,
        'recentTransactions' => $recentTransactions,
        'topDays' => $topDays
    ]);
}

    public function settings()
    {
        return view('Pages/spotowner/profile');
    }



    public function storeMySpots()
    {
        try {
            $businessModel = new BusinessModel();
            $touristSpotModel = new TouristSpotModel();
            $spotGalleryModel = new \App\Models\SpotGalleryModel();

            // Get logged-in user’s business
            $userID = session()->get('UserID');
            $businessData = $businessModel->where('user_id', $userID)->first();

            if (!$businessData) {
                return redirect()->back()->with('error', 'Business information not found.');
            }

            // --- Prepare spot data ---
            $data = [
                'business_id'          => $businessData['business_id'],
                'spot_name'            => $this->request->getPost('spot_name'),
                'description'          => $this->request->getPost('description'),
                'latitude'             => $this->request->getPost('latitude'),
                'longitude'            => $this->request->getPost('longitude'),
                'category'             => $this->request->getPost('category'),
                'location'             => $this->request->getPost('location'),
                'capacity'             => $this->request->getPost('capacity'),
                'opening_time'         => $this->request->getPost('opening_time'),
                'closing_time'         => $this->request->getPost('closing_time'),
                'operating_days'       => implode(', ', (array)$this->request->getPost('operating_days')),
                'status'               => 'pending',
                'price_per_person'     => $this->request->getPost('price_per_person'),
                'child_price'          => $this->request->getPost('child_price'),
                'senior_price'         => $this->request->getPost('senior_price'),
                'group_discount_percent' => $this->request->getPost('group_discount_percent'),
                'created_at'           => date('Y-m-d H:i:s'),
                'updated_at'           => date('Y-m-d H:i:s')
            ];

            // --- Handle primary image ---
            $primaryImage = $this->request->getFile('primary_image');
            if ($primaryImage && $primaryImage->isValid() && !$primaryImage->hasMoved()) {
                $newName = $primaryImage->getRandomName();
                $uploadPath = FCPATH . 'uploads/spots/';

                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $primaryImage->move($uploadPath, $newName);
                $data['primary_image'] = $newName; // store filename in tourist_spots table
            }

            // --- Insert tourist spot ---
            $spotId = $touristSpotModel->insert($data);

            if (!$spotId) {
                $error = $touristSpotModel->errors();
                log_message('error', '[storeMySpots] Tourist spot insert failed: ' . print_r($error, true));
                return redirect()->back()->with('error', 'Failed to add tourist spot.');
            }

            // --- Handle gallery images (multiple) ---
            $galleryImages = $this->request->getFiles();
            if (isset($galleryImages['gallery_images'])) {
                foreach ($galleryImages['gallery_images'] as $image) {
                    if ($image->isValid() && !$image->hasMoved()) {
                        $newName = $image->getRandomName();
                        $galleryPath = FCPATH . 'uploads/spots/gallery/';

                        if (!is_dir($galleryPath)) {
                            mkdir($galleryPath, 0777, true);
                        }

                        $image->move($galleryPath, $newName);

                        // insert to SpotGallery model
                        $spotGalleryModel->insert([
                            'spot_id' => $spotId,
                            'image' => $newName
                        ]);
                    }
                }
            }

            // --- Success ---
            return redirect()->to('/spotowner/mySpots')
                            ->with('success', 'Tourist spot added successfully!');

        } catch (\Exception $e) {
            log_message('error', '[storeMySpots] Exception: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An unexpected error occurred while adding the tourist spot.');
        }
    }


    public function getMySpots()
    {
        $userId = session()->get('UserID'); 
        if (!$userId) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Unauthorized']);
        }

        $businessModel = new BusinessModel();
        $spotModel = new TouristSpotModel();
        $galleryModel = new SpotGalleryModel();

        // 1️⃣ Find the business linked to this user
        $business = $businessModel->where('user_id', $userId)->first();
        if (!$business) {
            return $this->response->setJSON([]); // no business, no spots
        }

        // 2️⃣ Get tourist spots for that business
        // ⚠️ use correct column name in your DB, usually `business_id`
        $spots = $spotModel->where('business_id', $business['business_id'])->findAll();

        // 3️⃣ For each spot, attach gallery images and normalize field names
        foreach ($spots as &$spot) {
            // Get all images in SpotGallery related to this spot
            $spot['images'] = $galleryModel->where('spot_id', $spot['spot_id'])->findColumn('image');

            // Map field names to match your JS expectations
            $spot['id'] = $spot['spot_id'];
            $spot['name'] = $spot['spot_name'];
            $spot['image'] = base_url('uploads/spots/' . $spot['primary_image']);
            $spot['status'] = $spot['status'] ?? 'inactive';
            $spot['price'] = $spot['price_per_person'];
            $spot['maxVisitors'] = $spot['capacity'];
            $spot['openTime'] = $spot['opening_time'];
            $spot['closeTime'] = $spot['closing_time'];
            $spot['rating'] = $spot['rating'] ?? 0;
            $spot['reviews'] = $spot['reviews'] ?? 0;

            // Convert image paths for frontend (if gallery exists)
            if (!empty($spot['images'])) {
                $spot['images'] = array_map(fn($img) => base_url('uploads/spots/gallery/' . $img), $spot['images']);
            }
        }

        return $this->response->setJSON($spots);
    }

    public function getSpot($id)
    {
        $spotModel = new \App\Models\TouristSpotModel();
        $spot = $spotModel->find($id);

        if (!$spot) {
            return $this->response->setJSON(['error' => 'Spot not found']);
        }

        // Optionally include gallery images
        $galleryModel = new \App\Models\SpotGalleryModel();
        $spot['images'] = array_map(fn($g) => base_url('uploads/spots/gallery/' . $g['image']),
                                    $galleryModel->where('spot_id', $id)->findAll());

        return $this->response->setJSON($spot);
    }

    public function getBookings()
    {
        $bookingModel = new BookingModel();
        $businessModel = new BusinessModel(); // Assuming you have one

        $userID = session()->get('UserID');

        // Get the business ID linked to this user
        $businessData = $businessModel->where('user_id', $userID)->first();
        if (!$businessData) {
            return $this->response->setJSON([]);
        }

        $businessID = $businessData['business_id'];

        // Get bookings related to this business
        $bookings = $bookingModel->getBookingsByBusinessID($businessID);

        return $this->response->setJSON($bookings);
    }


    public function getBooking($id)
    {
        $model = new BookingModel();
        $booking = $model->getBookingDetails($id);

        return $this->response->setJSON($booking);
    }

    public function confirmBooking($id)
    {
        $model = new BookingModel();
        $model->update($id, ['booking_status' => 'Confirmed']);
        return $this->response->setJSON(['success' => true]);
    }

    public function rejectBooking($id)
    {
        $model = new BookingModel();
        $model->update($id, ['booking_status' => 'Rejected']);
        return $this->response->setJSON(['success' => true]);
    }



/**
 * API: Get monthly revenue data for chart
 */
public function getMonthlyRevenueData()
{
    $userID = session()->get('UserID');
    $businessModel = new BusinessModel();
    $bookingModel = new BookingModel();

    $businessData = $businessModel->where('user_id', $userID)->first();
    if (!$businessData) {
        return $this->response->setJSON(['error' => 'Business not found']);
    }

    $businessID = $businessData['business_id'];
    $data = $bookingModel->getMonthlyRevenueByBusiness($businessID, 6);

    return $this->response->setJSON($data);
}

/**
 * API: Get weekly revenue data for chart
 */
public function getWeeklyRevenueData()
{
    $userID = session()->get('UserID');
    $businessModel = new BusinessModel();
    $bookingModel = new BookingModel();

    $businessData = $businessModel->where('user_id', $userID)->first();
    if (!$businessData) {
        return $this->response->setJSON(['error' => 'Business not found']);
    }

    $businessID = $businessData['business_id'];
    $data = $bookingModel->getWeeklyRevenueByBusiness($businessID, 8);

    return $this->response->setJSON($data);
}

/**
 * API: Get booking trends data for chart   
 */
public function getBookingTrendsData()
{
    $userID = session()->get('UserID');
    $businessModel = new BusinessModel();
    $bookingModel = new BookingModel();

    $businessData = $businessModel->where('user_id', $userID)->first();
    if (!$businessData) {
        return $this->response->setJSON(['error' => 'Business not found']);
    }

    $businessID = $businessData['business_id'];
    $data = $bookingModel->getBookingTrendsByBusiness($businessID, 6);

    return $this->response->setJSON($data);
}


}
