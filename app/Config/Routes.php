
<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/under-development', 'Home::index'); 
$routes->get('/', 'AuthController::login'); 
$routes->get('/users/login', 'AuthController::login');
$routes->get('/signup', 'AuthController::signup'); 
$routes->post('/users/login', 'AuthController::Handlelogin');
$routes->post('signup/submit', 'AuthController::handleSignup');
$routes->get('/users/logout', 'AuthController::logout');

//Admin routes
$routes->group('admin', ['namespace' => 'App\Controllers', 'filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'AdminController::dashboard');
    $routes->get('reports', 'AdminController::reports');
    $routes->get('settings', 'AdminController::settings');
    

    // REGISTRATIONS PAGE ROUTES
    $routes->get('registrations', 'AdminController::registrations');
    $routes->get('registrations/list', 'AdminController::getRegistrationList');
    $routes->get('registrations/pending-count', 'AdminController::getRegistrationsPendingCount');
    $routes->get('registrations/view/(:num)', 'AdminController::viewRegistration/$1');
    $routes->post('registrations/approve/(:num)', 'AdminController::approveRegistration/$1');
    $routes->post('registrations/reject/(:num)', 'AdminController::rejectRegistration/$1');


    // ATTRACTIONS ROUTES
    $routes->get('attractions', 'AdminController::attractions');
    $routes->get('attractions/list', 'AdminController::getAttractionList');
    $routes->get('attractions/pending-count', 'AdminController::getAttractionsPendingCount');
    $routes->get('attractions/pending', 'AdminController::getPendingAttractionList');
    $routes->post('attractions/approve/(:num)', 'AdminController::approveAttraction/$1');
    $routes->post('attractions/reject/(:num)', 'AdminController::rejectAttraction/$1');
    $routes->get('attractions/view/(:num)', 'AdminController::viewAttraction/$1');
    $routes->post('attractions/suspend/(:num)', 'AdminController::suspendAttraction/$1');
    $routes->post('attractions/delete/(:num)', 'AdminController::deleteAttraction/$1');

    // ==========================================================
    //  ADD THIS LINE TO FIX THE 404 NOT FOUND ERROR
    // ==========================================================
    $routes->post('reports/analytics', 'AdminController::getAnalytics');
    // Notifications API for admin
    $routes->get('notifications/list', 'AdminController::getNotificationsList');
    $routes->get('notifications/unread-count', 'AdminController::getUnreadNotificationsCount');
    $routes->post('notifications/mark-read', 'AdminController::markNotificationsRead');
   
});



//spot owner routes
$routes->get('/spotowner/dashboard', 'SpotOwnerController::dashboard');
$routes->get('/spotowner/mySpots', 'SpotOwnerController::mySpots');
$routes->get('/spotowner/bookings', 'SpotOwnerController::bookings');
$routes->get('/spotowner/earnings', 'SpotOwnerController::earnings');
$routes->get('/spotowner/settings', 'SpotOwnerController::settings');
$routes->get('spotowner/my-spots/data', 'SpotOwnerController::getMySpots');
$routes->get('spotowner/my-spots/get-spot/(:num)', 'SpotOwnerController::getSpot/$1');

// Spot Owner Notification Routes
$routes->get('spotowner/notifications/unread-count', 'SpotOwnerController::getUnreadNotificationCount');
$routes->get('spotowner/notifications/list', 'SpotOwnerController::getNotifications');
$routes->post('spotowner/notifications/mark-read/(:num)', 'SpotOwnerController::markNotificationAsRead/$1');
$routes->post('spotowner/notifications/mark-all-read', 'SpotOwnerController::markAllNotificationsAsRead');

//spot owner earnings API routes - point chart endpoints to SpotOwner\Api controller
$routes->get('spotowner/api/monthly-revenue', 'SpotOwner\Api::monthlyRevenue');
$routes->get('spotowner/api/weekly-revenue', 'SpotOwner\Api::weeklyRevenue');
$routes->get('spotowner/api/booking-trends', 'SpotOwner\Api::bookingTrends');
$routes->get('spotowner/api/dashboard-analytics', 'SpotOwnerController::getDashboardAnalytics');
$routes->get('spotowner/api/spot-analytics/(:num)', 'SpotOwnerController::getSpotAnalytics/$1');





//spot owner crud bookings routes
$routes->get('/spotowner/getBookings', 'SpotOwnerController::getBookings');
$routes->get('/spotowner/getBooking/(:num)', 'SpotOwnerController::getBooking/$1');
$routes->post('/spotowner/confirmBooking/(:num)', 'SpotOwnerController::confirmBooking/$1');
$routes->post('/spotowner/rejectBooking/(:num)', 'SpotOwnerController::rejectBooking/$1');
$routes->post('/spotowner/my-spots/store', 'SpotOwnerController::storeMySpots');
$routes->post('spotowner/my-spots/update/(:num)', 'SpotOwnerController::updateSpot/$1');


$routes->get('/spotowner/spots/edit/(:num)', 'SpotOwnerController::editSpot/$1');
$routes->post('/spotowner/spots/update/(:num)', 'SpotOwnerController::updateSpot/$1');
$routes->post('/spotowner/spots/delete/(:num)', 'SpotOwnerController::deleteSpot/$1');

//Tourist routes
$routes->get('/tourist/dashboard', 'TouristController::touristDashboard');
$routes->get('/tourist/exploreSpots', 'TouristController::exploreSpots');
$routes->get('/tourist/myBookings', 'TouristController::myBookings');
$routes->get('/tourist/profile', 'TouristController::touristProfile');
$routes->get('/tourist/itinerary', 'TouristController::touristIternary');
// Recommended spots for Add Activity modal
$routes->get('/tourist/recommendedSpots', 'TouristController::recommendedSpots');
$routes->get('/tourist/reviews', 'TouristController::touristReviews');
$routes->get('/tourist/visits', 'TouristController::touristVisits');
$routes->get('/tourist/budget', 'TouristController::touristBudget');
$routes->get('/tourist/favorites', 'TouristController::touristFavorites');

// Ajax endpoints for tourist actions
$routes->post('/tourist/createBooking', 'TouristController::createBooking');
$routes->post('/tourist/toggleFavorite', 'TouristController::toggleFavorite');
// Create payment intent and return checkout URL
$routes->post('/tourist/createPaymentIntent', 'TouristController::createPaymentIntent');
// Check payment status for a booking (used by client to detect if already paid)
$routes->get('/tourist/checkPayment/(:num)', 'TouristController::checkBookingPayment/$1');
$routes->get('tourist/visited/ajax', 'TouristController::getVisitedPlacesAjax');
// Favorites API for dashboard AJAX
$routes->get('/tourist/getFavorites', 'TouristController::getFavorites');
// Dashboard live stats (AJAX)
$routes->get('/tourist/dashboardStats', 'TouristController::dashboardStats');
// Weather data for dashboard
$routes->get('/tourist/getWeather', 'TouristController::getWeather');
// Feedback/Review endpoints
$routes->post('/tourist/feedback', 'TouristController::createFeedback');
$routes->get('/tourist/feedback/(:num)', 'TouristController::getFeedback/$1');
$routes->put('/tourist/feedback/(:num)', 'TouristController::updateFeedback/$1');
$routes->delete('/tourist/feedback/(:num)', 'TouristController::deleteFeedback/$1');
// Get all reviews for a specific spot
$routes->get('/tourist/spot/(:num)/reviews', 'TouristController::getSpotReviews/$1');


// Save user category preferences
$routes->post('/tourist/savePreferences', 'TouristController::savePreferences');

// Cancel booking (tourist) - accepts optional POST body { reason: '...' }
$routes->post('/tourist/cancelBooking/(:num)', 'TouristController::cancelBooking/$1');

// Save review (AJAX)
$routes->post('/tourist/saveReview', 'TouristController::saveReview');


//TEST API ROUTE
$routes->get('/test-api/key', 'TestApi::testKey');

//itinerary routes

$routes->get('itinerary/list', 'TouristController::listUserTrips');
$routes->get('itinerary/get', 'TouristController::getTrip');
// Create itinerary (from UI modal)
$routes->post('itinerary/create', 'TouristController::createItinerary');
// API aliases for frontend
$routes->get('api/tourist-spots', 'TouristController::recommendedSpots');

// Route for tourist spot details page (view)
$routes->get('/tourist/spot/(:num)', 'TouristController::viewSpotDetails/$1');
// Route for AJAX spot details and gallery (modal/API)
$routes->get('/tourist/viewSpot/(:num)', 'TouristController::viewSpot/$1');
// allow GET (used by frontend fetch)
// Check-in token endpoints
$routes->get('tourist/generateCheckinToken/(:num)', 'TouristController::generateCheckinToken/$1');
$routes->post('tourist/generateCheckinToken/(:num)', 'TouristController::generateCheckinToken/$1');

// Badges (removed)

// Verify token (scanner uses POST)
$routes->post('tourist/verifyCheckinToken', 'TouristController::verifyCheckinToken');
// optional GET for quick manual testing (remove/disable in production)
$routes->get('tourist/verifyCheckinToken', 'TouristController::verifyCheckinToken');

// Spot owner endpoints
$routes->get('spotowner/getBookings', 'SpotOwnerController::getBookings');
$routes->get('spotowner/getBooking/(:num)', 'SpotOwnerController::getBooking/$1');
$routes->post('spotowner/recordCheckin', 'SpotOwnerController::recordCheckin');
$routes->post('spotowner/confirmBooking/(:num)', 'SpotOwnerController::confirmBooking/$1');
$routes->get('api/attractions/top/(:num)?', 'AttractionsController::topSpotsAjax/$1');
$routes->post('api/attractions/view', 'AttractionsController::logViewAjax');

// SpotOwner API endpoints for charts
$routes->get('spotowner/api/monthly-revenue', 'SpotOwner\Api::monthlyRevenue');
$routes->get('spotowner/api/weekly-revenue',  'SpotOwner\Api::weeklyRevenue');
$routes->get('spotowner/api/booking-trends',  'SpotOwner\Api::bookingTrends');
