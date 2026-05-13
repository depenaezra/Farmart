<?php

namespace App\Models;

use CodeIgniter\Model;

class SystemSettingModel extends Model
{
    protected $table = 'system_settings';
    protected $primaryKey = 'key';
    protected $returnType = 'array';
    protected $useAutoIncrement = false;

    protected $allowedFields = ['key', 'value', 'updated_at'];
    protected $useTimestamps = false;

    public function getValue(string $key, ?string $default = null): ?string
    {
        try {
            $row = $this->find($key);
            if (!$row) {
                return $default;
            }
            return array_key_exists('value', $row) ? (string) $row['value'] : $default;
        } catch (\Throwable $e) {
            // If table doesn't exist yet (manual SQL not applied), fail safe.
            return $default;
        }
    }

    public function setValue(string $key, ?string $value): bool
    {
        $payload = [
            'key' => $key,
            'value' => $value,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        try {
            return (bool) $this->save($payload);
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function isEnabled(string $key, bool $default = false): bool
    {
        $value = $this->getValue($key, $default ? '1' : '0');
        return $value === '1' || strtolower((string) $value) === 'true' || (string) $value === 'yes';
    }
}

