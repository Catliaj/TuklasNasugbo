<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsersModel;

class AuthController extends BaseController
{
    public function login()
    {
        // Just show the login view
        return view('Pages/login');
    }

public function handleLogin()
{
    $session = session();
    $model = new UsersModel();
    $businessModel = new \App\Models\BusinessModel();

    $email = $this->request->getPost('InputEmail');
    $password = $this->request->getPost('InputPassword');

    $user = $model->where('email', $email)->first();

    if ($user && password_verify($password, $user['password'])) {
        
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


            switch (strtolower($user['role'])) {
                case 'admin':
                    return redirect()->to(base_url('/admin/dashboard'))->with('success', 'Login successful!');
                default:
                    return redirect()->to(base_url('/tourist/dashboard'))->with('success', 'Login successful!');
            }
        }

        else if ($user['role'] === 'Spot Owner') {
           
            $business = $businessModel->where('user_id', $user['UserID'])->first();

            if (!$business) {
                $session->setFlashdata('error', 'No business record found. Please contact the tourism office.');
                return redirect()->back();
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

                    return redirect()->to(base_url('/spotowner/dashboard'))->with('success', 'Login successful!');
                
                case 'pending':
                    $session->setFlashdata('error', 'Your business is still pending approval. Please wait for confirmation.');
                    return redirect()->back();

                case 'rejected':
                    $session->setFlashdata('error', 'Your business application was rejected. Please visit the tourism office for assistance.');
                    return redirect()->back();

                default:
                    $session->setFlashdata('error', 'Unknown business status. Please contact support.');
                    return redirect()->back();
            }
        }
    }

    $session->setFlashdata('error', 'Incorrect email or password.');
    return redirect()->back();
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
