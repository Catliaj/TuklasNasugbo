<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\BusinessModel;
use App\Models\TouristSpotModel;
use App\Models\BookingModel;
use App\Models\UsersModel;
use App\Models\SpotGalleryModel;

class SpotOwnerController extends BaseController
{
    public function dashboard()
    {
        if (!session()->get('isLoggedIn') || session()->get('Role') !== 'Spot Owner') {
        return redirect()->to(base_url('/login'))->with('error', 'Please log in as Spot Owner to access the Spot Owner dashboard.');
        }

        $userID = session()->get('UserID');
         $businessModel = new BusinessModel();
        $touristSpotModel = new TouristSpotModel();

        $userID = session()->get('UserID');
        $businessData = $businessModel->where('user_id', $userID)->first();
        $businessID = $businessData['business_id'];
        $spots = $touristSpotModel->getSpotsByBusinessID($businessID);
        $data['spots'] = $spots;
        
        return view('Pages/spotowner/home', [
            'userID' => $userID,
            'FullName' => session()->get('FirstName') . ' ' . session()->get('LastName'),
            'email' => session()->get('Email'),
        ]);
    }

    public function mySpots()
    {
        $businessModel = new BusinessModel();
        $touristSpotModel = new TouristSpotModel();

        $userID = session()->get('UserID');
        $businessData = $businessModel->where('user_id', $userID)->first();
        $businessID = $businessData['business_id'];
        $spots = $touristSpotModel->getSpotsByBusinessID($businessID);
        $data['spots'] = $spots;


        return view('Pages/spotowner/manage-spot', [
            'userID' => $userID,
            'FullName' => session()->get('FirstName') . ' ' . session()->get('LastName'),
            'email' => session()->get('Email'),
            'spots' => $data['spots'],

        ]);
    }

    public function bookings()
    {
        return view('Pages/spotowner/bookings');
    }

    public function earnings()
    {
        return view('Pages/spotowner/earnings');
    }

    public function settings()
    {
        return view('Pages/spotowner/profile');
    }



    public function storeMySpots()
    {
        try {
            $businessModel = new BusinessModel();
            $touristSpotModel = new TouristSpotModel();
            $spotGalleryModel = new \App\Models\SpotGalleryModel();

            // Get logged-in user’s business
            $userID = session()->get('UserID');
            $businessData = $businessModel->where('user_id', $userID)->first();

            if (!$businessData) {
                return redirect()->back()->with('error', 'Business information not found.');
            }

            // --- Prepare spot data ---
            $data = [
                'business_id'          => $businessData['business_id'],
                'spot_name'            => $this->request->getPost('spot_name'),
                'description'          => $this->request->getPost('description'),
                'latitude'             => $this->request->getPost('latitude'),
                'longitude'            => $this->request->getPost('longitude'),
                'category'             => $this->request->getPost('category'),
                'location'             => $this->request->getPost('location'),
                'capacity'             => $this->request->getPost('capacity'),
                'opening_time'         => $this->request->getPost('opening_time'),
                'closing_time'         => $this->request->getPost('closing_time'),
                'operating_days'       => implode(', ', (array)$this->request->getPost('operating_days')),
                'status'               => 'pending',
                'price_per_person'     => $this->request->getPost('price_per_person'),
                'child_price'          => $this->request->getPost('child_price'),
                'senior_price'         => $this->request->getPost('senior_price'),
                'group_discount_percent' => $this->request->getPost('group_discount_percent'),
                'created_at'           => date('Y-m-d H:i:s'),
                'updated_at'           => date('Y-m-d H:i:s')
            ];

            // --- Handle primary image ---
            $primaryImage = $this->request->getFile('primary_image');
            if ($primaryImage && $primaryImage->isValid() && !$primaryImage->hasMoved()) {
                $newName = $primaryImage->getRandomName();
                $uploadPath = FCPATH . 'uploads/spots/';

                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $primaryImage->move($uploadPath, $newName);
                $data['primary_image'] = $newName; // store filename in tourist_spots table
            }

            // --- Insert tourist spot ---
            $spotId = $touristSpotModel->insert($data);

            if (!$spotId) {
                $error = $touristSpotModel->errors();
                log_message('error', '[storeMySpots] Tourist spot insert failed: ' . print_r($error, true));
                return redirect()->back()->with('error', 'Failed to add tourist spot.');
            }

            // --- Handle gallery images (multiple) ---
            $galleryImages = $this->request->getFiles();
            if (isset($galleryImages['gallery_images'])) {
                foreach ($galleryImages['gallery_images'] as $image) {
                    if ($image->isValid() && !$image->hasMoved()) {
                        $newName = $image->getRandomName();
                        $galleryPath = FCPATH . 'uploads/spots/gallery/';

                        if (!is_dir($galleryPath)) {
                            mkdir($galleryPath, 0777, true);
                        }

                        $image->move($galleryPath, $newName);

                        // insert to SpotGallery model
                        $spotGalleryModel->insert([
                            'spot_id' => $spotId,
                            'image' => $newName
                        ]);
                    }
                }
            }

            // --- Success ---
            return redirect()->to('/spotowner/mySpots')
                            ->with('success', 'Tourist spot added successfully!');

        } catch (\Exception $e) {
            log_message('error', '[storeMySpots] Exception: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An unexpected error occurred while adding the tourist spot.');
        }
    }


    public function getMySpots()
    {
        $userId = session()->get('UserID'); 
        if (!$userId) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Unauthorized']);
        }

        $businessModel = new BusinessModel();
        $spotModel = new TouristSpotModel();
        $galleryModel = new SpotGalleryModel();

        // 1️⃣ Find the business linked to this user
        $business = $businessModel->where('user_id', $userId)->first();
        if (!$business) {
            return $this->response->setJSON([]); // no business, no spots
        }

        // 2️⃣ Get tourist spots for that business
        // ⚠️ use correct column name in your DB, usually `business_id`
        $spots = $spotModel->where('business_id', $business['business_id'])->findAll();

        // 3️⃣ For each spot, attach gallery images and normalize field names
        foreach ($spots as &$spot) {
            // Get all images in SpotGallery related to this spot
            $spot['images'] = $galleryModel->where('spot_id', $spot['spot_id'])->findColumn('image');

            // Map field names to match your JS expectations
            $spot['id'] = $spot['spot_id'];
            $spot['name'] = $spot['spot_name'];
            $spot['image'] = base_url('uploads/spots/' . $spot['primary_image']);
            $spot['status'] = $spot['status'] ?? 'inactive';
            $spot['price'] = $spot['price_per_person'];
            $spot['maxVisitors'] = $spot['capacity'];
            $spot['openTime'] = $spot['opening_time'];
            $spot['closeTime'] = $spot['closing_time'];
            $spot['rating'] = $spot['rating'] ?? 0;
            $spot['reviews'] = $spot['reviews'] ?? 0;

            // Convert image paths for frontend (if gallery exists)
            if (!empty($spot['images'])) {
                $spot['images'] = array_map(fn($img) => base_url('uploads/spots/gallery/' . $img), $spot['images']);
            }
        }

        return $this->response->setJSON($spots);
    }

    public function getSpot($id)
    {
        $spotModel = new \App\Models\TouristSpotModel();
        $spot = $spotModel->find($id);

        if (!$spot) {
            return $this->response->setJSON(['error' => 'Spot not found']);
        }

        // Optionally include gallery images
        $galleryModel = new \App\Models\SpotGalleryModel();
        $spot['images'] = array_map(fn($g) => base_url('uploads/spots/gallery/' . $g['image']),
                                    $galleryModel->where('spot_id', $id)->findAll());

        return $this->response->setJSON($spot);
    }






}
