<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TouristSpotModel;

class TouristApiController extends BaseController
{
    // AJAX endpoint for spot details and gallery (for modal)
    public function viewSpot($spot_id)
    {
        $spotModel = new TouristSpotModel();
        $result = $spotModel->getSpotDetailsWithGallery((int)$spot_id);
        if ($result['status'] === 'error') {
            return $this->response->setJSON(['error' => $result['message']], 404);
        }
        $spot = $result['data'];
        $gallery = $spot['gallery'] ?? [];
        unset($spot['gallery']);
        return $this->response->setJSON([
            'spot' => $spot,
            'gallery' => $gallery
        ]);
    }
}
