<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendance';

    protected $fillable = [
        'student_id',
        'date',
        'check_in',
        'check_out',
        'status',
        'late_minutes',
        'method',
        'scanned_by',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'late_minutes' => 'integer',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function scanner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scanned_by');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'hadir' => 'bg-emerald-100 text-emerald-700 border-emerald-300',
            'terlambat' => 'bg-amber-100 text-amber-700 border-amber-300',
            'izin' => 'bg-blue-100 text-blue-700 border-blue-300',
            'sakit' => 'bg-purple-100 text-purple-700 border-purple-300',
            'alpa' => 'bg-rose-100 text-rose-700 border-rose-300',
            default => 'bg-gray-100 text-gray-700 border-gray-300',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'hadir' => 'Hadir',
            'terlambat' => 'Terlambat' . ($this->late_minutes > 0 ? " ({$this->late_minutes}m)" : ''),
            'izin' => 'Izin',
            'sakit' => 'Sakit',
            'alpa' => 'Alpa',
            default => ucfirst($this->status),
        };
    }
}
