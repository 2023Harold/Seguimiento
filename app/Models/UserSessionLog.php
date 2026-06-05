<?php

// app/Models/UserSessionLog.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;

class UserSessionLog extends Model
{
    use RoutesWithFakeIds;

    protected $connection = 'oracle';

    // Nombre exacto de la tabla como existe en Oracle (mayúsculas)
    protected $table = 'user_sessions_log';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'login_at',
        'logout_at',
        'duration_seconds',
        'logout_type'
    ];

    protected $casts = [
        'login_at'  => 'datetime',
        'logout_at' => 'datetime',
    ];

    // Accessor: duración formateada (ej: "1h 23m 45s")
    public function getDurationFormattedAttribute(): string
    {
        if (!$this->duration_seconds) return 'N/A';

        $h = intdiv($this->duration_seconds, 3600);
        $m = intdiv($this->duration_seconds % 3600, 60);
        $s = $this->duration_seconds % 60;

        return "{$h}h {$m}m {$s}s";
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
