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

        $violations = $builder->get()->getResultArray();

        // Fetch reported item details for each violation
        $db = \Config\Database::connect();
        foreach ($violations as &$violation) {
            $violation['item_exists'] = false; // Track if item still exists
            
            switch ($violation['reported_type']) {
                case 'forum_post':
                    $violation['reported_item'] = $db->table('forum_posts')
                        ->select('content')
                        ->where('id', $violation['reported_id'])
                        ->get()
                        ->getRowArray();
                    $violation['item_exists'] = !empty($violation['reported_item']);
                    break;
                case 'product':
                    $violation['reported_item'] = $db->table('products')
                        ->select('name, image_url')
                        ->where('id', $violation['reported_id'])
                        ->get()
                        ->getRowArray();
                    $violation['item_exists'] = !empty($violation['reported_item']);
                    if ($violation['reported_item'] && $violation['reported_item']['image_url']) {
                        $violation['reported_item']['images'] = [$violation['reported_item']['image_url']];
                        unset($violation['reported_item']['image_url']);
                    } else {
                        $violation['reported_item']['images'] = [];
                    }
                    break;
                case 'user':
                    $violation['reported_item'] = $db->table('users')
                        ->select('name, email')
                        ->where('id', $violation['reported_id'])
                        ->get()
                        ->getRowArray();
                    $violation['item_exists'] = !empty($violation['reported_item']);
                    break;
            }
        }

        return $violations;
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