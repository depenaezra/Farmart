<?php

namespace App\Models;

use CodeIgniter\Model;

class TwoFactorAttemptModel extends Model
{
    protected $table = 'twofa_attempts';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'ip_address',
        'code_entered',
        'success',
        'created_at'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';
    
    /**
     * Record a 2FA attempt
     */
    public function recordAttempt($userId, ?string $ip, ?string $code, bool $success)
    {
        return $this->insert([
            'user_id' => $userId,
            'ip_address' => $ip,
            'code_entered' => $code,
            'success' => $success ? 1 : 0,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Count failed attempts in the last X seconds
     */
    public function countRecentFailures($userId, int $seconds = 300): int
    {
        $since = date('Y-m-d H:i:s', time() - $seconds);
        return $this->where('user_id', $userId)
                    ->where('success', 0)
                    ->where('created_at >=', $since)
                    ->countAllResults();
    }
    
    /**
     * Check if user is rate-limited (too many failures)
     * Returns remaining seconds or 0 if not limited
     */
    public function getRateLimitRemaining($userId, int $maxAttempts = 5, int $lockoutSeconds = 300): int
    {
        $recentFailures = $this->countRecentFailures($userId, $lockoutSeconds);
        if ($recentFailures < $maxAttempts) {
            return 0;
        }
        
        // Find the most recent failure
        $last = $this->select('created_at')
                     ->where('user_id', $userId)
                     ->where('success', 0)
                     ->orderBy('created_at', 'DESC')
                     ->first();
        
        if ($last) {
            $elapsed = time() - strtotime($last['created_at']);
            $remaining = $lockoutSeconds - $elapsed;
            return max(0, $remaining);
        }
        
        return 0;
    }
    
    /**
     * Clear old attempts (older than X days)
     */
    public function cleanupOld(int $days = 30)
    {
        $cutoff = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        return $this->where('created_at <', $cutoff)
                    ->delete();
    }
}
