<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\TouristSpotModel;
use App\Models\SpotViewLogModel;


class AttractionsController extends BaseController
{
    public function index()
    {
        //
    }

    
      public function topSpotsAjax($limit = 6)
    {
        $limit = (int) $limit;
        $limit = $limit > 0 ? $limit : 6;

        try {
            $model = new TouristSpotModel();
            $spots = $model->getTopSpotsByViews($limit);

            if (!is_array($spots)) {
                // defensive: ensure we return a consistent JSON structure
                return $this->response->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => 'Failed to retrieve attractions'
                ]);
            }

            $data = array_map(function ($s) {
                return [
                    'spot_id'       => $s['spot_id'] ?? null,
                    'spot_name'     => $s['spot_name'] ?? '',
                    'category'      => $s['category'] ?? '',
                    'location'      => $s['location'] ?? '',
                    'primary_image' => $s['primary_image'] ?? null,
                    'short_description' => $s['short_description'] ?? ($s['description'] ?? ''),
                    'views'         => isset($s['views']) ? (int)$s['views'] : 0,
                    'price_per_person' => isset($s['price_per_person']) ? (float)$s['price_per_person'] : 0
                ];
            }, $spots);

            return $this->response->setJSON(['success' => true, 'data' => $data]);
        } catch (\Throwable $e) {
            log_message('error', 'topSpotsAjax error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => 'Server error']);
        }
    }

    /**
     * POST /api/attractions/view
     * Body: JSON { spot_id: int }
     * Records a view in spot_view_logs and increments tourist_spots.view_count.
     */
    public function logViewAjax()
    {
        try {
            $input = $this->request->getJSON(true) ?? $this->request->getPost();

            $spotId = isset($input['spot_id']) ? (int)$input['spot_id'] : null;
            if (!$spotId) {
                return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'spot_id required']);
            }

            $session = session();
            $userId = $session->get('UserID') ?? null;

            $logModel = new SpotViewLogModel();
            $insertData = [
                'spot_id'   => $spotId,
                'user_id'   => $userId,
                'viewed_at' => date('Y-m-d H:i:s')
            ];
            $logModel->insert($insertData);

            // Safely increment view_count if column exists
            $db = \Config\Database::connect();
            try {
                $db->table('tourist_spots')
                    ->set('view_count', 'COALESCE(view_count,0) + 1', false)
                    ->where('spot_id', $spotId)
                    ->update();
            } catch (\Throwable $e) {
                // don't fail the request just because view_count column is missing
                log_message('warning', 'logViewAjax increment view_count failed: ' . $e->getMessage());
            }

            return $this->response->setJSON(['success' => true]);
        } catch (\Throwable $e) {
            log_message('error', 'logViewAjax error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => 'Server error']);
        }
    }

}
