<?php

namespace App\Libraries;

use CodeIgniter\Encryption\Encryption;

class TwoFactorAuth
{
    protected Encryption $encryption;

    public function __construct()
    {
        $this->encryption = service('encryption');
    }

    /**
     * Generate 10 single-use backup codes (8 chars alphanumeric)
     * Returns array of plain codes; store only their hashes
     */
    public function generateBackupCodes(): array
    {
        $codes = [];
        for ($i = 0; $i < 10; $i++) {
            $code = substr(str_shuffle('23456789ABCDEFGHJKLMNPQRSTUVWXYZ'), 0, 8);
            $codes[] = $code;
        }
        return $codes;
    }

    /**
     * Hash a backup code for storage (like password hash)
     */
    public function hashBackupCode(string $code): string
    {
        return password_hash($code, PASSWORD_DEFAULT);
    }

    /**
     * Verify and consume a backup code
     * Returns true if valid, false otherwise
     * Modifies $backupCodes array by removing used code
     */
    public function verifyBackupCode(array &$backupCodes, string $inputCode): bool
    {
        foreach ($backupCodes as $index => $hash) {
            if (password_verify($inputCode, $hash)) {
                unset($backupCodes[$index]);
                $backupCodes = array_values($backupCodes);
                return true;
            }
        }
        return false;
    }
}
