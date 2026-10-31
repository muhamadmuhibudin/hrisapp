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

    public function getBadgeClassAttribute(): string
    {
        return match (true) {
            str_ends_with($this->action, '.deleted'),
            str_ends_with($this->action, '.rejected') => 'bg-danger',

            str_ends_with($this->action, '.created'),
            str_ends_with($this->action, '.confirmed'),
            $this->action === 'auth.login' => 'bg-success',

            str_ends_with($this->action, '.updated') => 'bg-warning text-dark',

            str_ends_with($this->action, '.exported') => 'bg-primary',

            $this->action === 'auth.logout' => 'bg-secondary',

            default => 'bg-info',
        };
    }

}