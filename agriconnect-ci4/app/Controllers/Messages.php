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
     * Get conversation messages (AJAX or direct)
     */
    public function getConversation($otherUserId = null)
    {
        $userId = session()->get('user_id');
        $otherUserId = $otherUserId ?? $this->request->getPost('other_user_id');
        
        if (!$otherUserId) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['error' => 'User ID required']);
            }
            return redirect()->to('/messages')
                ->with('error', 'User ID required');
        }
        
        // Verify other user exists
        $otherUser = $this->userModel->find($otherUserId);
        if (!$otherUser) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['error' => 'User not found']);
            }
            return redirect()->to('/messages')
                ->with('error', 'User not found');
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
        // Handle POST requests (sending messages)
        if ($this->request->getMethod() === 'post') {
            return $this->send();
        }
        
        // Handle GET requests (redirect to conversation)
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
        $userId = session()->get('user_id');
        
        if (!$userId) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['error' => 'Please login to send messages']);
            }
            return redirect()->to('/auth/login')
                ->with('error', 'Please login to send messages');
        }
        
        $receiverId = $this->request->getPost('receiver_id');
        $message = $this->request->getPost('message');
        
        if (!$receiverId) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['error' => 'Receiver ID is required']);
            }
            return redirect()->back()
                ->with('error', 'Receiver ID is required');
        }
        
        // Verify receiver exists
        $receiver = $this->userModel->find($receiverId);
        if (!$receiver) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['error' => 'Receiver not found']);
            }
            return redirect()->back()
                ->with('error', 'Receiver not found');
        }
        
        // Message can be empty if only attachments are sent
        $message = trim($this->request->getPost('message') ?? '');
        
        // Handle file uploads
        $files = $this->request->getFiles();
        $attachments = [];
        
        if (isset($files['attachments']) && is_array($files['attachments'])) {
            $uploadPath = FCPATH . 'uploads/messages/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            foreach ($files['attachments'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    try {
                        // Get MIME type before moving the file
                        $mimeType = null;
                        $isImage = false;
                        
                        try {
                            $mimeType = $file->getMimeType();
                            $isImage = ($mimeType && strpos($mimeType, 'image/') === 0);
                        } catch (\Exception $e) {
                            // Fallback: check file extension if MIME type fails
                            $extension = strtolower($file->getClientExtension());
                            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];
                            $isImage = in_array($extension, $imageExtensions);
                            if ($isImage) {
                                $mimeType = 'image/' . ($extension === 'jpg' ? 'jpeg' : $extension);
                            }
                        }
                        
                        // Validate it's an image
                        if ($isImage) {
                            $newName = $file->getRandomName();
                            if ($file->move($uploadPath, $newName)) {
                                $attachments[] = [
                                    'file_path' => 'uploads/messages/' . $newName,
                                    'file_name' => $file->getClientName(),
                                    'file_type' => $mimeType ?: 'image/jpeg',
                                    'file_size' => $file->getSize()
                                ];
                            }
                        }
                    } catch (\Exception $e) {
                        // Log error but continue with other files
                        log_message('error', 'Error processing file upload: ' . $e->getMessage());
                        continue;
                    }
                }
            }
        }
        
        // Validate that we have either a message or attachments
        if (empty($message) && empty($attachments)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['error' => 'Please enter a message or attach images']);
            }
            return redirect()->back()
                ->with('error', 'Please enter a message or attach images');
        }
        
        // Ensure message is not empty string if we have attachments (use space as placeholder)
        // Database requires message field, so use a space if only attachments are sent
        $messageText = !empty(trim($message)) ? $message : (!empty($attachments) ? ' ' : '');
        
        // Send message with attachments
        try {
            $messageId = $this->messageModel->sendMessageWithAttachments(
                $userId,
                $receiverId,
                '', // No subject for chat messages
                $messageText,
                $attachments
            );
            
            if ($messageId) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Message sent successfully',
                        'message_id' => $messageId
                    ]);
                }
                return redirect()->to('/messages/conversation/' . $receiverId)
                    ->with('success', 'Message sent successfully');
            } else {
                log_message('error', 'Failed to send message. User: ' . $userId . ', Receiver: ' . $receiverId);
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON(['error' => 'Failed to send message. Please try again.']);
                }
                return redirect()->back()
                    ->with('error', 'Failed to send message. Please try again.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception sending message: ' . $e->getMessage());
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['error' => 'Error sending message: ' . $e->getMessage()]);
            }
            return redirect()->back()
                ->with('error', 'Error sending message. Please try again.');
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
