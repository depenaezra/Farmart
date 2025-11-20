<?php

namespace App\Models;

use CodeIgniter\Model;

class MessageModel extends Model
{
    protected $table = 'messages';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'sender_id',
        'receiver_id',
        'subject',
        'message',
        'is_read'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = false; // No updated_at field
    
    protected $validationRules = [
        'sender_id' => 'required|integer',
        'receiver_id' => 'required|integer',
        'subject' => 'permit_empty|max_length[255]',
        'message' => 'required|min_length[1]'
    ];
    
    /**
     * Get inbox messages for user
     */
    public function getInbox($userId)
    {
        return $this->select('messages.*, 
                              sender.name as sender_name, 
                              sender.email as sender_email')
                    ->join('users as sender', 'sender.id = messages.sender_id')
                    ->where('messages.receiver_id', $userId)
                    ->orderBy('messages.created_at', 'DESC')
                    ->findAll();
    }
    
    /**
     * Get sent messages for user
     */
    public function getSent($userId)
    {
        return $this->select('messages.*, 
                              receiver.name as receiver_name, 
                              receiver.email as receiver_email')
                    ->join('users as receiver', 'receiver.id = messages.receiver_id')
                    ->where('messages.sender_id', $userId)
                    ->orderBy('messages.created_at', 'DESC')
                    ->findAll();
    }
    
    /**
     * Get message with sender and receiver details
     */
    public function getMessageWithDetails($messageId)
    {
        return $this->select('messages.*, 
                              sender.name as sender_name, 
                              sender.email as sender_email,
                              sender.phone as sender_phone,
                              receiver.name as receiver_name, 
                              receiver.email as receiver_email,
                              receiver.phone as receiver_phone')
                    ->join('users as sender', 'sender.id = messages.sender_id')
                    ->join('users as receiver', 'receiver.id = messages.receiver_id')
                    ->where('messages.id', $messageId)
                    ->first();
    }
    
    /**
     * Mark message as read
     */
    public function markAsRead($messageId)
    {
        return $this->update($messageId, ['is_read' => 1]);
    }
    
    /**
     * Get unread message count
     */
    public function getUnreadCount($userId)
    {
        return $this->where('receiver_id', $userId)
                    ->where('is_read', 0)
                    ->countAllResults();
    }
    
    /**
     * Get conversation between two users
     */
    public function getConversation($userId1, $userId2, $limit = 50)
    {
        return $this->groupStart()
                        ->where('sender_id', $userId1)
                        ->where('receiver_id', $userId2)
                    ->groupEnd()
                    ->orGroupStart()
                        ->where('sender_id', $userId2)
                        ->where('receiver_id', $userId1)
                    ->groupEnd()
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
    
    /**
     * Send message
     */
    public function sendMessage($senderId, $receiverId, $subject, $message)
    {
        $data = [
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'subject' => $subject,
            'message' => $message,
            'is_read' => 0
        ];
        
        return $this->insert($data);
    }
    
    /**
     * Get recent messages
     */
    public function getRecentMessages($userId, $limit = 10)
    {
        return $this->select('messages.*, 
                              sender.name as sender_name,
                              receiver.name as receiver_name')
                    ->join('users as sender', 'sender.id = messages.sender_id')
                    ->join('users as receiver', 'receiver.id = messages.receiver_id')
                    ->groupStart()
                        ->where('messages.sender_id', $userId)
                        ->orWhere('messages.receiver_id', $userId)
                    ->groupEnd()
                    ->orderBy('messages.created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
}
