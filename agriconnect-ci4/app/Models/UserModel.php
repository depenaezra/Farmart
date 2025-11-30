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
        'status'
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
            return $this->update($id, ['status' => $newStatus]);
        }
        return false;
    }
}
