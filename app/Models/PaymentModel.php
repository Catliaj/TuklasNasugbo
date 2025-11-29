<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'payment_id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'booking_id', 'amount', 'payment_method', 'payment_date', 'transaction_id', 'reference_number', 'status', 'notes', 'processed_by', 'created_at'
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
}
