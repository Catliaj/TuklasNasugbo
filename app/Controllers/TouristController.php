<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

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
        return view('Pages/tourist/itinerary', [
            'userID' => session()->get('UserID'),
            'FullName' => session()->get('FirstName') . ' ' . session()->get('LastName'),
            'email' => session()->get('Email'),
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
}
