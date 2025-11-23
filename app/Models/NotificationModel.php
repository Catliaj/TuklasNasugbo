<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['user_id', 'message', 'url', 'is_read', 'created_at'];
    protected $useTimestamps = false;

    public function getLatestForAdmin($limit = 10)
    {
        return $this->orderBy('created_at', 'DESC')->limit($limit)->findAll();
    }

    public function getUnreadCount()
    {
        return $this->where('is_read', 0)->countAllResults();
    }

    public function markAllRead()
    {
        $db = \Config\Database::connect();
        return $db->table($this->table)->where('is_read', 0)->update(['is_read' => 1]);
    }
}
