<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsersModel;

class AuthController extends BaseController
{
    public function login()
    {
        // Just show the login view
        return view('Pages/landing_page');
    }

public function handleLogin()
{
    helper(['form']);
    $session = session();
    $model = new UsersModel();
    $businessModel = new \App\Models\BusinessModel();

    //sample session saka na ayosin
    // if ($session->get('isLoggedIn')) {
    //     $role = strtolower($session->get('Role'));

    //     switch ($role) {
    //         case 'admin':
    //             return redirect()->to(base_url('/admin/dashboard'));
    //         case 'tourist':
    //             return redirect()->to(base_url('/tourist/dashboard'));
    //         case 'spot owner':
    //             return redirect()->to(base_url('/spotowner/dashboard'));
    //     }
    // }

    $email = $this->request->getPost('InputEmail');
    $password = $this->request->getPost('InputPassword');

    $user = $model->where('email', $email)->first();

    if ($user && password_verify($password, $user['password'])) {
        // --- handle roles ---
        $redirectURL = '';

        if (in_array($user['role'], ['Admin', 'Tourist'])) {
            $sessionData = [
                'isLoggedIn' => true,
                'UserID'     => $user['UserID'],
                'Email'      => $user['email'],
                'Role'       => $user['role'],
                'FirstName'  => $user['FirstName'],
                'MiddleName' => $user['MiddleName'],
                'LastName'   => $user['LastName']
            ];
            $session->set($sessionData);

            $redirectURL = ($user['role'] === 'Admin') ? '/admin/dashboard' : '/tourist/dashboard';
        } elseif ($user['role'] === 'Spot Owner') {
            $business = $businessModel->where('user_id', $user['UserID'])->first();

            if (!$business) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'No business record found.']);
            }

            switch (strtolower($business['status'])) {
                case 'approved':
                    $sessionData = [
                        'isLoggedIn' => true,
                        'UserID'     => $user['UserID'],
                        'Email'      => $user['email'],
                        'Role'       => $user['role'],
                        'FirstName'  => $user['FirstName'],
                        'MiddleName' => $user['MiddleName'],
                        'LastName'   => $user['LastName'],
                        'BusinessID' => $business['business_id'] ?? null,
                        'BusinessName' => $business['business_name'] ?? null
                    ];
                    $session->set($sessionData);
                    $redirectURL = '/spotowner/dashboard';
                    break;

                case 'pending':
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Your business is still pending approval.']);
                case 'rejected':
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Your business application was rejected.']);
                default:
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Unknown business status.']);
            }
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Login successful!', 'redirect' => base_url($redirectURL)]);
    }

    return $this->response->setJSON(['status' => 'error', 'message' => 'Incorrect email or password.']);
}



    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('/'))->with('success', 'You have been logged out.');
    }


    public function signup()
    {
    
        return view('Pages/signup');
    }

    public function handleSignup()
    {
        // Handle user registration logic here
        return null;
    }
}
