<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'location',
        'cooperative',
        'status',
        'login_suspended_until',
        'failed_login_attempts',
        'total_failed_login_attempts',
        'last_failed_login',
        'lockout_until',
        'security_email_sent'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[255]',
        'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
        'phone' => 'permit_empty|max_length[20]',
        'password' => 'required|min_length[8]',
        'role' => 'required|in_list[farmer,buyer,admin,user]',
        'location' => 'permit_empty|max_length[255]',
        'cooperative' => 'permit_empty|max_length[255]'
    ];
    
    protected $validationMessages = [
        'name' => [
            'required' => 'Name is required',
            'min_length' => 'Name must be at least 3 characters'
        ],
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please provide a valid email',
            'is_unique' => 'This email is already registered'
        ],
        'password' => [
            'required' => 'Password is required',
            'min_length' => 'Password must be at least 8 characters'
        ]
    ];
    
    // Don't auto-hash password (we'll do it manually in controller)
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];
    
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }
    
    /**
     * Get all farmers
     */
    public function getFarmers()
    {
        return $this->where('role', 'farmer')
                    ->where('status', 'active')
                    ->findAll();
    }
    
    /**
     * Get all buyers
     */
    public function getBuyers()
    {
        return $this->where('role', 'buyer')
                    ->where('status', 'active')
                    ->findAll();
    }
    
    /**
     * Get all admins
     */
    public function getAdmins()
    {
        return $this->where('role', 'admin')
                    ->where('status', 'active')
                    ->findAll();
    }
    
    /**
     * Get user by email
     */
    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }
    
    /**
     * Get user statistics
     */
    public function getStatistics()
    {
        return [
            'total' => $this->countAll(),
            'farmers' => $this->where('role', 'farmer')->countAllResults(false),
            'buyers' => $this->where('role', 'buyer')->countAllResults(false),
            'admins' => $this->where('role', 'admin')->countAllResults(false),
            'active' => $this->where('status', 'active')->countAllResults(false),
            'inactive' => $this->where('status', 'inactive')->countAllResults()
        ];
    }
    
    /**
     * Search users
     */
    public function searchUsers($keyword, $role = null)
    {
        $builder = $this->builder();
        
        $builder->groupStart()
                ->like('name', $keyword)
                ->orLike('email', $keyword)
                ->orLike('location', $keyword)
                ->groupEnd();
        
        if ($role) {
            $builder->where('role', $role);
        }
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Toggle user status
     */
    public function toggleStatus($id)
    {
        $user = $this->find($id);
        if ($user) {
            $newStatus = $user['status'] === 'active' ? 'inactive' : 'active';
            $payload = ['status' => $newStatus];

            // When re-enabling account status, also clear temporary login suspension.
            if ($newStatus === 'active') {
                $payload['login_suspended_until'] = null;
            }

            return $this->update($id, $payload);
        }
        return false;
    }

    /**
     * Increment failed login attempts for a user
     * Increments both recent (failed_login_attempts) and total (total_failed_login_attempts)
     */
    public function incrementFailedAttempt($userId)
    {
        $now = date('Y-m-d H:i:s');
        $builder = $this->builder();
        $builder->set('failed_login_attempts', 'failed_login_attempts + 1', false)
                ->set('total_failed_login_attempts', 'total_failed_login_attempts + 1', false)
                ->set('last_failed_login', $now)
                ->where('id', $userId)
                ->update();
        return $this->db->affectedRows() > 0;
    }

    /**
     * Reset failed login attempts for a user (on successful login)
     */
    public function resetFailedAttempts($userId)
    {
        return $this->update($userId, [
            'failed_login_attempts' => 0,
            'total_failed_login_attempts' => 0,
            'last_failed_login' => null,
            'lockout_until' => null,
            'security_email_sent' => 0
        ]);
    }

    /**
     * Check if user is currently locked out
     */
    public function isLockedOut($userId)
    {
        $user = $this->find($userId);
        if (!$user) {
            return false;
        }
        
        $lockoutUntil = $user['lockout_until'];
        if (!$lockoutUntil) {
            return false;
        }
        
        return strtotime($lockoutUntil) > time();
    }

    /**
     * Clear expired lockout and reset recent failure counter
     * Called before checking lockout status
     */
    public function clearExpiredLockout($userId)
    {
        $user = $this->find($userId);
        if ($user && $user['lockout_until']) {
            if (strtotime($user['lockout_until']) <= time()) {
                // Lockout expired - reset recent counters but keep total
                return $this->update($userId, [
                    'failed_login_attempts' => 0,
                    'last_failed_login' => null,
                    'lockout_until' => null
                ]);
            }
        }
        return false;
    }

    /**
     * Get lockout expiration time for a user
     */
    public function getLockoutUntil($userId)
    {
        $user = $this->find($userId);
        return $user ? $user['lockout_until'] : null;
    }

    /**
     * Set lockout for a user
     */
    public function setLockout($userId, $minutes = 5)
    {
        $lockoutUntil = date('Y-m-d H:i:s', time() + ($minutes * 60));
        return $this->update($userId, ['lockout_until' => $lockoutUntil]);
    }

    /**
     * Check if security email should be sent (after 10 failed attempts)
     */
    public function shouldSendSecurityEmail($userId)
    {
        $user = $this->find($userId);
        if (!$user) {
            return false;
        }
        
        return ($user['failed_login_attempts'] >= 10 && 
                $user['security_email_sent'] == 0);
    }

    /**
     * Mark security email as sent
     */
    public function markSecurityEmailSent($userId)
    {
        return $this->update($userId, ['security_email_sent' => 1]);
    }

    /**
     * Get total failed login attempts count (lifetime)
     */
    public function getTotalFailedAttempts($userId)
    {
        $user = $this->find($userId);
        return $user ? (int)$user['total_failed_login_attempts'] : 0;
    }

    /**
     * Get failed login attempts count
     */
    public function getFailedAttempts($userId)
    {
        $user = $this->find($userId);
        return $user ? (int)$user['failed_login_attempts'] : 0;
    }
}
