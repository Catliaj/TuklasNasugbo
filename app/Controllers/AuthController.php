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

        $email = $this->request->getPost('InputEmail');
        $password = $this->request->getPost('InputPassword');

        // Find user by email
        $user = $model->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            // ✅ Store session data
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

            // ✅ Redirect user based on role
            switch (strtolower($user['role'])) {
                case 'spot owner':
                    return redirect()->to(base_url('/spotowner/dashboard'))->with('success', 'Login successful!');
                case 'admin':
                    return redirect()->to(base_url('/admin/dashboard'))->with('success', 'Login successful!');
                default:
                    return redirect()->to(base_url('/admin/dashboard'))->with('success', 'Login successful!');
            }
        }

        // ❌ Invalid credentials
        $session->setFlashdata('error', 'Incorrect email or password.');
        return redirect()->back();
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('/'))->with('success', 'You have been logged out.');
    }
}
