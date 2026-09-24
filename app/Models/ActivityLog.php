<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';

    protected $fillable = [
        'user_type',
        'user_identifier',
        'action',
        'module',
        'description',
        'ip_address',
        'user_agent',
    ];

    public static function log(string $action, string $module, string $description, ?string $userType = null, ?string $userIdentifier = null): self
    {
        $resolvedType = $userType ?? (auth()->check() ? 'admin' : (session()->has('voter_nik') ? 'voter' : 'system'));
        $resolvedIdentifier = $userIdentifier ?? (auth()->check() ? auth()->user()->email : (session()->get('voter_nik') ?? Request::ip()));

        return self::create([
            'user_type' => $resolvedType,
            'user_identifier' => $resolvedIdentifier,
            'action' => strtoupper($action),
            'module' => strtoupper($module),
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
