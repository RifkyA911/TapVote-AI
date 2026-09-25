<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AppSetting extends Model
{
    protected $table = 'app_settings';
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Dapatkan nilai setting berdasarkan key dengan default value
     */
    public static function get(string $key, $default = null)
    {
        return Cache::remember("app_setting_{$key}", 60, function () use ($key, $default) {
            $record = static::find($key);
            return $record ? $record->value : $default;
        });
    }

    /**
     * Simpan / Perbarui nilai setting
     */
    public static function set(string $key, $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::forget("app_setting_{$key}");
    }
}
