<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

//Auth Routes
$routes->get('/under-development', 'Home::index'); 
$routes->get('/', 'AuthController::login'); 
$routes->get('/signup', 'AuthController::signup'); 
$routes->post('/users/login', 'AuthController::Handlelogin');


//Admin routes
$routes->group('admin', ['namespace' => 'App\Controllers', 'filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'AdminController::dashboard');
    $routes->get('attractions', 'AdminController::attractions');
    $routes->get('reports', 'AdminController::reports');
    $routes->get('settings', 'AdminController::settings');
    
    // ==========================================================
    //  THESE ARE THE REQUIRED ROUTES FOR THE REGISTRATIONS PAGE
    // ==========================================================
    $routes->get('registrations', 'AdminController::registrations'); // Route to display the page
    $routes->get('registrations/list', 'AdminController::getRegistrationList'); // API to get all registrations
    $routes->get('registrations/view/(:num)', 'AdminController::viewRegistration/$1'); // API to get a single registration's details
    $routes->post('registrations/approve/(:num)', 'AdminController::approveRegistration/$1'); // API to APPROVE a registration
    $routes->post('registrations/reject/(:num)', 'AdminController::rejectRegistration/$1'); // API to REJECT a registration
    // ==========================================================
});


//spot owner routes
$routes->get('/spotowner/dashboard', 'SpotOwnerController::dashboard');
$routes->get('/spotowner/mySpots', 'SpotOwnerController::mySpots');
$routes->get('/spotowner/bookings', 'SpotOwnerController::bookings');
$routes->get('/spotowner/earnings', 'SpotOwnerController::earnings');
$routes->get('/spotowner/settings', 'SpotOwnerController::settings');

//spot owner crud routes

$routes->post('/spotowner/my-spots/store', 'SpotOwnerController::storeMySpots');
$routes->get('/spotowner/my-spots/data', 'SpotOwnerController::getMySpots');
$routes->get('spotowner/my-spots/get-spot/(:num)', 'SpotOwnerController::getSpot/$1');


$routes->get('/spotowner/spots/edit/(:num)', 'SpotOwnerController::editSpot/$1');
$routes->post('/spotowner/spots/update/(:num)', 'SpotOwnerController::updateSpot/$1');
$routes->post('/spotowner/spots/delete/(:num)', 'SpotOwnerController::deleteSpot/$1');



//Tourist routes
$routes->get('/tourist/dashboard', 'TouristController::touristDashboard');
$routes->get('/tourist/exploreSpots', 'TouristController::exploreSpots');
$routes->get('/tourist/myBookings', 'TouristController::myBookings');
$routes->get('/tourist/profile', 'TouristController::touristProfile');
$routes->get('/tourist/itinerary', 'TouristController::touristIternary');
$routes->get('/tourist/reviews', 'TouristController::touristReviews');
$routes->get('/tourist/visits', 'TouristController::touristVisits');
$routes->get('/tourist/budget', 'TouristController::touristBudget');
$routes->get('/tourist/favorites', 'TouristController::touristFavorites');


//TEST API ROUTE
$routes->get('/test-api/key', 'TestApi::testKey');