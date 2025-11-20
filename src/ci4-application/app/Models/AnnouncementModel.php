<?php

namespace App\Models;

use CodeIgniter\Model;

class AnnouncementModel extends Model
{
    protected $table = 'announcements';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'title',
        'content',
        'category',
        'priority',
        'created_by'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'title' => 'required|min_length[5]|max_length[255]',
        'content' => 'required|min_length[20]',
        'category' => 'required|in_list[weather,government,market,general]',
        'priority' => 'required|in_list[low,medium,high]',
        'created_by' => 'required|integer'
    ];
    
    protected $validationMessages = [
        'title' => [
            'required' => 'Title is required',
            'min_length' => 'Title must be at least 5 characters'
        ],
        'content' => [
            'required' => 'Content is required',
            'min_length' => 'Content must be at least 20 characters'
        ]
    ];
    
    /**
     * Get all announcements with creator info
     */
    public function getAllWithCreator()
    {
        return $this->select('announcements.*, users.name as creator_name')
                    ->join('users', 'users.id = announcements.created_by')
                    ->orderBy('announcements.priority', 'DESC')
                    ->orderBy('announcements.created_at', 'DESC')
                    ->findAll();
    }
    
    /**
     * Get announcements by category
     */
    public function getByCategory($category)
    {
        return $this->select('announcements.*, users.name as creator_name')
                    ->join('users', 'users.id = announcements.created_by')
                    ->where('announcements.category', $category)
                    ->orderBy('announcements.created_at', 'DESC')
                    ->findAll();
    }
    
    /**
     * Get recent announcements
     */
    public function getRecent($limit = 10)
    {
        return $this->select('announcements.*, users.name as creator_name')
                    ->join('users', 'users.id = announcements.created_by')
                    ->orderBy('announcements.priority', 'DESC')
                    ->orderBy('announcements.created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
    
    /**
     * Get high priority announcements
     */
    public function getHighPriority()
    {
        return $this->select('announcements.*, users.name as creator_name')
                    ->join('users', 'users.id = announcements.created_by')
                    ->where('announcements.priority', 'high')
                    ->orderBy('announcements.created_at', 'DESC')
                    ->findAll();
    }
    
    /**
     * Get announcement with creator details
     */
    public function getWithCreator($id)
    {
        return $this->select('announcements.*, users.name as creator_name, users.email as creator_email')
                    ->join('users', 'users.id = announcements.created_by')
                    ->where('announcements.id', $id)
                    ->first();
    }
    
    /**
     * Search announcements
     */
    public function searchAnnouncements($keyword)
    {
        return $this->select('announcements.*, users.name as creator_name')
                    ->join('users', 'users.id = announcements.created_by')
                    ->groupStart()
                        ->like('announcements.title', $keyword)
                        ->orLike('announcements.content', $keyword)
                    ->groupEnd()
                    ->orderBy('announcements.created_at', 'DESC')
                    ->findAll();
    }
}
