<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Check if user is authenticated and has required role
     *
     * @param RequestInterface $request
     * @param array|null $arguments - Array of allowed roles
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if user is logged in
        if (!session()->has('logged_in') || !session()->get('logged_in')) {
            // Store intended URL
            session()->set('redirect_url', current_url());
            
            // Redirect to login with error message
            return redirect()->to('/auth/login')
                ->with('error', 'Please login to access this page.');
        }

        // If specific roles are required
        if ($arguments !== null && !empty($arguments)) {
            $userRole = session()->get('user_role');
            
            // Check if user's role is in allowed roles
            if (!in_array($userRole, $arguments)) {
                return redirect()->to('/')
                    ->with('error', 'You do not have permission to access this page.');
            }
        }

        return null;
    }

    /**
     * Allows after filter to be executed
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param array|null $arguments
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
