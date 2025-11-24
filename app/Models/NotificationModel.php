<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table      = 'notifications';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id',
        'message',
        'url',
        'is_read',
        'created_at',
    ];

    // If you want CI to manage created_at automatically, set this true and define $createdField.
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    // protected $createdField  = 'created_at';

    /**
     * Create a notification
     *
     * @param array $data
     * @return int|false Insert ID or false on failure
     */
    public function createNotification(array $data)
    {
        if (! $this->useTimestamps && ! isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return $this->insert($data);
    }

    /**
     * Get unread notifications for a user
     *
     * @param int $userId
     * @param int $limit
     * @return array
     */
    public function getUnreadNotifications(int $userId, int $limit = 10): array
    {
        return $this->where('user_id', $userId)
                    ->where('is_read', 0)
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get notifications for a user (or admin notifications when $userId is null)
     *
     * @param int|null $userId
     * @param int $limit
     * @return array
     */
    public function getUserNotifications(?int $userId = null, int $limit = 20): array
    {
        $this->orderBy('created_at', 'DESC')->limit($limit);

        if ($userId !== null) {
            $this->where('user_id', $userId);
        } else {
            // Notifications for admins (user_id IS NULL)
            $this->where('user_id IS NULL', null, false);
        }

        return $this->findAll();
    }

    /**
     * Get unread count for a user (or for admins if $userId is null)
     *
     * @param int|null $userId
     * @return int
     */
    public function getUnreadCount(?int $userId = null): int
    {
        $this->where('is_read', 0);

        if ($userId !== null) {
            $this->where('user_id', $userId);
        } else {
            $this->where('user_id IS NULL', null, false);
        }

        return (int) $this->countAllResults();
    }

    /**
     * Mark single notification as read
     *
     * @param int $notificationId
     * @return bool
     */
    public function markAsRead(int $notificationId): bool
    {
        return (bool) $this->update($notificationId, ['is_read' => 1]);
    }

    /**
     * Mark all as read for a user (or for admin notifications when $userId is null)
     *
     * @param int|null $userId
     * @return bool
     */
    public function markAllAsRead(?int $userId = null): bool
    {
        $builder = $this->builder(); // explicit builder
        $builder->where('is_read', 0);

        if ($userId !== null) {
            $builder->where('user_id', $userId);
        } else {
            $builder->where('user_id IS NULL', null, false);
        }

        return (bool) $builder->set(['is_read' => 1])->update();
    }

    /**
     * Delete old read notifications (cleanup)
     *
     * @param int $days
     * @return bool
     */
    public function deleteOldNotifications(int $days = 30): bool
    {
        $date = date('Y-m-d H:i:s', strtotime("-{$days} days"));

        return (bool) $this->where('is_read', 1)
                           ->where('created_at <', $date)
                           ->delete();
    }

    /**
     * Notify all admins (user_id = NULL means visible to admins)
     *
     * @param string $message
     * @param string|null $url
     * @return int|false
     */
    public function notifyAdmins(string $message, ?string $url = null)
    {
        return $this->insert([
            'user_id'    => null,
            'message'    => $message,
            'url'        => $url,
            'is_read'    => 0,
            'created_at' => $this->useTimestamps ? null : date('Y-m-d H:i:s'),
        ]);
    }
}