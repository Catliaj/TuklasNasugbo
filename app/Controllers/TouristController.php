<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UsersModel;
use App\Models\TouristSpotModel;

class TouristController extends BaseController
{
    public function touristDashboard()
    {
        $session = session();
        $userID = $session->get('UserID');

        // Load model
        $preferencesModel = new \App\Models\UserPreferenceModel();
        $itineraryModel = new \App\Models\ItineraryModel();
        // Find the preference record for this user
       $preference = $preferencesModel->where('user_id', $userID)->first();
        $preferenceID = $preference['preference_id'] ?? null; // extract the ID

        $TotalSaveItineray = 0;
        if ($preferenceID) {
            $TotalSaveItineray = $itineraryModel->countDistinctDates($preferenceID);
        }


        // Get preference_id if exists
        $preferenceID = $preference['preference_id'] ?? null;

        // Additional dashboard metrics
        $db = \Config\Database::connect();

        // 1) Places Visited (createuservisits table)
        try {
            $placesVisited = (int) $db->table('createuservisits')->where('user_id', $userID)->countAllResults();
        } catch (\Exception $e) {
            $placesVisited = 0;
        }

        // 2) Favorite spots (try common table names)
        $favoriteCount = 0;
        try {
            if ($db->tableExists('user_favorites')) {
                $favoriteCount = (int) $db->table('user_favorites')->where('user_id', $userID)->countAllResults();
            } elseif ($db->tableExists('favorites')) {
                $favoriteCount = (int) $db->table('favorites')->where('user_id', $userID)->countAllResults();
            } else {
                // fallback: check a favorites column on tourist_spots (unlikely)
                $favoriteCount = 0;
            }
        } catch (\Exception $e) {
            $favoriteCount = 0;
        }

        // 3) Upcoming bookings (BookingModel)
        $upcomingBookings = 0;
        try {
            $today = date('Y-m-d');
            $bookingModel = new \App\Models\BookingModel();
            $qb = $bookingModel->builder();
            if ($db->tableExists('bookings')) {
                $upcomingBookings = (int) $qb->where('user_id', $userID)
                                           ->where('date >=', $today)
                                           ->where('status !=', 'cancelled')
                                           ->countAllResults();
            }
        } catch (\Exception $e) {
            $upcomingBookings = 0;
        }

        // 4) Popular spots (top viewed) - attempt to use SpotViewLogModel if exists
        $popularSpots = [];
        try {
            if ($db->tableExists('spot_view_log') || $db->tableExists('spotviewlogs') || $db->tableExists('spot_view_logs')) {
                // try SpotViewLogModel
                $svlModel = new \App\Models\SpotViewLogModel();
                $builder = $db->table('spot_view_log');
                if (!$db->tableExists('spot_view_log')) {
                    // find actual table from model or common variants
                    if ($db->tableExists('spotviewlogs')) $builder = $db->table('spotviewlogs');
                    elseif ($db->tableExists('spot_view_logs')) $builder = $db->table('spot_view_logs');
                }
                // aggregate top 6
                $rows = $builder->select('spot_id, COUNT(*) as views')
                                ->groupBy('spot_id')
                                ->orderBy('views', 'DESC')
                                ->limit(6)
                                ->get()
                                ->getResultArray();
                $spotModel = new \App\Models\TouristSpotModel();
                foreach ($rows as $r) {
                    $s = $spotModel->where('spot_id', $r['spot_id'])->first();
                    if ($s) {
                        $s['views'] = $r['views'];
                        $popularSpots[] = $s;
                    }
                }
            } else {
                // fallback: take recent 6 spots
                $spotModel = new \App\Models\TouristSpotModel();
                $popularSpots = array_slice($spotModel->orderBy('created_at', 'DESC')->findAll(), 0, 6);
            }
        } catch (\Exception $e) {
            // fallback to recent spots
            $spotModel = new \App\Models\TouristSpotModel();
            $popularSpots = array_slice($spotModel->orderBy('created_at', 'DESC')->findAll(), 0, 6);
        }

        return view('Pages/tourist/dashboard', [
            'userID'            => $userID,
            'preferenceID'      => $preferenceID,
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


    //function for the history will get the title, date, how much, 

   
}
