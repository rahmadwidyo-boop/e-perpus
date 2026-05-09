<?php

namespace App\Models;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'code', 'student_id', 'book_id',
        'loan_date', 'due_date', 'return_date',
        'status', 'fine',
    ];

    protected $casts = [
        'loan_date'   => 'date',
        'due_date'    => 'date',
        'return_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope());

        static::creating(function ($model) {
            if (app()->bound('current_school_id') && empty($model->school_id)) {
                $model->school_id = app()->make('current_school_id');
            }
        });
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Hitung keterlambatan dalam hari.
     */
    public function getLateDaysAttribute(): int
    {
        $checkDate = $this->return_date ?? Carbon::today();
        if ($checkDate->gt($this->due_date)) {
            return $checkDate->diffInDays($this->due_date);
        }
        return 0;
    }

    /**
     * Hitung denda: Rp 1.000 per hari terlambat.
     */
    public function getCalculatedFineAttribute(): int
    {
        return $this->late_days * 1000;
    }
}
