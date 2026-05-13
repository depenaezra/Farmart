<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * When an admin is logged in, keep them out of public marketplace-style URLs.
 */
class RedirectAdminFromClientFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session()->get('logged_in') && session()->get('user_role') === 'admin') {
            return redirect()->to(base_url('admin/dashboard'))
                ->with('error', 'Use the admin panel to manage the site.');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
