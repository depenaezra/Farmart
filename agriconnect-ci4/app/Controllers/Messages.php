<?php

namespace App\Controllers;

use App\Models\MessageModel;
use App\Models\UserModel;

class Messages extends BaseController
{
    protected $messageModel;
    protected $userModel;
    
    public function __construct()
    {
        $this->messageModel = new MessageModel();
        $this->userModel = new UserModel();
    }
    
    /**
     * Messages index - show messenger
     */
    public function index()
    {
        $userId = session()->get('user_id');
        $conversations = $this->messageModel->getConversations($userId);
        
        // Get all users for new conversation
        $users = $this->userModel->where('id !=', $userId)
                                 ->where('status', 'active')
                                 ->findAll();
        
        $data = [
            'title' => 'Messages',
            'conversations' => $conversations,
            'users' => $users,
            'unread_count' => $this->messageModel->getUnreadCount($userId)
        ];
        
        return view('messages/messenger', $data);
    }
    
    /**
     * Inbox - redirect to messenger
     */
    public function inbox()
    {
        return redirect()->to('/messages');
    }
    
    /**
     * Get conversation messages (AJAX)
     */
    public function getConversation($otherUserId = null)
    {
        if (!$this->request->isAJAX() && !$otherUserId) {
            return redirect()->to('/messages');
        }
        
        $userId = session()->get('user_id');
        $otherUserId = $otherUserId ?? $this->request->getPost('other_user_id');
        
        if (!$otherUserId) {
            return $this->response->setJSON(['error' => 'User ID required']);
        }
        
        // Verify other user exists
        $otherUser = $this->userModel->find($otherUserId);
        if (!$otherUser) {
            return $this->response->setJSON(['error' => 'User not found']);
        }
        
        // Get conversation messages with attachments
        $messages = $this->messageModel->getConversationWithAttachments($userId, $otherUserId, 100);
        
        // Mark messages as read
        foreach ($messages as $msg) {
            if ($msg['receiver_id'] == $userId && !$msg['is_read']) {
                $this->messageModel->markAsRead($msg['id']);
            }
        }
        
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'messages' => $messages,
                'other_user' => [
                    'id' => $otherUser['id'],
                    'name' => $otherUser['name'],
                    'role' => $otherUser['role'] ?? ''
                ]
            ]);
        }
        
        // Non-AJAX request - show messenger with conversation
        $conversations = $this->messageModel->getConversations($userId);
        $users = $this->userModel->where('id !=', $userId)
                                 ->where('status', 'active')
                                 ->findAll();
        
        $data = [
            'title' => 'Messages',
            'conversations' => $conversations,
            'users' => $users,
            'selected_user_id' => $otherUserId,
            'selected_user' => $otherUser,
            'messages' => $messages,
            'unread_count' => $this->messageModel->getUnreadCount($userId)
        ];
        
        return view('messages/messenger', $data);
    }
    
    /**
     * Sent messages
     */
    public function sent()
    {
        $userId = session()->get('user_id');
        $messages = $this->messageModel->getSent($userId);
        
        $data = [
            'title' => 'Sent Messages',
            'messages' => $messages
        ];
        
        return view('messages/sent', $data);
    }
    
    /**
     * Compose message form - redirect to messenger with user selected
     */
    public function compose()
    {
        $receiverId = $this->request->getGet('to');
        
        if ($receiverId) {
            // Redirect to messenger with conversation open
            return redirect()->to('/messages/conversation/' . $receiverId);
        }
        
        // No user selected, just show messenger
        return redirect()->to('/messages');
    }
    
    /**
     * Send message
     */
    public function send()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'receiver_id' => 'required|integer',
            'subject' => 'permit_empty|max_length[255]',
            'message' => 'permit_empty'
        ];

        // But ensure at least message or attachments are provided
        $message = $this->request->getPost('message');
        $hasFiles = $this->request->getFiles() && isset($this->request->getFiles()['attachments']) &&
                   is_array($this->request->getFiles()['attachments']) &&
                   !empty(array_filter($this->request->getFiles()['attachments'], function($file) {
                       return $file->isValid() && !$file->hasMoved() && !empty($file->getName());
                   }));

        if (empty(trim($message)) && !$hasFiles) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Please enter a message or attach images.');
        }

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $senderId = session()->get('user_id');
        $receiverId = $this->request->getPost('receiver_id');
        $subject = $this->request->getPost('subject');
        $rawMessage = trim($this->request->getPost('message'));

        // Validate users exist
        if (!$this->userModel->find($senderId)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Invalid sender.');
        }

        if (!$this->userModel->find($receiverId)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Invalid recipient.');
        }

        // Handle file uploads
        $attachments = [];
        try {
            $files = $this->request->getFiles();

            if (isset($files['attachments'])) {
                $uploadPath = FCPATH . 'uploads/messages/';

                // Create directory if it doesn't exist
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                $maxSize = 10240 * 1024; // 10MB in bytes
                $maxWidth = 2048;
                $maxHeight = 2048;

                foreach ($files['attachments'] as $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        // Validate file type
                        if (!in_array($file->getMimeType(), $allowedTypes)) {
                            return redirect()->back()
                                ->withInput()
                                ->with('error', 'Invalid file type. Only images are allowed.');
                        }

                        // Validate file size
                        if ($file->getSize() > $maxSize) {
                            return redirect()->back()
                                ->withInput()
                                ->with('error', 'File size too large. Maximum 10MB allowed.');
                        }

                        // Validate dimensions
                        $imageInfo = @getimagesize($file->getTempName());
                        if ($imageInfo && ($imageInfo[0] > $maxWidth || $imageInfo[1] > $maxHeight)) {
                            return redirect()->back()
                                ->withInput()
                                ->with('error', 'Image dimensions too large. Maximum 2048x2048 pixels allowed.');
                        }

                        $newName = $file->getRandomName();
                        if ($file->move($uploadPath, $newName)) {
                            $attachments[] = [
                                'file_path' => 'uploads/messages/' . $newName,
                                'file_name' => $file->getName(),
                                'file_type' => $file->getMimeType(),
                                'file_size' => $file->getSize()
                            ];
                        } else {
                            return redirect()->back()
                                ->withInput()
                                ->with('error', 'Failed to upload file.');
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'File upload failed: ' . $e->getMessage());
        }

        // Set final message content
        $message = $rawMessage;
        if (empty($message) && !empty($attachments)) {
            $attachmentCount = count($attachments);
            $message = $attachmentCount === 1 ? 'Sent an image' : 'Sent ' . $attachmentCount . ' images';
        }

        $result = $this->messageModel->sendMessageWithAttachments($senderId, $receiverId, $subject, $message, $attachments);

        if ($result) {
            return redirect()->to('/messages/conversation/' . $receiverId)
                ->with('success', 'Message sent successfully!');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to send message.');
        }
    }
    
    /**
     * View message
     */
    public function view($id)
    {
        $message = $this->messageModel->getMessageWithAttachments($id);

        if (!$message) {
            return redirect()->to('/messages/inbox')
                ->with('error', 'Message not found.');
        }

        $userId = session()->get('user_id');

        // Verify user is sender or receiver
        if ($message['sender_id'] != $userId && $message['receiver_id'] != $userId) {
            return redirect()->to('/messages/inbox')
                ->with('error', 'Access denied.');
        }

        // Mark as read if user is receiver
        if ($message['receiver_id'] == $userId && !$message['is_read']) {
            $this->messageModel->markAsRead($id);
        }

        $data = [
            'title' => $message['subject'] ?? 'Message',
            'message' => $message
        ];

        return view('messages/view', $data);
    }
    
    /**
     * Reply to message
     */
    public function reply($id)
    {
        $originalMessage = $this->messageModel->find($id);
        
        if (!$originalMessage) {
            return redirect()->to('/messages/inbox')
                ->with('error', 'Message not found.');
        }
        
        $validation = \Config\Services::validation();
        
        $rules = [
            'message' => 'required|min_length[1]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }
        
        $senderId = session()->get('user_id');
        $receiverId = $originalMessage['sender_id'] == $senderId 
            ? $originalMessage['receiver_id'] 
            : $originalMessage['sender_id'];
        
        $subject = 'Re: ' . ($originalMessage['subject'] ?? '');
        $message = $this->request->getPost('message');
        
        if ($this->messageModel->sendMessage($senderId, $receiverId, $subject, $message)) {
            return redirect()->to('/messages/sent')
                ->with('success', 'Reply sent successfully!');
        } else {
            return redirect()->back()
                ->with('error', 'Failed to send reply.');
        }
    }
    
    /**
     * Delete message
     */
    public function delete($id)
    {
        $message = $this->messageModel->find($id);
        
        if (!$message) {
            return redirect()->back()
                ->with('error', 'Message not found.');
        }
        
        $userId = session()->get('user_id');
        
        // Only allow deleting if user is sender or receiver
        if ($message['sender_id'] != $userId && $message['receiver_id'] != $userId) {
            return redirect()->back()
                ->with('error', 'Access denied.');
        }
        
        if ($this->messageModel->delete($id)) {
            return redirect()->to('/messages/inbox')
                ->with('success', 'Message deleted.');
        } else {
            return redirect()->back()
                ->with('error', 'Failed to delete message.');
        }
    }
}
