<?php

namespace App\Models;

use CodeIgniter\Model;

class LoginWhitelistEmailModel extends Model
{
    protected $table         = 'login_whitelist_emails';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'email',
        'created_by',
        'created_at',
    ];
    protected $useTimestamps = false;

    public function normalizeEmail(string $email): string
    {
        return strtolower(trim($email));
    }

    public function isWhitelisted(string $email): bool
    {
        $normalized = $this->normalizeEmail($email);

        return $this->where('email', $normalized)->first() !== null;
    }

    public function addEmail(string $email, ?int $adminId): bool|string
    {
        $normalized = $this->normalizeEmail($email);
        if (! filter_var($normalized, FILTER_VALIDATE_EMAIL)) {
            return 'Invalid email address.';
        }
        if ($this->isWhitelisted($normalized)) {
            return 'This email is already on the whitelist.';
        }

        if (! $this->insert([
            'email'      => $normalized,
            'created_by' => $adminId,
            'created_at' => date('Y-m-d H:i:s'),
        ])) {
            return 'Could not add email to the whitelist.';
        }

        return true;
    }

    public function listAll(): array
    {
        return $this->orderBy('created_at', 'DESC')->findAll();
    }
}
