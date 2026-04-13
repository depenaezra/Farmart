<?php

namespace App\Models;

use CodeIgniter\Model;

class BlockedEmailModel extends Model
{
    protected $table = 'blocked_emails';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'email',
        'blocked_by',
        'blocked_at',
        'reason'
    ];
    
    protected $useTimestamps = false; // We'll handle blocked_at manually
    
    protected $validationRules = [
        'email' => 'required|valid_email|max_length[255]',
        'blocked_by' => 'required|integer',
        'reason' => 'permit_empty|max_length[500]'
    ];
    
    protected $validationMessages = [
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please provide a valid email',
            'max_length' => 'Email must be less than 255 characters'
        ]
    ];
    
    /**
     * Check if email is blocked
     */
    public function isBlocked($email)
    {
        return $this->where('email', strtolower(trim($email)))->first() !== null;
    }
    
    /**
     * Block an email
     */
    public function blockEmail($email, $blockedBy, $reason = null)
    {
        $data = [
            'email' => strtolower(trim($email)),
            'blocked_by' => $blockedBy,
            'blocked_at' => date('Y-m-d H:i:s'),
            'reason' => $reason
        ];
        
        return $this->insert($data);
    }
    
    /**
     * Unblock an email
     */
    public function unblockEmail($email)
    {
        return $this->where('email', strtolower(trim($email)))->delete();
    }
    
    /**
     * Get all blocked emails with admin info
     */
    public function getBlockedEmailsWithAdmins()
    {
        return $this->select('blocked_emails.*, users.name as blocked_by_name')
                    ->join('users', 'users.id = blocked_emails.blocked_by')
                    ->orderBy('blocked_at', 'DESC')
                    ->findAll();
    }
}