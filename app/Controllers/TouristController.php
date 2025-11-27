<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UsersModel;
use App\Models\TouristSpotModel;
use App\Models\BookingModel;

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

    // --- Favorite spots data (for dashboard) ---
    $favoriteSpots = [];
    try {
        // Prefer dedicated favorite table
        if ($db->tableExists('spot_fav_by_customer')) {
            $rows = $db->table('spot_fav_by_customer')
                ->select('spot_id')
                ->where('user_id', $userID)
                ->orderBy('favorited_at', 'DESC')
                ->limit(4)
                ->get()
                ->getResultArray();
            $ids = array_column($rows, 'spot_id');
            if (!empty($ids)) {
                $spotModel = new \App\Models\TouristSpotModel();
                $favoriteSpots = $spotModel->whereIn('spot_id', $ids)->findAll();
            }
        } elseif ($db->tableExists('user_favorites')) {
            $rows = $db->table('user_favorites')
                ->select('spot_id')
                ->where('user_id', $userID)
                ->orderBy('created_at', 'DESC')
                ->limit(4)
                ->get()
                ->getResultArray();
            $ids = array_column($rows, 'spot_id');
            if (!empty($ids)) {
                $spotModel = new \App\Models\TouristSpotModel();
                $favoriteSpots = $spotModel->whereIn('spot_id', $ids)->findAll();
            }
        } elseif ($db->tableExists('favorites')) {
            $rows = $db->table('favorites')
                ->select('spot_id')
                ->where('user_id', $userID)
                ->orderBy('created_at', 'DESC')
                ->limit(4)
                ->get()
                ->getResultArray();
            $ids = array_column($rows, 'spot_id');
            if (!empty($ids)) {
                $spotModel = new \App\Models\TouristSpotModel();
                $favoriteSpots = $spotModel->whereIn('spot_id', $ids)->findAll();
            }
        }
    } catch (\Exception $e) {
        $favoriteSpots = [];
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

    // Normalize popularSpots: ensure each has a numeric 'views' key for the view
    foreach ($popularSpots as &$ps) {
        $ps['views'] = isset($ps['views']) ? (int)$ps['views'] : 0;
    }
    unset($ps);

    // --- Finally render view with data ---
    return view('Pages/tourist/dashboard', [
        'userID'            => $userID,
        'preferenceID'      => $preference['preference_id'] ?? null,
        'FullName'          => $session->get('FirstName') . ' ' . $session->get('LastName'),
        'email'             => $session->get('Email'),
        'TotalSaveItineray' => $TotalSaveItineray,
        'placesVisited'     => $placesVisited,
        'favoriteCount'     => $favoriteCount,
            'favoriteSpots'     => $favoriteSpots,
        'upcomingBookings'  => $upcomingBookings,
        'popularSpots'      => $popularSpots,
    ]);
}

    /**
     * Return live dashboard stats as JSON for the current user.
     * Used by dashboard AJAX polling to keep counts up-to-date.
     */
    public function dashboardStats()
    {
        $session = session();
        $userID = $session->get('UserID');
        if (!$userID) {
            return $this->response->setJSON(['error' => 'Not logged in'], 401);
        }

        $db = \Config\Database::connect();

        // Saved itineraries
        $TotalSaveItineray = 0;
        try {
            $preferencesModel = new \App\Models\UserPreferenceModel();
            $itineraryModel = new \App\Models\ItineraryModel();
            $preference = $preferencesModel->where('user_id', $userID)->first();
            $preferenceID = $preference['preference_id'] ?? null;
            if ($preferenceID) {
                $TotalSaveItineray = (int) $itineraryModel->countDistinctDates($preferenceID);
            }
        } catch (\Throwable $e) {
            $TotalSaveItineray = 0;
        }

        // Places visited
        $placesVisited = 0;
        try {
            if ($db->tableExists('createuservisits')) {
                $placesVisited = (int) $db->table('createuservisits')->where('user_id', $userID)->countAllResults();
            }
        } catch (\Throwable $e) {
            $placesVisited = 0;
        }

        // Favorite count
        $favoriteCount = 0;
        try {
            if ($db->tableExists('spot_fav_by_customer')) {
                $favoriteCount = (int) $db->table('spot_fav_by_customer')->where('user_id', $userID)->countAllResults();
            } elseif ($db->tableExists('user_favorites')) {
                $favoriteCount = (int) $db->table('user_favorites')->where('user_id', $userID)->countAllResults();
            } elseif ($db->tableExists('favorites')) {
                $favoriteCount = (int) $db->table('favorites')->where('user_id', $userID)->countAllResults();
            }
        } catch (\Throwable $e) {
            $favoriteCount = 0;
        }

        // Upcoming bookings
        $upcomingBookings = 0;
        try {
            $today = date('Y-m-d');
            $bookingModel = new \App\Models\BookingModel();
            $fields = [];
            if ($db->tableExists('bookings')) {
                $fields = $db->getFieldNames('bookings');
            }
            if (!empty($fields) && in_array('user_id', $fields, true)) {
                $upcomingBookings = (int) $bookingModel->where('user_id', $userID)->where('visit_date >=', $today)->where('booking_status !=', 'cancelled')->countAllResults();
            } elseif (!empty($fields) && in_array('customer_id', $fields, true)) {
                // try resolve customer id
                try {
                    $customerModel = new \App\Models\CustomerModel();
                    $customerRow = $customerModel->where('user_id', $userID)->first();
                    $customerID = $customerRow['customer_id'] ?? null;
                    if ($customerID) {
                        $upcomingBookings = (int) $bookingModel->where('customer_id', $customerID)->where('visit_date >=', $today)->where('booking_status !=', 'cancelled')->countAllResults();
                    }
                } catch (\Throwable $e) {
                    $upcomingBookings = 0;
                }
            }
        } catch (\Throwable $e) {
            $upcomingBookings = 0;
        }

        return $this->response->setJSON([
            'savedItineraries' => $TotalSaveItineray,
            'placesVisited' => $placesVisited,
            'favoriteCount' => $favoriteCount,
            'upcomingBookings' => $upcomingBookings,
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

        // Get the preference string (format: "Historical,Adventure")
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

    /**
     * Save a review submitted by the tourist (AJAX endpoint).
     */
    public function saveReview()
    {
        $session = session();
        $userID = $session->get('UserID');
        if (!$userID) {
            return $this->response->setJSON(['success' => false, 'message' => 'Not authenticated'])->setStatusCode(401);
        }

        $place = $this->request->getPost('place');
        $visit_date = $this->request->getPost('visit_date');
        $rating = (int) $this->request->getPost('rating');
        $comment = $this->request->getPost('comment');

        if (!$place || !$visit_date || !$rating || !$comment) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing required fields'])->setStatusCode(422);
        }

        $db = \Config\Database::connect();

        // Resolve spot by exact name (required) and derive business_id
        try {
            $spotModel = new \App\Models\TouristSpotModel();
            $s = $spotModel->where('spot_name', $place)->first();
            if (!$s) {
                return $this->response->setJSON(['success' => false, 'message' => 'Place not found'], 422);
            }
            $spot_id = $s['spot_id'];
            $business_id = $s['business_id'] ?? null;
            if (empty($business_id)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Place owner data missing; cannot accept review'], 422);
            }
        } catch (\Throwable $e) {
            log_message('error', 'Error resolving spot: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Server error resolving place'])->setStatusCode(500);
        }

        // Resolve customer_id from customers table if available
        $customer_id = null;
        try {
            $customerModel = new \App\Models\CustomerModel();
            $c = $customerModel->where('user_id', $userID)->first();
            if ($c) $customer_id = $c['customer_id'];
        } catch (\Throwable $e) {
            // ignore
        }

        // Verify the user has a booking/checkin for this spot on the visit_date
        try {
            $bookingModel = new \App\Models\BookingModel();
            // Defensive: getFieldNames may fail on some DB drivers or when table missing
            try {
                $fields = $db->getFieldNames('bookings');
                if (!is_array($fields)) $fields = [];
            } catch (\Throwable $e) {
                log_message('warning', 'saveReview: could not get bookings fields: ' . $e->getMessage());
                $fields = [];
            }

            $visitYmd = date('Y-m-d', strtotime($visit_date));
            $booking = null;

            // allowed statuses: include Confirmed (many of your DB rows are Confirmed)
            $allowedStatuses = ['Checked-in','Checked-In','Checked-out','Checked-Out','Completed','Confirmed'];

            // Prefer matching by customer_id if bookings table has customer_id
            if (!empty($customer_id) && in_array('customer_id', $fields, true)) {
                try {
                    $booking = $bookingModel->where('customer_id', $customer_id)
                        ->where('spot_id', $spot_id)
                        ->where('visit_date', $visitYmd)
                        ->whereIn('booking_status', $allowedStatuses)
                        ->orderBy('booking_id', 'DESC')
                        ->first();
                } catch (\Throwable $e) {
                    log_message('error', 'saveReview: booking query (customer_id) failed: ' . $e->getMessage());
                }
            }

            // Fallback: try matching by user_id column in bookings table if present
            if (!$booking && in_array('user_id', $fields, true)) {
                try {
                    $booking = $bookingModel->where('user_id', $userID)
                        ->where('spot_id', $spot_id)
                        ->where('visit_date', $visitYmd)
                        ->whereIn('booking_status', $allowedStatuses)
                        ->orderBy('booking_id', 'DESC')
                        ->first();
                } catch (\Throwable $e) {
                    log_message('error', 'saveReview: booking query (user_id) failed: ' . $e->getMessage());
                }
            }

            // Also allow checkin records (visitor_checkins) for walk-ins or non-booking checkins
            if (!$booking && $db->tableExists('visitor_checkins')) {
                $checkinModel = new \App\Models\CreateVisitorCheckIn();
                $existing = $checkinModel->where('spot_id', $spot_id)
                    ->where('customer_id', $customer_id ?: $userID)
                    ->where('checkin_time >=', $visitYmd . ' 00:00:00')
                    ->where('checkin_time <=', $visitYmd . ' 23:59:59')
                    ->orderBy('checkin_id', 'DESC')
                    ->first();

                if ($existing && !empty($existing['booking_id'])) {
                    // load the referenced booking to ensure FK exists
                    $booking = $bookingModel->find($existing['booking_id']);
                }
            }

            if (!$booking || empty($booking)) {
                return $this->response->setJSON(['success' => false, 'message' => 'You can only review places you have checked in to.'])->setStatusCode(403);
            }

            $booking_id = (int) ($booking['booking_id'] ?? 0);
        } catch (\Throwable $e) {
            $msg = $e->getMessage();
            log_message('error', 'Error verifying booking/checkin for review: ' . $msg);
            // In development include the exception message to aid debugging
            $env = getenv('CI_ENVIRONMENT') ?: getenv('ENVIRONMENT') ?: 'production';
            $userMessage = 'Server error verifying visit';
            if (strtolower($env) === 'development') {
                $userMessage .= ': ' . $msg;
            }
            return $this->response->setJSON(['success' => false, 'message' => $userMessage])->setStatusCode(500);
        }

        // Build feedback payload filling required fields for the schema
        $feedbackModel = new \App\Models\FeedbackModel();
        $data = [
            'booking_id' => $booking_id,
            'spot_id' => $spot_id,
            'customer_id' => $customer_id ?: $userID,
            'business_id' => $business_id ?: 0,
            'rating' => $rating,
            'title' => $place,
            'comment' => $comment,
            // set sub-ratings to the same overall rating if not provided separately
            'cleanliness_rating' => $rating,
            'staff_rating' => $rating,
            'value_rating' => $rating,
            'location_rating' => $rating,
            'status' => 'Pending',
            'is_verified_visit' => 1,
        ];

        try {
            $insertId = $feedbackModel->insert($data);
        } catch (\Throwable $e) {
            log_message('error', 'Failed saving review: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to save review'])->setStatusCode(500);
        }

        // Optionally accept files (move to uploads/reviews) but do not currently link them in DB
        try {
            $files = $this->request->getFiles();
            if (!empty($files)) {
                $targetDir = WRITEPATH . 'uploads/reviews/';
                if (!is_dir($targetDir)) @mkdir($targetDir, 0755, true);
                foreach ($files as $field => $f) {
                    if (is_array($f)) {
                        foreach ($f as $one) {
                            if ($one->isValid() && !$one->hasMoved()) {
                                $name = $one->getRandomName();
                                $one->move($targetDir, $name);
                            }
                        }
                    } else {
                        if ($f->isValid() && !$f->hasMoved()) {
                            $name = $f->getRandomName();
                            $f->move($targetDir, $name);
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            // do not fail the request for file move issues
            log_message('error', 'Error moving review files: ' . $e->getMessage());
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Review saved', 'id' => $insertId]);
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

    /**
     * Return recommended spots for the Add Activity modal.
     * Accepts GET param `limit` to limit the number of spots returned.
     */
    public function recommendedSpots()
    {
        $limit = (int) ($this->request->getGet('limit') ?? 8);
        $spotModel = new \App\Models\TouristSpotModel();
        try {
            $spots = [];

            // Try to prefer user's categories if available
            $session = session();
            $userID = $session->get('UserID');
            if ($userID) {
                try {
                    $userModel = new \App\Models\UsersModel();
                    $catStr = $userModel->getUserCategoryString($userID) ?: '';
                    $cats = array_filter(array_map('trim', explode(',', $catStr)));
                    if (!empty($cats)) {
                        // fetch spots that match any of the user's categories
                        // Use model whereIn if available; fallback to getApprovedTouristSpots and filter
                        try {
                            $candidates = $spotModel->whereIn('category', $cats)->findAll();
                        } catch (\Throwable $inner) {
                            // fallback to filtering approved spots in PHP
                            $approved = $spotModel->getApprovedTouristSpots();
                            $candidates = array_values(array_filter($approved, function($s) use ($cats) {
                                return in_array(trim((string)($s['category'] ?? '')), $cats, true);
                            }));
                        }

                        if (!empty($candidates)) {
                            // randomize order
                            shuffle($candidates);
                            $spots = $candidates;
                        }
                    }
                } catch (\Throwable $e) {
                    // If user model or preferences fail, ignore and fallback
                    log_message('warning', 'recommendedSpots: failed to get user prefs: ' . $e->getMessage());
                }
            }

            // Fallback: use approved spots randomized
            if (empty($spots)) {
                $approved = $spotModel->getApprovedTouristSpots();
                if (!is_array($approved)) $approved = [];
                shuffle($approved);
                $spots = $approved;
            }

            if ($limit > 0) $spots = array_slice($spots, 0, $limit);

            // If we returned fewer than requested, attempt to top-up from approved spots
            if ($limit > 0 && count($spots) < $limit) {
                try {
                    $existingIds = array_filter(array_map(function($s){ return $s['spot_id'] ?? ($s['id'] ?? null); }, $spots));
                    $approvedAll = $spotModel->getApprovedTouristSpots();
                    foreach ($approvedAll as $c) {
                        $cid = $c['spot_id'] ?? ($c['id'] ?? null);
                        if (!$cid) continue;
                        if (in_array($cid, $existingIds, true)) continue;
                        $spots[] = $c;
                        $existingIds[] = $cid;
                        if (count($spots) >= $limit) break;
                    }
                } catch (\Throwable $topErr) {
                    log_message('warning', 'recommendedSpots top-up failed: ' . $topErr->getMessage());
                }
            }

            // Normalize output and include accessible image URL and lat/lng keys
            $out = array_map(function($s){
                return [
                    'id' => $s['spot_id'] ?? null,
                    'name' => $s['spot_name'] ?? ($s['name'] ?? ''),
                    'category' => $s['category'] ?? '',
                    'location' => $s['location'] ?? '',
                    'price_per_person' => $s['price_per_person'] ?? ($s['price'] ?? 0),
                    'lat' => $s['latitude'] ?? $s['lat'] ?? null,
                    'lng' => $s['longitude'] ?? $s['lng'] ?? null,
                    'primary_image' => isset($s['primary_image']) && $s['primary_image'] ? base_url('uploads/spots/'.$s['primary_image']) : base_url('uploads/spots/Spot-No-Image.png'),
                ];
            }, $spots);

            return $this->response->setJSON(['success' => true, 'spots' => $out]);
        } catch (\Throwable $e) {
            log_message('error', 'recommendedSpots failed: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'spots' => [], 'error' => 'Server error'], 500);
        }
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


    /**
     * Create a new itinerary (called from Create New Itinerary modal)
     * Expects JSON: { title, start_date, end_date, adults, children, seniors, selected_spots: [ { name, category, location, price_per_person } ] }
     */
    public function createItinerary()
    {
        $session = session();
        $userID = $session->get('UserID');
        if (!$userID) {
            return $this->response->setJSON(['error' => 'Not logged in'], 401);
        }

        $input = $this->request->getJSON(true) ?: $this->request->getPost();
        $title = trim($input['title'] ?? $input['trip_title'] ?? 'Untitled Trip');
        $start = $input['start_date'] ?? null;
        $end = $input['end_date'] ?? null;
        $adults = isset($input['adults']) ? (int)$input['adults'] : 0;
        $children = isset($input['children']) ? (int)$input['children'] : 0;
        $seniors = isset($input['seniors']) ? (int)$input['seniors'] : 0;
        $spots = $input['selected_spots'] ?? [];

        if (!$title || !$start) {
            return $this->response->setJSON(['error' => 'Missing required fields (title/start_date)'], 400);
        }

        try {
            $prefModel = new \App\Models\UserPreferenceModel();
            $pref = $prefModel->where('user_id', $userID)->first();
            $preferenceId = $pref['preference_id'] ?? null;

            $itModel = new \App\Models\ItineraryModel();
            $spotModel = new \App\Models\TouristSpotModel();

            $created = [];
            $errors = [];

            // Insert each selected spot as a row; days are assigned sequentially
            $day = 1;
            foreach ($spots as $s) {
                // Try to resolve spot_id by name (best-effort)
                $spotId = null;
                if (!empty($s['spot_id'])) $spotId = (int)$s['spot_id'];
                if (!$spotId && !empty($s['name'])) {
                    $found = $spotModel->like('spot_name', $s['name'])->first();
                    if ($found) $spotId = $found['spot_id'] ?? null;
                }

                if (!$spotId) {
                    // skip unresolved spots but record error
                    $errors[] = ['spot' => $s, 'error' => 'Could not resolve spot by name'];
                    // still increment day so UI ordering remains predictable
                    $day++;
                    continue;
                }

                $row = [
                    'preference_id' => $preferenceId,
                    'spot_id' => $spotId,
                    'description' => $s['description'] ?? null,
                    'day' => $day,
                    'budget' => $input['budget'] ?? null,
                    'adults' => $adults,
                    'children' => $children,
                    'seniors' => $seniors,
                    'trip_title' => $title,
                    'start_date' => $start,
                    'end_date' => $end,
                    'created_at' => date('Y-m-d H:i:s')
                ];

                $insertId = $itModel->insert($row);
                if ($insertId === false) {
                    $errors[] = ['spot' => $s, 'error' => 'Failed to insert'];
                } else {
                    $created[] = $insertId;
                }

                $day++;
            }

            return $this->response->setJSON(['success' => true, 'created' => $created, 'errors' => $errors]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['error' => 'Server error: ' . $e->getMessage()], 500);
        }
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

    /**
     * Return JSON list of favorite spots for the current user.
     * Used by dashboard JS: GET /tourist/getFavorites
     */
    public function getFavorites()
    {
        $session = session();
        $userID = $session->get('UserID');
        if (!$userID) {
            return $this->response->setJSON([], 200);
        }

        $db = \Config\Database::connect();
        $out = [];
        try {
            $spotModel = new \App\Models\TouristSpotModel();
            $ids = [];

            if ($db->tableExists('spot_fav_by_customer')) {
                $rows = $db->table('spot_fav_by_customer')
                    ->select('spot_id')
                    ->where('user_id', $userID)
                    ->orderBy('favorited_at', 'DESC')
                    ->get()
                    ->getResultArray();
                $ids = array_column($rows, 'spot_id');
            } elseif ($db->tableExists('user_favorites')) {
                $rows = $db->table('user_favorites')
                    ->select('spot_id')
                    ->where('user_id', $userID)
                    ->orderBy('created_at', 'DESC')
                    ->get()
                    ->getResultArray();
                $ids = array_column($rows, 'spot_id');
            } elseif ($db->tableExists('favorites')) {
                $rows = $db->table('favorites')
                    ->select('spot_id')
                    ->where('user_id', $userID)
                    ->orderBy('created_at', 'DESC')
                    ->get()
                    ->getResultArray();
                $ids = array_column($rows, 'spot_id');
            }

            if (!empty($ids)) {
                // keep original order: fetch records and map by id
                $spots = $spotModel->whereIn('spot_id', $ids)->findAll();
                foreach ($spots as $s) {
                    $out[] = [
                        'id' => $s['spot_id'] ?? null,
                        'spot_name' => $s['spot_name'] ?? ($s['name'] ?? ''),
                        'primary_image' => $s['primary_image'] ?? '', // filename only; client concatenates base_url
                        'category' => $s['category'] ?? '',
                        'rating' => $s['rating'] ?? null,
                    ];
                }
            }

            return $this->response->setJSON($out);
        } catch (\Throwable $e) {
            log_message('error', 'getFavorites error: ' . $e->getMessage());
            return $this->response->setJSON([], 200);
        }
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
        // allow bulk itinerary payloads
        $itinerary = $input['itinerary'] ?? null;
        $visitTime = $input['visit_time'] ?? null;
        $numAdults = isset($input['num_adults']) ? (int)$input['num_adults'] : 0;
        $numChildren = isset($input['num_children']) ? (int)$input['num_children'] : 0;
        $numSeniors = isset($input['num_seniors']) ? (int)$input['num_seniors'] : 0;
        $specialRequests = $input['special_requests'] ?? null;
        $totalPrice = isset($input['total_price']) ? $input['total_price'] : 0;

        // If not booking via itinerary array, require spot_id and visit_date
        if (empty($itinerary) && (!$spotId || !$visitDate)) {
            return $this->response->setJSON(['error' => 'Missing required fields'], 400);
        }

        try {
            $bookingModel = new \App\Models\BookingModel();
            $spotModel = new \App\Models\TouristSpotModel();

            // Support bulk itinerary payloads: { itinerary: [ { day_number, date, activities: [ ... ] }, ... ] }
            $itinerary = $input['itinerary'] ?? null;
            if (is_array($itinerary) && count($itinerary) > 0) {
                $created = [];
                $errors = [];

                foreach ($itinerary as $dayEntry) {
                    $dateRaw = $dayEntry['date'] ?? null;
                    $visitDateForDay = $dateRaw ? date('Y-m-d', strtotime($dateRaw)) : null;
                    $activities = $dayEntry['activities'] ?? $dayEntry['spots'] ?? [];

                    foreach ($activities as $act) {
                        // Resolve spot id
                        $spotIdAct = null;
                        if (isset($act['id']) && is_numeric($act['id']) && (int)$act['id'] > 0) {
                            $spotIdAct = (int)$act['id'];
                        }
                        if (!$spotIdAct && !empty($act['title'])) {
                            $found = $spotModel->like('spot_name', $act['title'])->first();
                            if ($found) $spotIdAct = $found['spot_id'] ?? null;
                        }

                        if (!$spotIdAct) {
                            $errors[] = ['activity' => $act, 'error' => 'No spot_id resolved'];
                            continue;
                        }

                        $visit_date_to_use = $visitDateForDay ?: ($act['date'] ?? date('Y-m-d'));
                        $visit_time = $act['time'] ?? $act['visit_time'] ?? null;

                        $numAdultsAct = isset($act['num_adults']) ? (int)$act['num_adults'] : $numAdults;
                        $numChildrenAct = isset($act['num_children']) ? (int)$act['num_children'] : $numChildren;
                        $numSeniorsAct = isset($act['num_seniors']) ? (int)$act['num_seniors'] : $numSeniors;
                        $totalGuestsAct = $numAdultsAct + $numChildrenAct + $numSeniorsAct;

                        $totalPriceAct = isset($act['total_price']) ? $act['total_price'] : 0;
                        if (!$totalPriceAct) {
                            $s = $spotModel->find($spotIdAct);
                            $pp = isset($s['price_per_person']) ? (float)$s['price_per_person'] : 0;
                            $totalPriceAct = $pp * max(1, $totalGuestsAct);
                        }

                        $data = [
                            'spot_id' => $spotIdAct,
                            'customer_id' => $userID,
                            'booking_date' => date('Y-m-d'),
                            'visit_date' => $visit_date_to_use,
                            'visit_time' => $visit_time,
                            'num_adults' => $numAdultsAct,
                            'num_children' => $numChildrenAct,
                            'num_seniors' => $numSeniorsAct,
                            'total_guests' => $totalGuestsAct,
                            'price_per_person' => $totalGuestsAct ? round($totalPriceAct / $totalGuestsAct, 2) : null,
                            'subtotal' => $totalPriceAct,
                            'total_price' => $totalPriceAct,
                            'booking_status' => 'Pending',
                            'payment_status' => 'Unpaid',
                            'special_requests' => $act['notes'] ?? $input['special_requests'] ?? null,
                            'created_at' => date('Y-m-d H:i:s')
                        ];

                        $insertId = $bookingModel->insert($data);
                        if ($insertId === false) {
                            $errors[] = ['activity' => $act, 'error' => 'Failed to create booking'];
                        } else {
                            $created[] = $insertId;
                        }
                    }
                }

                // Bookings created via itinerary are pending payment by default
                return $this->response->setJSON(['success' => true, 'created' => $created, 'errors' => $errors, 'booking_status' => 'Pending', 'payment_status' => 'Unpaid']);
            }

            // Legacy single booking path
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

// Create notification for spot owner about new booking
// Create notification for spot owner about new booking
try {
    $notificationModel = new \App\Models\NotificationModel();
    $spotModel = new \App\Models\TouristSpotModel();
    $businessModel = new \App\Models\BusinessModel();
    
    $spot = $spotModel->find($spotId);
    $spotName = $spot['spot_name'] ?? 'Unknown Spot';
    $businessId = $spot['business_id'] ?? null;
    
    if ($businessId) {
        // Get the spot owner's user_id from the business
        $business = $businessModel->find($businessId);
        $spotOwnerId = $business['user_id'] ?? null;
        
        if ($spotOwnerId) {
            // Insert notification for spot owner
            $notificationModel->insert([
                'user_id' => $spotOwnerId,
                'message' => "New booking #$insertId for $spotName on $visitDate",
                'url' => '/spotowner/bookings',
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
    
    // Also notify admins (user_id = NULL for all admins)
    $notificationModel->insert([
        'user_id' => null,
        'message' => "New booking #$insertId for $spotName on $visitDate",
        'url' => '/admin/bookings',
        'is_read' => 0,
        'created_at' => date('Y-m-d H:i:s')
    ]);
    
} catch (\Exception $e) {
    log_message('error', 'Failed to create booking notification: ' . $e->getMessage());
}

return $this->response->setJSON(['success' => true, 'booking_id' => $insertId, 'booking_status' => 'Pending', 'payment_status' => 'Unpaid']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => 'Database error: ' . $e->getMessage()], 500);
        }

        
    }

<<<<<<< Updated upstream
=======
    /**
     * Cancel a booking owned by the current tourist.
     * POST /tourist/cancelBooking/{bookingId}
     * Accepts JSON or form post: { reason: 'optional text' }
     */
    public function cancelBooking($bookingId)
    {
        $session = session();
        $userID = $session->get('UserID');
        if (!$userID) {
            return $this->response->setJSON(['success' => false, 'message' => 'Not logged in'])->setStatusCode(401);
        }

        $bookingId = (int) $bookingId;
        if ($bookingId <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid booking id'])->setStatusCode(400);
        }

        try {
            $db = \Config\Database::connect();
            $bookingModel = new \App\Models\BookingModel();
            $booking = $bookingModel->find($bookingId);
            if (!$booking) {
                return $this->response->setJSON(['success' => false, 'message' => 'Booking not found'])->setStatusCode(404);
            }

            // Determine ownership: bookings may reference customers.customer_id or users.UserID
            $fields = [];
            try {
                $fields = $db->getFieldNames('bookings');
            } catch (\Throwable $e) {
                $fields = [];
            }

            $isOwner = false;
            // If bookings has customer_id column, resolve customer's customer_id for this user
            if (!empty($fields) && in_array('customer_id', $fields, true)) {
                try {
                    $customerModel = new \App\Models\CustomerModel();
                    $cust = $customerModel->where('user_id', $userID)->first();
                    $customerID = $cust['customer_id'] ?? null;
                    if ($customerID !== null && (string)$booking['customer_id'] === (string)$customerID) {
                        $isOwner = true;
                    }
                } catch (\Throwable $e) {
                    // ignore
                }
            }

            // Fallback: if bookings has user_id column, compare directly
            if (!$isOwner && !empty($fields) && in_array('user_id', $fields, true)) {
                if ((string)($booking['user_id'] ?? '') === (string)$userID) $isOwner = true;
            }

            // Final fallback: some installs store booking.customer_id directly as users.UserID
            if (!$isOwner) {
                if (isset($booking['customer_id']) && (string)$booking['customer_id'] === (string)$userID) {
                    $isOwner = true;
                }
            }

            if (!$isOwner) {
                return $this->response->setJSON(['success' => false, 'message' => 'Forbidden: you do not own this booking'])->setStatusCode(403);
            }

            // Read cancellation reason from JSON or form
            $input = $this->request->getJSON(true) ?: $this->request->getPost();
            $reason = $input['reason'] ?? $input['cancellation_reason'] ?? $this->request->getPost('reason') ?? null;

            // Build update payload; only include optional columns if they exist in table
            $update = ['booking_status' => 'cancelled', 'updated_at' => date('Y-m-d H:i:s')];
            if (!empty($fields) && in_array('cancellation_reason', $fields, true)) {
                $update['cancellation_reason'] = $reason;
            } elseif (!empty($fields) && in_array('cancel_reason', $fields, true)) {
                $update['cancel_reason'] = $reason;
            }
            if (!empty($fields) && in_array('cancelled_at', $fields, true)) {
                $update['cancelled_at'] = date('Y-m-d H:i:s');
            }

            $bookingModel->update($bookingId, $update);

            return $this->response->setJSON(['success' => true, 'message' => 'Booking cancelled']);
        } catch (\Throwable $e) {
            log_message('error', 'cancelBooking error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Server error while cancelling booking'])->setStatusCode(500);
        }
    }

    /**
     * Create a payment intent / record and return a checkout URL.
     * POST /tourist/createPaymentIntent
     * Body: { booking_id, amount, method }
     */
    public function createPaymentIntent()
    {
        $session = session();
        $userID = $session->get('UserID');
        if (!$userID) {
            return $this->response->setJSON(['success' => false, 'error' => 'Not logged in'])->setStatusCode(401);
        }

        $input = $this->request->getJSON(true) ?: $this->request->getPost();
        $bookingId = isset($input['booking_id']) ? (int)$input['booking_id'] : 0;
        $amount = isset($input['amount']) ? $input['amount'] : null;
        $method = isset($input['method']) ? $input['method'] : 'card';

        if ($bookingId <= 0 || !$amount) {
            return $this->response->setJSON(['success' => false, 'error' => 'Missing booking_id or amount'])->setStatusCode(400);
        }

        try {
            $bookingModel = new \App\Models\BookingModel();
            $booking = $bookingModel->find($bookingId);
            if (!$booking) {
                return $this->response->setJSON(['success' => false, 'error' => 'Booking not found'])->setStatusCode(404);
            }

            // Persist a payments row (pending)
            $paymentModel = new \App\Models\PaymentModel();
            $now = date('Y-m-d H:i:s');
            $insertData = [
                'booking_id' => $bookingId,
                'amount' => (float)$amount,
                'payment_method' => ucfirst($method),
                'status' => 'Pending',
                'notes' => 'Created via createPaymentIntent',
                'created_at' => $now
            ];
            $ins = $paymentModel->insert($insertData);
            $paymentId = $paymentModel->getInsertID() ?: $ins;

            // Attempt to create a hosted checkout session at PayMongo and attach metadata for reconciliation.
            // Read secret key from env; if not present, fall back to legacy hosted link construction.
            $paymongoSecret = getenv('PAYMONGO_SECRET_KEY') ?: getenv('PAYMONGO_SECRET');
            $checkoutUrl = null;
            $providerSessionId = null;

            if ($paymongoSecret) {
                try {
                    // Prepare request payload. Many providers expect amount in cents.
                    $payload = [
                        'data' => [
                            'attributes' => [
                                'amount' => (int) round(((float)$amount) * 100),
                                'currency' => 'PHP',
                                'metadata' => [
                                    'booking_id' => (int)$bookingId,
                                    'payment_id' => (int)$paymentId
                                ],
                                // Optional: allowed payment methods. Let provider choose if absent.
                                'payment_method_types' => [$method]
                            ]
                        ]
                    ];

                    $apiUrl = getenv('PAYMONGO_API_URL') ?: 'https://api.paymongo.com/v1/links';

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $apiUrl);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Content-Type: application/json',
                        'Authorization: Basic ' . base64_encode($paymongoSecret . ':'),
                    ]);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
                    curl_setopt($ch, CURLOPT_TIMEOUT, 15);

                    $resp = curl_exec($ch);
                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    $curlErr = curl_error($ch);
                    curl_close($ch);

                    if ($resp !== false && $httpCode >= 200 && $httpCode < 300) {
                        $json = json_decode($resp, true);
                        // Provider response shapes vary; try multiple likely locations for a hosted URL
                        if (!empty($json['data']['attributes']['url'])) {
                            $checkoutUrl = $json['data']['attributes']['url'];
                        } elseif (!empty($json['data']['attributes']['checkout_url'])) {
                            $checkoutUrl = $json['data']['attributes']['checkout_url'];
                        } elseif (!empty($json['data']['attributes']['hosted_url'])) {
                            $checkoutUrl = $json['data']['attributes']['hosted_url'];
                        } elseif (!empty($json['data']['attributes']['session_url'])) {
                            $checkoutUrl = $json['data']['attributes']['session_url'];
                        }

                        // Capture provider session/id if available
                        if (!empty($json['data']['id'])) {
                            $providerSessionId = $json['data']['id'];
                        }
                    } else {
                        log_message('error', 'PayMongo create session failed HTTP ' . $httpCode . ' response: ' . substr($resp ?? '', 0, 1000) . ' curlErr:' . $curlErr);
                    }
                } catch (\Throwable $e) {
                    log_message('error', 'PayMongo request error: ' . $e->getMessage());
                }
            }

            // If provider did not return a hosted URL, fall back to legacy public hosted page link
            if (!$checkoutUrl) {
                $checkoutBase = 'https://paymongo.page/l/tuklasnasugbu';
                $checkoutUrl = $checkoutBase . '?booking_id=' . urlencode($bookingId) . '&amount=' . urlencode($amount) . '&payment_id=' . urlencode($paymentId);
            }

            // Persist provider session id (if any) into transaction_id for later reconciliation; also append provider url to notes
            $update = [];
            if ($providerSessionId) $update['transaction_id'] = $providerSessionId;
            $update['notes'] = (isset($insertData['notes']) ? $insertData['notes'] : '') . '\nhosted_url:' . $checkoutUrl;
            try {
                $paymentModel->update($paymentId, $update);
            } catch (\Throwable $e) {
                log_message('error', 'Failed to update payment with provider info: ' . $e->getMessage());
            }

            return $this->response->setJSON(['success' => true, 'checkout_url' => $checkoutUrl, 'payment_id' => $paymentId]);
        } catch (\Throwable $e) {
            log_message('error', 'createPaymentIntent error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'error' => 'Server error while creating payment intent'])->setStatusCode(500);
        }
    }

>>>>>>> Stashed changes
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

    /**
     * Generate a short-lived checkin token for a booking (URL-safe payload + HMAC)
     * Accepts: booking id as argument in URL segment
     */
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

        // Normalize visit_date to Y-m-d
        $visit_date_raw = $booking['visit_date'] ?? $booking['booking_date'] ?? $booking['date'] ?? null;
        $visit_date = $visit_date_raw ? date('Y-m-d', strtotime($visit_date_raw)) : null;

        try {
            $jti = bin2hex(random_bytes(8));
        } catch (\Throwable $e) {
            $jti = uniqid('', true);
        }

        $payload = [
            'type' => 'checkin_token',
            'booking_id' => (int)$booking_id,
            'customer_id' => isset($booking['customer_id']) ? (int)$booking['customer_id'] : null,
            'spot_id' => isset($booking['spot_id']) ? (int)$booking['spot_id'] : null,
            'visit_date' => $visit_date,
            'booking_date' => $visit_date,
            'iat' => time(),
            'exp' => time() + 60 * 60 * 24,
            'jti' => $jti
        ];

        $payloadJson = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($payloadJson === false) {
            return $this->response->setJSON(['error' => 'Failed to create token payload'], 500);
        }

        $b64 = rtrim(strtr(base64_encode($payloadJson), '+/', '-_'), '=');
        $secret = getenv('VOUCHER_SECRET') ?: (getenv('app.secret') ?: 'change_this_secret');
        $signature = hash_hmac('sha256', $b64, $secret);
        $token = $b64 . '.' . $signature;

        return $this->response->setJSON(['token' => $token, 'expires_at' => date('c', $payload['exp'])]);
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
public function getVisitedPlacesAjax()
    {
        // Ensure JSON response and CORS/headers if needed
        $response = service('response');

        $session = session();
        $userId = $session->get('UserID');
        if (!$userId) {
            return $response->setStatusCode(401)->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        try {
            // Some installations store bookings.customer_id (numeric) that differs from users.UserID.
            // Try to resolve a customer_id for the current user and pass that to the model.
            $customerSearchId = (int)$userId;
            try {
                $customerModel = new \App\Models\CustomerModel();
                $cust = $customerModel->where('user_id', $userId)->first();
                if ($cust && !empty($cust['customer_id'])) {
                    $customerSearchId = (int)$cust['customer_id'];
                }
            } catch (\Throwable $e) {
                // ignore and fallback to using UserID directly
            }

            $bookingModel = new BookingModel();
            $visited = $bookingModel->getVisitedPlacesByUser((int)$customerSearchId);

            // Normalize output keys if necessary
            $data = array_map(function ($row) {
                return [
                    'booking_id'   => $row['booking_id'] ?? null,
                    'booking_date' => $row['booking_date'] ?? null,
                    'visit_date'   => $row['visit_date'] ?? null,
                    'visit_time'   => $row['visit_time'] ?? null,
                    'total_guests' => $row['total_guests'] ?? 0,
                    'total_price'  => $row['total_price'] ?? 0,
                    'spot_name'    => $row['spot_name'] ?? '',
                    'location'     => $row['location'] ?? '',
                    'primary_image'=> $row['primary_image'] ?? null,
                    'booking_status'=> $row['booking_status'] ?? null, // may be null if query didn't select it
                ];
            }, $visited);

            return $response->setJSON(['success' => true, 'data' => $data]);
        } catch (\Throwable $e) {
            log_message('error', 'getVisitedPlacesAjax error: ' . $e->getMessage());
            return $response->setStatusCode(500)->setJSON(['success' => false, 'message' => 'Server error']);
        }
    }

   
    /**
     * Check whether a booking has been paid.
     * GET /tourist/checkPayment/{bookingId}
     * Returns JSON: { paid: bool, payment: null|array }
     */
    public function checkBookingPayment($bookingId)
    {
        $session = session();
        $userID = $session->get('UserID');
        if (!$userID) {
            return $this->response->setJSON(['success' => false, 'error' => 'Not logged in'])->setStatusCode(401);
        }

        $bookingId = (int)$bookingId;
        if ($bookingId <= 0) {
            return $this->response->setJSON(['success' => false, 'error' => 'Invalid booking id'])->setStatusCode(400);
        }

        try {
            $bookingModel = new \App\Models\BookingModel();
            $booking = $bookingModel->find($bookingId);
            if (!$booking) {
                return $this->response->setJSON(['success' => false, 'error' => 'Booking not found'])->setStatusCode(404);
            }

            // Verify ownership
            $db = \Config\Database::connect();
            $fields = [];
            try { $fields = $db->getFieldNames('bookings'); } catch (\Throwable $e) { $fields = []; }

            $isOwner = false;
            if (!empty($fields) && in_array('customer_id', $fields, true)) {
                try {
                    $customerModel = new \App\Models\CustomerModel();
                    $cust = $customerModel->where('user_id', $userID)->first();
                    $customerID = $cust['customer_id'] ?? null;
                    if ($customerID !== null && (string)($booking['customer_id'] ?? '') === (string)$customerID) $isOwner = true;
                } catch (\Throwable $e) {}
            }
            if (!$isOwner && !empty($fields) && in_array('user_id', $fields, true)) {
                if ((string)($booking['user_id'] ?? '') === (string)$userID) $isOwner = true;
            }
            if (!$isOwner) {
                if (isset($booking['customer_id']) && (string)$booking['customer_id'] === (string)$userID) $isOwner = true;
            }
            if (!$isOwner) {
                return $this->response->setJSON(['success' => false, 'error' => 'Forbidden: you do not own this booking'])->setStatusCode(403);
            }

            $paymentModel = new \App\Models\PaymentModel();
            $payment = $paymentModel->where('booking_id', $bookingId)->orderBy('created_at', 'DESC')->first();

            // If we have a payment record that's already completed, report paid
            if ($payment && isset($payment['status']) && in_array(strtolower($payment['status']), ['completed','paid','succeeded'], true)) {
                if (($booking['payment_status'] ?? '') !== 'Paid') {
                    $bookingModel->update($bookingId, ['payment_status' => 'Paid', 'booking_status' => 'Confirmed', 'updated_at' => date('Y-m-d H:i:s')]);
                }
                return $this->response->setJSON(['success' => true, 'paid' => true, 'payment' => $payment]);
            }

            // No completed payment found locally. If we have a provider transaction id, try to verify with PayMongo API.
            if ($payment && !empty($payment['transaction_id'])) {
                $secret = getenv('PAYMONGO_SECRET_KEY') ?: env('PAYMONGO_SECRET_KEY');
                if ($secret) {
                    $tx = $payment['transaction_id'];
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, "https://api.paymongo.com/v1/payments/" . urlencode($tx));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_USERPWD, $secret . ":");
                    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                    $resp = curl_exec($ch);
                    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    $curlErr = curl_error($ch);
                    curl_close($ch);

                    if ($resp && $code >= 200 && $code < 300) {
                        $body = json_decode($resp, true);
                        $pmStatus = $body['data']['attributes']['status'] ?? null;
                        if ($pmStatus && in_array(strtolower($pmStatus), ['succeeded','paid','captured'], true)) {
                            $paymentModel->update($payment['payment_id'], ['status' => 'Completed', 'payment_date' => date('Y-m-d H:i:s')]);
                            $bookingModel->update($bookingId, ['payment_status' => 'Paid', 'booking_status' => 'Confirmed', 'updated_at' => date('Y-m-d H:i:s')]);
                            $payment['status'] = 'Completed';
                            return $this->response->setJSON(['success' => true, 'paid' => true, 'payment' => $payment]);
                        } else {
                            return $this->response->setJSON(['success' => true, 'paid' => false, 'payment' => $payment, 'provider_status' => $pmStatus]);
                        }
                    } else {
                        log_message('error', 'PayMongo verify failed: HTTP ' . $code . ' curl: ' . $curlErr . ' resp: ' . substr($resp ?? '',0,200));
                        return $this->response->setJSON(['success' => true, 'paid' => false, 'payment' => $payment, 'provider_error' => 'api_error']);
                    }
                }
            }

            return $this->response->setJSON(['success' => true, 'paid' => false, 'payment' => $payment]);

        } catch (\Throwable $e) {
            log_message('error', 'checkBookingPayment error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'error' => 'Server error while checking payment'])->setStatusCode(500);
        }
    }

    /**
     * Webhook endpoint for PayMongo events.
     * POST /webhook
     * Verifies signature using PAYMONGO_WEBHOOK_SECRET and updates payments/bookings.
     */
    public function paymentWebhook()
    {
        // Read raw body and headers
        $raw = $this->request->getBody();
        $allHeaders = [];
        foreach ($this->request->getHeaders() as $name => $headerObj) {
            $allHeaders[$name] = $this->request->getHeaderLine($name);
        }
        $sigHeader = $this->request->getHeaderLine('Paymongo-Signature') ?: $this->request->getHeaderLine('Paymongo-Sig') ?: $this->request->getHeaderLine('Webhook-Signature') ?: $this->request->getHeaderLine('Signature');

        // Debug: write headers + truncated payload to a debug file (safe for local debugging).
        try {
            $debugPath = WRITEPATH . 'logs/webhook_debug_' . date('Ymd') . '.log';
            $logEntry = "\n[" . date('c') . "] WEBHOOK RECEIVED\n";
            $logEntry .= "Headers: " . json_encode($allHeaders) . "\n";
            $logEntry .= "Raw: " . (strlen($raw) > 4000 ? substr($raw,0,4000) . '... [truncated]' : $raw) . "\n";
            @file_put_contents($debugPath, $logEntry, FILE_APPEND | LOCK_EX);
        } catch (\Throwable $e) {
            // swallow debug file write errors
        }

        $secret = getenv('PAYMONGO_WEBHOOK_SECRET') ?: null;
        if (empty($secret)) {
            log_message('error', 'paymentWebhook: PAYMONGO_WEBHOOK_SECRET not set');
            return $this->response->setStatusCode(400)->setBody('webhook secret not configured');
        }

        // Extract signature value if header contains structured value like "t=..., v1=..."
        $sig = null;
        if ($sigHeader) {
            if (preg_match('/v1=([0-9a-fA-F]+)/', $sigHeader, $m)) {
                $sig = $m[1];
            } elseif (preg_match('/signature=([^,\s]+)/', $sigHeader, $m)) {
                $sig = $m[1];
            } else {
                // fallback to using the whole header value
                $sig = trim($sigHeader);
            }
        }

        if (empty($sig)) {
            log_message('warning', 'paymentWebhook: missing signature header');
            return $this->response->setStatusCode(400)->setBody('missing signature');
        }

        $expected = hash_hmac('sha256', $raw, $secret);
        if (!hash_equals($expected, $sig)) {
            log_message('warning', 'paymentWebhook: signature mismatch. expected=' . substr($expected,0,8) . ' got=' . substr($sig,0,8));
            return $this->response->setStatusCode(400)->setBody('invalid signature');
        }

        // Parse JSON
        $payload = json_decode($raw, true);
        if (!is_array($payload)) {
            log_message('warning', 'paymentWebhook: invalid json payload');
            return $this->response->setStatusCode(400)->setBody('invalid payload');
        }

        // Determine event type (PayMongo sends top-level 'type' like 'payment.paid')
        $eventType = $payload['type'] ?? ($payload['data']['type'] ?? null);

        // Try to extract provider payment id and metadata
        $data = $payload['data'] ?? [];
        $providerId = $data['id'] ?? null;
        // Some events include relationships.payment.data.id
        if (empty($providerId) && isset($data['relationships']['payment']['data']['id'])) {
            $providerId = $data['relationships']['payment']['data']['id'];
        }

        $attributes = $data['attributes'] ?? [];
        $metadata = $attributes['metadata'] ?? [];
        $metaBookingId = isset($metadata['booking_id']) ? (int)$metadata['booking_id'] : null;
        $metaPaymentId = isset($metadata['payment_id']) ? (int)$metadata['payment_id'] : null;

        try {
            $paymentModel = new \App\Models\PaymentModel();
            $bookingModel = new \App\Models\BookingModel();

            // Try to find local payment record
            $localPayment = null;
            if ($providerId) {
                $localPayment = $paymentModel->where('transaction_id', $providerId)->orderBy('created_at', 'DESC')->first();
            }
            if (!$localPayment && $metaPaymentId) {
                $localPayment = $paymentModel->find($metaPaymentId);
            }
            if (!$localPayment && $metaBookingId) {
                $localPayment = $paymentModel->where('booking_id', $metaBookingId)->orderBy('created_at', 'DESC')->first();
            }

            // Helper to create a payment when only booking_id present
            if (!$localPayment && $metaBookingId && $providerId) {
                $ins = [
                    'booking_id' => $metaBookingId,
                    'amount' => isset($attributes['amount']) ? $attributes['amount'] / 100 : null,
                    'payment_method' => $attributes['payment_method'] ?? null,
                    'payment_date' => date('Y-m-d H:i:s'),
                    'transaction_id' => $providerId,
                    'status' => 'Completed',
                    'notes' => 'Created from webhook ' . ($eventType ?? ''),
                    'created_at' => date('Y-m-d H:i:s')
                ];
                try {
                    $paymentModel->insert($ins);
                    $localPayment = $paymentModel->where('transaction_id', $providerId)->orderBy('created_at', 'DESC')->first();
                } catch (\Throwable $e) {
                    log_message('error', 'paymentWebhook: failed to create payment row: ' . $e->getMessage());
                }
            }

            // Decide action based on event type
            $now = date('Y-m-d H:i:s');
            $handled = false;

            $paidEvents = ['payment.paid', 'checkout_session.payment.paid', 'link.payment.paid'];
            $failedEvents = ['payment.failed'];
            $refundEvents = ['payment.refunded', 'payment.refund.updated'];

            if (in_array($eventType, $paidEvents, true)) {
                if ($localPayment) {
                    $update = ['status' => 'Completed', 'payment_date' => $now];
                    if ($providerId) $update['transaction_id'] = $providerId;
                    $paymentModel->update($localPayment['payment_id'], $update);

                    // Update booking status
                    if (!empty($localPayment['booking_id'])) {
                        $bookingModel->update($localPayment['booking_id'], ['payment_status' => 'Paid', 'booking_status' => 'Confirmed', 'updated_at' => $now]);
                    }
                    $handled = true;
                } else {
                    // If we have booking id in metadata, create/mark payment
                    if ($metaBookingId) {
                        try {
                            $pm = $paymentModel->where('booking_id', $metaBookingId)->orderBy('created_at', 'DESC')->first();
                            if ($pm) {
                                $paymentModel->update($pm['payment_id'], ['status' => 'Completed', 'payment_date' => $now, 'transaction_id' => $providerId]);
                            } else {
                                $paymentModel->insert(['booking_id' => $metaBookingId, 'amount' => isset($attributes['amount']) ? $attributes['amount']/100 : null, 'transaction_id' => $providerId, 'status' => 'Completed', 'payment_date' => $now, 'created_at' => $now]);
                            }
                            $bookingModel->update($metaBookingId, ['payment_status' => 'Paid', 'booking_status' => 'Confirmed', 'updated_at' => $now]);
                            $handled = true;
                        } catch (\Throwable $e) {
                            log_message('error', 'paymentWebhook: failed to mark booking paid: ' . $e->getMessage());
                        }
                    }
                }
            } elseif (in_array($eventType, $failedEvents, true)) {
                if ($localPayment) {
                    $paymentModel->update($localPayment['payment_id'], ['status' => 'Failed', 'payment_date' => $now]);
                    if (!empty($localPayment['booking_id'])) {
                        $bookingModel->update($localPayment['booking_id'], ['payment_status' => 'Failed', 'updated_at' => $now]);
                    }
                    $handled = true;
                }
            } elseif (in_array($eventType, $refundEvents, true)) {
                if ($localPayment) {
                    $paymentModel->update($localPayment['payment_id'], ['status' => 'Refunded', 'updated_at' => $now]);
                    if (!empty($localPayment['booking_id'])) {
                        $bookingModel->update($localPayment['booking_id'], ['payment_status' => 'Refunded', 'booking_status' => 'Refunded', 'updated_at' => $now]);
                    }
                    $handled = true;
                }
            }

            // Log event for debugging
            log_message('info', 'paymentWebhook: event=' . ($eventType ?? 'unknown') . ' provider_id=' . ($providerId ?? '') . ' meta_booking=' . ($metaBookingId ?? '') . ' handled=' . ($handled ? '1' : '0'));

            return $this->response->setStatusCode(200)->setBody('ok');
        } catch (\Throwable $e) {
            log_message('error', 'paymentWebhook error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setBody('server error');
        }
    }

}
