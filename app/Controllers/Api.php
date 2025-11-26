<?php

namespace App\Controllers\SpotOwner;

use App\Controllers\BaseController;
use App\Models\BookingModel;

class Api extends BaseController
{
    protected $bookingModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
    }

    /**
     * Helper: resolve business ID for current logged in owner
     */
    protected function resolveBusinessId()
    {
        $session = session();
        // Try common session keys first
        $businessId = $session->get('business_id') ?? $session->get('owner_business_id') ?? null;

        if ($businessId) {
            return (int) $businessId;
        }

        // Fallback: if user_id is owner id, try to fetch business_id from tourist_spots table
        $userId = $session->get('user_id') ?? $session->get('owner_id') ?? null;
        if ($userId) {
            $db = \Config\Database::connect();
            $row = $db->table('tourist_spots')
                      ->select('business_id')
                      ->where('owner_id', $userId)
                      ->limit(1)
                      ->get()
                      ->getRow();
            if ($row && isset($row->business_id)) {
                return (int) $row->business_id;
            }
        }

        return null;
    }

    /**
     * GET /spotowner/api/monthly-revenue
     * Returns array of last N months: [{ month: '2025-06', month_name: 'Jun', revenue: 1234.00, bookings: 12 }, ...]
     */
    public function monthlyRevenue()
    {
        $businessId = $this->resolveBusinessId();
        if (! $businessId) {
            return $this->response->setStatusCode(401)->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        // BookingModel::getMonthlyRevenueByBusiness($businessID, $months = 6)
        $months = 6;
        $rows = $this->bookingModel->getMonthlyRevenueByBusiness($businessId, $months);

        // Normalize and compute prev-month comparison fields for frontend
        $normalized = array_map(function($r) {
            return [
                'month'      => $r['month'] ?? null,
                'month_name' => $r['month_name'] ?? (date('M', strtotime(($r['month'] ?? date('Y-m-01'))))),
                'revenue'    => (float) ($r['revenue'] ?? 0),
                'bookings'   => (int) ($r['bookings'] ?? 0)
            ];
        }, $rows ?: []);

        // Build month => revenue map for prev comparison
        $monthMap = [];
        foreach ($normalized as $row) {
            if (!empty($row['month'])) $monthMap[$row['month']] = (float)$row['revenue'];
        }
        $monthsKeys = array_keys($monthMap);
        sort($monthsKeys, SORT_STRING);
        $prevMap = [];
        for ($i = 0; $i < count($monthsKeys); $i++) {
            $m = $monthsKeys[$i];
            $prevMap[$m] = $i > 0 ? $monthMap[$monthsKeys[$i-1]] : 0.0;
        }

        foreach ($normalized as &$r) {
            $m = $r['month'];
            $prev = $prevMap[$m] ?? 0.0;
            $curr = (float)$r['revenue'];
            $r['prev_revenue'] = $prev;
            if ($prev > 0.0) {
                $r['change_percent'] = round((($curr - $prev) / $prev) * 100, 1);
            } else {
                $r['change_percent'] = $prev == 0.0 && $curr > 0.0 ? 100.0 : 0.0;
            }
        }

        return $this->response->setJSON($normalized);
    }

    /**
     * GET /spotowner/api/weekly-revenue
     * Returns array of last N weeks: [{ week_num: '2025WW45', week_start:'Nov 03', week_end:'Nov 09', revenue: 1234.00, bookings: 10 }, ...]
     */
    public function weeklyRevenue()
    {
        $businessId = $this->resolveBusinessId();
        if (! $businessId) {
            return $this->response->setStatusCode(401)->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $weeks = 8;
        $rows = $this->bookingModel->getWeeklyRevenueByBusiness($businessId, $weeks);

        $out = array_map(function($r) {
            return [
                'week_num'   => $r['week_num'] ?? null,
                'week_start' => $r['week_start'] ?? null,
                'week_end'   => $r['week_end'] ?? null,
                'revenue'    => (float) ($r['revenue'] ?? 0),
                'bookings'   => (int) ($r['bookings'] ?? 0)
            ];
        }, $rows ?: []);

        return $this->response->setJSON($out);
    }

    /**
     * GET /spotowner/api/booking-trends
     * Returns rows like [{ month: '2025-11', month_name: 'Nov', booking_status: 'Confirmed', count: 12 }, ...]
     */
    public function bookingTrends()
    {
        $businessId = $this->resolveBusinessId();
        if (! $businessId) {
            return $this->response->setStatusCode(401)->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $months = 6;
        $rows = $this->bookingModel->getBookingTrendsByBusiness($businessId, $months);

        // Aggregate counts per month (sum across statuses) so frontend receives monthly totals
        $monthTotals = [];
        foreach ($rows as $r) {
            $m = $r['month'] ?? null;
            if (!$m) continue;
            $monthTotals[$m] = ($monthTotals[$m] ?? 0) + (int)($r['count'] ?? 0);
        }

        // Build ordered result array
        $keys = array_keys($monthTotals);
        sort($keys, SORT_STRING);

        $out = [];
        foreach ($keys as $k) {
            $out[] = [
                'month' => $k,
                'bookings' => $monthTotals[$k]
            ];
        }

        return $this->response->setJSON($out);
    }
}