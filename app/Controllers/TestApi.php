<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class TestApi extends BaseController
{
    public function testKey() {
    echo getenv('GEOAPIFY_KEY');
    }

}
