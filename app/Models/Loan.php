<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'student_id', 'book_id',
        'loan_date', 'due_date', 'return_date',
        'status', 'fine',
    ];

    protected $casts = [
        'loan_date'   => 'date',
        'due_date'    => 'date',
        'return_date' => 'date',
    ];

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
