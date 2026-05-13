<?php

namespace App\Models;

use CodeIgniter\Model;

class WhitelistedEmailModel extends Model
{
    protected $table = 'whitelisted_emails';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = ['email', 'created_by', 'created_at'];
    protected $useTimestamps = false;

    public function isWhitelisted(string $email): bool
    {
        $email = strtolower(trim($email));
        if ($email === '') {
            return false;
        }

        try {
            return $this->where('email', $email)->countAllResults() > 0;
        } catch (\Throwable $e) {
            // If table doesn't exist yet, fail closed (not whitelisted).
            return false;
        }
    }

    /**
     * Replace the current whitelist with a new list.
     *
     * @param string[] $emails lowercased, validated, unique.
     */
    public function replaceAll(array $emails, ?int $adminId = null): void
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $db->table($this->table)->truncate();
        } catch (\Throwable $e) {
            $db->transComplete();
            return;
        }

        $now = date('Y-m-d H:i:s');
        foreach ($emails as $email) {
            try {
                $db->table($this->table)->insert([
                    'email' => $email,
                    'created_by' => $adminId,
                    'created_at' => $now,
                ]);
            } catch (\Throwable $e) {
                // ignore row errors
            }
        }

        $db->transComplete();
    }

    /**
     * @return string[]
     */
    public function listEmails(): array
    {
        try {
            $rows = $this->select('email')->orderBy('email', 'ASC')->findAll();
            return array_values(array_filter(array_map(static fn($r) => (string) ($r['email'] ?? ''), $rows)));
        } catch (\Throwable $e) {
            return [];
        }
    }
}

