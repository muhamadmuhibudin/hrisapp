<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Prunable;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'subject_type',
        'subject_id',
        'description',
        'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Record a sensitive system activity for audit trail.
     */
    public static function record(string $action, string $description, ?Model $subject = null): self
    {
        return self::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->id,
            'description' => $description,
            'ip_address' => request()->ip(),
        ]);
    }

    use Prunable;

    protected $table = 'activity_logs';

    public function prunable()
    {
        return static::where('created_at', '<=', now()->subDays(360));
    }

}