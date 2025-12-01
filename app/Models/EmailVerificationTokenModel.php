<?php

namespace App\Models;

use CodeIgniter\Model;

class EmailVerificationTokenModel extends Model
{
    protected $table            = 'email_verification_tokens';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'token', 'email', 'payload', 'expires_at', 'used_at', 'created_at', 'updated_at'
    ];

    protected $useTimestamps = false;
}
