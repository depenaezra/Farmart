<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Blocks admin accounts from buyer/marketplace-side authenticated routes.
 */
class NonAdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session()->get('user_role') === 'admin') {
            return redirect()->to(base_url('admin/dashboard'))
                ->with('error', 'Administrator accounts use the admin panel only.');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
