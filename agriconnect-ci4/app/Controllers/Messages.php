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
     * Messages index - redirect to inbox
     */
    public function index()
    {
        return redirect()->to('/messages/inbox');
    }
    
    /**
     * Inbox
     */
    public function inbox()
    {
        $userId = session()->get('user_id');
        $messages = $this->messageModel->getInbox($userId);
        
        $data = [
            'title' => 'Inbox',
            'messages' => $messages,
            'unread_count' => $this->messageModel->getUnreadCount($userId)
        ];
        
        return view('messages/inbox', $data);
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
     * Compose message form
     */
    public function compose()
    {
        $receiverId = $this->request->getGet('to');
        $receiver = null;
        
        if ($receiverId) {
            $receiver = $this->userModel->find($receiverId);
        }
        
        // Get all users except current user
        $users = $this->userModel->where('id !=', session()->get('user_id'))
                                 ->where('status', 'active')
                                 ->findAll();
        
        $data = [
            'title' => 'Compose Message',
            'users' => $users,
            'receiver' => $receiver
        ];
        
        return view('messages/compose', $data);
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
            'message' => 'required|min_length[1]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }
        
        $senderId = session()->get('user_id');
        $receiverId = $this->request->getPost('receiver_id');
        $subject = $this->request->getPost('subject');
        $message = $this->request->getPost('message');
        
        if ($this->messageModel->sendMessage($senderId, $receiverId, $subject, $message)) {
            return redirect()->to('/messages/sent')
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
        $message = $this->messageModel->getMessageWithDetails($id);
        
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
