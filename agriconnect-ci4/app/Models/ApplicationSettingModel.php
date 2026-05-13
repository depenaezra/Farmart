<?php

namespace App\Models;

use CodeIgniter\Model;

class ApplicationSettingModel extends Model
{
    protected $table            = 'application_settings';
    protected $primaryKey       = 'setting_key';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'setting_key',
        'setting_value',
    ];

    public function getValue(string $key, string $default = ''): string
    {
        $row = $this->find($key);

        return $row ? (string) $row['setting_value'] : $default;
    }

    public function setValue(string $key, string $value): bool
    {
        if ($this->find($key)) {
            return $this->update($key, ['setting_value' => $value]);
        }

        return $this->insert([
            'setting_key'   => $key,
            'setting_value' => $value,
        ]) !== false;
    }
}
