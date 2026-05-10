<?php

namespace App\Filters;

use App\Models\UserModel;
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
        $uri = $request->getUri();
        $path = $uri->getPath();

        // Allow OTP verification routes without full login
        if (str_starts_with($path, '/auth/login-verify') ||
            str_starts_with($path, '/auth/resend-login-otp')) {
            return null;
        }

        // Check if user is logged in
        if (!session()->has('logged_in') || !session()->get('logged_in') || !session()->get('user_id')) {
            // Check if user is in OTP verification flow (after password, before OTP)
            if (session()->has('otp_login_user_id') && session()->get('otp_login_user_id')) {
                return redirect()->to('/auth/login-verify');
            }

            // Clear stale session
            session()->destroy();

            // Store intended URL
            session()->set('redirect_url', current_url());
            
            // Redirect to login with error message
            return redirect()->to('/auth/login')
                ->with('error', 'Please login to access this page.');
        }

        // Enforce account status
        $userId = (int) session()->get('user_id');
        $user = (new UserModel())->find($userId);
        if (!$user || ($user['status'] ?? 'inactive') !== 'active') {
            session()->destroy();
            return redirect()->to('/auth/disabled');
        }

        // Check role permissions
        if ($arguments !== null && !empty($arguments)) {
            $userRole = session()->get('user_role');
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
