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
        $request = service('request');

        $firstName  = $request->getPost('firstName');
        $middleName = $request->getPost('middleName');
        $lastName   = $request->getPost('lastName');
        $email      = $request->getPost('email');
        $role       = $request->getPost('role');
        $password   = $request->getPost('password');
        $confirmPw  = $request->getPost('confirmPassword');

        // Validate password match
        if ($password !== $confirmPw) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Passwords do not match'
            ]);
        }

        // Hash password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Load models
        $usersModel    = new \App\Models\UsersModel();
        $customerModel = new \App\Models\CustomerModel();
        $businessModel = new \App\Models\BusinessModel();

        // Save user
        $userData = [
            'FirstName' => $firstName,
            'MiddleName' => $middleName,
            'LastName' => $lastName,
            'email' => $email,
            'password' => $passwordHash,
            'role' => $role,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $usersModel->insert($userData);
        $userId = $usersModel->getInsertID();

        if (!$userId) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to create user'
            ]);
        }

        // =================================================
        // TOURIST EXTRA FIELDS
        // =================================================
        if ($role === 'tourist') {
            $customerModel->insert([
                'user_id' => $userId,
                'type' => 'tourist',
                'phone' => $request->getPost('touristContact'),
                'address' => $request->getPost('touristAddress'),
                'emergency_contact' => $request->getPost('emergencyContact'),
                'emergency_phone' => $request->getPost('emergencyNumber'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        // =================================================
        // SPOT OWNER EXTRA FIELDS + FILE UPLOAD
        // =================================================
        if ($role === 'Spot Owner') {

            $idFile = $this->request->getFile('govIdImage');
            $fileName = null;

            // Ensure upload folder exists (prevents crash)
            $uploadPath = FCPATH . 'uploads/id';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // File Upload
            if ($idFile && $idFile->isValid() && !$idFile->hasMoved()) {

                // Validate image type
                $allowed = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!in_array($idFile->getMimeType(), $allowed)) {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Invalid ID image format. Only JPG and PNG allowed.'
                    ]);
                }

                $fileName = $idFile->getRandomName();
                $idFile->move($uploadPath, $fileName);
            }

            // Insert business data
            $businessModel->insert([
                'user_id' => $userId,
                'business_name' => $request->getPost('businessName'),
                'contact_phone' => $request->getPost('businessContact'),
                'business_address' => $request->getPost('businessAddress'),
                'gov_id_type' => $request->getPost('govIdType'),
                'gov_id_number' => $request->getPost('govIdNumber'),
                'gov_id_image' => $fileName,
                'status' => 'Pending',
                'rejection_reason' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Account created successfully'
        ]);
    }




 
}
