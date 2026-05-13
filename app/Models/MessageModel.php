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

    protected $attachmentsTable = 'message_attachments';
    protected $attachmentsAllowedFields = [
        'message_id',
        'file_path',
        'file_name',
        'file_type',
        'file_size'
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
        $db = \Config\Database::connect();
        return $db->table($this->table)
                  ->where('id', $messageId)
                  ->update(['is_read' => 1]);
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
        return $this->select('messages.*, 
                              sender.name as sender_name,
                              receiver.name as receiver_name')
                    ->join('users as sender', 'sender.id = messages.sender_id')
                    ->join('users as receiver', 'receiver.id = messages.receiver_id')
                    ->groupStart()
                        ->where('messages.sender_id', $userId1)
                        ->where('messages.receiver_id', $userId2)
                    ->groupEnd()
                    ->orGroupStart()
                        ->where('messages.sender_id', $userId2)
                        ->where('messages.receiver_id', $userId1)
                    ->groupEnd()
                    ->orderBy('messages.created_at', 'ASC')
                    ->limit($limit)
                    ->findAll();
    }
    
    /**
     * Get all conversations for a user (grouped by other user)
     */
    public function getConversations($userId)
    {
        $db = \Config\Database::connect();
        
        // Get all unique conversation partners using raw query
        $sql = "
            SELECT 
                CASE 
                    WHEN sender_id = ? THEN receiver_id 
                    ELSE sender_id 
                END as other_user_id,
                MAX(created_at) as last_message_time,
                SUM(CASE WHEN receiver_id = ? AND is_read = 0 THEN 1 ELSE 0 END) as unread_count
            FROM messages
            WHERE sender_id = ? OR receiver_id = ?
            GROUP BY other_user_id
            ORDER BY last_message_time DESC
        ";
        
        $query = $db->query($sql, [(int)$userId, (int)$userId, (int)$userId, (int)$userId]);
        $conversations = $query->getResultArray();
        
        // Get user details for each conversation
        $userModel = new \App\Models\UserModel();
        foreach ($conversations as &$conv) {
            $otherUser = $userModel->find($conv['other_user_id']);
            if ($otherUser) {
                $conv['other_user_name'] = $otherUser['name'];
                $conv['other_user_role'] = $otherUser['role'] ?? '';
                
                // Get last message preview
                $lastMessage = $this->select('message, subject, created_at, sender_id')
                    ->groupStart()
                        ->where('sender_id', $userId)
                        ->where('receiver_id', $conv['other_user_id'])
                    ->groupEnd()
                    ->orGroupStart()
                        ->where('sender_id', $conv['other_user_id'])
                        ->where('receiver_id', $userId)
                    ->groupEnd()
                    ->orderBy('created_at', 'DESC')
                    ->first();
                
                if ($lastMessage) {
                    $conv['last_message'] = $lastMessage['message'];
                    $conv['last_message_subject'] = $lastMessage['subject'];
                    $conv['last_message_time'] = $lastMessage['created_at'];
                }
            }
        }
        
        return $conversations;
    }
    
    /**
     * Send message
     */
    public function sendMessage($senderId, $receiverId, $subject, $message)
    {
        // Use query builder directly to bypass Model insert which has setBind issue
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);
        
        $data = [
            'sender_id' => (int) $senderId,
            'receiver_id' => (int) $receiverId,
            'message' => (string) $message,
            'is_read' => 0
        ];
        
        // Only add subject if it's not empty
        if (!empty($subject)) {
            $data['subject'] = (string) $subject;
        }
        
        // Let the database handle the timestamp with DEFAULT CURRENT_TIMESTAMP
        
        if ($builder->insert($data)) {
            return $db->insertID();
        }
        
        return false;
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

    /**
     * Save message attachments
     */
    public function saveAttachments($messageId, $attachments)
    {
        if (empty($attachments)) {
            return true;
        }

        $db = \Config\Database::connect();
        
        // Check if table exists before inserting
        if (!$db->tableExists($this->attachmentsTable)) {
            log_message('warning', 'message_attachments table does not exist. Attachments not saved.');
            return false;
        }
        
        $builder = $db->table($this->attachmentsTable);

        $data = [];
        foreach ($attachments as $attachment) {
            $data[] = [
                'message_id' => (int) $messageId,
                'file_path' => $attachment['file_path'],
                'file_name' => $attachment['file_name'],
                'file_type' => $attachment['file_type'],
                'file_size' => (int) $attachment['file_size'],
                'created_at' => date('Y-m-d H:i:s')
            ];
        }

        return $builder->insertBatch($data);
    }

    /**
     * Get message with attachments
     */
    public function getMessageWithAttachments($messageId)
    {
        $message = $this->getMessageWithDetails($messageId);
        if ($message) {
            $message['attachments'] = $this->getMessageAttachments($messageId);
        }
        return $message;
    }

    /**
     * Get message attachments
     */
    public function getMessageAttachments($messageId)
    {
        $db = \Config\Database::connect();
        
        // Check if table exists before querying
        if (!$db->tableExists($this->attachmentsTable)) {
            return [];
        }
        
        $result = $db->table($this->attachmentsTable)
                     ->where('message_id', $messageId)
                     ->orderBy('created_at', 'ASC')
                     ->get()
                     ->getResultArray();
        
        // Ensure we always return an array
        return is_array($result) ? $result : [];
    }

    /**
     * Get conversation messages with attachments
     */
    public function getConversationWithAttachments($userId1, $userId2, $limit = 50)
    {
        $messages = $this->getConversation($userId1, $userId2, $limit);

        // Add attachments to each message
        foreach ($messages as &$message) {
            $attachments = $this->getMessageAttachments($message['id']);
            // Ensure attachments is always an array
            $message['attachments'] = is_array($attachments) ? $attachments : [];
        }

        return $messages;
    }

    /**
     * Send message with attachments
     */
    public function sendMessageWithAttachments($senderId, $receiverId, $subject, $message, $attachments = [])
    {
        // Send the message first
        $messageId = $this->sendMessage($senderId, $receiverId, $subject, $message);

        if ($messageId && !empty($attachments)) {
            // Save attachments
            $saved = $this->saveAttachments($messageId, $attachments);
            if (!$saved) {
                // If attachments fail to save, log the error
                log_message('error', 'Failed to save attachments for message ID: ' . $messageId . '. Attachments: ' . json_encode($attachments));
            } else {
                // Log success for debugging
                log_message('debug', 'Successfully saved ' . count($attachments) . ' attachment(s) for message ID: ' . $messageId);
            }
        }

        return $messageId;
    }
}
