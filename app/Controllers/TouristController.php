<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UsersModel;
use App\Models\TouristSpotModel;

class TouristController extends BaseController
{
public function viewSpot($spot_id)
{
    // Normalize incoming id
    $spotId = (int) $spot_id;
    if ($spotId <= 0) {
        return $this->response->setJSON(['error' => 'Invalid spot id'], 400);
    }

    $session = session();
    $userID = $session->get('UserID') ?? null;
    $ipAddress = $this->request->getIPAddress(); // for anonymous dedupe if supported

    // 1) Record view in spot_view_logs (if table exists) with dedupe
    try {
        $db = \Config\Database::connect();
        if ($db->tableExists('spot_view_logs')) {
            $logModel = new \App\Models\SpotViewLogModel();

            // Determine which dedupe columns are available
            $fields = $db->getFieldNames('spot_view_logs');

            // Dedup window (adjust as needed)
            $dedupeWindow = '-24 hours';
            $since = date('Y-m-d H:i:s', strtotime($dedupeWindow));

            $shouldInsert = true;

            // If table has user_id and we have a logged-in user -> dedupe by user_id
            if (in_array('user_id', $fields, true) && $userID) {
                $existing = $logModel
                    ->where('spot_id', $spotId)
                    ->where('user_id', $userID)
                    ->where('viewed_at >=', $since)
                    ->first();
                if ($existing) {
                    $shouldInsert = false;
                }
            }
            // Else if table has ip_address column -> dedupe by IP for anonymous or all users if desired
            elseif (in_array('ip_address', $fields, true) && $ipAddress) {
                $existing = $logModel
                    ->where('spot_id', $spotId)
                    ->where('ip_address', $ipAddress)
                    ->where('viewed_at >=', $since)
                    ->first();
                if ($existing) {
                    $shouldInsert = false;
                }
            }
            // Otherwise no suitable dedupe column -> always insert

            if ($shouldInsert) {
                // Build insert data only with allowed fields present in table
                $insertData = ['spot_id' => $spotId, 'viewed_at' => date('Y-m-d H:i:s')];

                if (in_array('user_id', $fields, true)) {
                    $insertData['user_id'] = $userID;
                }
                if (in_array('ip_address', $fields, true)) {
                    $insertData['ip_address'] = $ipAddress;
                }

                // Insert (wrapped in try/catch to avoid breaking the response on DB errors)
                try {
                    $logModel->insert($insertData);
                } catch (\Throwable $e) {
                    // log error and continue
                    log_message('error', 'Failed to insert spot view log: ' . $e->getMessage());
                }
            }
        }
    } catch (\Throwable $e) {
        // Don't fail the whole request if logging or schema inspection fails — just continue
        log_message('error', 'Failed to record spot view (dedupe process): ' . $e->getMessage());
    }

    // 2) Fetch spot details + gallery for frontend modal
    $spotModel = new \App\Models\TouristSpotModel();
    $result = $spotModel->getSpotDetailsWithGallery((int)$spotId);
    if ($result['status'] === 'error') {
        return $this->response->setJSON(['error' => $result['message']], 404);
    }
    $spot = $result['data'];
    $gallery = $spot['gallery'] ?? [];
    unset($spot['gallery']);

    return $this->response->setJSON([
        'spot'    => $spot,
        'gallery' => $gallery
    ]);
}


public function touristDashboard()
{
    $session = session();
    $userID = $session->get('UserID');
    if (!$userID) {
        // Redirect to login or show empty dashboard
        return redirect()->to('/users/login');
    }

    $db = \Config\Database::connect();

    // --- 1) Saved itineraries (via user preferences + itineraries) ---
    $TotalSaveItineray = 0;
    try {
        $preferencesModel = new \App\Models\UserPreferenceModel();
        $itineraryModel = new \App\Models\ItineraryModel();

        $preference = $preferencesModel->where('user_id', $userID)->first();
        $preferenceID = $preference['preference_id'] ?? null;

        if ($preferenceID) {
            // countDistinctDates is your model helper — leave as is; ensure it exists
            $TotalSaveItineray = (int) $itineraryModel->countDistinctDates($preferenceID);
        }
    } catch (\Exception $e) {
        $TotalSaveItineray = 0;
    }

    // --- 2) Places visited ---
    $placesVisited = 0;
    try {
        // common table name in your code: createuservisits
        if ($db->tableExists('createuservisits')) {
            $placesVisited = (int) $db->table('createuservisits')->where('user_id', $userID)->countAllResults();
        } else {
            $placesVisited = 0;
        }
    } catch (\Exception $e) {
        $placesVisited = 0;
    }

    // --- 3) Favorite spots ---
    $favoriteCount = 0;
    try {
        // Try variants; pick the one that stores user_id/customer_id consistently in your app
        if ($db->tableExists('spot_fav_by_customer')) {
            $favoriteCount = (int) $db->table('spot_fav_by_customer')->where('user_id', $userID)->countAllResults();
        } elseif ($db->tableExists('user_favorites')) {
            $favoriteCount = (int) $db->table('user_favorites')->where('user_id', $userID)->countAllResults();
        } elseif ($db->tableExists('favorites')) {
            $favoriteCount = (int) $db->table('favorites')->where('user_id', $userID)->countAllResults();
        } else {
            $favoriteCount = 0;
        }
    } catch (\Exception $e) {
        $favoriteCount = 0;
    }

    // --- 4) Upcoming bookings ---
    $upcomingBookings = 0;
    try {
        $today = date('Y-m-d');
        $bookingModel = new \App\Models\BookingModel();
        // Determine whether bookings table uses customer_id or user_id
        $bookingTable = $bookingModel->getTable() ?? 'bookings';
        $usesCustomerId = $db->tableExists('bookings') && $db->getFieldData('bookings') ? null : null;
        // safe approach: check whether bookings table has column 'customer_id'
        $fields = $db->getFieldNames('bookings');
        if (in_array('customer_id', $fields, true)) {
            // need customer_id associated with userID (resolve from customers table)
            $customerID = null;
            try {
                $customerModel = new \App\Models\CustomerModel();
                $customerRow = $customerModel->where('user_id', $userID)->first();
                $customerID = $customerRow['customer_id'] ?? null;
            } catch (\Exception $e) {
                $customerID = null;
            }
            if ($customerID) {
                $upcomingBookings = (int) $bookingModel
                    ->where('customer_id', $customerID)
                    ->where('visit_date >=', $today)
                    ->where('booking_status !=', 'cancelled')
                    ->countAllResults();
            } else {
                // fallback to checking user_id column if exists
                if (in_array('user_id', $fields, true)) {
                    $upcomingBookings = (int) $bookingModel
                        ->where('user_id', $userID)
                        ->where('visit_date >=', $today)
                        ->where('booking_status !=', 'cancelled')
                        ->countAllResults();
                } else {
                    $upcomingBookings = 0;
                }
            }
        } else {
            // bookings table does not have customer_id (maybe uses user_id)
            if (in_array('user_id', $fields, true)) {
                $upcomingBookings = (int) $bookingModel
                    ->where('user_id', $userID)
                    ->where('visit_date >=', $today)
                    ->where('booking_status !=', 'cancelled')
                    ->countAllResults();
            } else {
                $upcomingBookings = 0;
            }
        }
    } catch (\Exception $e) {
        $upcomingBookings = 0;
    }

    // --- 5) Popular spots (top viewed or recent) ---
    $popularSpots = [];
    try {
        $spotModel = new \App\Models\TouristSpotModel();
        // prefer a view log table if available
        if ($db->tableExists('spot_view_log') || $db->tableExists('spotviewlogs') || $db->tableExists('spot_view_logs')) {
            $logTable = $db->tableExists('spot_view_log') ? 'spot_view_log' : ($db->tableExists('spotviewlogs') ? 'spotviewlogs' : 'spot_view_logs');
            $rows = $db->table($logTable)
                ->select('spot_id, COUNT(*) AS views')
                ->groupBy('spot_id')
                ->orderBy('views', 'DESC')
                ->limit(6)
                ->get()
                ->getResultArray();

            foreach ($rows as $r) {
                $s = $spotModel->where('spot_id', $r['spot_id'])->first();
                if ($s) {
                    $s['views'] = $r['views'];
                    $popularSpots[] = $s;
                }
            }
        } else {
            // fallback to recent 6 spots
            $popularSpots = array_slice($spotModel->orderBy('created_at', 'DESC')->findAll(), 0, 6);
        }
    } catch (\Exception $e) {
        // fallback
        try {
            $spotModel = new \App\Models\TouristSpotModel();
            $popularSpots = array_slice($spotModel->orderBy('created_at', 'DESC')->findAll(), 0, 6);
        } catch (\Exception $e2) {
            $popularSpots = [];
        }
    }

    // --- Finally render view with data ---
    return view('Pages/tourist/dashboard', [
        'userID'            => $userID,
        'preferenceID'      => $preference['preference_id'] ?? null,
        'FullName'          => $session->get('FirstName') . ' ' . $session->get('LastName'),
        'email'             => $session->get('Email'),
        'TotalSaveItineray' => $TotalSaveItineray,
        'placesVisited'     => $placesVisited,
        'favoriteCount'     => $favoriteCount,
        'upcomingBookings'  => $upcomingBookings,
        'popularSpots'      => $popularSpots,
    ]);
}


    public function exploreSpots()
    {
        $spotModel = new TouristSpotModel();
        $spots = $spotModel->getAllTouristSpots();

        // Get user's favorite spot IDs
        $userID = session()->get('UserID');
        $favoriteSpotIds = [];
        $db = \Config\Database::connect();
        if ($db->tableExists('spot_fav_by_customer') && $userID) {
            $favoriteSpotIds = $db->table('spot_fav_by_customer')
                ->select('spot_id')
                ->where('user_id', $userID)
                ->get()
                ->getResultArray();
            $favoriteSpotIds = array_column($favoriteSpotIds, 'spot_id');
        }

        return view('Pages/tourist/explore', [
            'userID' => $userID,
            'FullName' => session()->get('FirstName') . ' ' . session()->get('LastName'),
            'email' => session()->get('Email'),
            'spots' => $spots,
            'favoriteSpotIds' => $favoriteSpotIds,
        ]);
    }

    //add a view spot and will get the  spot details and the gallery images

    

    public function myBookings()
    {
        $session = session();
        $userID = $session->get('UserID');

        // Get customer_id for this user
        $customerModel = new \App\Models\CustomerModel();
        $customer = $customerModel->where('user_id', $userID)->first();
        $customerID = $customer['customer_id'] ?? null;

        $bookings = [];
        if ($customerID) {
            $bookingModel = new \App\Models\BookingModel();
            $bookings = $bookingModel
                 ->select('bookings.*, ts.spot_name, ts.category')
                ->join('tourist_spots ts', 'bookings.spot_id = ts.spot_id', 'left')
                ->where('bookings.customer_id', $userID)
                ->orderBy('bookings.booking_date', 'DESC')
                ->findAll();
        }

        return view('Pages/tourist/bookings', [
            'userID' => $userID,
            'FullName' => $session->get('FirstName') . ' ' . $session->get('LastName'),
            'email' => $session->get('Email'),
            'bookings' => $bookings,
        ]);
    }

    public function touristProfile()
    {

        return view('Pages/tourist/profile', [
            'userID' => session()->get('UserID'),
            'FullName' => session()->get('FirstName') . ' ' . session()->get('LastName'),
            'email' => session()->get('Email'),
        ]);
    }

    public function touristIternary()
    {
        $usermodel = new \App\Models\UsersModel();
        $userID = session()->get('UserID');

        // Get the preference string (format: "History,Adventure")
        $categories = $usermodel->getUserCategoryString($userID);

  
        return view('Pages/tourist/itinerary', [
            'userID'     => $userID,
            'FullName'   => session()->get('FirstName') . ' ' . session()->get('LastName'),
            'email'      => session()->get('Email'),
            'categories' => $categories,
        ]);
    }


    public function touristReviews()
    {
        return view('Pages/tourist/reviews', [
            'userID' => session()->get('UserID'),
            'FullName' => session()->get('FirstName') . ' ' . session()->get('LastName'),
            'email' => session()->get('Email'),
        ]);
    }

    public function touristVisits()
    {
        return view('Pages/tourist/visited', [
            'userID' => session()->get('UserID'),
            'FullName' => session()->get('FirstName') . ' ' . session()->get('LastName'),
            'email' => session()->get('Email'),
        ]);
    }
    public function touristBudget()
    {
        return view('Pages/tourist/budget', [
            'userID' => session()->get('UserID'),
            'FullName' => session()->get('FirstName') . ' ' . session()->get('LastName'),
            'email' => session()->get('Email'),
        ]);
    }

    public function touristFavorites()
    {
        return view('Pages/tourist/favorites', [
            'userID' => session()->get('UserID'),
            'FullName' => session()->get('FirstName') . ' ' . session()->get('LastName'),
            'email' => session()->get('Email'),
        ]);
    }

    //goods
    public function getTrip()
    {
        $userID = session()->get('UserID');
        $tripTitle = $this->request->getGet('trip_title');
        $startDate = $this->request->getGet('start_date');

        if (!$userID || !$tripTitle || !$startDate) {
            return $this->response->setJSON([
                'error' => 'Missing trip_title or start_date'
            ], 400);
        }

        $db = \Config\Database::connect();
        // Fetch all itinerary items for this trip
        $rows = $db->query(
            "SELECT 
                i.itinerary_id,
                i.day,
                i.spot_id,
                i.description,
                ts.spot_name,
                ts.category,
                ts.location,
                ts.price_per_person,
                ts.child_price,
                ts.senior_price,
                ts.latitude,
                ts.longitude,
                i.trip_title,
                i.start_date,
                i.end_date,
                i.adults,
                i.children,
                i.seniors,
                i.budget
            FROM itinerary i
            LEFT JOIN tourist_spots ts ON i.spot_id = ts.spot_id
            INNER JOIN user_preferences up ON i.preference_id = up.preference_id
            WHERE up.user_id = ? AND i.trip_title = ? AND i.start_date = ?
            ORDER BY i.day ASC, i.itinerary_id ASC",
            [$userID, $tripTitle, $startDate]
        )->getResultArray();

        if (empty($rows)) {
            return $this->response->setJSON([
                'error' => 'Trip not found',
                'itinerary' => []
            ], 404);
        }

        // Group by day
        $grouped = [];
        $tripInfo = [
            'trip_title' => $rows[0]['trip_title'],
            'start_date' => $rows[0]['start_date'],
            'end_date' => $rows[0]['end_date'],
            'adults' => $rows[0]['adults'],
            'children' => $rows[0]['children'],
            'seniors' => $rows[0]['seniors'],
            'budget' => $rows[0]['budget'],
        ];

        foreach ($rows as $row) {
            $day = $row['day'];
            if (!isset($grouped[$day])) {
                $grouped[$day] = [];
            }

            $grouped[$day][] = [
                'itinerary_id' => $row['itinerary_id'],
                'spot_id' => $row['spot_id'],
                'name' => $row['spot_name'],
                'category' => $row['category'],
                'location' => $row['location'],
                'price_per_person' => $row['price_per_person'],
                'child_price' => $row['child_price'],
                'senior_price' => $row['senior_price'],
                'lat' => $row['latitude'],
                'lng' => $row['longitude'],
                'description' => $row['description'],
            ];
        }

        // Convert to array format
        $itinerary = [];
        foreach ($grouped as $day => $spots) {
            $itinerary[] = [
                'day' => $day,
                'spots' => $spots
            ];
        }

        return $this->response->setJSON([
            'trip_info' => $tripInfo,
            'itinerary' => $itinerary
        ]);
    }



   // Return a list of saved trips for the current user (grouped by title and dates)
public function listUserTrips()
{
    $userID = session()->get('UserID');
    if (!$userID) {
        return $this->response->setJSON([
            'error' => 'Not logged in',
            'trips' => []
        ], 401);
    }

    $db = \Config\Database::connect();
    $sql = "
        SELECT 
            i.trip_title,
            i.start_date,
            i.end_date,
            COUNT(i.itinerary_id) AS spot_count,
            MIN(i.created_at) AS created_at
        FROM itinerary i
        INNER JOIN user_preferences up 
            ON i.preference_id = up.preference_id
        WHERE up.user_id = ?
        GROUP BY i.trip_title, i.start_date, i.end_date
        ORDER BY i.start_date DESC
    ";

    $rows = $db->query($sql, [$userID])->getResultArray();

    if (empty($rows)) {
        return $this->response->setJSON([
            'trips' => [],
            'message' => 'No saved trips found'
        ]);
    }

    $trips = [];
    foreach ($rows as $row) {
        $trips[] = [
            'trip_title' => $row['trip_title'] ?? 'Untitled Trip',
            'start_date' => $row['start_date'],
            'end_date'   => $row['end_date'],
            'spot_count' => $row['spot_count'],
            'created_at' => $row['created_at'],
        ];
    }

    return $this->response->setJSON([
        'trips' => $trips,
        'count' => count($trips)
    ]);
}


    // Toggle favorite for current tourist (adds/removes entry)
    public function toggleFavorite()
    {
        $session = session();
        $userID = $session->get('UserID');
        if (!$userID) {
            return $this->response->setJSON(['error' => 'Not logged in'], 401);
        }

        $input = $this->request->getJSON(true);
        $spotId = $input['spot_id'] ?? $this->request->getPost('spot_id');
        $action = $input['action'] ?? $this->request->getPost('action') ?? 'add';

        if (!$spotId) {
            return $this->response->setJSON(['error' => 'Missing spot_id'], 400);
        }

        $db = \Config\Database::connect();

        // Prefer dedicated model/table if exists
        try {
            if ($db->tableExists('spot_fav_by_customer')) {
                $model = new \App\Models\SpotFavByCustomerModel();
                if ($action === 'add') {
                    // avoid duplicates
                    $exists = $model->where('user_id', $userID)->where('spot_id', $spotId)->first();
                    if (!$exists) {
                        $model->insert([
                            'user_id' => $userID,
                            'spot_id' => $spotId,
                            'favorited_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                    return $this->response->setJSON(['success' => true, 'message' => 'Added to favorites']);
                } else {
                    $model->where('customer_id', $userID)->where('spot_id', $spotId)->delete();
                    return $this->response->setJSON(['success' => true, 'message' => 'Removed from favorites']);
                }
            }

            // fallback to common table names
            if ($db->tableExists('user_favorites')) {
                $tbl = $db->table('user_favorites');
                if ($action === 'add') {
                    $exists = $tbl->where('user_id', $userID)->where('spot_id', $spotId)->get()->getRowArray();
                    if (!$exists) $tbl->insert(['user_id' => $userID, 'spot_id' => $spotId, 'created_at' => date('Y-m-d H:i:s')]);
                    return $this->response->setJSON(['success' => true, 'message' => 'Added to favorites']);
                } else {
                    $tbl->where('user_id', $userID)->where('spot_id', $spotId)->delete();
                    return $this->response->setJSON(['success' => true, 'message' => 'Removed from favorites']);
                }
            }

            if ($db->tableExists('favorites')) {
                $tbl = $db->table('favorites');
                if ($action === 'add') {
                    $exists = $tbl->where('user_id', $userID)->where('spot_id', $spotId)->get()->getRowArray();
                    if (!$exists) $tbl->insert(['user_id' => $userID, 'spot_id' => $spotId, 'created_at' => date('Y-m-d H:i:s')]);
                    return $this->response->setJSON(['success' => true, 'message' => 'Added to favorites']);
                } else {
                    $tbl->where('user_id', $userID)->where('spot_id', $spotId)->delete();
                    return $this->response->setJSON(['success' => true, 'message' => 'Removed from favorites']);
                }
            }

        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => 'Database error: ' . $e->getMessage()], 500);
        }

        return $this->response->setJSON(['error' => 'Favorites not supported on this installation'], 400);
    }


    // Create a booking (called from tourist booking modal)
    public function createBooking()
    {
        $session = session();
        $userID = $session->get('UserID');
        if (!$userID) {
            return $this->response->setJSON(['error' => 'Not logged in'], 401);
        }

        $input = $this->request->getJSON(true);
        if (!$input) {
            // also accept form data
            $input = $this->request->getPost();
        }


        $spotId = $input['spot_id'] ?? null;
        $visitDate = $input['visit_date'] ?? null;
        $visitTime = $input['visit_time'] ?? null;
        $numAdults = isset($input['num_adults']) ? (int)$input['num_adults'] : 0;
        $numChildren = isset($input['num_children']) ? (int)$input['num_children'] : 0;
        $numSeniors = isset($input['num_seniors']) ? (int)$input['num_seniors'] : 0;
        $specialRequests = $input['special_requests'] ?? null;
        $totalPrice = isset($input['total_price']) ? $input['total_price'] : 0;

        if (!$spotId || !$visitDate) {
            return $this->response->setJSON(['error' => 'Missing required fields'], 400);
        }

        try {
            $bookingModel = new \App\Models\BookingModel();
            $totalGuests = $numAdults + $numChildren + $numSeniors;
            $data = [
                'spot_id' => $spotId,
                'customer_id' => $userID,
                'booking_date' => date('Y-m-d'),
                'visit_date' => $visitDate,
                'visit_time' => $visitTime,
                'num_adults' => $numAdults,
                'num_children' => $numChildren,
                'num_seniors' => $numSeniors,
                'total_guests' => $totalGuests,
                'price_per_person' => $totalGuests ? round($totalPrice / $totalGuests, 2) : null,
                'subtotal' => $totalPrice,
                'total_price' => $totalPrice,
                'booking_status' => 'Pending',
                'payment_status' => 'Unpaid',
                'special_requests' => $specialRequests,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $insertId = $bookingModel->insert($data);
            if ($insertId === false) {
                return $this->response->setJSON(['error' => 'Failed to create booking'], 500);
            }

            return $this->response->setJSON(['success' => true, 'booking_id' => $insertId]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => 'Database error: ' . $e->getMessage()], 500);
        }
    }

    public function viewSpotDetails($spot_id)
    {
        $spotModel = new TouristSpotModel();
        $result = $spotModel->getSpotDetailsWithGallery((int)$spot_id);
        if ($result['status'] === 'error') {
            // Optionally, redirect or show a 404 page
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound($result['message']);
        }
        $spot = $result['data'];
        return view('Pages/tourist/spot_details', [
            'spot' => $spot
        ]);
    }
 public function generateCheckinToken($booking_id)
{
    $session = session();
    $userID = $session->get('UserID');
    if (!$userID) {
        return $this->response->setJSON(['error' => 'Not logged in'], 401);
    }

    $bookingModel = new \App\Models\BookingModel();
    $booking = $bookingModel->find((int)$booking_id);
    if (!$booking) {
        return $this->response->setJSON(['error' => 'Booking not found'], 404);
    }

    // Only allow token generation for confirmed bookings
    $status = strtolower($booking['booking_status'] ?? '');
    if ($status !== 'confirmed') {
        return $this->response->setJSON(['error' => 'Token can be generated only for confirmed bookings'], 403);
    }

    // Verify ownership: booking.customer_id matches session user (or via CustomerModel)
    $bookingOwnerId = $booking['customer_id'] ?? null;
    $isOwner = false;
    if ($bookingOwnerId !== null && (string)$bookingOwnerId === (string)$userID) {
        $isOwner = true;
    } else {
        try {
            $customerModel = new \App\Models\CustomerModel();
            $customerRow = $customerModel->where('user_id', $userID)->first();
            $customerIdForUser = $customerRow['customer_id'] ?? null;
            if ($customerIdForUser !== null && (string)$bookingOwnerId === (string)$customerIdForUser) {
                $isOwner = true;
            }
        } catch (\Throwable $e) {
            // ignore if no CustomerModel
        }
    }

    if (!$isOwner) {
        return $this->response->setJSON(['error' => 'Forbidden: you do not own this booking'], 403);
    }

    // Normalize visit_date to Y-m-d (try visit_date, booking_date, date)
    $visit_date_raw = $booking['visit_date'] ?? $booking['booking_date'] ?? $booking['date'] ?? null;
    $visit_date = $visit_date_raw ? date('Y-m-d', strtotime($visit_date_raw)) : null;

    // Optional: require token generation only for same-day bookings
    // if ($visit_date !== date('Y-m-d')) {
    //     return $this->response->setJSON(['error' => 'Token can only be generated on the booking date'])->setStatusCode(400);
    // }

    // Build payload
    try {
        $jti = bin2hex(random_bytes(8));
    } catch (\Throwable $e) {
        $jti = uniqid('', true);
    }

    $payload = [
        'type'        => 'checkin_token',                      // consistent type
        'booking_id'  => (int) $booking_id,
        'customer_id' => isset($booking['customer_id']) ? (int)$booking['customer_id'] : null,
        'spot_id'     => isset($booking['spot_id']) ? (int)$booking['spot_id'] : null,
        // include visit_date (canonical) and booking_date for backward compatibility
        'visit_date'  => $visit_date,                          // normalized Y-m-d
        'booking_date'=> $visit_date,                          // alias for older clients (optional)
        'iat'         => time(),
        'exp'         => time() + 60 * 60 * 24,                // 24h expiry (adjust if needed)
        'jti'         => $jti
    ];

    $payloadJson = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    if ($payloadJson === false) {
        return $this->response->setJSON(['error' => 'Failed to create token payload'], 500);
    }

    // URL-safe base64 encode
    $b64 = rtrim(strtr(base64_encode($payloadJson), '+/', '-_'), '=');

    $secret = getenv('VOUCHER_SECRET') ?: (getenv('app.secret') ?: 'change_this_secret');
    $signature = hash_hmac('sha256', $b64, $secret);

    // token format: <base64url(payload)>.<hex-hmac>
    $token = $b64 . '.' . $signature;

    return $this->response->setJSON([
        'token' => $token,
        'expires_at' => date('c', $payload['exp'])
    ]);
}
// Replace verifyCheckinToken with this implementation.

public function verifyCheckinToken()
{
    $input = $this->request->getJSON(true) ?: $this->request->getPost();
    $token = $input['token'] ?? null;
    if (!$token) {
        return $this->response->setJSON(['valid' => false, 'error' => 'Missing token'])->setStatusCode(400);
    }

    // Expect token as "<b64>.<signature>"
    if (!is_string($token) || strpos($token, '.') === false) {
        return $this->response->setJSON(['valid' => false, 'error' => 'Invalid token format'])->setStatusCode(400);
    }

    list($b64, $sig) = explode('.', $token, 2);
    $secret = getenv('VOUCHER_SECRET') ?: (getenv('app.secret') ?: 'change_this_secret');
    $expected = hash_hmac('sha256', $b64, $secret);
    if (!hash_equals($expected, $sig)) {
        return $this->response->setJSON(['valid' => false, 'error' => 'Invalid token signature'])->setStatusCode(400);
    }

    // decode base64url payload
    $b64padded = strtr($b64, '-_', '+/');
    $padlen = 4 - (strlen($b64padded) % 4);
    if ($padlen < 4) $b64padded .= str_repeat('=', $padlen);
    $json = base64_decode($b64padded);
    if ($json === false) {
        return $this->response->setJSON(['valid' => false, 'error' => 'Invalid payload encoding'])->setStatusCode(400);
    }

    $payload = json_decode($json, true);
    if (!is_array($payload)) {
        return $this->response->setJSON(['valid' => false, 'error' => 'Invalid payload JSON'])->setStatusCode(400);
    }

    // Basic claims checks (allow both type names for backwards compatibility)
    if (($payload['type'] ?? '') !== 'checkin_token' && ($payload['type'] ?? '') !== 'booking_checkin') {
        return $this->response->setJSON(['valid' => false, 'error' => 'Unexpected token type'])->setStatusCode(400);
    }

    if (isset($payload['exp']) && (int)$payload['exp'] < time()) {
        return $this->response->setJSON(['valid' => false, 'error' => 'Token expired'])->setStatusCode(410);
    }

    $bookingId = (int) ($payload['booking_id'] ?? 0);
    if (!$bookingId) {
        return $this->response->setJSON(['valid' => false, 'error' => 'Missing booking_id'])->setStatusCode(400);
    }

    // Load booking from DB
    $bookingModel = new \App\Models\BookingModel();
    $booking = $bookingModel->find($bookingId);
    if (!$booking) {
        return $this->response->setJSON(['valid' => false, 'error' => 'Booking not found'])->setStatusCode(404);
    }

    // Normalize DB visit_date (try visit_date, booking_date, date)
    $booking_date_raw = $booking['visit_date'] ?? $booking['booking_date'] ?? $booking['date'] ?? null;
    $visit_date = $booking_date_raw ? date('Y-m-d', strtotime($booking_date_raw)) : null;

    // If token contains visit_date (or booking_date) ensure it matches DB — protect against token mismatch
    $tokenVisit = $payload['visit_date'] ?? $payload['visit_date'] ?? null;
    if (!empty($tokenVisit) && $tokenVisit !== $visit_date) {
        return $this->response->setJSON(['valid' => false, 'error' => 'Booking date mismatch'])->setStatusCode(400);
    }

    // Detect existing active checkin for this booking/customer
    $checkinModel = new \App\Models\CreateVisitorCheckIn();
    $customerId = $payload['customer_id'] ?? $booking['customer_id'] ?? null;

    $existing = $checkinModel
        ->where('booking_id', $bookingId)
        ->where('customer_id', $customerId)
        ->where('checkout_time', null)
        ->orderBy('checkin_id', 'DESC')
        ->first();

    $actionSuggestion = $existing ? 'checkout' : 'checkin';

    return $this->response->setJSON([
        'valid' => true,
        'booking_id' => $bookingId,
        'customer_id' => $customerId,
        'spot_id' => $payload['spot_id'] ?? $booking['spot_id'] ?? null,
        'visit_date' => $visit_date,
        'booking_date' => $visit_date, // backward compat
        'issued_at' => isset($payload['iat']) ? date('c', (int)$payload['iat']) : null,
        'expires_at' => isset($payload['exp']) ? date('c', (int)$payload['exp']) : null,
        'action_suggestion' => $actionSuggestion,
        'existing_checkin' => $existing ? ['checkin_id' => $existing['checkin_id'], 'checkin_time' => $existing['checkin_time']] : null
    ]);
}

   
}
