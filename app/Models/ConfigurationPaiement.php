<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// ============================================================================
// MODÈLE CONFIGURATION PAIEMENT
// ============================================================================
class ConfigurationPaiement extends Model
{
    protected $fillable = ['key', 'value', 'type', 'description'];

    public static function get($key, $default = null)
    {
        $config = static::where('key', $key)->first();
        
        if (!$config) {
            return $default;
        }

        return match($config->type) {
            'boolean' => filter_var($config->value, FILTER_VALIDATE_BOOLEAN),
            'json' => json_decode($config->value, true),
            'encrypted' => decrypt($config->value),
            default => $config->value,
        };
    }

    public static function set($key, $value, $type = 'string')
    {
        $processedValue = match($type) {
            'boolean' => $value ? '1' : '0',
            'json' => json_encode($value),
            'encrypted' => encrypt($value),
            default => $value,
        };

        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $processedValue, 'type' => $type]
        );
    }
}
