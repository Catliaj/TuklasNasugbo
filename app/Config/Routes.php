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
$routes->get('/users/logout', 'AuthController::logout');

//Admin routes
$routes->group('admin', ['namespace' => 'App\Controllers', 'filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'AdminController::dashboard');
    $routes->get('reports', 'AdminController::reports');
    $routes->get('settings', 'AdminController::settings');
    
    // REGISTRATIONS PAGE ROUTES
    $routes->get('registrations', 'AdminController::registrations');
    $routes->get('registrations/list', 'AdminController::getRegistrationList');
    $routes->get('registrations/view/(:num)', 'AdminController::viewRegistration/$1');
    $routes->post('registrations/approve/(:num)', 'AdminController::approveRegistration/$1');
    $routes->post('registrations/reject/(:num)', 'AdminController::rejectRegistration/$1');


    $routes->get('attractions', 'AdminController::attractions'); // Route to display the page
    $routes->get('attractions/list', 'AdminController::getAttractionList'); // API to get all attractions
    $routes->get('attractions/view/(:num)', 'AdminController::viewAttraction/$1'); // API for modal details
    $routes->post('attractions/suspend/(:num)', 'AdminController::suspendAttraction/$1'); // API to SUSPEND an attraction
    $routes->post('attractions/delete/(:num)', 'AdminController::deleteAttraction/$1'); // API to DELETE an attraction
   
});



//spot owner routes
$routes->get('/spotowner/dashboard', 'SpotOwnerController::dashboard');
$routes->get('/spotowner/mySpots', 'SpotOwnerController::mySpots');
$routes->get('/spotowner/bookings', 'SpotOwnerController::bookings');
$routes->get('/spotowner/earnings', 'SpotOwnerController::earnings');
$routes->get('/spotowner/settings', 'SpotOwnerController::settings');

//spot owner earnings API routes - ADD THESE THREE LINES
$routes->get('spotowner/api/monthly-revenue', 'SpotOwnerController::getMonthlyRevenueData');
$routes->get('spotowner/api/weekly-revenue', 'SpotOwnerController::getWeeklyRevenueData');
$routes->get('spotowner/api/booking-trends', 'SpotOwnerController::getBookingTrendsData');





//spot owner crud bookings routes
$routes->get('/spotowner/getBookings', 'SpotOwnerController::getBookings');
$routes->get('/spotowner/getBooking/(:num)', 'SpotOwnerController::getBooking/$1');
$routes->post('/spotowner/confirmBooking/(:num)', 'SpotOwnerController::confirmBooking/$1');
$routes->post('/spotowner/rejectBooking/(:num)', 'SpotOwnerController::rejectBooking/$1');
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