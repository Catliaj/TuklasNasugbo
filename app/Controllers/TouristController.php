<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UsersModel;

class TouristController extends BaseController
{
    public function touristDashboard()
    {
        return view('Pages/tourist/dashboard', [
            'userID' => session()->get('UserID'),
            'FullName' => session()->get('FirstName') . ' ' . session()->get('LastName'),
            'email' => session()->get('Email'),
        ]);
    }

    public function exploreSpots()
    {
        return view('Pages/tourist/explore', [
            'userID' => session()->get('UserID'),
            'FullName' => session()->get('FirstName') . ' ' . session()->get('LastName'),
            'email' => session()->get('Email'),
        ]);
    }

    public function myBookings()
    {
        return view('Pages/tourist/bookings', [
            'userID' => session()->get('UserID'),
            'FullName' => session()->get('FirstName') . ' ' . session()->get('LastName'),
            'email' => session()->get('Email'),
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


    //function for the history will get the title, date, how much, 
}
