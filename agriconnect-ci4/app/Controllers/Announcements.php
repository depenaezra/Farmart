<?php

namespace App\Controllers;

use App\Models\AnnouncementModel;

class Announcements extends BaseController
{
    protected $announcementModel;
    
    public function __construct()
    {
        $this->announcementModel = new AnnouncementModel();
    }
    
    /**
     * List all announcements
     */
    public function index()
    {
        $category = $this->request->getGet('category');
        
        if ($category && $category !== 'all') {
            $announcements = $this->announcementModel->getByCategory($category);
        } else {
            $announcements = $this->announcementModel->getAllWithCreator();
        }
        
        $data = [
            'title' => 'Announcements',
            'announcements' => $announcements,
            'current_category' => $category
        ];
        
        return view('announcements/index', $data);
    }
    
    /**
     * View single announcement
     */
    public function view($id)
    {
        $announcement = $this->announcementModel->getWithCreator($id);
        
        if (!$announcement) {
            return redirect()->to('/announcements')
                ->with('error', 'Announcement not found.');
        }
        
        $data = [
            'title' => $announcement['title'],
            'announcement' => $announcement
        ];
        
        return view('announcements/view', $data);
    }
}
