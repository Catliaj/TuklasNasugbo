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
            if ($primaryImage) {
                if ($primaryImage->isValid() && !$primaryImage->hasMoved()) {
                    $newName = $primaryImage->getRandomName();
                    $uploadPath = FCPATH . 'uploads/spots/';

                    if (!is_dir($uploadPath)) {
                        if (!mkdir($uploadPath, 0777, true) && !is_dir($uploadPath)) {
                            log_message('error', '[storeMySpots] Failed to create upload directory: ' . $uploadPath);
                        }
                    }

                    try {
                        $primaryImage->move($uploadPath, $newName);
                        // verify file exists after move
                        if (is_file($uploadPath . $newName)) {
                            $data['primary_image'] = $newName; // store filename in tourist_spots table
                        } else {
                            log_message('error', '[storeMySpots] primaryImage moved but file not found at destination: ' . $uploadPath . $newName);
                            session()->setFlashdata('spot_image_error', 'Primary image upload failed (file missing after move).');
                        }
                    } catch (\Throwable $t) {
                        log_message('error', '[storeMySpots] Exception while moving primary image: ' . $t->getMessage());
                        session()->setFlashdata('spot_image_error', 'Primary image upload failed: ' . $t->getMessage());
                    }
                } else {
                    // Log the specific upload error code to help debug (production vs local differences)
                    $errCode = method_exists($primaryImage, 'getError') ? $primaryImage->getError() : 'unknown';
                    log_message('warning', "[storeMySpots] primary_image not uploaded or invalid. isValid=" . var_export($primaryImage->isValid(), true) . ", hasMoved=" . var_export($primaryImage->hasMoved(), true) . ", error={$errCode}");
                    session()->setFlashdata('spot_image_error', 'Primary image not uploaded or invalid (code: ' . $errCode . ').');
                }
            }

            // --- Insert tourist spot ---
            $spotId = $touristSpotModel->insert($data);

if (!$spotId) {
    $error = $touristSpotModel->errors();
    log_message('error', '[storeMySpots] Tourist spot insert failed: ' . print_r($error, true));
    return redirect()->back()->with('error', 'Failed to add tourist spot.');
}

// Create notification for admins about new spot submission
try {
    $notificationModel = new \App\Models\NotificationModel();
    $spotName = $data['spot_name'];
    $businessName = $businessData['business_name'] ?? 'Unknown Business';
    
    $notificationModel->notifyAdmins(
        "New tourist spot '$spotName' submitted by $businessName",
        base_url('/admin/attractions')
    );
} catch (\Exception $e) {
    log_message('error', 'Failed to create spot notification: ' . $e->getMessage());
}

// --- Handle gallery images (multiple) ---

            // Notify admin about new pending tourist spot
            try {
                $notifModel = new \App\Models\NotificationModel();
                $notifModel->insert([
                    'user_id' => null,
                    'message' => 'New tourist spot submission: ' . $data['spot_name'],
                    // Keep notifications informational only — do not include clickable link
                    'url' => '',
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            } catch (\Exception $e) {
                log_message('error', 'Failed to insert notification for new spot: ' . $e->getMessage());
            }

            // --- Handle gallery images (multiple) ---
            $galleryImages = $this->request->getFiles();
            if (isset($galleryImages['gallery_images']) && is_array($galleryImages['gallery_images'])) {
                $galleryPath = FCPATH . 'uploads/spots/gallery/';
                if (!is_dir($galleryPath)) {
                    if (!mkdir($galleryPath, 0777, true) && !is_dir($galleryPath)) {
                        log_message('error', '[storeMySpots] Failed to create gallery directory: ' . $galleryPath);
                    }
                }

                foreach ($galleryImages['gallery_images'] as $image) {
                    if (!$image) continue;
                    if ($image->isValid() && !$image->hasMoved()) {
                        $newName = $image->getRandomName();
                        try {
                            $image->move($galleryPath, $newName);
                            if (is_file($galleryPath . $newName)) {
                                // insert to SpotGallery model
                                $spotGalleryModel->insert([
                                    'spot_id' => $spotId,
                                    'image' => $newName
                                ]);
                            } else {
                                log_message('error', '[storeMySpots] Gallery image moved but not found: ' . $galleryPath . $newName);
                            }
                        } catch (\Throwable $t) {
                            log_message('error', '[storeMySpots] Exception while moving gallery image: ' . $t->getMessage());
                        }
                    } else {
                        $err = method_exists($image, 'getError') ? $image->getError() : 'unknown';
                        log_message('warning', "[storeMySpots] gallery image invalid or already moved. error={$err}");
                    }
                }
            }

            // --- Success ---
session()->setFlashdata('spot_added', true);
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
        // ⚠️ use correct column name in your DB, usually business_id
        $spots = $spotModel->where('business_id', $business['business_id'])->findAll();

        // 3️⃣ For each spot, attach gallery images and normalize field names
        foreach ($spots as &$spot) {
            // Get all images in SpotGallery related to this spot
            $spot['images'] = $galleryModel->where('spot_id', $spot['spot_id'])->findColumn('image');

            // Map field names to match your JS expectations
            $spot['id'] = $spot['spot_id'];
            $spot['name'] = $spot['spot_name'];
            $spot['image'] = !empty($spot['primary_image']) ? base_url('uploads/spots/' . $spot['primary_image']) : base_url('uploads/spots/Spot-No-Image.png');
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
    try {
        $spotModel = new \App\Models\TouristSpotModel();
        $spot = $spotModel->find($id);

        if (!$spot) {
            return $this->response->setJSON(['error' => 'Spot not found'])->setStatusCode(404);
        }

        // Optionally include gallery images
        $galleryModel = new \App\Models\SpotGalleryModel();
        $galleryImages = $galleryModel->where('spot_id', $id)->findAll();
        
        $spot['images'] = array_map(
            fn($g) => base_url('uploads/spots/gallery/' . $g['image']),
            $galleryImages
        );

        // Add primary image to response if it exists
        if (!empty($spot['primary_image'])) {
            $spot['image'] = !empty($spot['primary_image']) ? base_url('uploads/spots/' . $spot['primary_image']) : base_url('uploads/spots/Spot-No-Image.png');
        }

        return $this->response->setJSON($spot);
    } catch (\Exception $e) {
        log_message('error', '[getSpot] Error: ' . $e->getMessage());
        return $this->response->setJSON(['error' => 'Failed to load spot'])->setStatusCode(500);
    }
}

public function updateSpot($id)
{
    $session = session();
    $userID = $session->get('UserID');
    
    if (!$userID || !$session->get('isLoggedIn') || $session->get('Role') !== 'Spot Owner') {
        return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
    }

    try {
        $spotModel = new \App\Models\TouristSpotModel();
        $businessModel = new \App\Models\BusinessModel();
        
        // Verify ownership
        $spot = $spotModel->find($id);
        if (!$spot) {
            return $this->response->setJSON(['error' => 'Spot not found'])->setStatusCode(404);
        }
        
        $business = $businessModel->where('user_id', $userID)->first();
        if (!$business || $spot['business_id'] != $business['business_id']) {
            return $this->response->setJSON(['error' => 'Forbidden: You do not own this spot'])->setStatusCode(403);
        }
        
        // Get JSON input
        $input = $this->request->getJSON(true);
        
        // Prepare update data
        $updateData = [
            'spot_name' => $input['spot_name'] ?? $spot['spot_name'],
            'description' => $input['description'] ?? $spot['description'],
            'location' => $input['location'] ?? $spot['location'],
            'price_per_person' => $input['price_per_person'] ?? $spot['price_per_person'],
            'capacity' => $input['capacity'] ?? $spot['capacity'],
            'opening_time' => $input['opening_time'] ?? $spot['opening_time'],
            'closing_time' => $input['closing_time'] ?? $spot['closing_time'],
            'status' => $input['status'] ?? $spot['status'],
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Update the spot
        $updated = $spotModel->update($id, $updateData);
        
        if ($updated === false) {
            $errors = $spotModel->errors() ?: [];
            log_message('error', '[updateSpot] Update failed: ' . print_r($errors, true));
            return $this->response->setJSON(['error' => 'Failed to update spot', 'details' => $errors])->setStatusCode(500);
        }
        
        return $this->response->setJSON(['success' => true, 'message' => 'Spot updated successfully']);
        
    } catch (\Exception $e) {
        log_message('error', '[updateSpot] Exception: ' . $e->getMessage());
        return $this->response->setJSON(['error' => 'Server error while updating spot'])->setStatusCode(500);
    }
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
     * Mark a booking as paid (simple endpoint).
     * Note: In production, validate payment using PayMango webhooks or server-side verification.
     */
    public function markPaymentPaid($id)
    {
        try {
            $model = new BookingModel();
            $model->update($id, [
                'payment_status' => 'Paid',
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            return $this->response->setJSON(['success' => true]);
        } catch (\Exception $e) {
            log_message('error', '[markPaymentPaid] Exception: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Failed to mark payment as paid'])->setStatusCode(500);
        }
    }


    /**
     * Create a PayMango payment session for a booking and return checkout URL.
     * Requires environment variable: PAYMANGO_SECRET (server-side secret)
     * Optional: PAYMANGO_API_BASE (defaults to https://api.paymango.com)
     */
    public function createPaymentSession($id)
    {
        $session = session();
        $userID = $session->get('UserID');
        if (!$userID || !$session->get('isLoggedIn') || $session->get('Role') !== 'Spot Owner') {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $bookingModel = new BookingModel();
        $booking = $bookingModel->getBookingDetails($id);
        if (!$booking) {
            return $this->response->setJSON(['error' => 'Booking not found'])->setStatusCode(404);
        }

        // Verify booking belongs to this user's business
        $businessModel = new BusinessModel();
        $business = $businessModel->where('user_id', $userID)->first();
        if (!$business || ($booking['business_id'] ?? $booking['businessid'] ?? null) != $business['business_id']) {
            return $this->response->setJSON(['error' => 'Forbidden: booking does not belong to your business'])->setStatusCode(403);
        }

        $secret = getenv('PAYMANGO_SECRET') ?: null;
        if (empty($secret)) {
            log_message('error', '[createPaymentSession] PAYMANGO_SECRET not set');
            return $this->response->setJSON(['error' => 'Payment gateway not configured (PAYMANGO_SECRET missing)'])->setStatusCode(500);
        }

        $apiBase = getenv('PAYMANGO_API_BASE') ?: 'https://api.paymango.com';

        // Prefer total_price if available, else fallback to amount/price fields
        $total = isset($booking['total_price']) ? (float)$booking['total_price'] : (float)($booking['amount'] ?? 0);
        // Many gateways expect amount in cents - adapt as needed by your PayMango account
        $amountCents = (int)round($total * 100);

        $successUrl = base_url('/spotowner/bookings?payment=success&booking_id=' . $id);
        $cancelUrl = base_url('/spotowner/bookings?payment=cancel&booking_id=' . $id);

        $payload = [
            'amount' => $amountCents,
            'currency' => 'PHP',
            'reference' => 'booking_' . $id,
            'description' => 'Payment for booking #' . $id,
            'metadata' => [
                'booking_id' => $id
            ],
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl
        ];

        try {
            $ch = curl_init();
            $url = rtrim($apiBase, '/') . '/v1/checkout/sessions';
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Bearer ' . $secret
            ]);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            $resp = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($resp === false) {
                $err = curl_error($ch);
                curl_close($ch);
                log_message('error', '[createPaymentSession] cURL error: ' . $err);
                return $this->response->setJSON(['error' => 'Failed to contact payment provider'])->setStatusCode(502);
            }
            curl_close($ch);

            $json = json_decode($resp, true);
            if ($httpCode >= 200 && $httpCode < 300) {
                // Try several possible response shapes for checkout URL
                $checkoutUrl = $json['checkout_url'] ?? $json['data']['checkout_url'] ?? $json['data']['url'] ?? $json['redirect_url'] ?? null;
                if ($checkoutUrl) {
                    return $this->response->setJSON(['checkout_url' => $checkoutUrl]);
                }
                // If no checkout_url found, return raw response for debugging
                return $this->response->setJSON(['error' => 'Unexpected provider response', 'response' => $json])->setStatusCode(502);
            }

            log_message('error', '[createPaymentSession] Provider returned HTTP ' . $httpCode . ' body: ' . $resp);
            return $this->response->setJSON(['error' => 'Payment provider error', 'details' => $json])->setStatusCode(502);
        } catch (\Throwable $e) {
            log_message('error', '[createPaymentSession] Exception: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Server error while creating payment session'])->setStatusCode(500);
        }
    }


    /**
     * Webhook endpoint for PayMango to notify payment events.
     * Verifies signature with PAYMANGO_WEBHOOK_SECRET and marks bookings paid.
     */
    public function paymangoWebhook()
    {
        $secret = getenv('PAYMANGO_WEBHOOK_SECRET') ?: null;
        if (empty($secret)) {
            log_message('error', '[paymangoWebhook] PAYMANGO_WEBHOOK_SECRET not set');
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Webhook secret not configured']);
        }

        $raw = file_get_contents('php://input');
        $sig = $this->request->getHeaderLine('X-Paymango-Signature') ?: $this->request->getHeaderLine('X-Signature') ?: $this->request->getHeaderLine('Signature');
        if (empty($sig)) {
            log_message('warning', '[paymangoWebhook] Missing signature header');
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Missing signature']);
        }

        $expected = hash_hmac('sha256', $raw, $secret);
        if (!hash_equals($expected, $sig)) {
            log_message('warning', '[paymangoWebhook] Signature mismatch');
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid signature']);
        }

        $event = json_decode($raw, true);
        if (!$event) {
            log_message('warning', '[paymangoWebhook] Invalid JSON payload');
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid payload']);
        }

        // normalize event data
        $type = $event['type'] ?? $event['event'] ?? null;
        $data = $event['data']['object'] ?? $event['data'] ?? $event['object'] ?? $event;

        // find booking id: prefer metadata.booking_id, then parse reference
        $bookingId = $data['metadata']['booking_id'] ?? null;
        if (!$bookingId && !empty($data['reference'])) {
            if (strpos($data['reference'], 'booking_') === 0) {
                $bookingId = (int)substr($data['reference'], 8);
            }
        }

        // determine if this is a successful payment event
        $status = strtolower($data['status'] ?? $data['payment_status'] ?? '');
        $isPaid = false;
        if (strpos($type ?? '', 'payment') !== false || strpos($type ?? '', 'checkout') !== false) {
            if (in_array($status, ['paid', 'succeeded', 'completed'])) $isPaid = true;
        }
        // also accept explicit flags in payload
        if (!$isPaid && (isset($data['paid']) && $data['paid'] == true)) $isPaid = true;

        if ($isPaid && $bookingId) {
            try {
                $db = \Config\Database::connect();
                $update = [
                    'payment_status' => 'Paid',
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                // try to store provider txn id if present
                if (!empty($data['id'])) $update['payment_provider_txn_id'] = $data['id'];
                if (!empty($data['transaction_id'])) $update['payment_provider_txn_id'] = $data['transaction_id'];
                if (!empty($data['payment_id'])) $update['payment_provider_txn_id'] = $data['payment_id'];
                $update['payment_received_at'] = date('Y-m-d H:i:s');

                $db->table('bookings')->where('booking_id', $bookingId)->update($update);
                log_message('info', '[paymangoWebhook] Marked booking ' . $bookingId . ' as Paid');
                return $this->response->setStatusCode(200)->setJSON(['success' => true]);
            } catch (\Throwable $e) {
                log_message('error', '[paymangoWebhook] DB update error: ' . $e->getMessage());
                return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to update booking']);
            }
        }

        log_message('info', '[paymangoWebhook] Ignored event type: ' . json_encode($event));
        return $this->response->setStatusCode(200)->setJSON(['ignored' => true]);
    }



/**
 * API: Get monthly revenue data for chart
 */
/**
 * API: Get monthly revenue data for chart
 */
public function getMonthlyRevenueData()
{
    $userID = session()->get('UserID');
    
    if (!$userID) {
        return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
    }

    try {
        $db = \Config\Database::connect();
        
        // Get business_id
        $business = $db->table('businesses')
            ->where('user_id', $userID)
            ->get()
            ->getRow();
        
        if (!$business) {
            return $this->response->setJSON([]);
        }
        
        $businessID = $business->business_id;
        
        // Get last 6 months revenue with proper month names
        $query = $db->query("
            SELECT 
                DATE_FORMAT(b.booking_date, '%Y-%m') as month,
                DATE_FORMAT(b.booking_date, '%b %Y') as month_name,
                SUM(b.total_price) as revenue,
                COUNT(b.booking_id) as bookings
            FROM bookings b
            INNER JOIN tourist_spots ts ON b.spot_id = ts.spot_id
            WHERE ts.business_id = ?
                AND b.booking_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                AND b.booking_status IN ('Confirmed', 'Completed', 'Checked-in', 'Checked-out', 'Checked-In', 'Checked-Out')
                AND b.payment_status = 'Paid'
            GROUP BY DATE_FORMAT(b.booking_date, '%Y-%m')
            ORDER BY month ASC
        ", [$businessID]);
        
        $results = $query->getResultArray();
        
        return $this->response->setJSON($results);
        
    } catch (\Exception $e) {
        log_message('error', 'Monthly revenue error: ' . $e->getMessage());
        return $this->response->setJSON(['error' => 'Failed to fetch revenue data'])
            ->setStatusCode(500);
    }
}

/**
 * API: Get weekly revenue data for chart
 */
public function getWeeklyRevenueData()
{
    $userID = session()->get('UserID');
    
    if (!$userID) {
        return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
    }

    try {
        $db = \Config\Database::connect();
        
        // Get business_id
        $business = $db->table('businesses')
            ->where('user_id', $userID)
            ->get()
            ->getRow();
        
        if (!$business) {
            return $this->response->setJSON([]);
        }
        
        $businessID = $business->business_id;
        
        // Get last 8 weeks revenue
        $query = $db->query("
            SELECT 
                DATE_FORMAT(b.booking_date, '%Y-%u') as week,
                DATE_FORMAT(DATE_SUB(b.booking_date, INTERVAL WEEKDAY(b.booking_date) DAY), '%b %d') as week_start,
                DATE_FORMAT(DATE_ADD(DATE_SUB(b.booking_date, INTERVAL WEEKDAY(b.booking_date) DAY), INTERVAL 6 DAY), '%b %d') as week_end,
                SUM(b.total_price) as revenue,
                COUNT(b.booking_id) as bookings
            FROM bookings b
            INNER JOIN tourist_spots ts ON b.spot_id = ts.spot_id
            WHERE ts.business_id = ?
                AND b.booking_date >= DATE_SUB(CURDATE(), INTERVAL 8 WEEK)
                AND b.booking_status IN ('Confirmed', 'Completed', 'Checked-in', 'Checked-out', 'Checked-In', 'Checked-Out')
                AND b.payment_status = 'Paid'
            GROUP BY DATE_FORMAT(b.booking_date, '%Y-%u')
            ORDER BY week ASC
        ", [$businessID]);
        
        $results = $query->getResultArray();
        
        return $this->response->setJSON($results);
        
    } catch (\Exception $e) {
        log_message('error', 'Weekly revenue error: ' . $e->getMessage());
        return $this->response->setJSON(['error' => 'Failed to fetch weekly data'])
            ->setStatusCode(500);
    }
}

/**
 * API: Get booking trends data for chart   
 */
public function getBookingTrendsData()
{
    $userID = session()->get('UserID');
    
    if (!$userID) {
        return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
    }

    try {
        $db = \Config\Database::connect();
        
        // Get business_id
        $business = $db->table('businesses')
            ->where('user_id', $userID)
            ->get()
            ->getRow();
        
        if (!$business) {
            return $this->response->setJSON([]);
        }
        
        $businessID = $business->business_id;
        
        // Get last 6 months booking trends
        $query = $db->query("
            SELECT 
                DATE_FORMAT(b.booking_date, '%Y-%m') as month,
                DATE_FORMAT(b.booking_date, '%b %Y') as month_name,
                COUNT(b.booking_id) as bookings
            FROM bookings b
            INNER JOIN tourist_spots ts ON b.spot_id = ts.spot_id
            WHERE ts.business_id = ?
                AND b.booking_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
            GROUP BY DATE_FORMAT(b.booking_date, '%Y-%m')
            ORDER BY month ASC
        ", [$businessID]);
        
        $results = $query->getResultArray();
        
        return $this->response->setJSON($results);
        
    } catch (\Exception $e) {
        log_message('error', 'Booking trends error: ' . $e->getMessage());
        return $this->response->setJSON(['error' => 'Failed to fetch booking trends'])
            ->setStatusCode(500);
    }
}

/**
 * Get dashboard analytics overview
 */
public function getDashboardAnalytics()
{
    $userId = session()->get('UserID');
    
    if (!$userId) {
        return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
    }

    try {
        $db = \Config\Database::connect();
        
        // Get business_id for the logged-in user
        $business = $db->table('businesses')
            ->where('user_id', $userId)
            ->get()
            ->getRow();
        
        if (!$business) {
            return $this->response->setJSON([
                'totalSpots' => 0,
                'totalBookings' => 0,
                'totalRevenue' => 0,
                'averageRating' => 0
            ]);
        }
        
        $businessId = $business->business_id;
        
        // Get total spots
        $totalSpots = $db->table('tourist_spots')
            ->where('business_id', $businessId)
            ->where('status', 'approved')
            ->countAllResults();
        
        // Get total bookings (this month)
        $currentMonth = date('Y-m');
        $totalBookings = $db->table('bookings b')
            ->join('tourist_spots ts', 'b.spot_id = ts.spot_id')
            ->where('ts.business_id', $businessId)
            ->where('DATE_FORMAT(b.booking_date, "%Y-%m")', $currentMonth)
            ->whereIn('b.booking_status', ['Confirmed', 'Completed', 'Checked-in', 'Checked-out', 'Checked-In', 'Checked-Out'])
            ->countAllResults();
        
        // Get total revenue (this month)
        $revenueQuery = $db->table('bookings b')
            ->select('SUM(b.total_price) as total_revenue')
            ->join('tourist_spots ts', 'b.spot_id = ts.spot_id')
            ->where('ts.business_id', $businessId)
            ->where('DATE_FORMAT(b.booking_date, "%Y-%m")', $currentMonth)
            ->whereIn('b.booking_status', ['Confirmed', 'Completed', 'Checked-in', 'Checked-out', 'Checked-In', 'Checked-Out'])
            ->where('b.payment_status', 'Paid')
            ->get()
            ->getRow();
        
        $totalRevenue = $revenueQuery ? (float)$revenueQuery->total_revenue : 0;
        
        // Get average rating across all spots
        $ratingQuery = $db->table('review_feedback rf')
            ->select('AVG(rf.rating) as avg_rating')
            ->join('tourist_spots ts', 'rf.spot_id = ts.spot_id')
            ->where('ts.business_id', $businessId)
            ->where('rf.status', 'Approved')
            ->get()
            ->getRow();
        
        $averageRating = $ratingQuery && $ratingQuery->avg_rating ? 
            round((float)$ratingQuery->avg_rating, 1) : 0;
        
        return $this->response->setJSON([
            'totalSpots' => $totalSpots,
            'totalBookings' => $totalBookings,
            'totalRevenue' => $totalRevenue,
            'averageRating' => $averageRating
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Dashboard analytics error: ' . $e->getMessage());
        return $this->response->setJSON(['error' => 'Failed to fetch analytics'])
            ->setStatusCode(500);
    }
}

/**
 * Get spot-specific analytics for each tourist spot
 */
public function getSpotAnalytics($spotId)
{
    $userId = session()->get('UserID');
    
    if (!$userId) {
        return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
    }

    try {
        $db = \Config\Database::connect();
        
        // Verify ownership
        $spot = $db->table('tourist_spots ts')
            ->join('businesses b', 'ts.business_id = b.business_id')
            ->where('ts.spot_id', $spotId)
            ->where('b.user_id', $userId)
            ->get()
            ->getRow();
        
        if (!$spot) {
            return $this->response->setJSON(['error' => 'Unauthorized access'])
                ->setStatusCode(403);
        }
        
        // Get bookings count
        $bookings = $db->table('bookings')
            ->where('spot_id', $spotId)
            ->whereIn('booking_status', ['Confirmed', 'Completed', 'Checked-in', 'Checked-out', 'Checked-In', 'Checked-Out'])
            ->countAllResults();
        
        // Get revenue
        $revenueQuery = $db->table('bookings')
            ->select('SUM(total_price) as revenue')
            ->where('spot_id', $spotId)
            ->whereIn('booking_status', ['Confirmed', 'Completed', 'Checked-in', 'Checked-out', 'Checked-In', 'Checked-Out'])
            ->where('payment_status', 'Paid')
            ->get()
            ->getRow();
        
        $revenue = $revenueQuery ? (float)$revenueQuery->revenue : 0;
        
        // Get visitors count
        $visitorsQuery = $db->table('bookings')
            ->select('SUM(total_guests) as visitors')
            ->where('spot_id', $spotId)
            ->whereIn('booking_status', ['Completed', 'Checked-out', 'Checked-Out'])
            ->get()
            ->getRow();
        
        $visitors = $visitorsQuery ? (int)$visitorsQuery->visitors : 0;
        
        // Get rating
        $ratingQuery = $db->table('review_feedback')
            ->select('AVG(rating) as avg_rating, COUNT(review_id) as review_count')
            ->where('spot_id', $spotId)
            ->where('status', 'Approved')
            ->get()
            ->getRow();
        
        $rating = $ratingQuery && $ratingQuery->avg_rating ? 
            round((float)$ratingQuery->avg_rating, 1) : 0;
        $reviews = $ratingQuery ? (int)$ratingQuery->review_count : 0;
        
        return $this->response->setJSON([
            'bookings' => $bookings,
            'revenue' => $revenue,
            'visitors' => $visitors,
            'rating' => $rating,
            'reviews' => $reviews
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Spot analytics error: ' . $e->getMessage());
        return $this->response->setJSON(['error' => 'Failed to fetch spot analytics'])
            ->setStatusCode(500);
    }
}


// ============================================
// NOTIFICATION METHODS
// ============================================

public function getUnreadNotificationCount()
{
    $notificationModel = new \App\Models\NotificationModel();
    
    // Get the logged-in spot owner's user_id from session
    $userId = session()->get('UserID');
    
    if (!$userId) {
        return $this->response->setJSON(['count' => 0]);
    }
    
    $count = $notificationModel->getUnreadCount($userId);
    
    return $this->response->setJSON(['count' => $count]);
}

public function getNotifications()
{
    $notificationModel = new \App\Models\NotificationModel();
    
    // Get the logged-in spot owner's user_id from session
    $userId = session()->get('UserID');
    
    if (!$userId) {
        return $this->response->setJSON([]);
    }
    
    $notifications = $notificationModel->getUserNotifications($userId, 20);
    
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
    
    // Get the logged-in spot owner's user_id from session
    $userId = session()->get('UserID');
    
    if (!$userId) {
        return $this->response->setJSON(['success' => false]);
    }
    
    $result = $notificationModel->markAllAsRead($userId);
    
    return $this->response->setJSON(['success' => $result]);
}

}
