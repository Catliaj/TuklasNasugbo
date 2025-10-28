<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class AdminController extends BaseController
{
    public function dashboard()
    {
        return view('Pages/admin/dashboard');
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
