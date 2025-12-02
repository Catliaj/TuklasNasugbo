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
        // Try common session keys first. Accept multiple naming conventions (uppercase/lowercase).
        $businessId = $session->get('business_id') ?? $session->get('owner_business_id') ?? $session->get('BusinessID') ?? $session->get('BusinessId') ?? null;

        if ($businessId) {
            return (int) $businessId;
        }

        // Fallback: if user id exists under common keys, try to fetch business_id from tourist_spots table
        $userId = $session->get('user_id') ?? $session->get('owner_id') ?? $session->get('UserID') ?? $session->get('userId') ?? null;
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

        // Normalize rows and ensure month ordering
        $normalized = array_map(function($r) {
            return [
                'month'      => $r['month'] ?? null,
                'month_name' => $r['month_name'] ?? (date('M', strtotime(($r['month'] ?? date('Y-m-01'))))),
                'revenue'    => (float) ($r['revenue'] ?? 0),
                'bookings'   => (int) ($r['bookings'] ?? 0)
            ];
        }, $rows ?: []);

        // Determine month keys (ensure last $months months even if some are empty)
        $monthsKeys = [];
        if (!empty($normalized)) {
            foreach ($normalized as $r) if (!empty($r['month'])) $monthsKeys[] = $r['month'];
            $monthsKeys = array_values(array_unique($monthsKeys));
            sort($monthsKeys, SORT_STRING);
        } else {
            // generate last N month keys
            for ($i = $months - 1; $i >= 0; $i--) {
                $monthsKeys[] = date('Y-m', strtotime("-{$i} months"));
            }
        }

        // Build month => revenue map for prev comparison
        $monthMap = [];
        foreach ($normalized as $row) {
            if (!empty($row['month'])) $monthMap[$row['month']] = (float)$row['revenue'];
        }

        $prevMap = [];
        for ($i = 0; $i < count($monthsKeys); $i++) {
            $m = $monthsKeys[$i];
            $prevMap[$m] = $i > 0 ? ($monthMap[$monthsKeys[$i-1]] ?? 0.0) : 0.0;
        }

        // Compose ordered normalized output (include prev and change percent)
        $out = [];
        foreach ($monthsKeys as $m) {
            $curr = (float) ($monthMap[$m] ?? 0);
            $prev = $prevMap[$m] ?? 0.0;
            $change = 0.0;
            if ($prev > 0.0) {
                $change = round((($curr - $prev) / $prev) * 100, 1);
            } else {
                $change = ($prev == 0.0 && $curr > 0.0) ? 100.0 : 0.0;
            }
            $out[] = [
                'month' => $m,
                'month_name' => date('M', strtotime($m . '-01')),
                'revenue' => $curr,
                'bookings' => (int)($normalized[array_search($m, array_column($normalized, 'month'))]['bookings'] ?? 0),
                'prev_revenue' => $prev,
                'change_percent' => $change
            ];
        }

        // Additionally provide per-spot monthly series across all spots with confirmed bookings
        $db = \Config\Database::connect();
        $startDate = date('Y-m-d', strtotime('-' . ($months) . ' months'));
        $endDate = date('Y-m-d');
        $monthIndexMap = array_flip($monthsKeys);

        $spotSeriesRows = $db->table('bookings b')
            ->select("ts.spot_id, ts.spot_name, DATE_FORMAT(b.booking_date, '%Y-%m') as month, SUM(b.total_price) as revenue", false)
            ->join('tourist_spots ts', 'b.spot_id = ts.spot_id')
            ->where('ts.business_id', $businessId)
            ->whereIn('b.booking_status', ['Confirmed', 'Completed', 'Checked-in', 'Checked-out', 'Checked-In', 'Checked-Out'])
            ->where('b.payment_status', 'Paid')
            ->where('DATE(b.booking_date) >=', $startDate)
            ->where('DATE(b.booking_date) <=', $endDate)
            ->groupBy('ts.spot_id, month')
            ->orderBy('ts.spot_id')
            ->orderBy('month')
            ->get()
            ->getResultArray();

        $spotSeries = [];
        foreach ($spotSeriesRows as $row) {
            $spotId = (int) ($row['spot_id'] ?? 0);
            $monthKey = $row['month'] ?? null;
            if (!$spotId || !$monthKey || !isset($monthIndexMap[$monthKey])) {
                continue;
            }

            if (!isset($spotSeries[$spotId])) {
                $spotSeries[$spotId] = [
                    'spot_id' => $spotId,
                    'spot_name' => $row['spot_name'] ?? ('Spot ' . $spotId),
                    'series' => array_fill(0, count($monthsKeys), 0.0)
                ];
            }

            $spotSeries[$spotId]['series'][$monthIndexMap[$monthKey]] = (float) ($row['revenue'] ?? 0);
        }

        $by_spot = array_values($spotSeries);

        return $this->response->setJSON([
            'months' => $monthsKeys,
            'monthly' => $out,
            'by_spot' => $by_spot
        ]);
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
