<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsersModel;
use App\Models\EmailVerificationModel;
use App\Models\EmailVerificationTokenModel;
use Google_Client;
use Google_Service_Oauth2;

class AuthController extends BaseController
{
    public function login()
    {
        return view('Pages/landing_page');
    }

    public function handleLogin()
    {
        helper(['form']);
        $session = session();
        $model = new UsersModel();
        $businessModel = new \App\Models\BusinessModel();

        $email = $this->request->getPost('InputEmail');
        $password = $this->request->getPost('InputPassword');
        $user = $model->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            $redirectURL = '';
            if (in_array($user['role'], ['Admin', 'Tourist'])) {
                $session->set([
                    'isLoggedIn' => true,
                    'UserID' => $user['UserID'],
                    'Email' => $user['email'],
                    'Role' => $user['role'],
                    'FirstName' => $user['FirstName'],
                    'MiddleName' => $user['MiddleName'],
                    'LastName' => $user['LastName']
                ]);
                $redirectURL = ($user['role'] === 'Admin') ? '/admin/dashboard' : '/tourist/dashboard';
            } elseif ($user['role'] === 'Spot Owner') {
                $business = $businessModel->where('user_id', $user['UserID'])->first();
                if (!$business) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'No business record found.']);
                }
                switch (strtolower($business['status'])) {
                    case 'approved':
                        $session->set([
                            'isLoggedIn' => true,
                            'UserID' => $user['UserID'],
                            'Email' => $user['email'],
                            'Role' => $user['role'],
                            'FirstName' => $user['FirstName'],
                            'MiddleName' => $user['MiddleName'],
                            'LastName' => $user['LastName'],
                            'BusinessID' => $business['business_id'] ?? null,
                            'BusinessName' => $business['business_name'] ?? null
                        ]);
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
        $session = session();
        $email = $request->getPost('email');
        if (!str_ends_with(strtolower($email), '@gmail.com')) {
            return $this->response->setJSON(['status' => 'error','message' => 'Email must be a valid gmail.com address']);
        }
        $payload = [
            'FirstName' => $request->getPost('firstName'),
            'MiddleName' => $request->getPost('middleName'),
            'LastName' => $request->getPost('lastName'),
            'email' => $email,
            'role' => $request->getPost('role'),
            'password_plain' => $request->getPost('password'),
            'confirmPassword' => $request->getPost('confirmPassword'),
            'touristContact' => $request->getPost('touristContact'),
            'touristAddress' => $request->getPost('touristAddress'),
            'emergencyContact' => $request->getPost('emergencyContact'),
            'emergencyNumber' => $request->getPost('emergencyNumber'),
            'businessName' => $request->getPost('businessName'),
            'businessContact' => $request->getPost('businessContact'),
            'businessAddress' => $request->getPost('businessAddress'),
            'govIdType' => $request->getPost('govIdType'),
            'govIdNumber' => $request->getPost('govIdNumber'),
        ];
        if ($payload['password_plain'] !== $payload['confirmPassword']) {
            return $this->response->setJSON(['status' => 'error','message' => 'Passwords do not match']);
        }
        // Create a signed verification token record that carries the registration payload
        $tokenModel = new EmailVerificationTokenModel();
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', time() + 60 * 60 * 24); // 24 hours

        $tokenModel->insert([
            'token' => $token,
            'email' => $email,
            'payload' => json_encode($payload),
            'expires_at' => $expiresAt,
            'used_at' => null,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $sent = $this->sendVerificationEmail($email, $token);
        if (!$sent) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to send verification email. Please check email settings and try again.'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'verification_sent',
            'message' => 'We sent a verification link to your Gmail. Please click Verify to complete your registration.'
        ]);
    }

    private function finalizeSignup(array $payload)
    {
        $usersModel    = new \App\Models\UsersModel();
        $customerModel = new \App\Models\CustomerModel();
        $businessModel = new \App\Models\BusinessModel();
        $passwordHash = password_hash($payload['password_plain'], PASSWORD_DEFAULT);
        $userData = [
            'FirstName' => $payload['FirstName'],
            'MiddleName' => $payload['MiddleName'],
            'LastName' => $payload['LastName'],
            'email' => $payload['email'],
            'password' => $passwordHash,
            'role' => $payload['role'],
            'email_verified' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $usersModel->insert($userData);
        $userId = $usersModel->getInsertID();
        if (!$userId) {
            return ['status' => 'error','message' => 'Failed to create user'];
        }
        if ($payload['role'] === 'tourist') {
            $customerModel->insert([
                'user_id' => $userId,
                'type' => 'tourist',
                'phone' => $payload['touristContact'],
                'address' => $payload['touristAddress'],
                'emergency_contact' => $payload['emergencyContact'],
                'emergency_phone' => $payload['emergencyNumber'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
        if ($payload['role'] === 'Spot Owner') {
            $idFile = $this->request->getFile('govIdImage');
            $fileName = null;
            $uploadPath = FCPATH . 'uploads/id';
            if (!is_dir($uploadPath)) { mkdir($uploadPath, 0755, true); }
            if ($idFile && $idFile->isValid() && !$idFile->hasMoved()) {
                $allowed = ['image/jpeg','image/png','image/jpg'];
                if (in_array($idFile->getMimeType(), $allowed)) {
                    $fileName = $idFile->getRandomName();
                    $idFile->move($uploadPath, $fileName);
                }
            }
            $businessModel->insert([
                'user_id' => $userId,
                'business_name' => $payload['businessName'],
                'contact_phone' => $payload['businessContact'],
                'business_address' => $payload['businessAddress'],
                'gov_id_type' => $payload['govIdType'],
                'gov_id_number' => $payload['govIdNumber'],
                'gov_id_image' => $fileName,
                'status' => 'Pending',
                'rejection_reason' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            try {
                $notifModel = new \App\Models\NotificationModel();
                $notifModel->insert([
                    'user_id' => null,
                    'message' => 'New spot owner registration: ' . $payload['businessName'],
                    'url' => '',
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            } catch (\Exception $e) {
                log_message('error','Failed to insert notification: '.$e->getMessage());
            }
        }
        return ['status' => 'success','message' => 'Account created successfully'];
    }

    public function sendOtpEmail(string $email, string $otp): bool
    {
        $emailService = \Config\Services::email();
        $emailConfig  = config('Email');

        // Ensure From is explicitly set
        if (!empty($emailConfig->fromEmail)) {
            $emailService->setFrom($emailConfig->fromEmail, $emailConfig->fromName ?: 'Tuklas Nasugbu');
        }

        $emailService->setTo($email);
        $emailService->setSubject('Your TuklasNasugbo Verification Code');
        $body = '<p>Your verification code is:</p><h2 style="letter-spacing:4px">'.esc($otp).'</h2><p>This code expires in 10 minutes.</p>';
        $emailService->setMessage($body);
        
        try {
            $sent = $emailService->send();
            if (!$sent) {
                // Capture detailed debug info to logs for troubleshooting
                $debug = method_exists($emailService, 'printDebugger') ? $emailService->printDebugger(['headers','subject','body']) : 'send() returned false without debug info';
                log_message('error', 'OTP email send failed (false return). Debug: '.$debug);
            }
            return $sent;
        } catch (\Throwable $e) {
            log_message('error','OTP email send exception: '.$e->getMessage());
            return false;
        }
    }

    /**
     * Sends a verification link to the provided Gmail address.
     */
    // (removed: legacy sendVerificationEmail)
        private function sendVerificationEmail(string $email, string $token): bool
        {
            $emailService = \Config\Services::email();
            $appConfig = new \Config\App();
            $appName = env('app.name', 'Tuklas Nasugbu');
            $baseURL = rtrim($appConfig->baseURL, '/');
            $verifyUrl = $baseURL . '/verify-email?token=' . urlencode($token);

            // Theming values (fallbacks if not present in .env)
            $primaryColor = env('theme.primaryColor', '#0d6efd'); // Bootstrap primary
            $accentColor  = env('theme.accentColor', '#22c55e');  // Emerald
            $textColor    = env('theme.textColor', '#1f2937');
            $logoUrl      = rtrim($baseURL, '/') . '/fulllogo.png';

            $supportEmail = env('email.support', env('email.fromAddress'));
            $footerLinks = [
                'Visit Website' => $baseURL,
                'Privacy' => $baseURL . '/privacy',
                'Help' => $baseURL . '/help',
            ];

            // Ensure From and Subject are non-empty strings to avoid CI Email errors
            $fromAddress = env('email.fromAddress');
            $fromName    = env('email.fromName', $appName) ?: $appName;
            if (empty($fromAddress)) {
                // Safe default for local dev
                $fromAddress = 'no-reply@localhost';
            }

            $emailService->setTo($email);
            $emailService->setFrom($fromAddress, $fromName);
            $subject = 'Verify your Gmail for ' . ($appName ?: 'Tuklas Nasugbu');
            if (!is_string($subject) || $subject === '') {
                $subject = 'Verify your email';
            }
            $emailService->setSubject($subject);

            // Render themed view
            $viewData = compact('appName', 'verifyUrl', 'supportEmail', 'primaryColor', 'accentColor', 'textColor', 'logoUrl', 'footerLinks');
            $message = view('Emails/verify_email', $viewData);

            $emailService->setMessage($message);
            $emailService->setMailType('html');

            // Plaintext alternative (fallback for some clients)
            $altMessage = 'Verify your email for ' . $appName . "\n\n" . $verifyUrl . "\n\nIf you didn't create an account, ignore this email.";
            if (method_exists($emailService, 'setAltMessage')) {
                $emailService->setAltMessage($altMessage);
            }

            $sent = $emailService->send();
            if (!$sent) {
                log_message('error', 'Verification email failed: ' . $emailService->printDebugger(['headers', 'subject', 'body']));
            }
            return $sent;
        }

    public function showOtpForm()
    {
        return view('Pages/verify_otp');
    }

    public function verifyOtp()
    {
        $request = service('request');
        $email = $request->getPost('email');
        $code = $request->getPost('otp');
        $otpModel = new EmailVerificationModel();
        if (!$otpModel->verifyOtp($email, $code)) {
            return $this->response->setJSON(['status' => 'error','message' => 'Invalid or expired code']);
        }
        $session = session();
        $payload = $session->get('pending_signup');
        if (!$payload || $payload['email'] !== $email) {
            return $this->response->setJSON(['status' => 'error','message' => 'No pending signup found']);
        }
        $result = $this->finalizeSignup($payload);
        $session->remove('pending_signup');
        return $this->response->setJSON($result);
    }

    /**
     * GET /verify-email?token=...
     * Consumes verification token, creates the account, and redirects to login with a flash message.
     */
    public function verifyEmail()
    {
        $token = $this->request->getGet('token');
        if (!$token) {
            return redirect()->to(base_url('/'))->with('error','Missing verification token');
        }

        $tokenModel = new EmailVerificationTokenModel();
        $row = $tokenModel->where('token', $token)->first();
        if (!$row) {
            return redirect()->to(base_url('/'))->with('error','Invalid verification link');
        }
        if (!empty($row['used_at'])) {
            return redirect()->to(base_url('/'))->with('error','This verification link was already used');
        }
        if (strtotime($row['expires_at']) < time()) {
            return redirect()->to(base_url('/'))->with('error','Verification link has expired');
        }

        $payload = json_decode($row['payload'], true) ?: [];
        if (empty($payload) || (strtolower($payload['email'] ?? '') !== strtolower($row['email']))) {
            return redirect()->to(base_url('/'))->with('error','Verification data invalid');
        }

        $result = $this->finalizeSignup($payload);
        if (($result['status'] ?? '') === 'success') {
            // Mark token as used
            $tokenModel->update($row['id'], ['used_at' => date('Y-m-d H:i:s')]);
            return redirect()->to(base_url('/'))->with('success','Email verified! Your account has been created. You can now log in.');
        }

        return redirect()->to(base_url('/'))->with('error', $result['message'] ?? 'Failed to create account');
    }

    public function googleRedirect()
    {
        $client = $this->buildGoogleClient();
        $client->setScopes(['openid','email','profile']);
        return redirect()->to($client->createAuthUrl());
    }

    public function googleCallback()
    {
        $code = $this->request->getGet('code');
        if (!$code) {
            return redirect()->to('/signup')->with('error','Missing Google authorization code');
        }
        $client = $this->buildGoogleClient();
        $token = $client->fetchAccessTokenWithAuthCode($code);
        if (isset($token['error'])) {
            return redirect()->to('/signup')->with('error','Google auth failed');
        }
        $client->setAccessToken($token);
        $oauth2 = new Google_Service_Oauth2($client);
        $info = $oauth2->userinfo->get();
        $email = strtolower($info->email);
        if (!str_ends_with($email,'@gmail.com') || !$info->verifiedEmail) {
            return redirect()->to('/signup')->with('error','Google email must be verified Gmail');
        }
        $users = new UsersModel();
        $user = $users->where('google_id',$info->id)->orWhere('email',$email)->first();
        if ($user) {
            if (empty($user['google_id'])) {
                $users->update($user['UserID'], ['google_id' => $info->id,'email_verified' => 1]);
            }
        } else {
            $users->insert([
                'FirstName' => $info->givenName ?? 'Google',
                'MiddleName' => null,
                'LastName' => $info->familyName ?? 'User',
                'email' => $email,
                'password' => password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT),
                'role' => 'Tourist',
                'google_id' => $info->id,
                'email_verified' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            $user = $users->where('email',$email)->first();
        }
        session()->set([
            'isLoggedIn' => true,
            'UserID' => $user['UserID'],
            'Email' => $user['email'],
            'Role' => $user['role'],
            'FirstName' => $user['FirstName'],
            'MiddleName' => $user['MiddleName'],
            'LastName' => $user['LastName']
        ]);
        return redirect()->to('/tourist/dashboard');
    }

    private function buildGoogleClient(): Google_Client
    {
        $client = new Google_Client();
        $clientId = getenv('GOOGLE_CLIENT_ID') ?: config('App')->googleClientId ?? '';
        $clientSecret = getenv('GOOGLE_CLIENT_SECRET') ?: config('App')->googleClientSecret ?? '';
        $redirect = getenv('GOOGLE_REDIRECT_URI') ?: base_url('auth/google/callback');
        $client->setClientId($clientId);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri($redirect);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        return $client;
    }
}

