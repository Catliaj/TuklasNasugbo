<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

//Auth Routes
$routes->get('/', 'AuthController::login'); 
$routes->post('/users/login', 'AuthController::Handlelogin');


//Admin routes
$routes->get('/admin/dashboard', 'AdminController::dashboard');
$routes->get('/admin/registrations', 'AdminController::registrations');
$routes->get('/admin/attractions', 'AdminController::attractions');
$routes->get('/admin/reports', 'AdminController::reports');
$routes->get('/admin/settings', 'AdminController::settings');

//spot owner routes
$routes->get('/spotowner/dashboard', 'SpotOwnerController::dashboard');
$routes->get('/spotowner/mySpots', 'SpotOwnerController::mySpots');
$routes->get('/spotowner/bookings', 'SpotOwnerController::bookings');
$routes->get('/spotowner/earnings', 'SpotOwnerController::earnings');
$routes->get('/spotowner/settings', 'SpotOwnerController::settings');




