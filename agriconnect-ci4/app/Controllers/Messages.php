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
        // Simple test - just return success
        return redirect()->back()
            ->with('success', 'Test: Method called successfully!');
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
