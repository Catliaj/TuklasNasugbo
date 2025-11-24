<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'message',
        'url',
        'is_read',
        'created_at'
    ];
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';

    /**
     * Create a notification
     */
    public function createNotification($data)
    {
        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        return $this->insert($data);
    }

    /**
     * Get unread notifications for a user
     */
    public function getUnreadNotifications($userId, $limit = 10)
    {
        return $this->where('user_id', $userId)
                    ->where('is_read', 0)
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get all notifications for a user (or all if userId is null for admin)
     */
    /**
 * Get all notifications for a user (or all if userId is null for admin)
 */
public function getUserNotifications($userId = null, $limit = 20)
{
    $builder = $this->orderBy('created_at', 'DESC')->limit($limit);
    
    if ($userId !== null) {
        $builder->where('user_id', $userId);
    } else {
        // For admins: get notifications where user_id IS NULL
        $builder->where('user_id IS NULL', null, false);
    }
    
    return $builder->findAll();
}

    /**
     * Get unread count for a user (or all unread if userId is null for admin)
     */
   /**
 * Get unread count for a user (or all unread if userId is null for admin)
 */
public function getUnreadCount($userId = null)
{
    $builder = $this->where('is_read', 0);
    
    if ($userId !== null) {
        $builder->where('user_id', $userId);
    } else {
        // For admins: count notifications where user_id IS NULL
        $builder->where('user_id IS NULL', null, false);
    }
    
    return $builder->countAllResults();
}

    /**
     * Mark notification as read
     */
    public function markAsRead($notificationId)
    {
        return $this->update($notificationId, [
            'is_read' => 1
        ]);
    }

    /**
     * Mark all as read for a user (or all if userId is null)
     */
    /**
 * Mark all as read for a user (or all if userId is null)
 */
public function markAllAsRead($userId = null)
{
    $builder = $this->where('is_read', 0);
    
    if ($userId !== null) {
        $builder->where('user_id', $userId);
    } else {
        // For admins: mark all where user_id IS NULL
        $builder->where('user_id IS NULL', null, false);
    }
    
    return $builder->set(['is_read' => 1])->update();
}

    /**
     * Delete old read notifications (cleanup)
     */
    public function deleteOldNotifications($days = 30)
    {
        $date = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        return $this->where('is_read', 1)
                    ->where('created_at <', $date)
                    ->delete();
    }

    /**
     * Notify all admins (user_id = NULL means all admins see it)
     */
    public function notifyAdmins($message, $url = null)
    {
        return $this->insert([
            'user_id' => null, // NULL means visible to all admins
            'message' => $message,
            'url' => $url,
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}