<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'admin_name',
        'subscription_status',
        'trial_ends_at',
        'subscription_ends_at',
    ];

    protected $casts = [
        'trial_ends_at'        => 'datetime',
        'subscription_ends_at' => 'datetime',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Return true jika status trial atau active.
     */
    public function isActive(): bool
    {
        return in_array($this->subscription_status, ['trial', 'active']);
    }

    /**
     * Return true jika:
     * - status expired, ATAU
     * - status trial dan trial_ends_at sudah lewat, ATAU
     * - status active dan subscription_ends_at sudah lewat.
     */
    public function isExpired(): bool
    {
        if ($this->subscription_status === 'expired') {
            return true;
        }

        if ($this->subscription_status === 'trial' && $this->trial_ends_at !== null) {
            return Carbon::now()->gt($this->trial_ends_at);
        }

        if ($this->subscription_status === 'active' && $this->subscription_ends_at !== null) {
            return Carbon::now()->gt($this->subscription_ends_at);
        }

        return false;
    }

    /**
     * Hitung sisa hari dari trial_ends_at (jika trial) atau subscription_ends_at (jika active).
     * Return 0 jika sudah lewat.
     */
    public function daysRemaining(): int
    {
        $endDate = null;

        if ($this->subscription_status === 'trial') {
            $endDate = $this->trial_ends_at;
        } elseif ($this->subscription_status === 'active') {
            $endDate = $this->subscription_ends_at;
        }

        if ($endDate === null) {
            return 0;
        }

        $diff = (int) Carbon::now()->startOfDay()->diffInDays($endDate->startOfDay(), false);

        return max(0, $diff);
    }
}
