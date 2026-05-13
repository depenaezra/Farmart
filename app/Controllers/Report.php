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

        $inserted = $this->violationModel->reportViolation($data);
        if ($inserted) {
            // Auto-suspend spam content so it is no longer visible until admin review.
            $reason = strtolower((string) $data['reason']);
            $description = strtolower((string) ($data['description'] ?? ''));
            $isSpam = str_contains($reason, 'spam') || str_contains($description, 'spam');

            if ($isSpam && in_array($data['reported_type'], ['forum_post', 'forum_comment'], true)) {
                $db = \Config\Database::connect();
                $now = date('Y-m-d H:i:s');
                $adminId = null; // system action

                if ($data['reported_type'] === 'forum_post') {
                    // suspend the post
                    $db->table('forum_posts')->where('id', (int) $data['reported_id'])->update([
                        'is_suspended' => 1,
                        'suspended_at' => $now,
                        'suspended_reason' => 'spam_report',
                        'suspended_by' => $adminId,
                    ]);
                } else {
                    // suspend the comment (if columns exist)
                    try {
                        $db->table('forum_comments')->where('id', (int) $data['reported_id'])->update([
                            'is_suspended' => 1,
                            'suspended_at' => $now,
                            'suspended_reason' => 'spam_report',
                            'suspended_by' => $adminId,
                        ]);
                    } catch (\Exception $e) {
                        // ignore
                    }
                }
            }

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