<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * This method is called before a controller is executed.
     * It's the "security guard" that checks the user's credentials.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. Check if the 'isLoggedIn' session variable exists and is true.
        if (!session()->get('isLoggedIn')) {
            // If the user is not logged in, redirect them to the main login page.
            // We also send a flash message with an error.
            return redirect()->to(base_url('/'))->with('error', 'You must be logged in to access this page.');
        }

        // 2. If they are logged in, check if their role is 'Admin'.
        // This is crucial for protecting the admin-specific pages.
        if (session()->get('Role') !== 'Admin') {
            // If the user is logged in but does not have the 'Admin' role,
            // they are forbidden from accessing the page.
            // We redirect them away with an error message.
            return redirect()->to(base_url('/'))->with('error', 'Access Denied. You do not have permission to view this page.');
        }

        // If both checks pass, the filter does nothing and allows the request to continue to the controller.
    }

    /**
     * This method is called after a controller is executed.
     * We don't need to do anything here for authentication.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after the controller has run.
    }
}