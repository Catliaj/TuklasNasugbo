<?php

namespace App\Models;

use CodeIgniter\Model;

class EmailVerificationModel extends Model
{
    protected $table = 'email_verifications';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'email','otp_code','expires_at','attempts','verified_at','created_at','updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function createOrRefreshOtp(string $email): array
    {
        // Generate 6-digit numeric OTP
        $otp = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expires = date('Y-m-d H:i:s', time() + 600); // 10 minutes

        $existing = $this->where('email', $email)->first();
        if ($existing) {
            $this->update($existing['id'], [
                'otp_code' => $otp,
                'expires_at' => $expires,
                'attempts' => 0,
                'verified_at' => null,
            ]);
            $existing = $this->find($existing['id']);
            return $existing;
        }
        $id = $this->insert([
            'email' => $email,
            'otp_code' => $otp,
            'expires_at' => $expires,
            'attempts' => 0,
        ]);
        return $this->find($id);
    }

    public function verifyOtp(string $email, string $otp): bool
    {
        $row = $this->where('email',$email)->first();
        if (!$row) {
            return false;
        }
        // Check attempts limit
        if ($row['attempts'] >= 5) {
            return false;
        }
        // Expired?
        if (strtotime($row['expires_at']) < time()) {
            return false;
        }
        $match = hash_equals($row['otp_code'], $otp);
        $this->update($row['id'], [
            'attempts' => $row['attempts'] + 1,
            'verified_at' => $match ? date('Y-m-d H:i:s') : $row['verified_at']
        ]);
        return $match;
    }

    public function isVerified(string $email): bool
    {
        $row = $this->where('email',$email)->first();
        return $row && !empty($row['verified_at']);
    }
}
