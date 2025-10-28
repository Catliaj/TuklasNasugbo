<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class SpotOwnerController extends BaseController
{
    public function dashboard()
    {
        return view('Pages/spotowner/home');
    }

    public function mySpots()
    {
        return view('Pages/spotowner/manage-spot');
    }

    public function bookings()
    {
        return view('Pages/spotowner/bookings');
    }

    public function earnings()
    {
        return view('Pages/spotowner/earnings');
    }

    public function settings()
    {
        return view('Pages/spotowner/profile');
    }
}
