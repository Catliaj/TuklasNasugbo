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
        // Detect if request expects JSON (AJAX or Accept header)
        $isAjax = false;
        if (method_exists($request, 'isAJAX') && $request->isAJAX()) {
            $isAjax = true;
        }
        $accept = '';
        if (method_exists($request, 'getHeaderLine')) {
            $accept = $request->getHeaderLine('Accept');
        }
        if (!$isAjax && stripos($accept, 'application/json') !== false) {
            $isAjax = true;
        }

        // 1. Check if the 'isLoggedIn' session variable exists and is true.
        if (! session()->get('isLoggedIn')) {
            if ($isAjax) {
                return service('response')
                    ->setStatusCode(401)
                    ->setJSON(['error' => 'Unauthorized', 'message' => 'You must be logged in to access this resource.']);
            }

            return redirect()->to(base_url('/'))->with('error', 'You must be logged in to access this page.');
        }

        // If role arguments are provided in the filter usage, treat them as allowed roles.
        // Example in Filters config: ['auth' => ['Admin','SpotOwner']]
        $allowedRoles = [];
        if (! empty($arguments)) {
            $allowedRoles = is_array($arguments) ? $arguments : [$arguments];
        }

        if (! empty($allowedRoles)) {
            $role = session()->get('Role');
            if (! in_array($role, $allowedRoles)) {
                if ($isAjax) {
                    return service('response')
                        ->setStatusCode(403)
                        ->setJSON(['error' => 'Forbidden', 'message' => 'You do not have permission to access this resource.']);
                }

                return redirect()->to(base_url('/'))->with('error', 'Access Denied. You do not have permission to view this page.');
            }
        }

        // If no allowed roles specified, any logged-in user is permitted to continue.
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