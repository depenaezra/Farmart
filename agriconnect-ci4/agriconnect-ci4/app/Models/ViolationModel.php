<?php

namespace App\Models;

use CodeIgniter\Model;

class ViolationModel extends Model
{
    protected $table = 'violations';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'reporter_id',
        'reported_type',
        'reported_id',
        'reason',
        'description',
        'status',
        'reviewed_at',
        'reviewed_by'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'reviewed_at';
    
    protected $validationRules = [
        'reporter_id' => 'required|integer',
        'reported_type' => 'required|in_list[forum_post,forum_comment,product,user]',
        'reported_id' => 'required|integer',
        'reason' => 'required|max_length[255]',
        'description' => 'permit_empty'
    ];
    
    /**
     * Report a violation
     */
    public function reportViolation($data)
    {
        return $this->insert($data);
    }
    
    /**
     * Get violations for admin
     */
    public function getViolationsForAdmin($status = null)
    {
        $builder = $this->select('violations.*, 
                                   reporter.name as reporter_name,
                                   reviewer.name as reviewer_name')
                        ->join('users as reporter', 'reporter.id = violations.reporter_id', 'left')
                        ->join('users as reviewer', 'reviewer.id = violations.reviewed_by', 'left')
                        ->orderBy('violations.created_at', 'DESC');
        
        if ($status) {
            $builder->where('violations.status', $status);
        }
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Update violation status
     */
    public function updateStatus($id, $status, $adminId)
    {
        return $this->update($id, [
            'status' => $status,
            'reviewed_by' => $adminId
        ]);
    }
    
    /**
     * Get pending violations count
     */
    public function getPendingCount()
    {
        return $this->where('status', 'pending')->countAllResults();
    }
}