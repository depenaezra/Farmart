<?php

namespace App\Controllers;

use App\Models\ViolationModel;

class Report extends BaseController
{
    protected $violationModel;
    
    public function __construct()
    {
        $this->violationModel = new ViolationModel();
    }
    
    /**
     * Submit a report
     */
    public function submit()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'reported_type' => 'required|in_list[forum_post,forum_comment,product,user]',
            'reported_id' => 'required|integer',
            'reason' => 'required|max_length[255]',
            'description' => 'permit_empty'
        ];
        
        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid report data.',
                'errors' => $validation->getErrors()
            ]);
        }
        
        $data = [
            'reporter_id' => session()->get('user_id'),
            'reported_type' => $this->request->getPost('reported_type'),
            'reported_id' => $this->request->getPost('reported_id'),
            'reason' => $this->request->getPost('reason'),
            'description' => $this->request->getPost('description'),
            'status' => 'pending'
        ];
        
        if ($this->violationModel->reportViolation($data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Report submitted successfully. Thank you for helping keep our community safe.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to submit report. Please try again.'
            ]);
        }
    }
}