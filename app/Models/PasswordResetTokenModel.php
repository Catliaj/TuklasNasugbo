<?php

namespace App\Models;

use CodeIgniter\Model;

class PasswordResetTokenModel extends Model
{
    protected $table = 'password_reset_tokens';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'token','email','user_id','expires_at','used_at','created_at'
    ];
    protected $useTimestamps = false;
}
